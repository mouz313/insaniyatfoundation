@extends('adminlte::page')

@section('title', 'Donor Stories')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div class="d-flex align-items-center">
            <div class="mr-3" style="width:56px;height:56px;background:linear-gradient(135deg,#e83e8c,#d63384);border-radius:12px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 15px rgba(232,62,140,0.3);">
                <i class="fas fa-quote-right text-white" style="font-size:24px;"></i>
            </div>
            <div>
                <h1 class="mb-0" style="font-weight:600;">Donor Stories</h1>
                <small class="text-muted">Testimonials from blood donors</small>
            </div>
        </div>
        <a href="{{ route('admin.donor-stories.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Add Story</a>
    </div>
@stop

@section('content')
    <div class="card shadow-sm border-0 rounded-lg">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr><th>Photo</th><th>Name</th><th>Blood Group</th><th>City</th><th>Donations</th><th>Active</th><th>Order</th><th width="120">Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($stories as $story)
                        <tr>
                            <td class="align-middle">
                                <div style="width:40px;height:40px;border-radius:50%;overflow:hidden;background:linear-gradient(135deg,#667eea,#764ba2);display:flex;align-items:center;justify-content:center;">
                                    @if($story->photo)
                                        <img src="{{ asset('storage/' . $story->photo) }}" alt="" style="width:100%;height:100%;object-fit:cover;">
                                    @else
                                        <i class="fas fa-user text-white" style="font-size:16px;"></i>
                                    @endif
                                </div>
                            </td>
                            <td class="align-middle" style="font-weight:500;">{{ $story->name }}</td>
                            <td class="align-middle"><span class="badge badge-danger">{{ $story->blood_group ?? 'N/A' }}</span></td>
                            <td class="align-middle">{{ $story->city ?? 'N/A' }}</td>
                            <td class="align-middle">{{ $story->donations_count }}</td>
                            <td class="align-middle">
                                @if($story->is_active)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-secondary">Inactive</span>
                                @endif
                            </td>
                            <td class="align-middle">{{ $story->sort_order }}</td>
                            <td class="align-middle text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.donor-stories.edit', $story) }}" class="btn btn-outline-warning"><i class="fas fa-edit"></i></a>
                                    <button type="button" class="btn btn-outline-danger btn-delete" data-id="{{ $story->id }}" data-name="{{ $story->name }}"><i class="fas fa-trash"></i></button>
                                </div>
                                <form id="delete-form-{{ $story->id }}" action="{{ route('admin.donor-stories.destroy', $story) }}" method="POST" style="display:none;">@csrf @method('DELETE')</form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center py-4 text-muted">No stories yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('js')
<script>
$('.btn-delete').on('click', function() {
    var id = $(this).data('id');
    var name = $(this).data('name');
    Swal.fire({
        title: 'Delete Story?',
        text: 'Are you sure you want to delete the story from ' + name + '?',
        icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete!', cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) $('#delete-form-' + id).submit();
    });
});
</script>
@if(session('success'))
<script>$(function() { toastr.success('{{ session('success') }}', 'Success'); });</script>
@endif
@stop
