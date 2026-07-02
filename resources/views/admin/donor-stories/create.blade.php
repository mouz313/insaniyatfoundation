@extends('adminlte::page')

@section('title', 'Add Donor Story')

@section('content_header')
    <div class="d-flex align-items-center">
        <div class="mr-3" style="width:56px;height:56px;background:linear-gradient(135deg,#e83e8c,#d63384);border-radius:12px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 15px rgba(232,62,140,0.3);">
            <i class="fas fa-quote-right text-white" style="font-size:24px;"></i>
        </div>
        <div>
            <h1 class="mb-0" style="font-weight:600;">Add Donor Story</h1>
            <small class="text-muted">Share a donor's testimonial on the landing page</small>
        </div>
    </div>
@stop

@section('content')
    <form action="{{ route('admin.donor-stories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card shadow-sm border-0 rounded-lg">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <x-adminlte-input name="name" label="Donor Name" placeholder="Enter full name" value="{{ old('name') }}" required/>
                        <x-adminlte-textarea name="quote" label="Quote / Testimonial" rows="4" placeholder="What did the donor say?" required>{{ old('quote') }}</x-adminlte-textarea>
                        <x-adminlte-select name="blood_group" label="Blood Group">
                            <option value="">Select</option>
                            @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                                <option value="{{ $bg }}" {{ old('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                            @endforeach
                        </x-adminlte-select>
                    </div>
                    <div class="col-md-6">
                        <x-adminlte-input name="city" label="City" placeholder="e.g. Lahore" value="{{ old('city') }}"/>
                        <x-adminlte-input name="donations_count" label="Donations Count" type="number" value="{{ old('donations_count', 1) }}"/>
                        <div class="form-group">
                            <label>Photo</label>
                            <input type="file" name="photo" class="form-control-file" accept="image/*">
                            <small class="text-muted">Photo will be cropped to 200x200px.</small>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Active</label>
                                    <div class="custom-control custom-switch custom-switch-off-secondary custom-switch-on-success" style="padding-top:6px;">
                                        <input type="checkbox" class="custom-control-input" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} id="is_active">
                                        <label class="custom-control-label" for="is_active">Show on landing page</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <x-adminlte-input name="sort_order" label="Sort Order" type="number" value="{{ old('sort_order', 0) }}"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-right mb-4">
            <x-adminlte-button type="submit" label="Save Story" theme="success" icon="fas fa-save"/>
            <a href="{{ route('admin.donor-stories.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
        </div>
    </form>
@stop
