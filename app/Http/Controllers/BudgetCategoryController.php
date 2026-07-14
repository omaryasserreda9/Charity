<?php

namespace App\Http\Controllers;

use App\Models\BudgetCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BudgetCategoryController extends Controller
{
    public function index(Request $request): View
    {
        $search = (string) $request->input('search', '');

        $categories = BudgetCategory::query()
            ->withCount('operations')
            ->when($search, fn ($query) => $query->where('title', 'like', "%{$search}%"))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $breadcrumbs = ['تصنيفات الميزانية' => route('budget-categories.index')];

        return view('budget.categories.index', compact('categories', 'search', 'breadcrumbs'));
    }

    public function create(): View
    {
        $breadcrumbs = [
            'تصنيفات الميزانية' => route('budget-categories.index'),
            'إضافة تصنيف' => route('budget-categories.create'),
        ];

        return view('budget.categories.create', compact('breadcrumbs'));
    }

    public function store(Request $request): RedirectResponse
    {
        BudgetCategory::create($this->validatedAttributes($request));

        return redirect()->route('budget-categories.index')->with('success', 'تم إنشاء التصنيف بنجاح.');
    }

    public function show(BudgetCategory $budgetCategory): View
    {
        $budgetCategory->loadCount('operations');

        $breadcrumbs = [
            'تصنيفات الميزانية' => route('budget-categories.index'),
            $budgetCategory->title => route('budget-categories.show', $budgetCategory),
        ];

        return view('budget.categories.show', compact('budgetCategory', 'breadcrumbs'));
    }

    public function edit(BudgetCategory $budgetCategory): View
    {
        $breadcrumbs = [
            'تصنيفات الميزانية' => route('budget-categories.index'),
            'تعديل التصنيف' => route('budget-categories.edit', $budgetCategory),
        ];

        return view('budget.categories.edit', compact('budgetCategory', 'breadcrumbs'));
    }

    public function update(Request $request, BudgetCategory $budgetCategory): RedirectResponse
    {
        $budgetCategory->update($this->validatedAttributes($request));

        return redirect()->route('budget-categories.index')->with('success', 'تم تحديث التصنيف بنجاح.');
    }

    public function destroy(BudgetCategory $budgetCategory): RedirectResponse
    {
        $budgetCategory->delete();

        return redirect()->route('budget-categories.index')->with('success', 'تم حذف التصنيف، وبقيت العمليات المرتبطة بدون تصنيف.');
    }

    private function validatedAttributes(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ]);
    }
}
