<?php

namespace App\Http\Controllers;

use App\Models\HumanitarianCaseFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class HumanitarianCaseFileController extends Controller
{
    public function preview(HumanitarianCaseFile $humanitarianCaseFile): BinaryFileResponse
    {
        return response()->file(
            Storage::disk('public')->path($humanitarianCaseFile->path),
            [
                'Content-Type' => $humanitarianCaseFile->mime_type ?? 'application/octet-stream',
                'Content-Disposition' => 'inline; filename="'.$humanitarianCaseFile->original_name.'"',
            ]
        );
    }

    public function download(HumanitarianCaseFile $humanitarianCaseFile): StreamedResponse
    {
        return Storage::disk('public')->download(
            $humanitarianCaseFile->path,
            $humanitarianCaseFile->original_name
        );
    }

    public function destroy(HumanitarianCaseFile $humanitarianCaseFile): RedirectResponse
    {
        Storage::disk('public')->delete($humanitarianCaseFile->path);
        $humanitarianCaseFile->delete();

        return back()->with('success', 'تم حذف الملف بنجاح.');
    }
}
