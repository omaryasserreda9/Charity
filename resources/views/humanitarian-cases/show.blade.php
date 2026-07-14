@extends('layouts.app')

@section('title', 'تفاصيل حالة')
@section('page_title', 'تفاصيل حالة')

@section('content')
    <section class="panel form-panel">
        <div class="panel-header">
            <div>
                <h2>{{ $humanitarianCase->name }}</h2>
                <p>
                    <span class="badge rounded-pill {{ $humanitarianCase->type === 'mine' ? 'text-bg-warning' : 'text-bg-info' }}">
                        {{ $humanitarianCase->typeLabel() }}
                    </span>
                </p>
            </div>
            <a href="{{ route('humanitarian-cases.edit', $humanitarianCase) }}" class="btn btn-primary btn-sm">تعديل</a>
        </div>

        <dl class="details-list">
            <dt>الاسم</dt>
            <dd>{{ $humanitarianCase->name }}</dd>
            <dt>رقم الجوال</dt>
            <dd>{{ $humanitarianCase->phone }}</dd>
            <dt>رقم الهوية</dt>
            <dd>{{ $humanitarianCase->national_id }}</dd>
            <dt>المنطقة</dt>
            <dd>{{ $humanitarianCase->district->title ?? '—' }}</dd>
            <dt>نوع الحالة</dt>
            <dd>{{ $humanitarianCase->typeLabel() }}</dd>
            <dt>ملاحظات</dt>
            <dd>{{ $humanitarianCase->notes ?: '—' }}</dd>
            <dt>تاريخ الإنشاء</dt>
            <dd>{{ optional($humanitarianCase->created_at)->format('Y-m-d') }}</dd>
        </dl>
    </section>

    <section class="panel mt-4">
        <div class="panel-header">
            <div>
                <h2>المرفقات</h2>
                <p>تنزيل ومعاينة وحذف الملفات المرتبطة بالحالة.</p>
            </div>
        </div>

        @include('humanitarian-cases._attachments', ['files' => $humanitarianCase->files])
    </section>

    @if($humanitarianCase->familyMembers->isNotEmpty())
        <section class="panel mt-4">
            <div class="panel-header">
                <div>
                    <h2>أفراد الأسرة</h2>
                    <p>تفاصيل أفراد الأسرة المرتبطين بالحالة.</p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>الاسم</th>
                            <th>العلاقة</th>
                            <th>العمر</th>
                            <th>التعليم</th>
                            <th>الحالة الصحية</th>
                            <th>الحالة الاجتماعية</th>
                            <th>متوسط الدخل</th>
                            <th>الوظيفة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($humanitarianCase->familyMembers as $member)
                            <tr>
                                <td>{{ $member->name }}</td>
                                <td>{{ $member->relation }}</td>
                                <td>{{ $member->age }}</td>
                                <td>{{ $member->education }}</td>
                                <td>{{ $member->health_status }}</td>
                                <td>{{ $member->marital_status }}</td>
                                <td>{{ $member->average_income }}</td>
                                <td>{{ $member->job }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    @endif

    @if($humanitarianCase->caseIncome || $humanitarianCase->caseExpense)
        <section class="panel mt-4">
            <div class="panel-header">
                <div>
                    <h2>الدخل والمصاريف</h2>
                    <p>عرض إجمالي الدخل والمصاريف مع التفاصيل الفرعية.</p>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h3 class="card-title">الدخل</h3>
                            <dl class="row">
                                <dt class="col-6">دخل العمل</dt>
                                <dd class="col-6">{{ optional($humanitarianCase->caseIncome)->job_income ?: '—' }}</dd>
                                <dt class="col-6">دخل المعاش</dt>
                                <dd class="col-6">{{ optional($humanitarianCase->caseIncome)->pension_income ?: '—' }}</dd>
                                <dt class="col-6">دخل المساعدات</dt>
                                <dd class="col-6">{{ optional($humanitarianCase->caseIncome)->charity_income ?: '—' }}</dd>
                                <dt class="col-6">دخل آخر</dt>
                                <dd class="col-6">{{ optional($humanitarianCase->caseIncome)->other_income ?: '—' }}</dd>
                                <dt class="col-6">إجمالي الدخل</dt>
                                <dd class="col-6">{{ optional($humanitarianCase->caseIncome)->total_income ?: '—' }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h3 class="card-title">المصاريف</h3>
                            <dl class="row">
                                <dt class="col-6">إيجار المنزل</dt>
                                <dd class="col-6">{{ optional($humanitarianCase->caseExpense)->home_rent ?: '—' }}</dd>
                                <dt class="col-6">مصاريف المدرسة</dt>
                                <dd class="col-6">{{ optional($humanitarianCase->caseExpense)->school_expenses ?: '—' }}</dd>
                                <dt class="col-6">الفواتير</dt>
                                <dd class="col-6">{{ optional($humanitarianCase->caseExpense)->utilities ?: '—' }}</dd>
                                <dt class="col-6">الدواء</dt>
                                <dd class="col-6">{{ optional($humanitarianCase->caseExpense)->medicine ?: '—' }}</dd>
                                <dt class="col-6">التغذية</dt>
                                <dd class="col-6">{{ optional($humanitarianCase->caseExpense)->nutrition ?: '—' }}</dd>
                                <dt class="col-6">مصروفات أخرى</dt>
                                <dd class="col-6">{{ optional($humanitarianCase->caseExpense)->other_expenses ?: '—' }}</dd>
                                <dt class="col-6">إجمالي المصاريف</dt>
                                <dd class="col-6">{{ optional($humanitarianCase->caseExpense)->total_expenses ?: '—' }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    @if($humanitarianCase->caseHomeDescription)
        <section class="panel mt-4">
            <div class="panel-header">
                <div>
                    <h2>وصف المنزل</h2>
                    <p>معلومات عن حالة المنزل والمرافق.</p>
                </div>
            </div>

            <dl class="details-list">
                <dt>عدد الغرف</dt>
                <dd>{{ optional($humanitarianCase->caseHomeDescription)->rooms_count ?: '—' }}</dd>
                <dt>ماء نظيف</dt>
                <dd>{{ optional($humanitarianCase->caseHomeDescription)->clean_water ? 'نعم' : 'لا' }}</dd>
                <dt>حالة السقف</dt>
                <dd>{{ optional($humanitarianCase->caseHomeDescription)->roof_condition ?: '—' }}</dd>
                <dt>نوع الأرضية</dt>
                <dd>{{ optional($humanitarianCase->caseHomeDescription)->flooring_type ?: '—' }}</dd>
                <dt>تلفاز</dt>
                <dd>{{ optional($humanitarianCase->caseHomeDescription)->has_tv ? 'نعم' : 'لا' }}</dd>
                <dt>غسالة</dt>
                <dd>{{ optional($humanitarianCase->caseHomeDescription)->has_washing_machine ? 'نعم' : 'لا' }}</dd>
                <dt>موقد غاز</dt>
                <dd>{{ optional($humanitarianCase->caseHomeDescription)->has_gas_stove ? 'نعم' : 'لا' }}</dd>
                <dt>مروحة</dt>
                <dd>{{ optional($humanitarianCase->caseHomeDescription)->has_fan ? 'نعم' : 'لا' }}</dd>
                <dt>هاتف</dt>
                <dd>{{ optional($humanitarianCase->caseHomeDescription)->has_phone ? 'نعم' : 'لا' }}</dd>
                <dt>ثلاجة</dt>
                <dd>{{ optional($humanitarianCase->caseHomeDescription)->has_fridge ? 'نعم' : 'لا' }}</dd>
            </dl>
        </section>
    @endif

    @if($humanitarianCase->caseNeed)
        <section class="panel mt-4">
            <div class="panel-header">
                <div>
                    <h2>الاحتياجات</h2>
                    <p>الاحتياجات الحالية والموصى بها للحالة.</p>
                </div>
            </div>

            <dl class="details-list">
                <dt>الاحتياجات المطلوبة</dt>
                <dd>{{ optional($humanitarianCase->caseNeed)->requested_needs ?: '—' }}</dd>
                <dt>الاحتياجات الموصى بها</dt>
                <dd>{{ optional($humanitarianCase->caseNeed)->recommended_needs ?: '—' }}</dd>
            </dl>
        </section>
    @endif
@endsection
