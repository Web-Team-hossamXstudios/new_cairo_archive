<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>رقم الأرض</th>
                <th>العميل</th>
                <th>المساحة</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($lands as $land)
                <tr>
                    <td>{{ $land->land_no }}</td>
                    <td>{{ $land->client->name ?? '-' }}</td>
                    <td>{{ $land->area }} م²</td>
                    <td>
                        <a href="{{ route('admin.lands.show', $land->id) }}" class="btn btn-sm btn-info">
                            <i class="ti ti-eye"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">لا توجد أراضي في هذا القطاع</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    @if($lands->hasPages())
        <div class="mt-3">
            {{ $lands->links() }}
        </div>
    @endif
</div>
