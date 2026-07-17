@if($files->isNotEmpty())
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>الملف</th>
                    <th>الحجم</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($files as $file)
                    <tr>
                        <td>
                            <i class="fa-solid fa-file ms-1 text-muted"></i>
                            {{ $file->original_name }}
                        </td>
                        <td>{{ $file->formattedSize() }}</td>
                        <td>
                            <div class="table-actions">
                                @can('humanitarian_cases.view')
                                <a href="{{ route('humanitarian-case-files.download', $file) }}" class="btn btn-sm btn-light">
                                    <i class="fa-solid fa-download ms-1"></i>
                                    تنزيل
                                </a>
                                <a href="{{ route('humanitarian-case-files.preview', $file) }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary">
                                    <i class="fa-solid fa-up-right-from-square ms-1"></i>
                                    معاينة
                                </a>
                                @endcan
                                @can('humanitarian_cases.delete')
                                <button class="btn btn-sm btn-outline-danger" type="button" data-bs-toggle="modal" data-bs-target="#deleteFile{{ $file->id }}">
                                    حذف
                                </button>
                                @endcan
                            </div>

                            <div class="modal fade" id="deleteFile{{ $file->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">حذف الملف</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                                        </div>
                                        <div class="modal-body">هل تريد حذف الملف "{{ $file->original_name }}"؟ لن يتم حذف الحالة.</div>
                                            <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                                            @can('humanitarian_cases.delete')
                                            <form method="POST" action="{{ route('humanitarian-case-files.destroy', $file) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">تأكيد الحذف</button>
                                            </form>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <p class="text-muted mb-0">لا توجد مرفقات لهذه الحالة.</p>
@endif
