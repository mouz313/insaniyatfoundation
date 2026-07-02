@extends('adminlte::page')

@section('title', 'Settings')

@section('content_header')
    <div class="d-flex align-items-center">
        <div class="mr-3" style="width: 56px; height: 56px; background: linear-gradient(135deg, #6f42c1, #b66dff); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(111,66,193,0.3);">
            <i class="fas fa-cog text-white" style="font-size: 24px;"></i>
        </div>
        <div>
            <h1 class="mb-0" style="font-weight: 600;">Settings</h1>
            <small class="text-muted"><i class="fas fa-fw fa-wrench"></i> Configure application settings &amp; run maintenance commands</small>
        </div>
    </div>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-lg shadow-sm" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-lg shadow-sm" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    @if($output = session('command_output'))
        <div class="card border-{{ $output['success'] ? 'success' : 'danger' }} shadow-sm rounded-lg mb-4">
            <div class="card-header bg-{{ $output['success'] ? 'success' : 'danger' }} text-white py-2">
                <i class="fas fa-{{ $output['success'] ? 'check-circle' : 'times-circle' }}"></i>
                {{ $output['signature'] }} &mdash; {{ $output['message'] }}
            </div>
            <div class="card-body bg-dark p-3">
                <pre class="mb-0" style="color: #0f0; font-size: 13px; white-space: pre-wrap; word-break: break-all; max-height: 300px; overflow-y: auto;">{{ $output['output'] ?: 'No output.' }}</pre>
            </div>
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-lg">
        <div class="card-header bg-white border-bottom-0 pt-3 pb-0">
            <ul class="nav nav-tabs card-header-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">
                        <i class="fas fa-wrench mr-1"></i> General
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="commands-tab" data-toggle="tab" href="#commands" role="tab" aria-controls="commands" aria-selected="false">
                        <i class="fas fa-terminal mr-1"></i> Commands
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body tab-content">
            {{-- GENERAL TAB --}}
            <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card shadow-sm border-0 rounded-lg" style="border-top: 3px solid #6f42c1;">
                                <div class="card-header bg-white border-bottom-0 pt-3">
                                    <h5 class="mb-0"><i class="fas fa-building text-purple"></i> Organization</h5>
                                </div>
                                <div class="card-body">
                                    <x-adminlte-input name="ngo_name" label="NGO Name" value="{{ $settings['ngo_name'] ?? '' }}" placeholder="Enter NGO name"/>
                                    <x-adminlte-textarea name="ngo_address" label="Address" rows="3" placeholder="Enter address">{{ $settings['ngo_address'] ?? '' }}</x-adminlte-textarea>
                                    <x-adminlte-input name="blood_groups" label="Blood Groups (comma separated)" value="{{ $settings['blood_groups'] ?? 'A+,A-,B+,B-,AB+,AB-,O+,O-' }}"/>
                                    <x-adminlte-input name="donation_cooldown_days" label="Donation Cooldown (Days)" type="number" value="{{ $settings['donation_cooldown_days'] ?? 90 }}"/>
                                </div>
                            </div>

                            <div class="card shadow-sm border-0 rounded-lg mt-3" style="border-top: 3px solid #28a745;">
                                <div class="card-header bg-white border-bottom-0 pt-3">
                                    <h5 class="mb-0"><i class="fas fa-paint-brush text-success"></i> Branding</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>NGO Logo</label>
                                        <div class="d-flex align-items-center mb-2">
                                            @if(isset($settings['ngo_logo']) && $settings['ngo_logo'])
                                                <img src="{{ asset('storage/' . $settings['ngo_logo']) }}" alt="Logo" style="max-width: 120px; max-height: 60px; border: 1px solid #dee2e6; border-radius: 6px; padding: 4px; background: #fff;">
                                                <label class="ml-3 mb-0">
                                                    <a href="{{ route('admin.settings.remove-logo') }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Remove logo?')"><i class="fas fa-trash"></i></a>
                                                </label>
                                            @else
                                                <div style="width: 120px; height: 60px; border: 2px dashed #dee2e6; border-radius: 6px; display: flex; align-items: center; justify-content: center; background: #f8f9fa;">
                                                    <small class="text-muted">No logo</small>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="custom-file">
                                            <input type="file" name="logo" class="custom-file-input" id="logoInput" accept="image/png,image/jpg,image/jpeg,image/svg">
                                            <label class="custom-file-label" for="logoInput">Choose new logo...</label>
                                        </div>
                                        <small class="text-muted">PNG, JPG, or SVG. Max 2MB. Will be displayed on donor cards and portal.</small>
                                    </div>

                                    <div class="form-group mt-3">
                                        <label>Favicon</label>
                                        <div class="d-flex align-items-center mb-2">
                                            @if(isset($settings['favicon']) && $settings['favicon'])
                                                <img src="{{ asset('storage/' . $settings['favicon']) }}" alt="Favicon" style="width: 32px; height: 32px; border: 1px solid #dee2e6; border-radius: 4px; padding: 2px; background: #fff;">
                                                <a href="{{ route('admin.settings.remove-favicon') }}" class="btn btn-sm btn-outline-danger ml-3" onclick="return confirm('Remove favicon?')"><i class="fas fa-trash"></i></a>
                                            @else
                                                <div style="width: 32px; height: 32px; border: 2px dashed #dee2e6; border-radius: 4px; display: flex; align-items: center; justify-content: center; background: #f8f9fa;">
                                                    <small class="text-muted" style="font-size: 8px;">None</small>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="custom-file">
                                            <input type="file" name="favicon" class="custom-file-input" id="faviconInput" accept="image/png,image/ico,image/jpg,image/jpeg">
                                            <label class="custom-file-label" for="faviconInput">Choose new favicon...</label>
                                        </div>
                                        <small class="text-muted">PNG or ICO. Max 1MB. Browser tab icon.</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card shadow-sm border-0 rounded-lg" style="border-top: 3px solid #17a2b8;">
                                <div class="card-header bg-white border-bottom-0 pt-3">
                                    <h5 class="mb-0"><i class="fas fa-sms text-info"></i> SMS Gateway</h5>
                                </div>
                                <div class="card-body">
                                    <x-adminlte-select name="sms_gateway" label="SMS Gateway Provider">
                                        <option value="">None</option>
                                        <option value="twilio" {{ ($settings['sms_gateway'] ?? '') == 'twilio' ? 'selected' : '' }}>Twilio</option>
                                        <option value="bulk_sms" {{ ($settings['sms_gateway'] ?? '') == 'bulk_sms' ? 'selected' : '' }}>BulkSMS.pk</option>
                                    </x-adminlte-select>
                                    <x-adminlte-input name="sms_api_key" label="API Key" value="{{ $settings['sms_api_key'] ?? '' }}"/>
                                    <x-adminlte-input name="sms_api_secret" label="API Secret" value="{{ $settings['sms_api_secret'] ?? '' }}"/>
                                    <x-adminlte-input name="sms_sender_id" label="Sender ID" value="{{ $settings['sms_sender_id'] ?? '' }}"/>
                                </div>
                            </div>

                            <div class="card shadow-sm border-0 rounded-lg mt-3" style="border-top: 3px solid #ffc107;">
                                <div class="card-header bg-white border-bottom-0 pt-3">
                                    <h5 class="mb-0"><i class="fas fa-id-card text-warning"></i> Card Template</h5>
                                </div>
                                <div class="card-body">
                                    <x-adminlte-select name="card_template" label="Card Template">
                                        <option value="template1" {{ ($settings['card_template'] ?? '') == 'template1' ? 'selected' : '' }}>Template 1 (Default)</option>
                                        <option value="template2" {{ ($settings['card_template'] ?? '') == 'template2' ? 'selected' : '' }}>Template 2 (Modern)</option>
                                    </x-adminlte-select>
                                </div>
                            </div>

                            <div class="card shadow-sm border-0 rounded-lg mt-3" style="border-top: 3px solid #343a40;">
                                <div class="card-header bg-white border-bottom-0 pt-3">
                                    <h5 class="mb-0"><i class="fas fa-shoe-prints text-dark"></i> Footer</h5>
                                </div>
                                <div class="card-body">
                                    <x-adminlte-textarea name="footer_text" label="Footer Text" rows="2" placeholder="e.g. Serving humanity since 2010">{{ $settings['footer_text'] ?? '' }}</x-adminlte-textarea>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <x-adminlte-input name="footer_email" label="Contact Email" type="email" placeholder="info@example.com" value="{{ $settings['footer_email'] ?? '' }}"/>
                                        </div>
                                        <div class="col-md-6">
                                            <x-adminlte-input name="footer_phone" label="Contact Phone" placeholder="+92-xxx-xxxxxxx" value="{{ $settings['footer_phone'] ?? '' }}"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-12">
                            <div class="card shadow-sm border-0 rounded-lg">
                                <div class="card-body text-center">
                                    <x-adminlte-button type="submit" label="Save All Settings" theme="success" icon="fas fa-save"/>
                                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary ml-2"><i class="fas fa-times"></i> Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            {{-- COMMANDS TAB --}}
            <div class="tab-pane fade" id="commands" role="tabpanel" aria-labelledby="commands-tab">
                <div class="row">
                    @foreach($commands as $cmd)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card shadow-sm border-0 rounded-lg h-100" style="border-top: 3px solid {{ $cmd['color'] }}; transition: transform 0.2s ease, box-shadow 0.2s ease;">
                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex align-items-center mb-3">
                                        <div style="width: 48px; height: 48px; background: {{ $cmd['color'] }}20; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                            <i class="fas {{ $cmd['icon'] }}" style="color: {{ $cmd['color'] }}; font-size: 20px;"></i>
                                        </div>
                                        <div class="ml-3">
                                            <h5 class="mb-0" style="font-weight: 600;">{{ $cmd['label'] }}</h5>
                                            <small class="text-muted"><code>{{ $cmd['signature'] }}</code></small>
                                        </div>
                                    </div>
                                    <p class="text-muted small mb-3 flex-grow-1">{{ $cmd['description'] }}</p>
                                    <form action="{{ route('admin.settings.run-command') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="signature" value="{{ $cmd['signature'] }}">
                                        <button type="submit" class="btn btn-block" style="background: {{ $cmd['color'] }}; color: #fff; border-radius: 20px; padding: 6px 16px;" onclick="return confirm('Run &ldquo;{{ $cmd['signature'] }}&rdquo;?')">
                                            <i class="fas fa-play mr-1"></i> Run
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
<style>
.card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
.card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important; }
.text-purple { color: #6f42c1; }
.text-dark { color: #343a40; }
.custom-file-label::after { content: "Browse"; }
.nav-tabs .nav-link { border: none; color: #6c757d; font-weight: 500; padding: 10px 20px; }
.nav-tabs .nav-link:hover { border: none; color: #495057; }
.nav-tabs .nav-link.active { color: #6f42c1; border-bottom: 3px solid #6f42c1; background: transparent; }
</style>
@stop

@section('js')
<script>
$(function() {
    $('.custom-file-input').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        $(this).siblings('.custom-file-label').addClass('selected').html(fileName);
    });
});
</script>
@stop