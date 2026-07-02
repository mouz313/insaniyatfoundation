@extends('adminlte::page')

@section('title', 'My Profile')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-user-circle mr-2 text-danger"></i>My Profile</h1>
        <small class="text-muted">{{ now()->format('l, F j, Y') }}</small>
    </div>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-lg" role="alert">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <div class="row">
        {{-- PHOTO CARD --}}
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm rounded-lg border-0">
                <div class="card-body text-center py-4">
                    <div class="mb-3 position-relative d-inline-block">
                        <img src="{{ $user->adminlte_image() }}"
                             alt="{{ $user->name }}"
                             class="rounded-circle img-thumbnail border-3"
                             style="width:140px;height:140px;object-fit:cover;border:4px solid #dc3545 !important;"
                             id="profilePreview">
                    </div>
                    <h4 class="mb-1 font-weight-bold">{{ $user->name }}</h4>
                    <p class="text-muted mb-2">{{ $user->email }}</p>
                    @foreach($user->getRoleNames() as $role)
                        <span class="badge badge-danger px-3 py-1 mb-2">{{ ucfirst($role) }}</span>
                    @endforeach
                    <hr>
                    <form action="{{ route('admin.profile.photo') }}" method="POST" enctype="multipart/form-data" id="photoForm">
                        @csrf
                        <div class="custom-file text-left mb-2">
                            <input type="file" class="custom-file-input" id="photo" name="photo" accept="image/*">
                            <label class="custom-file-label" for="photo">Choose photo</label>
                        </div>
                        @error('photo') <small class="text-danger">{{ $message }}</small> @enderror
                        <button type="submit" class="btn btn-danger btn-block btn-sm mt-2">
                            <i class="fas fa-upload mr-1"></i>Upload Photo
                        </button>
                    </form>
                    @if($user->profile_photo_path)
                        <form action="{{ route('admin.profile.photo.remove') }}" method="POST" class="mt-1">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary btn-sm btn-block"
                                    onclick="return confirm('Remove profile photo?')">
                                <i class="fas fa-trash-alt mr-1"></i>Remove Photo
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        {{-- INFO + PASSWORD --}}
        <div class="col-md-8 mb-3">
            {{-- UPDATE INFO --}}
            <div class="card shadow-sm rounded-lg border-0 mb-3">
                <div class="card-header bg-white border-bottom d-flex align-items-center py-3">
                    <div style="width:38px;height:38px;background:rgba(40,167,69,0.12);border-radius:10px;display:flex;align-items:center;justify-content:center;margin-right:12px;">
                        <i class="fas fa-user-edit text-success"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 font-weight-bold">Personal Information</h5>
                        <small class="text-muted">Update your name and email address</small>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.profile.update-info') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label><i class="fas fa-user mr-1 text-muted"></i>Full Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $user->name) }}" required>
                            @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-envelope mr-1 text-muted"></i>Email Address</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $user->email) }}" required>
                            @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" class="btn btn-success px-4">
                            <i class="fas fa-save mr-1"></i>Save Changes
                        </button>
                    </form>
                </div>
            </div>

            {{-- UPDATE PASSWORD --}}
            <div class="card shadow-sm rounded-lg border-0">
                <div class="card-header bg-white border-bottom d-flex align-items-center py-3">
                    <div style="width:38px;height:38px;background:rgba(220,53,69,0.12);border-radius:10px;display:flex;align-items:center;justify-content:center;margin-right:12px;">
                        <i class="fas fa-lock text-danger"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 font-weight-bold">Change Password</h5>
                        <small class="text-muted">Use a strong password you haven't used elsewhere</small>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.profile.update-password') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label><i class="fas fa-key mr-1 text-muted"></i>Current Password</label>
                            <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                            @error('current_password') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-lock mr-1 text-muted"></i>New Password</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                            @error('password') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-check-circle mr-1 text-muted"></i>Confirm New Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-danger px-4">
                            <i class="fas fa-sync-alt mr-1"></i>Change Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
<script>
    document.getElementById('photo')?.addEventListener('change', function(e) {
        var label = this.nextElementSibling;
        if (label) label.textContent = e.target.files[0]?.name || 'Choose photo';
        var reader = new FileReader();
        reader.onload = function(ev) {
            document.getElementById('profilePreview')?.setAttribute('src', ev.target.result);
        };
        if (e.target.files[0]) reader.readAsDataURL(e.target.files[0]);
    });
</script>
@stop