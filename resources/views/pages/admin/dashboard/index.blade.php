@extends('layouts.dashboard-base')

@section('content')
    <div class="page-heading mb-3">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Dashboard</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="page-content">
        <div class="row">
            @foreach($status_permohonan as $status)
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="text-white px-3 py-3" style="border-radius: 10px; background-color: {{ $status->get('bg_color') }};">
                                <i class="fs-1 isax isax-activity"></i>
                            </div>
                            <div class="">
                                <h5 class="mb-2">{{ $status->get('name') }}</h5>
                                <p class="mb-0 fw-bold fs-6">{{ number_format($status->get('count')) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
@endsection
