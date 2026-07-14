<?php

namespace App\Http\Controllers;

use App\Models\InventoryCategory;
use App\Models\InventoryOperation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class InventoryOperationController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'inventory_category_id', 'date_from', 'date_to']);
        $categories = InventoryCategory::orderBy('title')->get();

        $operations = InventoryOperation::query()
            ->with('category')
            ->when($filters['search'] ?? null, function ($query, string $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('donor_name', 'like', "%{$search}%")
                        ->orWhere('item_name', 'like', "%{$search}%");
                });
            })
            ->when($filters['inventory_category_id'] ?? null, fn ($query, $categoryId) => $query->where('inventory_category_id', $categoryId))
            ->when($filters['date_from'] ?? null, fn ($query, $date) => $query->whereDate('operation_date', '>=', $date))
            ->when($filters['date_to'] ?? null, fn ($query, $date) => $query->whereDate('operation_date', '<=', $date))
            ->latest('operation_date')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $breadcrumbs = ['عمليات المخزون' => route('inventory-operations.index')];

        return view('inventory.operations.index', compact('operations', 'categories', 'filters', 'breadcrumbs'));
    }

    public function create(): View
    {
        $categories = InventoryCategory::orderBy('title')->get();
        $breadcrumbs = [
            'عمليات المخزون' => route('inventory-operations.index'),
            'إضافة عملية' => route('inventory-operations.create'),
        ];

        return view('inventory.operations.create', compact('categories', 'breadcrumbs'));
    }

    public function store(Request $request): RedirectResponse
    {
        $attributes = $this->validatedAttributes($request);
        $this->ensureStockIsEnough($attributes);

        InventoryOperation::create($attributes);

        return redirect()->route('inventory-operations.index')->with('success', 'تم حفظ عملية المخزون بنجاح.');
    }

    public function show(InventoryOperation $inventoryOperation): View
    {
        $inventoryOperation->load('category');

        $breadcrumbs = [
            'عمليات المخزون' => route('inventory-operations.index'),
            'تفاصيل العملية' => route('inventory-operations.show', $inventoryOperation),
        ];

        return view('inventory.operations.show', compact('inventoryOperation', 'breadcrumbs'));
    }

    public function edit(InventoryOperation $inventoryOperation): View
    {
        $categories = InventoryCategory::orderBy('title')->get();
        $breadcrumbs = [
            'عمليات المخزون' => route('inventory-operations.index'),
            'تعديل عملية' => route('inventory-operations.edit', $inventoryOperation),
        ];

        return view('inventory.operations.edit', compact('inventoryOperation', 'categories', 'breadcrumbs'));
    }

    public function update(Request $request, InventoryOperation $inventoryOperation): RedirectResponse
    {
        $attributes = $this->validatedAttributes($request);
        $this->ensureStockIsEnough($attributes, $inventoryOperation);

        $inventoryOperation->update($attributes);

        return redirect()->route('inventory-operations.index')->with('success', 'تم تحديث عملية المخزون بنجاح.');
    }

    public function destroy(InventoryOperation $inventoryOperation): RedirectResponse
    {
        $inventoryOperation->delete();

        return redirect()->route('inventory-operations.index')->with('success', 'تم حذف العملية بنجاح.');
    }

    private function validatedAttributes(Request $request): array
    {
        return $request->validate([
            'inventory_category_id' => ['nullable', 'exists:inventory_categories,id'],
            'type' => ['required', Rule::in(['in', 'out'])],
            'donor_name' => ['required', 'string', 'max:255'],
            'item_name' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'numeric', 'min:0.01'],
            'operation_date' => ['required', 'date'],
        ]);
    }

    private function ensureStockIsEnough(array $attributes, ?InventoryOperation $original = null): void
    {
        if ($attributes['type'] !== 'out') {
            return;
        }

        $availableStock = $this->availableStockForItem($attributes['item_name'], $original);

        if ((float) $attributes['quantity'] > $availableStock) {
            throw ValidationException::withMessages([
                'quantity' => 'لا يمكن تسجيل عملية إخراج أكبر من المخزون المتاح للصنف.',
            ]);
        }
    }

    private function availableStockForItem(string $itemName, ?InventoryOperation $exclude = null): float
    {
        $inQuery = InventoryOperation::query()
            ->where('item_name', $itemName)
            ->where('type', 'in');

        $outQuery = InventoryOperation::query()
            ->where('item_name', $itemName)
            ->where('type', 'out');

        if ($exclude) {
            $inQuery->where('id', '!=', $exclude->id);
            $outQuery->where('id', '!=', $exclude->id);
        }

        return (float) $inQuery->sum('quantity') - (float) $outQuery->sum('quantity');
    }
}
