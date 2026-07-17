<?php

namespace App\Http\Controllers;

use App\Models\InventoryCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InventoryCategoryController extends Controller
{
    protected string $permissionPrefix = 'inventory_categories';
    public function index(Request $request): View
    {
        $search = (string) $request->input('search', '');

        $categories = InventoryCategory::query()
            ->withCount('operations')
            ->when($search, fn ($query) => $query->where('title', 'like', "%{$search}%"))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $breadcrumbs = ['بنود المخزون' => route('inventory-categories.index')];

        return view('inventory.categories.index', compact('categories', 'search', 'breadcrumbs'));
    }

    public function create(): View
    {
        $breadcrumbs = [
            'بنود المخزون' => route('inventory-categories.index'),
            'إضافة بند' => route('inventory-categories.create'),
        ];

        return view('inventory.categories.create', compact('breadcrumbs'));
    }

    public function store(Request $request): RedirectResponse
    {
        InventoryCategory::create($this->validatedAttributes($request));

        return redirect()->route('inventory-categories.index')->with('success', 'تم إنشاء البند بنجاح.');
    }

    public function show(InventoryCategory $inventoryCategory): View
    {
        $inventoryCategory->loadCount('operations');

        $breadcrumbs = [
            'بنود المخزون' => route('inventory-categories.index'),
            $inventoryCategory->title => route('inventory-categories.show', $inventoryCategory),
        ];

        return view('inventory.categories.show', compact('inventoryCategory', 'breadcrumbs'));
    }

    public function edit(InventoryCategory $inventoryCategory): View
    {
        $breadcrumbs = [
            'بنود المخزون' => route('inventory-categories.index'),
            'تعديل البند' => route('inventory-categories.edit', $inventoryCategory),
        ];

        return view('inventory.categories.edit', compact('inventoryCategory', 'breadcrumbs'));
    }

    public function update(Request $request, InventoryCategory $inventoryCategory): RedirectResponse
    {
        $inventoryCategory->update($this->validatedAttributes($request));

        return redirect()->route('inventory-categories.index')->with('success', 'تم تحديث البند بنجاح.');
    }

    public function destroy(InventoryCategory $inventoryCategory): RedirectResponse
    {
        $inventoryCategory->delete();

        return redirect()->route('inventory-categories.index')->with('success', 'تم حذف البند، وبقيت العمليات المرتبطة بدون بند.');
    }

    private function validatedAttributes(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ]);
    }
}
