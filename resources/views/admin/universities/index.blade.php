@extends('adminlte::page')

@section('title', 'Universities')

@section('content_header')
    <div class="d-flex align-items-center">
        <div class="mr-3" style="width: 56px; height: 56px; background: linear-gradient(135deg, #6610f2, #8b5cf6); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(102,16,242,0.3);">
            <i class="fas fa-university text-white" style="font-size: 24px;"></i>
        </div>
        <div>
            <h1 class="mb-0" style="font-weight: 600;">Universities</h1>
            <small class="text-muted"><i class="fas fa-fw fa-database"></i> {{ $universities->count() }} total</small>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-5">
            <div class="card shadow-sm border-0 rounded-lg" style="border-top: 3px solid #6610f2 !important;">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-plus text-purple"></i> Add University</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.universities.store') }}" method="POST">
                        @csrf
                        <x-adminlte-input name="name" label="University Name" placeholder="Enter university name"/>
                        <x-adminlte-button type="submit" label="Add University" theme="primary" icon="fas fa-save"/>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-list text-secondary"></i> All Universities</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Donors</th>
                                    <th width="120" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($universities as $university)
                                    <tr>
                                        <td class="align-middle"><span class="text-muted">#{{ $university->id }}</span></td>
                                        <td class="align-middle" style="font-weight: 500;">{{ $university->name }}</td>
                                        <td class="align-middle"><span class="badge badge-secondary" style="border-radius: 20px; padding: 4px 12px;">{{ $university->donors_count ?? $university->donors()->count() }}</span></td>
                                        <td class="align-middle text-center">
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.universities.edit', $university->id) }}" class="btn btn-outline-warning" title="Edit" style="border-radius: 6px 0 0 6px;"><i class="fas fa-edit"></i></a>
                                                <form action="{{ route('admin.universities.destroy', $university->id) }}" method="POST" style="display:inline;">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" title="Delete" style="border-radius: 0 6px 6px 0;" onclick="return confirm('Delete this university? Associated donor records will keep the name but lose the link.')"><i class="fas fa-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <i class="fas fa-university text-muted" style="font-size: 48px; opacity: 0.3;"></i>
                                            <p class="text-muted mt-2 mb-0">No universities found.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
<style>
.card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
.card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important; }
.table td, .table th { vertical-align: middle; }
.btn-group-sm .btn { padding: 3px 8px; }
</style>
@stop
