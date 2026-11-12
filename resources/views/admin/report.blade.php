@extends('layouts.dashboard')
@section('title', 'Platform Report')
@section('content')
<div class="page-head d-flex flex-wrap justify-content-between align-items-start gap-2">
    <div><h1>Platform Report</h1><p>Overall usage of Connect Me.</p></div>
    <button class="btn btn-sm btn-navy no-print" onclick="window.print()"><i class="fa-solid fa-print"></i> Print Report</button>
</div>
@include('partials.report-header', ['title' => 'Platform Report - Last 12 months', 'for' => 'Administrator'])
@php $period = 'monthly'; @endphp
<div class="row g-3 mb-4">
    @foreach([['Skilled youth', $stats['youth'], 'fa-user-graduate'], ['Employers', $stats['employers'], 'fa-building'], ['Verified accounts', $stats['verified'], 'fa-circle-check'], ['Awaiting verification', $stats['pending'], 'fa-hourglass-half'], ['Suspended accounts', $stats['suspended'], 'fa-ban'], ['Published vacancies', $stats['vacancies'], 'fa-briefcase'], ['Removed vacancies', $stats['removed'], 'fa-trash'], ['Applications', $totals['total'], 'fa-file-lines']] as [$l, $n, $i])
        <div class="col-6 col-lg-3"><div class="tile"><i class="fa-solid {{ $i }}"></i><div><div class="n">{{ $n }}</div><div class="l">{{ $l }}</div></div></div></div>
    @endforeach
</div>
<div class="panel mb-4"><h2 class="h5">Applications per month</h2>@include('partials.report-bars')</div>
<div class="panel"><h2 class="h5">Monthly statistics</h2>@include('partials.report-table')</div>
@endsection
