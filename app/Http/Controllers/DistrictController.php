<?php

namespace App\Http\Controllers;

use App\Models\District;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DistrictController extends Controller
{
    protected string $permissionPrefix = 'districts';
    public function index(Request $request): View
    {
        $search = (string) $request->input('search', '');

        $districts = District::query()
            ->withCount('campaigns')
            ->when($search, fn ($query) => $query->where('title', 'like', "%{$search}%"))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $breadcrumbs = ['المناطق' => route('districts.index')];

        return view('districts.index', compact('districts', 'search', 'breadcrumbs'));
    }

    public function create(): View
    {
        $breadcrumbs = [
            'المناطق' => route('districts.index'),
            'إضافة منطقة' => route('districts.create'),
        ];

        return view('districts.create', compact('breadcrumbs'));
    }

    public function store(Request $request): RedirectResponse
    {
        District::create($this->validatedAttributes($request));

        return redirect()->route('districts.index')->with('success', 'تم إنشاء المنطقة بنجاح.');
    }

    public function show(District $district): View
    {
        $district->loadCount('campaigns');

        $breadcrumbs = [
            'المناطق' => route('districts.index'),
            $district->title => route('districts.show', $district),
        ];

        return view('districts.show', compact('district', 'breadcrumbs'));
    }

    public function edit(District $district): View
    {
        $breadcrumbs = [
            'المناطق' => route('districts.index'),
            'تعديل المنطقة' => route('districts.edit', $district),
        ];

        return view('districts.edit', compact('district', 'breadcrumbs'));
    }

    public function update(Request $request, District $district): RedirectResponse
    {
        $district->update($this->validatedAttributes($request));

        return redirect()->route('districts.index')->with('success', 'تم تحديث المنطقة بنجاح.');
    }

    public function destroy(District $district): RedirectResponse
    {
        if ($district->campaigns()->exists()) {
            return redirect()->route('districts.index')->with('error', 'لا يمكن حذف المنطقة لوجود حملات مرتبطة بها.');
        }

        $district->delete();

        return redirect()->route('districts.index')->with('success', 'تم حذف المنطقة بنجاح.');
    }

    private function validatedAttributes(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ]);
    }
}
