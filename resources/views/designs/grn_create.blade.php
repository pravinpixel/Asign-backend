@extends('layouts.index')
@section('title', 'GRN Create')
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
        <div class="hstack gap-3">
            <a href="{{url('/purchase-orders')}}">
                <img src="{{ asset('icons/arrow-left-alt.svg') }}" width="24" height="24" class="cP" />
            </a>
            <h4>GRNO00001</h4>
        </div>
        <button type="button" class="btn apply-btn">
            Create GRN
        </button>
    </section>
    <section class="form-content">
        <form class="formFieldInput">
            <div class="row mb-4">
                <div class="col col-md-6">
                    <div class="row">
                        <div class="col col-md-10">
                            <label for="grn" class="form-label">GRN No. </label>
                            <input type="text" class="form-control form-control-lg" id="grn" placeholder="GRNO00001">
                        </div>
                    </div>
                </div>
                <div class="col col-md-6">
                    <div class="row">
                        <div class="col col-md-10">
                            <label for="transfer_no" class="form-label">Purchase / Transfer Order No. </label>
                            <div class="w100Select">
                                <select class="select2Box" data-placeholder="Select Order NO" id="transfer_no">
                                    <option></option>
                                    <option value="Chennai">Chennai</option>
                                    <option value="Manipal">Manipal</option>
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
                            <label for="grn_loc" class="form-label">GRN Location</label>
                            <div class="w100Select">
                                <select class="select2Box" data-placeholder="Select GRN Location" id="grn_loc">
                                    <option></option>
                                    <option value="Chennai">Chennai</option>
                                    <option value="Manipal">Manipal</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col col-md-6">
                    <div class="row">
                        <div class="col col-md-10 input-addon addon-right">
                            <label for="order_date" class="form-label">Created on</label>
                            <input type="text" class="datepicker form-control form-control-lg" id="order_date" placeholder="Select">
                            <span class="addon">
                                <img src="{{ asset('icons/calendar.svg') }}" />
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col col-md-6">
                    <div class="row">
                        <div class="col col-md-10">
                            <label for="sender" class="form-label">Sender Name</label>
                            <div class="w100Select">
                                <select class="select2Box" data-placeholder="Select Manufacturer Name" id="sender">
                                    <option></option>
                                    <option value="Manipal">Manipal</option>
                                    <option value="Harsh">Harsh</option>
                                    <option value="Redmond D’Souza">Redmond D’Souza</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col col-md-6">
                    <div class="row">
                        <div class="col col-md-10">
                            <label for="transporter" class="form-label">Transporter Name</label>
                            <div class="w100Select">
                                <select class="select2Box" data-placeholder="Select Delivery Location" id="transporter">
                                    <option></option>
                                    <option value="Chennai">Chennai</option>
                                    <option value="Manipal">Manipal</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
    <section class="table-content">        
        @include('components.tables.grn_create_table')
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
