<?php

namespace App\Http\Controllers;

use App\Models\CharityHome;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CharityHomeController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('can:charity_homes.view');
    }

    public function index(): View
    {
        $this->authorize('charity_homes.view');

        $charityHomes = CharityHome::query()
            ->withCount('users')
            ->latest()
            ->paginate(10);

        $breadcrumbs = ['بيوت الجمعيات' => route('charity-homes.index')];

        return view('charity-homes.index', compact('charityHomes', 'breadcrumbs'));
    }

    public function create(): View
    {
        $this->authorize('charity_homes.add');

        $breadcrumbs = [
            'بيوت الجمعيات' => route('charity-homes.index'),
            'إضافة بيت جمعية' => route('charity-homes.create'),
        ];

        return view('charity-homes.create', compact('breadcrumbs'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('charity_homes.add');

        CharityHome::create($this->validatedAttributes($request));

        return redirect()->route('charity-homes.index')->with('success', 'تم إنشاء بيت الجمعية بنجاح.');
    }

    public function show(CharityHome $charityHome): View
    {
        $this->authorize('charity_homes.view');

        $charityHome->load('users');

        $breadcrumbs = [
            'بيوت الجمعيات' => route('charity-homes.index'),
            $charityHome->title => route('charity-homes.show', $charityHome),
        ];

        return view('charity-homes.show', compact('charityHome', 'breadcrumbs'));
    }

    public function edit(CharityHome $charityHome): View
    {
        $this->authorize('charity_homes.edit');

        $breadcrumbs = [
            'بيوت الجمعيات' => route('charity-homes.index'),
            'تعديل بيت الجمعية' => route('charity-homes.edit', $charityHome),
        ];

        return view('charity-homes.edit', compact('charityHome', 'breadcrumbs'));
    }

    public function update(Request $request, CharityHome $charityHome): RedirectResponse
    {
        $this->authorize('charity_homes.edit');

        $charityHome->update($this->validatedAttributes($request));

        return redirect()->route('charity-homes.index')->with('success', 'تم تحديث بيت الجمعية بنجاح.');
    }

    public function destroy(CharityHome $charityHome): RedirectResponse
    {
        $this->authorize('charity_homes.delete');

        $charityHome->delete();

        return redirect()->route('charity-homes.index')->with('success', 'تم حذف بيت الجمعية بنجاح.');
    }

    private function validatedAttributes(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ]);
    }
}
