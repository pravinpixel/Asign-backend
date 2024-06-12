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

    .error{
        color: #FB6F6F; 
        font-size: 14px;
        font-weight: 450;
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
        <h4 id="purchase_order_title"><a href="{{url('purchase-orders')}}"><img id="purchase_create_arrow" src="{{ asset('icons/arrow-left-alt.svg') }}" /></a><span>Purchase Order</span></h4>
        <button type="button" class="btn apply-btn" id="create_po_button" disabled>
            Create PO
        </button>
        <div class="dropdown-bar">
            <i class='bx bx-dots-vertical-rounded fs-4 dropdown-toggle' data-bs-toggle="dropdown" aria-expanded="false"></i>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Export</a></li>
            </ul>
        </div>
    </section>
    <section class="form-content">
        <form class="formFieldInput" id="create_po_form" autocomplete="off">
            @csrf
            <div class="row mb-4">
                <div class="col col-md-6">
                    <div class="row">
                        <div class="col col-md-10">
                            <label for="manufacturer_name" class="form-label">Manufacturer Name</label>
                            {{-- <input type="text" class="form-control form-control-lg" id="manufacturer_name"> --}}
                            <div class="w100Select">
                                <select class="select2Box" data-placeholder="Select Manufacturer Name" name="manufacturer" value="{{ old('manufacturer_name') }}" required>
                                    <option></option>
                                    @foreach( $manufactures as $manufacture)
                                        <option value="{{ $manufacture->id }}">{{ $manufacture->name }}</option>
                                    @endforeach
                                </select>
                                <span class="error" id="manufacturer_name_error"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col col-md-6">
                    <div class="row">
                        <div class="col col-md-10">
                            <label for="delivery_location" class="form-label">Delivery Location</label>
                            <div class="w100Select">
                                <select class="select2Box" data-placeholder="Select Delivery Location" name="location" value="{{ old('location') }}" required>
                                    <option></option>
                                    @foreach ( $deliveryLocations as $deliveryLocation )
                                        <option value="{{ $deliveryLocation->id }}">{{ $deliveryLocation->location }}</option>
                                    @endforeach
                                </select>
                                <span class="error" id="location_name_error"></span>
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
                            <input type="text" class="form-control form-control-lg" id="purchase_order_no" value="{{ old('purchase_no') }}" name="purchase_no" placeholder="Enter Order No." value="">
                            <span class="error" id="purchaseno_name_error"></span>
                        </div>
                    </div>
                </div>
                <div class="col col-md-6">
                    <div class="row">
                        <div class="col col-md-10 input-addon addon-right">
                            <label for="order_date" class="form-label">Order Date</label>
                            <input type="text" class="datepicker form-control form-control-lg" id="order_date" value="{{ old('order_date') }}" placeholder="Select Order Date" name="order_date" readonly="true">
                            <span class="error" id="orderdate_name_error"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 txt-right">
                    <button type="button" id="add_modal_btn" class="btn cancel-btn addicon-btn" data-bs-toggle="modal"
                     data-bs-target="#product_popup">
                        {{-- <img src="{{ asset('icons/add.png') }}" class="pe-2"> --}}
                        <i class='bx bx-plus pe-2'></i>
                        Add Products
                    </button>
                </div>
            </div>
            <button type="submit" hidden>submit</button>
        </form>
    </section>
    <section class="table-content">
        @include('components.tables.purchase_order_create_table')
    </section>
</div>
@include('pages.stock_management.purchase_order.add_product_popup')
@endsection
@push('scripts')
<script src="{{asset('js/jquery-ui.min.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#qty').on('input blur paste', function() {
                $(this).val($(this).val().replace(/\D/g, ''))
            })
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
<script type="text/javascript" src="{{ asset('js/stock_management/purchase_order.js') }}"></script>
<script type="text/javascript">
    var base_url = {!! json_encode(url('/')) !!}
    let config = new Object();
    config.links = new Object;
    config.links.product = "{{ route('purchase.product') }}";
    config.links.save="{{ route('purchase.save') }}";

    new Po(config);
</script>
@endpush
