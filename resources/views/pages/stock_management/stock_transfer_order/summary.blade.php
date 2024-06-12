@extends('layouts.index')
@section('title', 'Purchase Order Summary')
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
    .stock-orders-title a{
        padding-right: 8px
    }
</style>
@endsection
@section('content')
<div class="pages purchase-order-summary">
    @php
        $splitSegments = request()->segments();
        $orderStatus = end($splitSegments);
    @endphp
    <section class="m-header">
        <h4 class="stock-orders-title"><a href="{{url('stock-transfer-orders')}}"><img src="{{ asset('icons/arrow-left-alt.svg') }}" /></a>{{ $stoSummary->sto_no }}</h4>
        <div>

            @if ($stoSummary->status == "Transit")
                <a class="cancel-btn sto_print_btn" href="{{ route('sto.pdf', ['id' => $stoSummary->id]) }}" target="_blank" style="cursor:pointer;">Print</a>
                <a class="apply-btn ms-3 create_sto_grn_id" style="cursor:pointer;">Create GRN</a>
            @else
                @if ($buttonName)
                    <a class="apply-btn" href="{{ route('sto.pack', [ 'sto_id' => $stoSummary->id, 'sto_status' => $orderStatus ]) }}" style="cursor:pointer;">{{ $buttonName }}</a>
                @else
                    <a class="apply-btn sto_print_btn" href="{{ route('sto.pdf', ['id' => $stoSummary->id]) }}" target="_blank" style="cursor:pointer;">Print</a>
                @endif
            @endif

        </div>
    </section>
    <section class="section-inner">
        <h1>Stock Transfer Order Summary</h1>
        <ul class="personal-info">
            <li>
                <span>Order No.</span>
                <span>{{ $stoSummary->sto_no }}</span>
            </li>
            <li>
                <span>STO Date</span>
                <span>{{ $stoSummary->created_date }}</span>
            </li>
            <li>
                <span>Stock Source</span>
                <span>{{ $stoSummary->stockSource?->location }}</span>
            </li>
            <li>
                <span>Stock Destination</span>
                <span>{{ $stoSummary->stockDestination?->location }}</span>
            </li>
            <li>
                <span>Transfer Reason</span>
                <span>{{ ($stoSummary->transfer_reasons?->name == "Others" || $stoSummary->transfer_reasons?->name == "others") ? $stoSummary->transfer_reason : $stoSummary->transfer_reasons->name}}</span>
            </li>
        </ul>
    </section>
    <section class="table-content">
        @include('pages.stock_management.stock_transfer_order.summary_table')
    </section>
</div>
@endsection
@push('scripts')
    <script type="text/javascript" src="{{ asset('js/stock_management/sto.js') }}"></script>
    <script type="text/javascript">
        var base_url = {!! json_encode(url('/')) !!}
        var stoId = "{{ $stoSummary->id }}"
        var orderStatus = "{{ $orderStatus }}"
        var stoOrderedData = {};
        let config = new Object();
        config.links = new Object;
        config.links.grnid = "{{ route('grn.grnid') }}";

        new Sto(config);
    </script>
@endpush
