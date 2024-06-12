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
                $spiltSegment = request()->segments();
                $type = request()->segment(count($spiltSegment));
                $grnId =  request()->segment(count($spiltSegment) - 1);
                $orderProductId =  request()->segment(count($spiltSegment) - 2);
                $productId =  request()->segment(count($spiltSegment) - 3);
            @endphp
            @if ($type == "sto")
                <a href="{{ url('/goods-received-notes/create-grn/'.$productDetails->stock_transfer_order_id.'/'.$grnId.'/'.$type) }}">
                    <img src="{{ asset('icons/arrow-left-alt.svg') }}" width="24" height="24" class="cP" />
                </a>
            @else
                <a href="{{ url('/goods-received-notes/create-grn/'.$productDetails->purchase_order_id.'/'.$grnId.'/'.$type) }}">
                    <img src="{{ asset('icons/arrow-left-alt.svg') }}" width="24" height="24" class="cP" />
                </a>
            @endif

            <h4>{{ $grn_no }}</h4>
        </main>
        <div>
            @if( $grnScanProducts->count() > 0 )
                <button type="button" class="btn cancel-btn" data-bs-toggle="modal" data-bs-target="#confirm-reset-popup">
                    Reset
                </button>
            @else
                <button type="button" class="btn cancel-btn" data-bs-toggle="modal" data-bs-target="#confirm-reset-popup" disabled>
                    Reset
                </button>
            @endif
        </div>
    </section>
    <section class="section-inner">
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="headerBorderBox">
                    <div class="vstack gap-2">
                        <div class="headerBorderBox-head">Product Name</div>
                        <div class="headerBorderBox-sub">{{ $productDetails['product_name'] ?? "-" }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="headerBorderBox">
                    <div class="vstack gap-2">
                        <div class="headerBorderBox-head">GRN Quantity</div>
                        {{-- <div class="headerBorderBox-sub">{{ $productDetails['grn_quantity'] ?? "-" }}</div> --}}
                        <div class="headerBorderBox-sub">{{ $grnScanProducts->count() ?? "-" }}</div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div style="width: 250px; margin: 0 auto;margin-top:20px;" id="reader">
            Loading ...
        </div> --}}
        <div class="row mb-4">
            <div class="col col-md-6">
                <div class="row">
                    <div class="col col-md-10">
                        <label for="grn" class="form-label">BAR Code / QR Code</label>
                        <input type="text" class="form-control form-control-lg" id="scan_code" name="scan_code"
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
        @include('pages.stock_management.grn.grn_scan_table')
    </section>
</div>
@include('pages.stock_management.grn.alert_popup')

{{-- @include('components.tables.asign_paginate') --}}
@endsection
@push('scripts')
<script type="text/javascript" src="{{ asset('js/stock_management/grn.js') }}"></script>
<script type="text/javascript">
    var base_url = {!! json_encode(url('/')) !!}

    // var urlPath = window.location.pathname;
    // var urlParts = urlPath.split("/");
    // var lastTwoValues = urlParts.slice(-3);
    @if ($productDetails['quantity'] === $productDetails['grn_quantity'])
        @if($type === "sto")
            window.location.href = "{{ url('/goods-received-notes/create-grn/'.$productDetails['stock_transfer_order_id'].'/'.$grnId.'/'.$type) }}";
        @elseif ($type === "po")
            window.location.href = "{{ url('/goods-received-notes/create-grn/'.$productDetails['purchase_order_id'].'/'.$grnId.'/'.$type) }}";
        @endif
    @endif
    var orderProductId = "{{ $orderProductId }}";
    var grnId = "{{ $grnId }}"
    var requestType = "{{ $type }}";
    var productId = "{{ $productId }}";

    let config = new Object();
    config.links = new Object;
    config.links.scan = "{{ route( 'grn.scan.product' ) }}";
    config.links.reset = "{{ route( 'grn.reset' ) }}";

    new Grn(config);

</script>
@endpush
