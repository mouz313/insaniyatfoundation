@extends('adminlte::page')

@section('title', 'Reports')

@section('content_header')
    <h1>Reports</h1>
@stop

@section('content')
    <x-adminlte-card title="Generate Report" icon="fas fa-file-alt">
        <form action="{{ route('admin.reports.generate') }}" method="POST" target="_blank">
            @csrf
            <div class="row">
                <div class="col-md-4">
                    <x-adminlte-select name="report_type" label="Report Type" id="reportType">
                        <option value="progress_report">Progress Report</option>
                        <option value="daily_calls">Daily Calls Report</option>
                        <option value="monthly_donor">Monthly Donor Report</option>
                        <option value="yearly_summary">Yearly Summary</option>
                        <option value="custom">Custom Range</option>
                        <option value="money_collected">Money Collected</option>
                        <option value="donor_list">Donor List</option>
                        <option value="card_queue">Card Queue</option>
                    </x-adminlte-select>
                </div>
                <div class="col-md-4">
                    <x-adminlte-input name="start_date" label="Start Date" type="date" value="{{ old('start_date', date('Y-m-01')) }}"/>
                </div>
                <div class="col-md-4">
                    <x-adminlte-input name="end_date" label="End Date" type="date" value="{{ old('end_date', date('Y-m-d')) }}"/>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <x-adminlte-select name="blood_group" label="Blood Group">
                        <option value="">All Groups</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                    </x-adminlte-select>
                </div>
                <div class="col-md-3">
                    <x-adminlte-select name="city_id" label="City">
                        <option value="">All Cities</option>
                        @foreach($cities ?? [] as $city)
                            <option value="{{ $city->id }}">{{ $city->name }}</option>
                        @endforeach
                    </x-adminlte-select>
                </div>
                <div class="col-md-3">
                    <x-adminlte-select name="status" label="Status">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </x-adminlte-select>
                </div>
                <div class="col-md-3">
                    <x-adminlte-select name="format" label="Format">
                        <option value="pdf">PDF</option>
                        <option value="excel">Excel</option>
                        <option value="word">Word</option>
                    </x-adminlte-select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <x-adminlte-button type="submit" label="Generate Report" theme="primary" icon="fas fa-download"/>
                </div>
            </div>
        </form>
    </x-adminlte-card>
@stop

@section('js')
<script>
    $('#reportType').on('change', function(){
        var val = $(this).val();
        var isProgress = val == 'progress_report';
        $('input[name="start_date"], input[name="end_date"], select[name="blood_group"], select[name="city_id"], select[name="status"]').closest('.col-md-3,.col-md-4').toggle(!isProgress);
    }).trigger('change');
</script>
@stop
