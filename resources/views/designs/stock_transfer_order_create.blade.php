@extends('layouts.index')
@section('title', 'Stock Transfer Order Create')
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
        <h4><a href="{{url('purchase-orders')}}"><img src="{{ asset('icons/arrow-left-alt.svg') }}" /></a> STO00001</h4>
        <button type="button" class="btn apply-btn">
            Create STO
        </button>
    </section>
    <section class="form-content">
        <form class="formFieldInput">
            <div class="row mb-4">
                <div class="col col-md-6">
                    <div class="row">
                        <div class="col col-md-10">
                            <label for="stock_source" class="form-label">Stock Source</label>
                            <div class="w100Select">
                                <select class="select2Box" id="stock_source" data-placeholder="Select Source">
                                    <option></option>
                                    <option value="admin">Admin</option>
                                    <option value="artist">Artist</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col col-md-6">
                    <div class="row">
                        <div class="col col-md-10">
                            <label for="stock_desctination" class="form-label">Stock Destination</label>
                            <div class="w100Select">
                                <select class="select2Box" id="stock_desctination" data-placeholder="Select Destination">
                                    <option></option>
                                    <option value="admin">Admin</option>
                                    <option value="artist">Artist</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col col-md-6">
                    <div class="row">
                        <div class="col col-md-10">
                            <label for="transfer_reason" class="form-label">Transfer Reason</label>
                            <div class="w100Select">
                                <select class="select2Box" id="transfer_reason" data-placeholder="Select Transfer Reason">
                                    <option></option>
                                    <option value="admin">Admin</option>
                                    <option value="artist">Artist</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col col-md-6">
                    <div class="row">
                        <div class="col col-md-10 input-addon addon-right">
                            <label for="order_date" class="form-label">Creation Date</label>
                            <input type="text" class="datepicker form-control form-control-lg" id="order_date" placeholder="Select Date">
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
                  <img src="{{ asset('icons/add.png') }}" class="pe-2"> Add Products
            </button>
                </div>
            </div>
        </form>
    </section>
    <section class="table-content">        
        @include('components.tables.stock_transfer_order_create_table')
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
