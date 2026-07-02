@extends('adminlte::page')

@section('title', 'Landing Page Settings')

@section('content_header')
    <div class="d-flex align-items-center">
        <div class="mr-3" style="width:56px;height:56px;background:linear-gradient(135deg,#6610f2,#6f42c1);border-radius:12px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 15px rgba(102,16,242,0.3);">
            <i class="fas fa-landmark text-white" style="font-size:24px;"></i>
        </div>
        <div>
            <h1 class="mb-0" style="font-weight:600;">Landing Page Settings</h1>
            <small class="text-muted">Customize the public-facing homepage content</small>
        </div>
    </div>
@stop

@section('content')
    <form action="{{ route('admin.landing-page.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card shadow-sm border-0 rounded-lg">
            <div class="card-header bg-white p-0">
                <ul class="nav nav-tabs card-header-tabs m-0 px-3 pt-3" id="landingTabs" role="tablist">
                    <li class="nav-item"><a class="nav-link active" id="tab-hero" data-toggle="tab" href="#hero"><i class="fas fa-play-circle"></i> Hero</a></li>
                    <li class="nav-item"><a class="nav-link" id="tab-about" data-toggle="tab" href="#about"><i class="fas fa-info-circle"></i> About</a></li>
                    <li class="nav-item"><a class="nav-link" id="tab-blood" data-toggle="tab" href="#blood"><i class="fas fa-tint"></i> Blood Groups</a></li>
                    <li class="nav-item"><a class="nav-link" id="tab-campaigns" data-toggle="tab" href="#campaigns"><i class="fas fa-calendar-alt"></i> Campaigns</a></li>
                    <li class="nav-item"><a class="nav-link" id="tab-how" data-toggle="tab" href="#how"><i class="fas fa-arrow-right"></i> How It Works</a></li>
                    <li class="nav-item"><a class="nav-link" id="tab-testimonials" data-toggle="tab" href="#testimonials"><i class="fas fa-quote-left"></i> Testimonials</a></li>
                    <li class="nav-item"><a class="nav-link" id="tab-cta" data-toggle="tab" href="#cta"><i class="fas fa-phone-alt"></i> CTA / Contact</a></li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    {{-- Hero Tab --}}
                    <div class="tab-pane fade show active" id="hero">
                        <h5 class="mb-3"><i class="fas fa-play-circle text-danger"></i> Hero Section</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <x-adminlte-input name="hero_tagline" label="Tagline" value="{{ $settings['hero_tagline'] ?? '' }}" placeholder="e.g. Save Lives"/>
                                <x-adminlte-input name="hero_sub_tagline" label="Main Heading" value="{{ $settings['hero_sub_tagline'] ?? '' }}" placeholder="e.g. Every Drop Counts"/>
                                <x-adminlte-textarea name="hero_description" label="Description" rows="3">{{ $settings['hero_description'] ?? '' }}</x-adminlte-textarea>
                                <x-adminlte-input name="hero_cta_text" label="Primary CTA Text" value="{{ $settings['hero_cta_text'] ?? 'Donate Blood' }}"/>
                                <x-adminlte-input name="hero_cta_url" label="Primary CTA URL" value="{{ $settings['hero_cta_url'] ?? '/portal/register' }}"/>
                                <x-adminlte-input name="hero_secondary_cta" label="Secondary CTA Text" value="{{ $settings['hero_secondary_cta'] ?? 'Learn More' }}"/>
                            </div>
                            <div class="col-md-6">
                                <x-adminlte-input name="hero_stat_1_value" label="Stat 1 - Value" value="{{ $settings['hero_stat_1_value'] ?? '5000+' }}"/>
                                <x-adminlte-input name="hero_stat_1_label" label="Stat 1 - Label" value="{{ $settings['hero_stat_1_label'] ?? 'Lives Saved' }}"/>
                                <x-adminlte-input name="hero_stat_2_value" label="Stat 2 - Value" value="{{ $settings['hero_stat_2_value'] ?? '2000+' }}"/>
                                <x-adminlte-input name="hero_stat_2_label" label="Stat 2 - Label" value="{{ $settings['hero_stat_2_label'] ?? 'Donors Registered' }}"/>
                                <x-adminlte-input name="hero_stat_3_value" label="Stat 3 - Value" value="{{ $settings['hero_stat_3_value'] ?? '150+' }}"/>
                                <x-adminlte-input name="hero_stat_3_label" label="Stat 3 - Label" value="{{ $settings['hero_stat_3_label'] ?? 'Drives Organized' }}"/>
                                <div class="form-group">
                                    <label>Background Image</label>
                                    <input type="file" name="hero_bg_image" class="form-control-file">
                                    @if(!empty($settings['hero_bg_image']) && file_exists(storage_path('app/public/' . $settings['hero_bg_image'])))
                                        <small class="text-muted d-block mt-1">Current: <a href="{{ asset('storage/' . $settings['hero_bg_image']) }}" target="_blank">{{ $settings['hero_bg_image'] }}</a></small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- About Tab --}}
                    <div class="tab-pane fade" id="about">
                        <h5 class="mb-3"><i class="fas fa-info-circle text-info"></i> About Section</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <x-adminlte-input name="about_eyebrow" label="Eyebrow Text" value="{{ $settings['about_eyebrow'] ?? 'Who We Are' }}"/>
                                <x-adminlte-input name="about_heading" label="Heading" value="{{ $settings['about_heading'] ?? '' }}"/>
                                <x-adminlte-textarea name="about_body" label="Body Text" rows="5">{{ $settings['about_body'] ?? '' }}</x-adminlte-textarea>
                                <x-adminlte-input name="about_founded_year" label="Founded Year" value="{{ $settings['about_founded_year'] ?? '2018' }}"/>
                            </div>
                            <div class="col-md-6">
                                <x-adminlte-textarea name="about_mission" label="Mission Statement" rows="3">{{ $settings['about_mission'] ?? '' }}</x-adminlte-textarea>
                                <x-adminlte-input name="about_cta_text" label="CTA Button Text" value="{{ $settings['about_cta_text'] ?? 'Our Story' }}"/>
                                <div class="form-group">
                                    <label>Image 1 (Main)</label>
                                    <input type="file" name="about_image_1" class="form-control-file">
                                    @if(!empty($settings['about_image_1']) && file_exists(storage_path('app/public/' . $settings['about_image_1'])))
                                        <small class="text-muted d-block mt-1">Current: <a href="{{ asset('storage/' . $settings['about_image_1']) }}" target="_blank">{{ $settings['about_image_1'] }}</a></small>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>Image 2 (Overlay)</label>
                                    <input type="file" name="about_image_2" class="form-control-file">
                                    @if(!empty($settings['about_image_2']) && file_exists(storage_path('app/public/' . $settings['about_image_2'])))
                                        <small class="text-muted d-block mt-1">Current: <a href="{{ asset('storage/' . $settings['about_image_2']) }}" target="_blank">{{ $settings['about_image_2'] }}</a></small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Blood Groups Tab --}}
                    <div class="tab-pane fade" id="blood">
                        <h5 class="mb-3"><i class="fas fa-tint text-danger"></i> Blood Groups Section</h5>
                        <p class="text-muted">This section displays data dynamically from the donors and inventory tables.</p>
                        <x-adminlte-input name="blood_section_heading" label="Section Heading" value="{{ $settings['blood_section_heading'] ?? 'Blood Groups Availability' }}"/>
                    </div>

                    {{-- Campaigns Tab --}}
                    <div class="tab-pane fade" id="campaigns">
                        <h5 class="mb-3"><i class="fas fa-calendar-alt text-success"></i> Campaigns Section</h5>
                        <p class="text-muted">Upcoming drives are pulled automatically from the Campaigns module. Only campaigns with <code>is_featured = true</code> are shown on the landing page. <a href="{{ route('admin.campaigns.index') }}" class="text-danger">Manage Campaigns</a></p>
                        <x-adminlte-input name="campaigns_section_heading" label="Section Heading" value="{{ $settings['campaigns_section_heading'] ?? 'Upcoming Blood Drives' }}"/>
                    </div>

                    {{-- How It Works Tab --}}
                    <div class="tab-pane fade" id="how">
                        <h5 class="mb-3"><i class="fas fa-arrow-right text-warning"></i> How It Works (4 Steps)</h5>
                        @for($i = 1; $i <= 4; $i++)
                            <div class="card mb-3 border-0 shadow-sm">
                                <div class="card-body">
                                    <h6 class="text-danger font-weight-bold">Step {{ $i }}</h6>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <x-adminlte-input name="how_step_{{ $i }}_icon" label="Icon Class" value="{{ $settings['how_step_' . $i . '_icon'] ?? '' }}" placeholder="e.g. fas fa-user-plus"/>
                                        </div>
                                        <div class="col-md-4">
                                            <x-adminlte-input name="how_step_{{ $i }}_title" label="Title" value="{{ $settings['how_step_' . $i . '_title'] ?? '' }}"/>
                                        </div>
                                        <div class="col-md-4">
                                            <x-adminlte-input name="how_step_{{ $i }}_desc" label="Description" value="{{ $settings['how_step_' . $i . '_desc'] ?? '' }}"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>

                    {{-- Testimonials Tab --}}
                    <div class="tab-pane fade" id="testimonials">
                        <h5 class="mb-3"><i class="fas fa-quote-left text-purple"></i> Testimonials Section</h5>
                        <p class="text-muted">Stories are managed separately. <a href="{{ route('admin.donor-stories.index') }}" class="text-danger">Manage Donor Stories</a></p>
                        <x-adminlte-input name="testimonials_section_heading" label="Section Heading" value="{{ $settings['testimonials_section_heading'] ?? 'Hear From Our Heroes' }}"/>
                    </div>

                    {{-- CTA Tab --}}
                    <div class="tab-pane fade" id="cta">
                        <h5 class="mb-3"><i class="fas fa-phone-alt text-success"></i> CTA / Contact Section</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <x-adminlte-input name="cta_heading" label="Heading" value="{{ $settings['cta_heading'] ?? 'Ready to Make a Difference?' }}"/>
                                <x-adminlte-input name="cta_subheading" label="Subheading" value="{{ $settings['cta_subheading'] ?? '' }}"/>
                                <x-adminlte-input name="cta_donate_btn_text" label="Donate Button Text" value="{{ $settings['cta_donate_btn_text'] ?? 'Donate Blood Now' }}"/>
                                <x-adminlte-input name="cta_register_btn_text" label="Register Button Text" value="{{ $settings['cta_register_btn_text'] ?? 'Register as Donor' }}"/>
                            </div>
                            <div class="col-md-6">
                                <x-adminlte-input name="cta_phone" label="Phone Number" value="{{ $settings['cta_phone'] ?? '' }}"/>
                                <x-adminlte-input name="cta_whatsapp" label="WhatsApp Number" value="{{ $settings['cta_whatsapp'] ?? '' }}"/>
                                <x-adminlte-input name="cta_email" label="Email Address" value="{{ $settings['cta_email'] ?? '' }}"/>
                                <div class="form-group">
                                    <label>Background Image</label>
                                    <input type="file" name="cta_bg_image" class="form-control-file">
                                    @if(!empty($settings['cta_bg_image']) && file_exists(storage_path('app/public/' . $settings['cta_bg_image'])))
                                        <small class="text-muted d-block mt-1">Current: <a href="{{ asset('storage/' . $settings['cta_bg_image']) }}" target="_blank">{{ $settings['cta_bg_image'] }}</a></small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-right mb-4">
            <x-adminlte-button type="submit" label="Save All Settings" theme="danger" icon="fas fa-save"/>
            <a href="{{ route('portal.landing') }}" class="btn btn-info" target="_blank"><i class="fas fa-eye"></i> Preview Landing Page</a>
        </div>
    </form>
@stop

@section('css')
<style>
.nav-tabs .nav-link { border: none; color: #666; font-weight: 500; padding: 12px 20px; border-radius: 8px 8px 0 0; }
.nav-tabs .nav-link.active { color: #dc3545; font-weight: 600; background: transparent; border-bottom: 3px solid #dc3545; }
.nav-tabs .nav-link i { margin-right: 6px; }
.card-header-tabs { border-bottom: 1px solid #eee; }
</style>
@stop
