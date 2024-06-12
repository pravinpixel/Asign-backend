@extends('layouts.index')
@section('title', 'Stock Check Scan')
@section('style')
@parent
<style type="text/css">
    .purchase-order-summary .m-header {
        padding: 24px 32px;
        display: flex;
        justify-content: flex-start;
        align-items: center;
        border-bottom: 1px solid rgba(29, 29, 29, 0.20);
    }

    .purchase-order-summary .m-header>div {
        margin-left: auto;
    }

    .purchase-order-summary .form-content {
        padding: 24px 32px 0px;
    }

    .accordion_head span.arrow_indicator {
        position: relative;
    }

    .accordion_head span.arrow_indicator.down::before {
        position: absolute;
        content: url(https://uat-api.asign.art/admin/public/assets/icons/down.png);
    }

    .accordion_head span.arrow_indicator.up::before {
        position: absolute;
        content: url(https://uat-api.asign.art/admin/public/assets/icons/up.png);
    }

    .accordion_body {
        display: none;
    }

    .accordion_body>td {
        padding: 0px;
    }
</style>
@endsection
@section('content')
<div class="pages purchase-order-summary">
    <section class="m-header">
        <main class="hstack gap-3">
            <a href="{{url('/stock-check/create')}}">
                <img src="{{ asset('icons/arrow-left-alt.svg') }}" width="24" height="24" class="cP" />
            </a>
            <h4>SC000001</h4>
        </main>
        <div>
            <a href="{{url('/stock-check/summary/SC000001')}}" class="btn cancel-btn">
                Reset
            </a>
        </div>
    </section>
    <section class="section-inner">
        <div class="row">
            <div class="col-md-4">
                <div class="headerBorderBox">
                    <div class="vstack gap-2">
                        <div class="headerBorderBox-head">Product Name</div>
                        <div class="headerBorderBox-sub">Inventory Label</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="headerBorderBox">
                    <div class="vstack gap-2">
                        <div class="headerBorderBox-head">Expected Stock</div>
                        <div class="headerBorderBox-sub">50000</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="headerBorderBox">
                    <div class="vstack gap-2">
                        <div class="headerBorderBox-head">Actual Stock</div>
                        <div class="headerBorderBox-sub">5000</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="table-content">
        @include('components.tables.stock_check_scan_table')
    </section>
</div>
@include('components.tables.asign_paginate')
@endsection
@push('scripts')
@endpush