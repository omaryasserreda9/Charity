<?php

namespace App\Http\Controllers;

use App\Models\BudgetCategory;
use App\Models\BudgetOperation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class BudgetOperationController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'budget_category_id', 'date_from', 'date_to']);
        $categories = BudgetCategory::orderBy('title')->get();
        $currentBalance = $this->currentBalance();

        $operations = BudgetOperation::query()
            ->with('category')
            ->when($filters['search'] ?? null, function ($query, string $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('donor_name', 'like', "%{$search}%")
                        ->orWhereHas('category', fn ($categoryQuery) => $categoryQuery->where('title', 'like', "%{$search}%"));
                });
            })
            ->when($filters['budget_category_id'] ?? null, fn ($query, $categoryId) => $query->where('budget_category_id', $categoryId))
            ->when($filters['date_from'] ?? null, fn ($query, $date) => $query->whereDate('operation_date', '>=', $date))
            ->when($filters['date_to'] ?? null, fn ($query, $date) => $query->whereDate('operation_date', '<=', $date))
            ->latest('operation_date')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $breadcrumbs = ['عمليات الميزانية' => route('budget-operations.index')];

        return view('budget.operations.index', compact('operations', 'categories', 'filters', 'currentBalance', 'breadcrumbs'));
    }

    public function create(): View
    {
        $categories = BudgetCategory::orderBy('title')->get();
        $currentBalance = $this->currentBalance();
        $breadcrumbs = [
            'عمليات الميزانية' => route('budget-operations.index'),
            'إضافة عملية' => route('budget-operations.create'),
        ];

        return view('budget.operations.create', compact('categories', 'currentBalance', 'breadcrumbs'));
    }

    public function store(Request $request): RedirectResponse
    {
        $attributes = $this->validatedAttributes($request);
        $this->ensureBalanceIsEnough($attributes);

        BudgetOperation::create($attributes);

        return redirect()->route('budget-operations.index')->with('success', 'تم حفظ عملية الميزانية بنجاح.');
    }

    public function show(BudgetOperation $budgetOperation): View
    {
        $budgetOperation->load('category');

        $breadcrumbs = [
            'عمليات الميزانية' => route('budget-operations.index'),
            'تفاصيل العملية' => route('budget-operations.show', $budgetOperation),
        ];

        return view('budget.operations.show', compact('budgetOperation', 'breadcrumbs'));
    }

    public function edit(BudgetOperation $budgetOperation): View
    {
        $categories = BudgetCategory::orderBy('title')->get();
        $currentBalance = $this->currentBalance();
        $breadcrumbs = [
            'عمليات الميزانية' => route('budget-operations.index'),
            'تعديل عملية' => route('budget-operations.edit', $budgetOperation),
        ];

        return view('budget.operations.edit', compact('budgetOperation', 'categories', 'currentBalance', 'breadcrumbs'));
    }

    public function update(Request $request, BudgetOperation $budgetOperation): RedirectResponse
    {
        $attributes = $this->validatedAttributes($request);
        $this->ensureBalanceIsEnough($attributes, $budgetOperation);

        $budgetOperation->update($attributes);

        return redirect()->route('budget-operations.index')->with('success', 'تم تحديث عملية الميزانية بنجاح.');
    }

    public function destroy(BudgetOperation $budgetOperation): RedirectResponse
    {
        $budgetOperation->delete();

        return redirect()->route('budget-operations.index')->with('success', 'تم حذف العملية بنجاح.');
    }

    private function validatedAttributes(Request $request): array
    {
        return $request->validate([
            'budget_category_id' => ['nullable', 'exists:budget_categories,id'],
            'type' => ['required', Rule::in(['in', 'out'])],
            'donor_name' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'numeric', 'min:0.01'],
            'operation_date' => ['required', 'date'],
        ]);
    }

    private function ensureBalanceIsEnough(array $attributes, ?BudgetOperation $original = null): void
    {
        $newEffect = $attributes['type'] === 'in' ? (float) $attributes['quantity'] : -1 * (float) $attributes['quantity'];
        $originalEffect = 0.0;

        if ($original) {
            $originalEffect = $original->type === 'in' ? (float) $original->quantity : -1 * (float) $original->quantity;
        }

        $balanceAfterSave = $this->currentBalance() - $originalEffect + $newEffect;

        if ($balanceAfterSave < 0) {
            throw ValidationException::withMessages([
                'quantity' => 'لا يمكن تسجيل عملية صرف أكبر من الرصيد الحالي.',
            ]);
        }
    }

    private function currentBalance(): float
    {
        $totalIn = (float) BudgetOperation::where('type', 'in')->sum('quantity');
        $totalOut = (float) BudgetOperation::where('type', 'out')->sum('quantity');

        return $totalIn - $totalOut;
    }
}
