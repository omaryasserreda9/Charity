<?php

namespace App\Http\Controllers;

use App\Models\Donor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DonorController extends Controller
{
    protected string $permissionPrefix = 'donors';

    public function index(Request $request): View
    {
        $search = (string) $request->input('search', '');

        $donors = Donor::query()
            ->withCount(['budgetOperations', 'inventoryOperations'])
            ->when($search, function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $breadcrumbs = ['المتبرعون' => route('donors.index')];

        return view('donors.index', compact('donors', 'search', 'breadcrumbs'));
    }

    public function create(): View
    {
        $breadcrumbs = [
            'المتبرعون' => route('donors.index'),
            'إضافة متبرع' => route('donors.create'),
        ];

        return view('donors.create', compact('breadcrumbs'));
    }

    public function store(Request $request): RedirectResponse
    {
        Donor::create($this->validatedAttributes($request));

        return redirect()->route('donors.index')->with('success', 'تم إنشاء المتبرع بنجاح.');
    }

    public function show(Donor $donor): View
    {
        $donor->loadCount(['budgetOperations', 'inventoryOperations']);

        $breadcrumbs = [
            'المتبرعون' => route('donors.index'),
            $donor->name => route('donors.show', $donor),
        ];

        return view('donors.show', compact('donor', 'breadcrumbs'));
    }

    public function edit(Donor $donor): View
    {
        $breadcrumbs = [
            'المتبرعون' => route('donors.index'),
            'تعديل المتبرع' => route('donors.edit', $donor),
        ];

        return view('donors.edit', compact('donor', 'breadcrumbs'));
    }

    public function update(Request $request, Donor $donor): RedirectResponse
    {
        $donor->update($this->validatedAttributes($request));

        return redirect()->route('donors.index')->with('success', 'تم تحديث المتبرع بنجاح.');
    }

    public function destroy(Donor $donor): RedirectResponse
    {
        $donor->delete();

        return redirect()->route('donors.index')->with('success', 'تم حذف المتبرع، وبقيت العمليات المرتبطة باسمه دون ربط.');
    }

    public function search(Request $request): JsonResponse
    {
        $this->authorizeModuleAction('view');

        $search = $request->input('q');

        $donors = Donor::query()
            ->when($search, function ($query, $search): void {
                $query->where('name', 'like', "%{$search}%");
            })
            ->limit(10)
            ->get(['id', 'name', 'phone']);

        return response()->json($donors);
    }

    private function validatedAttributes(Request $request): array
    {
        return $request->validate(Donor::$rules);
    }
}
