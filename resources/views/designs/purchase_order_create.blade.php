@extends('layouts.index')
@section('title', 'Purchase Order Create')
@section('style')
@parent
<link rel="stylesheet" type="text/css" href="{{asset('css/jquery-ui.css')}}" />
<style type="text/css">
    .purchase-order-create .m-header{
        padding: 24px 32px;
        display: flex;
        justify-content: flex-start;
        align-items: center;
        border-bottom: 1px solid rgba(29, 29, 29, 0.20);
    }
    .purchase-order-create .m-header .btn{
        margin-left: auto;
    }
    .purchase-order-create .form-content{
        padding: 24px 32px 0px;
    }
    .purchase-order-create  .table-content{
        padding-top: 20px;
    }

    .input-addon{
        position: relative;
    }
    .input-addon > .form-control {
        position: relative;
        z-index: 9;
    }
    
</style>
@endsection
@section('content')
<div class="pages purchase-order-create">
    <section class="m-header">
        <h4><a href="{{url('purchase-orders')}}"><img src="{{ asset('icons/arrow-left-alt.svg') }}" /></a>{{ $purchase_order_no }}</h4>
        <button type="button" class="btn apply-btn">
            Create PO
        </button>
    </section>
    <section class="form-content">
        <form class="formFieldInput">
            <div class="row mb-4">
                <div class="col col-md-6">
                    <div class="row">
                        <div class="col col-md-10">
                            <label for="manufacturer_name" class="form-label">Manufacturer Name</label>
                            {{-- <input type="text" class="form-control form-control-lg" id="manufacturer_name"> --}}
                            <div class="w100Select">
                                <select class="select2Box" data-placeholder="Select Manufacturer Name">
                                    <option></option>
                                    @foreach( $manufactures as $manufacture)
                                        <option value="{{ $manufacture->id }}">{{ $manufacture->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col col-md-6">
                    <div class="row">
                        <div class="col col-md-10">
                            <label for="delivery_location" class="form-label">Delivery Location</label>
                            <div class="w100Select">
                                <select class="select2Box" data-placeholder="Select Delivery Location">
                                    <option></option>
                                    @foreach ( $deliveryLocations as $deliveryLocation )
                                        <option value="{{ $deliveryLocation->id }}">{{ $deliveryLocation->location }}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- <input type="text" class="form-control form-control-lg" id="delivery_location" placeholder="Select Delivery Location"> --}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col col-md-6">
                    <div class="row">
                        <div class="col col-md-10">
                            <label for="purchase_order_no" class="form-label">Purchase Order No.</label>
                            <input type="text" class="form-control form-control-lg" id="purchase_order_no" placeholder="Enter Order No." value="{{ $purchase_order_no }}">
                        </div>
                    </div>
                </div>
                <div class="col col-md-6">
                    <div class="row">
                        <div class="col col-md-10 input-addon addon-right">
                            <label for="order_date" class="form-label">Order Date</label>
                            <input type="text" class="datepicker form-control form-control-lg" id="order_date" placeholder="Select Order Date">
                            <span class="addon">
                                <img src="{{ asset('icons/calendar.svg') }}" />
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 txt-right">
                    <button type="button" class="btn cancel-btn" data-bs-toggle="modal" data-bs-target="#product_popup">
                        <img src="{{ asset('icons/add.png') }}" class="pe-2">
                        Add Products
                    </button>
                </div>
            </div>
        </form>
    </section>
    <section class="table-content">
        @include('components.tables.purchase_order_create_table')
    </section>
</div>
@include('components.popups.add_product_popup')
@endsection
@push('scripts')
<script src="{{asset('js/jquery-ui.min.js')}}"></script>
<script type="text/javascript">
$(document).ready(function() {
    $(".select2Box").each(function(){
        var placeholder = $(this).attr('data-placeholder');
        $(this).select2({
            placeholder: placeholder,
            minimumResultsForSearch: Infinity,
        });
    });
    $(".datepicker").datepicker({
        dateFormat: "DD, d M, yy"
    });
});
</script>
@endpush
