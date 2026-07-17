<?php

namespace App\Http\Controllers;

use App\Models\BudgetCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BudgetCategoryController extends Controller
{
    protected string $permissionPrefix = 'budget_categories';
    public function index(Request $request): View
    {
        $search = (string) $request->input('search', '');

        $categories = BudgetCategory::query()
            ->withCount('operations')
            ->when($search, fn ($query) => $query->where('title', 'like', "%{$search}%"))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $breadcrumbs = ['بنود الميزانية' => route('budget-categories.index')];

        return view('budget.categories.index', compact('categories', 'search', 'breadcrumbs'));
    }

    public function create(): View
    {
        $breadcrumbs = [
            'بنود الميزانية' => route('budget-categories.index'),
            'إضافة بند' => route('budget-categories.create'),
        ];

        return view('budget.categories.create', compact('breadcrumbs'));
    }

    public function store(Request $request): RedirectResponse
    {
        BudgetCategory::create($this->validatedAttributes($request));

        return redirect()->route('budget-categories.index')->with('success', 'تم إنشاء البند بنجاح.');
    }

    public function show(BudgetCategory $budgetCategory): View
    {
        $budgetCategory->loadCount('operations');

        $breadcrumbs = [
            'بنود الميزانية' => route('budget-categories.index'),
            $budgetCategory->title => route('budget-categories.show', $budgetCategory),
        ];

        return view('budget.categories.show', compact('budgetCategory', 'breadcrumbs'));
    }

    public function edit(BudgetCategory $budgetCategory): View
    {
        $breadcrumbs = [
            'بنود الميزانية' => route('budget-categories.index'),
            'تعديل البند' => route('budget-categories.edit', $budgetCategory),
        ];

        return view('budget.categories.edit', compact('budgetCategory', 'breadcrumbs'));
    }

    public function update(Request $request, BudgetCategory $budgetCategory): RedirectResponse
    {
        $budgetCategory->update($this->validatedAttributes($request));

        return redirect()->route('budget-categories.index')->with('success', 'تم تحديث البند بنجاح.');
    }

    public function destroy(BudgetCategory $budgetCategory): RedirectResponse
    {
        $budgetCategory->delete();

        return redirect()->route('budget-categories.index')->with('success', 'تم حذف البند، وبقيت العمليات المرتبطة بدون بند.');
    }

    private function validatedAttributes(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ]);
    }
}
