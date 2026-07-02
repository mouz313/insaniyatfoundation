<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register as Donor</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        :root {
            --primary: #1A6B2E;
            --primary-dark: #145A26;
            --primary-light: #28a745;
            --secondary: #1a1a2e;
            --gradient-hero: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            --gradient-primary: linear-gradient(135deg, #1A6B2E, #28a745);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f4f6f9; min-height: 100vh; }
        .reg-wrapper { max-width: 860px; margin: 0 auto; padding: 40px 20px 60px; }

        .reg-header { text-align: center; margin-bottom: 40px; }
        .reg-header .icon-wrap { width: 80px; height: 80px; border-radius: 20px; background: var(--gradient-primary); display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; box-shadow: 0 10px 30px rgba(26,107,46,0.3); }
        .reg-header .icon-wrap i { font-size: 36px; color: #fff; }
        .reg-header h1 { font-size: 2.2rem; font-weight: 800; color: var(--secondary); margin-bottom: 8px; }
        .reg-header p { color: #888; font-size: 1rem; }

        .referrer-card { background: #fff; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #f0f0f0; margin-bottom: 24px; padding: 20px 28px; }
        .referrer-card .ref-label { font-size: 13px; font-weight: 600; color: #444; margin-bottom: 8px; }

        .section-card { background: #fff; border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #f0f0f0; margin-bottom: 24px; overflow: hidden; transition: box-shadow 0.3s ease; }
        .section-card:hover { box-shadow: 0 8px 30px rgba(0,0,0,0.1); }
        .section-card-header { display: flex; align-items: center; gap: 12px; padding: 20px 28px; border-bottom: 1px solid #f0f0f0; }
        .section-card-header .num { width: 32px; height: 32px; border-radius: 50%; background: var(--gradient-primary); color: #fff; font-size: 14px; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .section-card-header h3 { font-size: 1.05rem; font-weight: 700; color: var(--secondary); margin: 0; }
        .section-card-body { padding: 24px 28px; }

        .form-label { font-size: 13px; font-weight: 600; color: #444; margin-bottom: 6px; }
        .form-control, .form-select { border-radius: 10px; padding: 10px 14px; border: 1.5px solid #e9ecef; font-size: 14px; transition: all 0.2s; }
        .form-control:focus, .form-select:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(26,107,46,0.1); }
        .form-control.is-invalid, .form-select.is-invalid { border-color: var(--primary); }
        textarea.form-control { resize: vertical; }

        .btn-submit { background: var(--gradient-primary); color: #fff; border: none; padding: 14px 40px; border-radius: 50px; font-weight: 600; font-size: 16px; transition: all 0.3s ease; cursor: pointer; }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(26,107,46,0.4); color: #fff; }
        .btn-submit:active { transform: translateY(0); }
        .btn-outline-primary { border: 2px solid var(--primary); color: var(--primary); background: transparent; border-radius: 50px; padding: 8px 20px; font-weight: 600; font-size: 13px; transition: all 0.2s; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; }
        .btn-outline-primary:hover { background: var(--primary); color: #fff; }

        .reg-footer-card { background: #fff; border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #f0f0f0; padding: 20px 28px; text-align: center; }
        .reg-footer-card a { color: var(--primary); text-decoration: none; font-weight: 500; }
        .reg-footer-card a:hover { text-decoration: underline; }

        .back-link { display: inline-flex; align-items: center; gap: 8px; color: #888; text-decoration: none; font-size: 14px; font-weight: 500; margin-bottom: 24px; transition: color 0.2s; }
        .back-link:hover { color: var(--primary); }

        .alert-danger { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; border-radius: 12px; padding: 14px 18px; font-size: 14px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }

        .ref-search-input { border-radius: 10px 0 0 10px !important; }
        .ref-search-btn { border-radius: 0 10px 10px 0 !important; background: var(--gradient-primary); color: #fff; border: none; padding: 10px 18px; font-weight: 600; font-size: 13px; cursor: pointer; }
        .ref-search-btn:hover { opacity: 0.9; }
        .ref-result-item { padding: 10px 14px; border: 1px solid #e9ecef; border-radius: 10px; cursor: pointer; transition: all 0.15s; }
        .ref-result-item:hover { border-color: var(--primary); background: rgba(26,107,46,0.04); }
        .ref-result-item.selected { border-color: var(--primary); background: rgba(26,107,46,0.08); }
        .ref-selected { background: #f0fdf4; border: 1.5px solid var(--primary-light); border-radius: 12px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between; }
        .ref-selected-info { display: flex; align-items: center; gap: 12px; }
        .ref-selected-avatar { width: 36px; height: 36px; border-radius: 50%; background: var(--gradient-primary); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 15px; }
        .no-referral-check { display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 14px; color: #888; }

        .flag-checkbox-wrap { display: flex; align-items: flex-start; gap: 8px; padding: 10px 14px; border: 1.5px solid #e9ecef; border-radius: 10px; cursor: pointer; transition: all 0.15s; }
        .flag-checkbox-wrap:hover { border-color: var(--primary); }
        .flag-checkbox-wrap.checked { border-color: var(--primary); background: rgba(26,107,46,0.04); }

        .photo-upload-area { border: 2px dashed #e9ecef; border-radius: 16px; padding: 30px; text-align: center; cursor: pointer; transition: all 0.2s; }
        .photo-upload-area:hover { border-color: var(--primary); background: rgba(26,107,46,0.02); }
        .photo-preview { width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid var(--primary-light); }

        .custom-switch-wrap { display: flex; align-items: center; gap: 10px; }
        .custom-switch-wrap input[type="checkbox"] { width: 44px; height: 24px; -webkit-appearance: none; appearance: none; background: #ddd; border-radius: 12px; position: relative; cursor: pointer; transition: background 0.2s; }
        .custom-switch-wrap input[type="checkbox"]::before { content: ''; position: absolute; width: 20px; height: 20px; border-radius: 50%; top: 2px; left: 2px; background: #fff; transition: transform 0.2s; }
        .custom-switch-wrap input[type="checkbox"]:checked { background: var(--gradient-primary); }
        .custom-switch-wrap input[type="checkbox"]:checked::before { transform: translateX(20px); }
        .custom-switch-wrap .switch-label { font-size: 14px; font-weight: 500; color: #444; user-select: none; }

        .months-helper { display: inline-block; background: #f0fdf4; border: 1px solid #d1fae5; border-radius: 8px; padding: 4px 12px; font-size: 12px; color: var(--primary-dark); margin-top: 4px; }

        @media (max-width: 576px) {
            .reg-wrapper { padding: 20px 16px 40px; }
            .reg-header h1 { font-size: 1.6rem; }
            .section-card-body { padding: 16px; }
            .section-card-header { padding: 16px; }
        }
    </style>
</head>
<body>
    <div class="reg-wrapper">
        <a href="{{ url('/') }}" class="back-link"><i class="fas fa-arrow-left"></i> Back to Home</a>

        <div class="reg-header">
            <div class="icon-wrap"><i class="fas fa-tint"></i></div>
            <h1>Become a Blood Donor</h1>
            <p>Register yourself to save lives — it takes just 2 minutes</p>
        </div>

        @if(session('error'))
            <div class="alert-danger"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="alert-danger"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}</div>
        @endif

        <form action="{{ route('portal.registration.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Referred Donor Search --}}
            <div class="referrer-card">
                <div class="ref-label"><i class="fas fa-user-friends me-1" style="color:var(--primary);"></i>Referred By (optional)</div>
                <div class="row g-2 align-items-center">
                    <div class="col-md-8">
                        <div class="input-group">
                            <input type="text" id="refSearch" class="form-control ref-search-input" placeholder="Search by CNIC or Phone (min 3 chars)" value="{{ old('ref_search') }}">
                            <button type="button" id="refSearchBtn" class="ref-search-btn"><i class="fas fa-search"></i> Search</button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="no-referral-check">
                            <input type="checkbox" id="noReferral" {{ old('no_referral') ? 'checked' : '' }}>
                            <i class="fas fa-ban" style="color:#999;"></i> No Referred Donor
                        </label>
                    </div>
                </div>
                <div id="refResults" class="row g-2 mt-2" style="display:none;"></div>
                <div id="refSelected" class="mt-2" style="display:none;">
                    <div class="ref-selected">
                        <div class="ref-selected-info">
                            <div class="ref-selected-avatar"><i class="fas fa-user"></i></div>
                            <div>
                                <strong id="refSelectedName" style="font-size:14px;color:var(--secondary);"></strong>
                                <div id="refSelectedDetail" style="font-size:12px;color:#888;"></div>
                            </div>
                        </div>
                        <button type="button" id="refChangeBtn" class="btn-outline-primary" style="font-size:12px;padding:4px 14px;"><i class="fas fa-sync-alt"></i> Change</button>
                    </div>
                </div>
                <input type="hidden" name="referred_by" id="referredByInput" value="{{ old('referred_by') }}">
                <input type="hidden" name="no_referral" id="noReferralInput" value="{{ old('no_referral') ? '1' : '0' }}">
            </div>

            {{-- Section 1: Personal Information --}}
            <div class="section-card">
                <div class="section-card-header">
                    <div class="num">1</div>
                    <h3><i class="fas fa-user me-2" style="color:var(--primary);"></i>Personal Information</h3>
                </div>
                <div class="section-card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="John Doe" required>
                            @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Father's Name</label>
                            <input type="text" name="father_name" class="form-control" value="{{ old('father_name') }}" placeholder="Father's name">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">CNIC <span class="text-danger">*</span></label>
                            <input type="text" name="cnic" class="form-control @error('cnic') is-invalid @enderror" value="{{ old('cnic') }}" placeholder="XXXXX-XXXXXXX-X" required>
                            @error('cnic') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Phone <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="03XX-XXXXXXX" required>
                            @error('phone') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" name="dob" class="form-control" value="{{ old('dob') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-select">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Weight (kg)</label>
                            <input type="number" step="0.1" name="weight" class="form-control" value="{{ old('weight') }}" placeholder="e.g. 65">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Blood Group <span class="text-danger">*</span></label>
                            <select name="blood_group" class="form-select @error('blood_group') is-invalid @enderror" required>
                                <option value="">Select Blood Group</option>
                                @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                                    <option value="{{ $bg }}" {{ old('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                                @endforeach
                            </select>
                            @error('blood_group') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section 2: Contact Details --}}
            <div class="section-card">
                <div class="section-card-header">
                    <div class="num">2</div>
                    <h3><i class="fas fa-map-marker-alt me-2" style="color:var(--primary);"></i>Contact & Location</h3>
                </div>
                <div class="section-card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">City</label>
                            <select name="city_id" class="form-select" id="citySelect">
                                <option value="">Select City</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                @endforeach
                                <option value="new" {{ old('city_id') == 'new' ? 'selected' : '' }}>&#10133; Other (add new)</option>
                            </select>
                            <input type="text" name="city_name" class="form-control mt-2" id="cityNameInput" placeholder="Enter city name" value="{{ old('city_name') }}" style="{{ old('city_id') == 'new' ? '' : 'display:none;' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Area</label>
                            <select name="area_id" class="form-select" id="areaSelect">
                                <option value="">Select Area</option>
                                <option value="new" {{ old('area_id') == 'new' ? 'selected' : '' }}>&#10133; Other (add new)</option>
                            </select>
                            <input type="text" name="area_name" class="form-control mt-2" id="areaNameInput" placeholder="Enter area name" value="{{ old('area_name') }}" style="{{ old('area_id') == 'new' ? '' : 'display:none;' }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control" rows="2" placeholder="Street, building, landmark...">{{ old('address') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section 3: Health & Medical --}}
            <div class="section-card">
                <div class="section-card-header">
                    <div class="num">3</div>
                    <h3><i class="fas fa-heartbeat me-2" style="color:var(--primary);"></i>Health & Medical</h3>
                </div>
                <div class="section-card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Hemoglobin (g/dL)</label>
                            <input type="number" step="0.1" min="5" max="20" name="hemoglobin" class="form-control" value="{{ old('hemoglobin') }}" placeholder="e.g. 13.5">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Last Donation Date</label>
                            <input type="date" name="last_donation_date" id="lastDonationDate" class="form-control" value="{{ old('last_donation_date') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Or Months Ago</label>
                            <input type="number" name="months_ago" id="monthsAgo" class="form-control" min="0" max="120" value="0" placeholder="0">
                            <small class="months-helper"><i class="fas fa-clock me-1"></i>Set months back from today</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Total Donations (Life Time)</label>
                            <input type="number" name="total_donations" class="form-control" min="0" value="{{ old('total_donations', 0) }}">
                            <small class="months-helper"><i class="fas fa-info-circle me-1"></i>How many times they donated</small>
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="form-label mb-2">Health Flags</label>
                        <div class="row g-2">
                            @php
                                $flags = [
                                    'recent_illness' => ['Recent Illness/Surgery', 'Recent illness or surgery in past 6 months', 'warning'],
                                    'pregnant' => ['Pregnant / Breastfeeding', 'Currently pregnant or breastfeeding', 'danger'],
                                    'recent_tattoo' => ['Recent Tattoo/Piercing', 'Tattoo or piercing in past 6 months', 'info'],
                                    'medication' => ['On Medication', 'Taking restricted medications', 'secondary'],
                                    'chronic_disease' => ['Chronic Disease', 'Has a chronic disease (diabetes, BP, etc.)', 'danger'],
                                    'low_risk' => ['High-Risk Behavior', 'High-risk behavior flagged', 'warning'],
                                ];
                            @endphp
                            @foreach($flags as $key => $info)
                                <div class="col-md-4">
                                    <label class="flag-checkbox-wrap {{ is_array(old('health_flags')) && in_array($key, old('health_flags')) ? 'checked' : '' }}">
                                        <input type="checkbox" name="health_flags[]" value="{{ $key }}" {{ is_array(old('health_flags')) && in_array($key, old('health_flags')) ? 'checked' : '' }} style="display:none;">
                                        <span class="flag-checkbox-visual" style="width:18px;height:18px;border:2px solid #ccc;border-radius:4px;display:inline-flex;align-items:center;justify-content:center;flex-shrink:0;{{ is_array(old('health_flags')) && in_array($key, old('health_flags')) ? 'background:var(--primary);border-color:var(--primary);' : '' }}">
                                            @if(is_array(old('health_flags')) && in_array($key, old('health_flags')))<i class="fas fa-check text-white" style="font-size:10px;"></i>@endif
                                        </span>
                                        <div>
                                            <strong style="font-size:13px;color:#444;">{{ $info[0] }}</strong>
                                            <div style="font-size:11px;color:#999;"><i class="fas fa-info-circle"></i> {{ $info[1] }}</div>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section 4: Education --}}
            <div class="section-card">
                <div class="section-card-header">
                    <div class="num">4</div>
                    <h3><i class="fas fa-graduation-cap me-2" style="color:var(--primary);"></i>Education</h3>
                </div>
                <div class="section-card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Education</label>
                            <select name="education" class="form-select">
                                <option value="">Select Education</option>
                                @foreach(['Matric', 'Intermediate', 'Bachelor\'s', 'Master\'s', 'PhD', 'Other'] as $edu)
                                    <option value="{{ $edu }}" {{ old('education') == $edu ? 'selected' : '' }}>{{ $edu }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div class="custom-switch-wrap mt-3">
                                <input type="checkbox" name="is_student" value="1" id="isStudentToggle" {{ old('is_student') ? 'checked' : '' }}>
                                <label class="switch-label" for="isStudentToggle">I am a Student</label>
                            </div>
                        </div>
                        <div class="col-md-6" id="uniField" style="{{ old('is_student') ? '' : 'display:none;' }}">
                            <label class="form-label">University</label>
                            <select name="university_id" id="universitySelect" class="form-select">
                                <option value="">Select University</option>
                                @foreach($universities as $uni)
                                    <option value="{{ $uni->id }}" {{ old('university_id') == $uni->id ? 'selected' : '' }}>{{ $uni->name }}</option>
                                @endforeach
                                <option value="new" {{ old('university_id') == 'new' ? 'selected' : '' }}>&#10133; Other (add new)</option>
                            </select>
                            <input type="text" name="university_name" id="uniNameInput" class="form-control mt-2" placeholder="Enter university name" value="{{ old('university_name') }}" style="{{ old('university_id') == 'new' ? '' : 'display:none;' }}">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section 5: Photo --}}
            <div class="section-card">
                <div class="section-card-header">
                    <div class="num">5</div>
                    <h3><i class="fas fa-camera me-2" style="color:var(--primary);"></i>Photo</h3>
                </div>
                <div class="section-card-body">
                    <div class="photo-upload-area" id="photoArea" onclick="document.getElementById('photoInput').click()">
                        <img id="photoPreview" src="#" alt="Preview" class="photo-preview" style="display:none;">
                        <div id="photoPlaceholder">
                            <i class="fas fa-camera" style="font-size:32px;color:#ccc;"></i>
                            <p style="font-size:14px;color:#999;margin:8px 0 0;">Click to upload photo</p>
                        </div>
                        <input type="file" name="photo" id="photoInput" accept="image/*" style="display:none;">
                        <small class="text-muted" style="display:block;margin-top:8px;">Photo will be cropped to 300x300px automatically.</small>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn-submit"><i class="fas fa-heart me-2"></i> Register as Donor</button>
            </div>
        </form>

        <div class="reg-footer-card mt-4">
            <i class="fas fa-id-card me-1" style="color:var(--primary);"></i>
            Already registered?
            <a href="{{ route('portal.verify') }}">Verify your card</a>
            &bull;
            <a href="{{ route('portal.availability') }}">Check blood availability</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        var referredById = null;

        $('#refSearchBtn').on('click', function() { searchReferrer(); });
        $('#refSearch').on('keydown', function(e) { if (e.key === 'Enter') { e.preventDefault(); searchReferrer(); } });

        function searchReferrer() {
            var q = $('#refSearch').val().trim();
            if (q.length < 3) return;
            $.get('{{ route("portal.donors.search-referrer") }}', { q: q }, function(data) {
                var $res = $('#refResults');
                $res.empty().show();
                if (data.length === 0) {
                    $res.html('<div class="col-12"><p class="text-muted small mb-0 py-2"><i class="fas fa-search"></i> No donor found with this CNIC or Phone.</p></div>');
                    return;
                }
                $.each(data, function(i, d) {
                    $res.append(
                        '<div class="col-md-6"><div class="ref-result-item" data-id="' + d.id + '" data-name="' + d.name + '" data-cnic="' + d.cnic + '" data-phone="' + d.phone + '" data-blood="' + (d.blood_group || '') + '">' +
                        '<strong style="font-size:14px;">' + d.name + '</strong>' +
                        '<div style="font-size:12px;color:#888;">CNIC: ' + d.cnic + ' \u00B7 Phone: ' + d.phone + (d.blood_group ? ' \u00B7 ' + d.blood_group : '') + '</div>' +
                        '</div></div>'
                    );
                });
                $('.ref-result-item').on('click', function() {
                    selectReferrer($(this).data('id'), $(this).data('name'), $(this).data('cnic'), $(this).data('phone'), $(this).data('blood'));
                });
            });
        }

        function selectReferrer(id, name, cnic, phone, blood) {
            referredById = id;
            $('#referredByInput').val(id);
            $('#refSearch').val('');
            $('#refResults').hide().empty();
            $('#refSelected').show();
            $('#refSelectedName').text(name);
            $('#refSelectedDetail').text('CNIC: ' + cnic + ' \u00B7 Phone: ' + phone + (blood ? ' \u00B7 ' + blood : ''));
            $('#noReferral').prop('checked', false);
            $('#noReferralInput').val('0');
        }

        $('#refChangeBtn').on('click', function() {
            referredById = null;
            $('#referredByInput').val('');
            $('#refSelected').hide();
            $('#refSearch').focus();
        });

        $('#noReferral').on('change', function() {
            if ($(this).is(':checked')) {
                referredById = null;
                $('#referredByInput').val('');
                $('#refSelected').hide();
                $('#refResults').hide().empty();
                $('#refSearch').val('').prop('disabled', true);
                $('#refSearchBtn').prop('disabled', true);
                $('#noReferralInput').val('1');
            } else {
                $('#refSearch').prop('disabled', false);
                $('#refSearchBtn').prop('disabled', false);
                $('#noReferralInput').val('0');
            }
        });

        $('#citySelect').on('change', function() {
            var cityId = $(this).val();
            var $area = $('#areaSelect');
            var $cityInput = $('#cityNameInput');
            if (cityId === 'new') {
                $cityInput.show().prop('disabled', false);
                $area.html('<option value="">Select Area</option><option value="new">&#10133; Other (add new)</option>').prop('disabled', true);
                return;
            }
            $cityInput.hide().val('').prop('disabled', true);
            $area.prop('disabled', false);
            if (!cityId) { $area.html('<option value="">Select Area</option><option value="new">&#10133; Other (add new)</option>'); return; }
            $.get('{{ url("portal/areas/by-city") }}/' + cityId, function(data) {
                var html = '<option value="">Select Area</option>';
                $.each(data, function(i, a) { html += '<option value="' + a.id + '">' + a.name + '</option>'; });
                html += '<option value="new">&#10133; Other (add new)</option>';
                $area.html(html);
            });
        });

        $('#areaSelect').on('change', function() {
            var val = $(this).val();
            var $input = $('#areaNameInput');
            if (val === 'new') { $input.show().prop('disabled', false); }
            else { $input.hide().val('').prop('disabled', true); }
        });

        $('#isStudentToggle').on('change', function() {
            if ($(this).is(':checked')) { $('#uniField').slideDown(200); }
            else { $('#uniField').slideUp(200); $('#uniField input').val(''); $('#universitySelect').val(''); }
        });

        $('#universitySelect').on('change', function() {
            var $input = $('#uniNameInput');
            if ($(this).val() === 'new') { $input.show().prop('disabled', false); }
            else { $input.hide().val('').prop('disabled', true); }
        });

        $('#photoInput').on('change', function(e) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#photoPreview').attr('src', e.target.result).show();
                $('#photoPlaceholder').hide();
            };
            if (this.files && this.files[0]) reader.readAsDataURL(this.files[0]);
        });

        function monthsBetween(d1, d2) {
            return Math.max(0, (d2.getFullYear() - d1.getFullYear()) * 12 + (d2.getMonth() - d1.getMonth()));
        }

        $('#monthsAgo').on('input', function() {
            var val = parseInt($(this).val()) || 0;
            var d = new Date();
            d.setMonth(d.getMonth() - val);
            $('#lastDonationDate').val(d.toISOString().split('T')[0]);
        });

        $('#lastDonationDate').on('change', function() {
            var val = $(this).val();
            if (val) {
                var d = new Date(val + 'T00:00:00');
                var today = new Date();
                today.setHours(0,0,0,0);
                if (d <= today) $('#monthsAgo').val(monthsBetween(d, today));
            }
        });

        $('.flag-checkbox-wrap').on('click', function() {
            var $cb = $(this).find('input[type="checkbox"]');
            $cb.prop('checked', !$cb.is(':checked'));
            $(this).toggleClass('checked');
            var $visual = $(this).find('.flag-checkbox-visual');
            if ($cb.is(':checked')) {
                $visual.css({ background: 'var(--primary)', borderColor: 'var(--primary)' });
                $visual.html('<i class="fas fa-check text-white" style="font-size:10px;"></i>');
            } else {
                $visual.css({ background: '', borderColor: '#ccc' });
                $visual.html('');
            }
        });

        $(function() {
            if ($('#citySelect').val() === 'new') {
                $('#cityNameInput').show().prop('disabled', false);
                $('#areaSelect').prop('disabled', true);
            }
            $('form').on('submit', function() {
                $('#cityNameInput').prop('disabled', false);
                $('#areaNameInput').prop('disabled', false);
            });

            @if($refCnic)
            $('#refSearch').val('{{ $refCnic }}');
            searchReferrer();
            @endif
        });
    </script>
</body>
</html>
