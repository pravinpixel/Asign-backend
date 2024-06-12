@extends('layouts.index')
@section('title', 'Stock Transfer Order')
@section('style')
    @parent
    <link rel="stylesheet" type="text/css" href="{{ asset('css/jquery-ui.css') }}" />
    <style type="text/css">
        .purchase-order-create .m-header {
            padding: 24px 32px;
            display: flex;
            justify-content: flex-start;
            align-items: center;
            border-bottom: 1px solid rgba(29, 29, 29, 0.20);
        }

        .purchase-order-create .m-header .btn {
            margin-left: auto;
        }

        .purchase-order-create .form-content {
            padding: 24px 32px 0px;
        }

        .purchase-order-create .table-content {
            padding-top: 20px;
        }

        .input-addon {
            position: relative;
        }

        .input-addon>.form-control {
            position: relative;
            z-index: 9;
        }
        .error{
            color: #FB6F6F; 
            font-size: 14px;
            font-weight: 450;
        }
        .gray-colour{
            color: #B5B5B5 !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered{
            color: #B5B5B5 !important;
        }
        .sto_popup .select2-container--default .select2-selection--single .select2-selection__rendered{
            color: #000000 !important;
        }
        .sto_dropdown_arrow .select2-container--default .select2-selection--single .select2-selection__rendered{
            background-image: none;
        }
        .remove_background{
            background-color: #ffffff !important;
        }
    </style>
@endsection
@section('content')
    <div class="pages purchase-order-create">
        <section class="m-header">
            <div class="hstack gap-3">
                @php
                    $splitSegments = request()->segments();
                    $stoId = $splitSegments[count($splitSegments) - 1];
                @endphp
                <a href="{{ url('/stock-transfer-orders') }}">
                    <img src="{{ asset('icons/arrow-left-alt.svg') }}" width="24" height="24" class="cP" />
                </a>
                <h4>{{ $stoData['sto_no'] }}</h4>
            </div>
            {{-- @if (!empty($stoData->stock_transfer_order_products))
                @foreach ($stoData->stock_transfer_order_products as $stoProduct)
                    @if( $stoProduct['scanned_req_quantity'] > 0 )
                        <a class="btn apply-btn" id="pack_order_save_btn" href="{{ route('sto.pack.edit', ['edit_sto_id' => $stoData->id]) }}">Edit Order</a>   
                        @break
                    @endif
                    @if( $loop->last )
                        <a class="btn apply-btn" disabled>Edit Order</a>   
                    @endif
                @endforeach
            @else --}}
                <button id="sto_ordered_save_button" type="button" class="btn apply-btn">
                    Save Changes
                </button>  
               
            {{-- @endif --}}
            <div class="dropdown-bar">
                <i class='bx bx-dots-vertical-rounded fs-4 dropdown-toggle' data-bs-toggle="dropdown" aria-expanded="false"></i>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Export</a></li>
            </ul>
            </div>
           
        </section>
        <section class="form-content">
            <form id="grn_save_form" class="formFieldInput" autocomplete="off">
                @csrf
               
                <input type="text" class="form-control form-control-lg" name="grn_id" value={{ $stoId }} hidden>
                <div class="row mb-4">
                    <div class="col col-md-6">
                        <div class="row">
                            <div class="col col-md-10">
                                <label for="grn" class="form-label gray-colour">STO Number. </label>
                                <input type="text" class="form-control form-control-lg gray-colour remove_background" id="sto" name="sto_no"
                                    placeholder="GRNO00001" value={{ $stoData['sto_no'] }} disabled>
                                <span class="error" id="grnno_error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col col-md-6">
                        <div class="row">
                            <div class="col col-md-10 input-addon addon-right">
                                <label for="order_date" class="form-label gray-colour">Transfer Order Date</label>
                                <input type="text" class="datepicker form-control form-control-lg gray-colour" id="created_date" 
                                    value="{{ $stoData['created_date'] }}" placeholder="Select Creation Date" name="created_date" disabled>
                                <span class="error" id="created_date_error"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col col-md-6">
                        <div class="row">
                            <div class="col col-md-10">
                                <label for="manufacturer_name" class="form-label gray-colour">Stock Source</label>
                                {{-- <input type="text" class="form-control form-control-lg" id="manufacturer_name"> --}}
                                <div class="w100Select sto_dropdown_arrow">
                                    <select class="select2Box" id="location_id" data-placeholder="Select Stock Source" 
                                    name="stock_source" value="{{ old('manufacturer_name') }}" required disabled>
                                        <option></option>
                                        @foreach ($locations as $location)
                                            <option value="{{ $location->id }}"
                                                @if( isset( $stoData ) && $stoData['stock_source_id'] == $location->id ) selected="selected" @endif>
                                                {{ $location->location }}</option>
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
                                <label for="delivery_location" class="form-label gray-colour">Stock Destination</label>
                                <div class="w100Select sto_dropdown_arrow">
                                    <select class="select2Box" data-placeholder="Select Stock Destination" 
                                        name="stock_destination" value="{{ old('location') }}" required disabled>
                                        <option></option>
                                        @foreach ($locations as $location)
                                            <option value="{{ $location->id }}"
                                                @if( isset( $stoData ) && $stoData['stock_destination_id'] == $location->id ) selected="selected" @endif>
                                                {{ $location->location }}</option>
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
                            <div class="col col-md-10 input-addon addon-right">
                                <label for="order_date" class="form-label gray-colour">Shipping Date</label>
                                <input type="text" class="datepicker form-control form-control-lg" value="{{ old('shipping_date') }}" placeholder="Select Shipping Date" name="shipping_date" disabled>
                                <span class="error" id="created_date_error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col col-md-6 input-disables">
                        <div class="row">
                            <div class="col col-md-10">
                                <label for="grn" class="form-label gray-colour">Tracking ID</label>
                                <input type="text" class="form-control form-control-lg" id="tracking_id_edit_sto" name="tracking_id"
                                    placeholder="Enter Tracking Id" value={{ old('sto_no') }}>
                                <span class="error" id="grnno_error"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" hidden>Submit</button>
            </form>
        </section>
        <section class="table-content">
            @include('pages.stock_management.stock_transfer_order.pack_edit_table')
        </section>
    </div>
    {{-- @include('components.popups.add_product_popup') --}}
    @include('pages.stock_management.stock_transfer_order.add_product_popup')
@endsection
@push('scripts')
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $(".select2Box").each(function() {
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

            var originalDate = $('#created_date').val();
            if( originalDate ){
                var formattedDate = $.datepicker.formatDate("DD, d M, yy", new Date(originalDate));
                $('#created_date').val(formattedDate);
            }
            
        });
    </script>
<script type="text/javascript" src="{{ asset('js/stock_management/sto.js') }}"></script>
<script type="text/javascript">   
        var base_url = {!! json_encode(url('/')) !!}
        var warningIconUrl = "{{ asset('/icons/warning.svg') }}";
        var successIconUrl = "{{ asset('/icons/success.svg') }}";
        var stoOrderedData = @json($stoData);
        var stoId = "{{ $stoId }}";
        let config = new Object();
        config.links = new Object;
        config.links.stoOrderedProductEdit = "{{ route( 'sto.edit.pack.product' ) }}";
        config.links.productCheckAvailable = "{{ route('sto.productAvailability') }}";
        config.links.productValidate = "{{ route('sto.productValidate') }}";
        new Sto(config);
    
    </script>
@endpush