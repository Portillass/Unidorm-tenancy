@extends('tenantviews.tenantlayout.tenantlayout')

@section('content')
<!-- Main Content -->
<div class="col-md-9">
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            Dashboard
        </div>
        <div class="card-body">
            <h5 class="card-title">Welcome!</h5>
            <p class="card-text">This is your dashboard, {{ $tenantName }}.</p>
            <p class="card-text">Easily manage users, your dorm's booking, and more.</p>
        </div>
    </div>
@endsection
