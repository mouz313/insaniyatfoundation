@extends('adminlte::page')

@section('title', 'Edit Donor Story')

@section('content_header')
    <div class="d-flex align-items-center">
        <div class="mr-3" style="width:56px;height:56px;background:linear-gradient(135deg,#e83e8c,#d63384);border-radius:12px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 15px rgba(232,62,140,0.3);">
            <i class="fas fa-quote-right text-white" style="font-size:24px;"></i>
        </div>
        <div>
            <h1 class="mb-0" style="font-weight:600;">Edit Story: {{ $donorStory->name }}</h1>
            <small class="text-muted">Update donor testimonial</small>
        </div>
    </div>
@stop

@section('content')
    <form action="{{ route('admin.donor-stories.update', $donorStory) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card shadow-sm border-0 rounded-lg">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <x-adminlte-input name="name" label="Donor Name" placeholder="Enter full name" value="{{ old('name', $donorStory->name) }}" required/>
                        <x-adminlte-textarea name="quote" label="Quote / Testimonial" rows="4" placeholder="What did the donor say?" required>{{ old('quote', $donorStory->quote) }}</x-adminlte-textarea>
                        <x-adminlte-select name="blood_group" label="Blood Group">
                            <option value="">Select</option>
                            @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                                <option value="{{ $bg }}" {{ old('blood_group', $donorStory->blood_group) == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                            @endforeach
                        </x-adminlte-select>
                    </div>
                    <div class="col-md-6">
                        <x-adminlte-input name="city" label="City" value="{{ old('city', $donorStory->city) }}"/>
                        <x-adminlte-input name="donations_count" label="Donations Count" type="number" value="{{ old('donations_count', $donorStory->donations_count) }}"/>
                        <div class="form-group">
                            <label>Photo</label>
                            <input type="file" name="photo" class="form-control-file" accept="image/*">
                            @if($donorStory->photo)
                                <div class="mt-2"><img src="{{ asset('storage/' . $donorStory->photo) }}" alt="" style="width:80px;height:80px;border-radius:50%;object-fit:cover;"></div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Active</label>
                                    <div class="custom-control custom-switch custom-switch-off-secondary custom-switch-on-success" style="padding-top:6px;">
                                        <input type="checkbox" class="custom-control-input" name="is_active" value="1" {{ old('is_active', $donorStory->is_active) ? 'checked' : '' }} id="is_active">
                                        <label class="custom-control-label" for="is_active">Show on landing page</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <x-adminlte-input name="sort_order" label="Sort Order" type="number" value="{{ old('sort_order', $donorStory->sort_order) }}"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-right mb-4">
            <x-adminlte-button type="submit" label="Update Story" theme="warning" icon="fas fa-save"/>
            <a href="{{ route('admin.donor-stories.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
        </div>
    </form>
@stop
