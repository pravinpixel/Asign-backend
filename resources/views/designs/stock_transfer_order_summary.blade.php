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
</style>
@endsection
@section('content')
<div class="pages purchase-order-summary">
    <section class="m-header">
        <h4><a href="{{url('purchase-orders')}}"><img src="{{ asset('icons/arrow-left-alt.svg') }}" /></a> STO000001</h4>
        <div>
            <button type="button" class="btn apply-btn ms-3">
                Print
            </button>            
        </div>        
    </section>
    <section class="section-inner">
        <h1>Stock Transfer Order Summary</h1>
        <ul class="personal-info">
            <li>
                <span>Order No.</span>
                <span>STO000001</span>
            </li>
            <li>
                <span>STO Date</span>
                <span>23 Dec 2023</span>
            </li>
            <li>
                <span>Stock Source</span>
                <span>Chennai</span>
            </li>
            <li>
                <span>Stock Destination</span>
                <span>Mumbai</span>
            </li>
            <li>
                <span>Transfer Reason</span>
                <span>Low on Stock</span>
            </li>
        </ul>
    </section>
    <section class="table-content">        
        @include('components.tables.stock_transfer_order_summary_table')
    </section>
</div>
@endsection
@push('scripts')

@endpush
