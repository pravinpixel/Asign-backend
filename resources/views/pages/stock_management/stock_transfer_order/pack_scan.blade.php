@extends('layouts.index')
@section('title', 'GRN Scan')
@section('style')
@parent
<style type="text/css">
    .purchase-order-summary .m-header{
        padding: 24px 32px;
        display: flex;
        justify-content: flex-start;
        align-items: center;
        border-bottom: 1px solid rgba(29, 29, 29, 0.20); 
    }
    .purchase-order-summary .m-header > div{
        margin-left: auto;
    }
    .purchase-order-summary .form-content{
        padding: 24px 32px 0px;
    }
    .accordion_head span.arrow_indicator{
        position: relative;
    }
    .accordion_head span.arrow_indicator.down::before{
        position: absolute;
        content: url(https://uat-api.asign.art/admin/public/assets/icons/down.png);
    }
    .accordion_head span.arrow_indicator.up::before{
        position: absolute;
        content: url(https://uat-api.asign.art/admin/public/assets/icons/up.png);
    }
    .accordion_body{
        display:none;
    }
    .accordion_body > td{
        padding: 0px;
    }
</style>
@endsection
@section('content')
<div class="pages purchase-order-summary">
    <section class="m-header">
        <main class="hstack gap-3">
            @php
                $segments = request()->segments();
                $stoProductId = end($segments);
                $stoId = $stoProductDetails['stock_transfer_order_id'];
                $productId = $segments[count($segments) - 3];
            @endphp
            <a href="{{ url('/stock-transfer-orders/pack/'.$stoProductDetails['stock_transfer_order_id'].'/Ordered') }}">
                <img src="{{ asset('icons/arrow-left-alt.svg') }}" width="24" height="24" class="cP" />
            </a>
            <h4>{{ $stoProductDetails['sto_no'] }}</h4>
        </main>
        <div>
            @if( $stoProductDetails['scanned_req_quantity'] > 0 )
                <button type="button" class="btn cancel-btn" data-bs-toggle="modal" data-bs-target="#confirm-reset-sto-popup">
                    Reset
                </button>   
            @else
                <button type="button" class="btn cancel-btn" data-bs-toggle="modal" data-bs-target="#confirm-reset-sto-popup" disabled>
                    Reset
                </button>    
            @endif
             
        </div>             
    </section>
    <section class="section-inner">
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="headerBorderBox">
                    <div class="vstack gap-2">
                        <div class="headerBorderBox-head">Product Name</div>
                        <div class="headerBorderBox-sub">{{ $stoProductDetails['product']->name ?? "-" }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="headerBorderBox">
                    <div class="vstack gap-2">
                        <div class="headerBorderBox-head">Order Quantity</div>
                        <div class="headerBorderBox-sub">{{ $stoProductDetails['quantity'] ?? "-" }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="headerBorderBox">
                    <div class="vstack gap-2">
                        <div class="headerBorderBox-head">Issued Quantity</div>
                        <div class="headerBorderBox-sub">{{ $stoProductDetails['scanned_req_quantity'] ?? "-" }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4 mb-4">
            <div class="col col-md-6">
                <div class="row">
                    <div class="col col-md-10">
                        <label for="grn" class="form-label">BAR Code / QR Code</label>
                        <input type="text" class="form-control form-control-lg" id="ordered_product_code" name="scan_code"
                            placeholder="Please scan the code" value="">
                        {{-- <span class="error" id="grnno_error"></span> --}}
                    </div>
                </div>
            </div>
            <div class="col col-md-6">
                <div class="row">
                </div>
            </div>
        </div>
    </section>
    <section class="table-content">        
        @include('pages.stock_management.stock_transfer_order.pack_scan_table')
    </section>
</div>
@include('pages.stock_management.stock_transfer_order.alert_popup')
{{-- @include('components.tables.asign_paginate') --}}
@endsection
@push('scripts')
<script type="text/javascript" src="{{ asset('js/stock_management/sto.js') }}"></script>
<script type="text/javascript">
    var base_url = {!! json_encode(url('/')) !!}
    var stoId = "{{ $stoId }}";
    var stoProductId = "{{ $stoProductId }}";
    var productId = "{{ $productId }}";
    var stoOrderedData = {};
    @if ($stoProductDetails['quantity'] === $stoProductDetails['scanned_req_quantity'])
        window.location.href = "{{ url('/stock-transfer-orders/pack/'.$stoId.'/Ordered') }}";
    @endif
   
    let config = new Object();
    config.links = new Object;
    config.links.scan = "{{ route( 'sto.scan.pack.product' ) }}";
    config.links.stoPackReset = "{{ route( 'sto.reset.pack' ) }}";

    new Sto(config);

</script>
@endpush
