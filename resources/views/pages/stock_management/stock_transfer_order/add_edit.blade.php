@extends('layouts.index')
@section('title', 'Stock Transfer Orders')
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
    .error{
        color: #FB6F6F; 
        font-size: 14px;
        font-weight: 450;
    }
    .availble_error{
        color:black;
    }

    .input-addon{
        position: relative;
    }
    .input-addon > .form-control {
        position: relative;
        z-index: 9;
    }
    
    #purchase_order_title a {
        padding-right: 8px;
    }

</style>
@endsection
@section('content')
<div class="pages purchase-order-create">
    <section class="m-header">
        {{-- <h4><a href="{{url('purchase-orders')}}"><img id="purchase_create_arrow" src="{{ asset('icons/arrow-left-alt.svg') }}" /></a>{{ $purchase_order_no ?? "Purchase Order" }}</h4> --}}
        <h4 id="purchase_order_title"><a href="{{url('stock-transfer-orders')}}"><img id="purchase_create_arrow" src="{{ asset('icons/arrow-left-alt.svg') }}" /></a><span>{{ $stoNo }}</span></h4>
        <button type="button" class="btn apply-btn" id="create_sto_btn">
            Create STO
        </button>
        <div class="dropdown-bar">
            <i class='bx bx-dots-vertical-rounded fs-4 dropdown-toggle' data-bs-toggle="dropdown" aria-expanded="false"></i>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Export</a></li>
            </ul>
        </div>
    </section>
    <section class="form-content">
        <form class="formFieldInput" id="create_sto_form" autocomplete="off">
            @csrf
            <div class="row mb-4">
                <div class="col col-md-6">
                    <div class="row">
                        <div class="col col-md-10">
                            <label for="manufacturer_name" class="form-label">Stock Source</label>
                            {{-- <input type="text" class="form-control form-control-lg" id="manufacturer_name"> --}}
                            <div class="w100Select">
                                <select class="select2Box" id="location_id" data-placeholder="Select Stock Source" name="stock_source" value="{{ old('manufacturer_name') }}" required>
                                    <option></option>
                                    @foreach ($locations as $location)
                                        <option value="{{ $location->id }}">{{ $location->location }}</option>
                                    @endforeach
                                </select>
                                <span class="error" id="stock_source_error"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col col-md-6">
                    <div class="row">
                        <div class="col col-md-10">
                            <label for="delivery_location" class="form-label">Stock Destination</label>
                            <div class="w100Select">
                                <select class="select2Box" id="destination_id" data-placeholder="Select Stock Destination" name="stock_destination" value="{{ old('location') }}" required>
                                    <option></option>
                                    @foreach ($locations as $location)
                                        <option value="{{ $location->id }}">{{ $location->location }}</option>
                                    @endforeach
                                </select>
                                <span class="error" id="stock_destination_error"></span>
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
                            <label for="delivery_location" class="form-label">Transfer Reason</label>
                            <div class="w100Select">
                                <select class="select2Box" id="transfer_reason_list" data-placeholder="Select Transfer Reason" name="transfer_reason" value="{{ old('location') }}" required>                                    <option></option>
                                    @foreach ($transferReasons as $transferReason)
                                        <option value="{{ $transferReason->id }}">{{ $transferReason->name }}</option>
                                    @endforeach
                                </select>
                                <span class="error" id="transfer_reason_error"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col col-md-6">
                    <div class="row">
                        <div class="col col-md-10 input-addon addon-right">
                            <label for="order_date" class="form-label">Creation Date</label>
                            <input type="text" class="datepicker form-control form-control-lg" id="created_date" value="{{ old('order_date') }}" placeholder="Select Creation Date" name="created_date">
                            <span class="error" id="created_date_error"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col col-md-6">
                    <div class="row">
                        <div class="col col-md-10" id="other_reason_text">
                            <label for="other_reason" class="form-label">Other Reason</label>
                            <input type="text"  class="form-control form-control-lg" id="transfer_reason" value="{{ old('purchase_no') }}" name="other_transfer_reason" placeholder="Type Other Reason" value="">                            <span class="error" id="other_transfer_reason_error"></span>
                        </div>
                    </div>
                </div>
                <div class="col col-md-6">
                    <input type="text" class="form-control form-control-lg" id="auto_sto_no" name="sto_no" placeholder="STO NO" value="{{ $stoNo }}" hidden>
                </div>
            </div>
            <div class="row">
                <div class="col-12 txt-right">
                    <button type="button" id="add_sto_modal_btn" class="btn cancel-btn addicon-btn" data-bs-toggle="modal" data-bs-target="#product_sto_popup" >
                        <!-- <img src="{{ asset('icons/add.png') }}" class="pe-2"> -->
                        <i class='bx bx-plus pe-2'></i>
                        Add Products
                    </button>
                </div>
            </div>
            <button type="submit" hidden>submit</button>
        </form>
    </section>
    <section class="table-content">
        @include('pages.stock_management.stock_transfer_order.create_table')
    </section>
</div>
@include('pages.stock_management.stock_transfer_order.add_product_popup')
@include('pages.stock_management.stock_transfer_order.auto_no_popup')

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
            dateFormat: "DD, d M, yy",
            minDate: 0
        });
    });
</script>
<script type="text/javascript">
    var base_url = {!! json_encode(url('/')) !!}
</script>
<script type="text/javascript" src="{{ asset('js/stock_management/sto.js') }}"></script>
<script type="text/javascript">
    var base_url = {!! json_encode(url('/')) !!}
    var warningIconUrl = "{{ asset('/icons/warning.svg') }}";
    var successIconUrl = "{{ asset('/icons/success.svg') }}";

    var stoOrderedData = {};
    let config = new Object();
    config.links = new Object;
    config.links.productCheckAvailable = "{{ route('sto.productAvailability') }}";
    config.links.productValidate = "{{ route('sto.productValidate') }}";
    config.links.stoSave = "{{ route('sto.save') }}";

    new Sto(config);
</script>
@endpush
