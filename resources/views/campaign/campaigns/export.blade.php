<!doctype html>
<html lang="ar">
<head>
    <meta charset="utf-8">
    <title>تقرير الحملة - {{ $campaign->title }}</title>
    <style>
        body { font-family: Tahoma, Arial, sans-serif; direction: rtl; }
        .meta { margin-bottom: 16px; }
        .meta h2 { margin: 0 0 6px 0; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #cfcfcf; padding: 8px; text-align: right; }
        th { background: #2f6f9f; color: #fff; font-weight: bold; }
        tr:nth-child(even) td { background: #f7fbff; }
        .muted { color: #666; font-size: 12px; }
    </style>
</head>
<body>
    <div class="meta">
        <h2>تقرير الحملة: {{ $campaign->title }}</h2>
        <div class="muted">تاريخ الحملة: {{ optional($campaign->campaign_date)->format('Y-m-d') ?? '—' }} | المنطقة: {{ optional($campaign->district)->title ?? '—' }} | التصنيف: {{ optional($campaign->category)->title ?? '—' }}</div>
        <div class="muted">تاريخ التصدير: {{ now()->format('Y-m-d H:i') }} | إجمالي الحالات: {{ $humanitarianCases->count() }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>م</th>
                <th>الاسم</th>
                <th>الجوال</th>
                <th>الهوية</th>
                <th>المنطقة</th>
                <th>الدليل</th>
                <th>أفراد الأسرة</th>
                <th>نوع الحالة</th>
                <th>ملاحظات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($humanitarianCases as $i => $case)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $case->name }}</td>
                    <td>{{ $case->phone }}</td>
                    <td>{{ $case->national_id }}</td>
                    <td>{{ $case->district->title ?? '—' }}</td>
                    <td>{{ $case->referrer->name ?? '—' }}</td>
                    <td>{{ $case->family_members_count + 1 }}</td>
                    <td>{{ $case->typeLabel() }}</td>
                    <td>{{ $case->notes ?? '—' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
