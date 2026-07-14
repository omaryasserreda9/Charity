<?php

namespace App\Http\Controllers;

use App\Models\HumanitarianCase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class HumanitarianCaseController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'type']);

        $cases = HumanitarianCase::query()
            ->withCount('files')
            ->when($filters['search'] ?? null, function ($query, string $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('national_id', 'like', "%{$search}%");
                });
            })
            ->when($filters['type'] ?? null, fn ($query, string $type) => $query->where('type', $type))
            ->latest()
            ->get();

        $breadcrumbs = ['الحالات الإنسانية' => route('humanitarian-cases.index')];

        return view('humanitarian-cases.index', compact('cases', 'filters', 'breadcrumbs'));
    }

    public function create(): View
    {
        $breadcrumbs = [
            'الحالات الإنسانية' => route('humanitarian-cases.index'),
            'إضافة حالة' => route('humanitarian-cases.create'),
        ];

        return view('humanitarian-cases.create', compact('breadcrumbs'));
    }

    public function store(Request $request): RedirectResponse
    {
        $case = HumanitarianCase::create($this->validatedAttributes($request));
        $this->storeAttachments($request, $case);

        return redirect()->route('humanitarian-cases.index')->with('success', 'تم إنشاء الحالة بنجاح.');
    }

    public function show(HumanitarianCase $humanitarianCase): View
    {
        $humanitarianCase->load('files');

        $breadcrumbs = [
            'الحالات الإنسانية' => route('humanitarian-cases.index'),
            $humanitarianCase->name => route('humanitarian-cases.show', $humanitarianCase),
        ];

        return view('humanitarian-cases.show', compact('humanitarianCase', 'breadcrumbs'));
    }

    public function edit(HumanitarianCase $humanitarianCase): View
    {
        $humanitarianCase->load('files');

        $breadcrumbs = [
            'الحالات الإنسانية' => route('humanitarian-cases.index'),
            'تعديل الحالة' => route('humanitarian-cases.edit', $humanitarianCase),
        ];

        return view('humanitarian-cases.edit', compact('humanitarianCase', 'breadcrumbs'));
    }

    public function update(Request $request, HumanitarianCase $humanitarianCase): RedirectResponse
    {
        $humanitarianCase->update($this->validatedAttributes($request, $humanitarianCase));
        $this->storeAttachments($request, $humanitarianCase);

        return redirect()->route('humanitarian-cases.index')->with('success', 'تم تحديث الحالة بنجاح.');
    }

    public function destroy(HumanitarianCase $humanitarianCase): RedirectResponse
    {
        $humanitarianCase->delete();

        return redirect()->route('humanitarian-cases.index')->with('success', 'تم حذف الحالة بنجاح.');
    }

    private function validatedAttributes(Request $request, ?HumanitarianCase $case = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^0[0-9]{10}$/'],
            'national_id' => [
                'required',
                'string',
                'regex:/^[2-9][0-9]{13}$/',
                Rule::unique('humanitarian_cases')->ignore(optional($case)->id),
            ],
            'notes' => ['nullable', 'string'],
            'type' => ['required', Rule::in(['mine', 'seasonal'])],
            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['file', 'max:10240', 'mimes:pdf,jpg,jpeg,png,webp,doc,docx'],
        ], [
            'phone.regex' => 'رقم الجوال يجب أن يكون 11 رقمًا ويبدأ بـ 0.',
            'national_id.regex' => 'رقم الهوية يجب أن يكون 14 رقمًا ويبدأ بـ 2 أو أعلى (لا يبدأ بـ 0 أو 1).',
        ]);
    }

    private function storeAttachments(Request $request, HumanitarianCase $case): void
    {
        if (! $request->hasFile('attachments')) {
            return;
        }

        /** @var UploadedFile $file */
        foreach ($request->file('attachments') as $file) {
            $path = $file->store("humanitarian-cases/{$case->id}", 'public');

            $case->files()->create([
                'original_name' => $file->getClientOriginalName(),
                'path' => $path,
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
            ]);
        }
    }
}
