@extends('layouts.index')
@section('title', 'GRN Create')
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
        .sto_grn_sender_dropdown{
            display: none;
        }
    </style>
@endsection
@section('content')
    <div class="pages purchase-order-create">
        @php
            $splitSegment = request()->segments();
            $mainGrnId = end($splitSegment);
            // $grnId =  request()->segment(count($spiltSegment) - 1);
        @endphp
        <section class="m-header">
            <div class="hstack gap-3">
                <a href="{{ url('/purchase-orders') }}">
                    <img src="{{ asset('icons/arrow-left-alt.svg') }}" width="24" height="24" class="cP" />
                </a>
                <h4>{{ $grn_no }}</h4>
            </div>
            {{-- @if( !empty( $poData->purchase_order_products ) )
                @foreach ( $poData->purchase_order_products as $purchase_order )
                    @if( $purchase_order['grn_quantity'] > 0 )
                        <button id="grn_save_button" type="button" class="btn apply-btn">
                            Create GRN
                        </button>
                        @break
                    @endif
                    @if( $loop->last )
                        <button id="grn_save_button" type="button" class="btn apply-btn" disabled>
                            Create GRN
                        </button>
                    @endif
                @endforeach
            @else
                <button id="grn_save_button" type="button" class="btn apply-btn" disabled>
                    Create GRN
                </button>
            @endif --}}
            <button id="grn_save_button" type="button" class="btn apply-btn" disabled>
                Create GRN
            </button>
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
                <input type="text" class="form-control form-control-lg" name="grn_id" value={{ $mainGrnId }} hidden>
                <div class="row mb-4">
                    <div class="col col-md-6">
                        <div class="row">
                            <div class="col col-md-10">
                                <label for="grn" class="form-label">GRN No. </label>
                                <input type="text" class="form-control form-control-lg" id="grn" name="grn_no"
                                    placeholder="GRNO00001" value={{ $grn_no }} style="color: #B5B5B5;">
                                <span class="error" id="grnno_error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col col-md-6">
                        <div class="row">
                            <div class="col col-md-10">
                                <label for="transfer_no" class="form-label">Purchase / Transfer Order No. </label>
                                <div class="w100Select">
                                    <select class="select2Box" data-placeholder="Select Order NO" id="po_no_dropdown" name="purchase_order">
                                        <option value=""></option>
                                        @foreach( $all_purchase_orders as $all_purchase_order )
                                            <option data-type="{{ $all_purchase_order['type'] }}" value="{{ $all_purchase_order['id'] }}">
                                                {{ $all_purchase_order['purchase_order_no'] }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <span class="error" id="purchase_order_id_error"></span>

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
                                    <select class="select2Box" data-placeholder="Select GRN Location" name="branch_location"
                                        id="grn_loc">
                                        <option value=""></option>
                                        @foreach ($grn_locations as $grn_location)
                                            <option value={{ $grn_location['id'] }}>{{ $grn_location['location'] }}</option>
                                        @endforeach
                                    </select>
                                    <span class="error" id="branch_location_id_error"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col col-md-6">
                        <div class="row">
                            <div class="col col-md-10 input-addon addon-right">
                                <label for="order_date" class="form-label">Created on</label>
                                <input type="text" class="datepicker form-control form-control-lg" name="created_on"
                                    id="order_date" placeholder="Select" value="{{ $purchase_orders['order_date'] ?? "" }}">
                                    <span class="error" id="created_on_error"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col col-md-6">
                        <div class="row">
                            <div class="col col-md-10">
                                <label for="sender" class="form-label">Sender Name</label>
                                <div class="w100Select po_grn_sender_dropdown">
                                    <select class="select2Box po_sender" data-placeholder="Select Sender Name" name="manufacturer">
                                        <option></option>
                                        @foreach ($sender_names as $sender_name)
                                            <option value={{ $sender_name['id'] }}>{{ $sender_name['name'] }}</option>
                                        @endforeach
                                    </select>
                                    <span class="error" id="manufacturer_id_error"></span>
                                </div>
                                <div class="w100Select sto_grn_sender_dropdown">
                                    <select class="select2Box sto_sender" data-placeholder="Select Sender Name" name="manufacturer">
                                        <option></option>
                                        @foreach ($grn_locations as $grn_location)
                                            <option value={{ $grn_location['id'] }}>{{ $grn_location['location'] }}</option>
                                        @endforeach
                                    </select>
                                    <span class="error" id="manufacturer_id_error"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col col-md-6">
                        <div class="row">
                            <div class="col col-md-10">
                                <label for="transporter" class="form-label">Transporter Name</label>
                                <div class="w100Select">
                                    <select class="select2Box" data-placeholder="Select Transporter Name" id="transporter" name="transporter">
                                        <option></option>
                                        @foreach ($transporter_names as $transporter_name)
                                            <option value={{ $transporter_name['id'] }}>{{ $transporter_name['name'] }}</option>
                                        @endforeach
                                    </select>
                                    <span class="error" id="transporter_id_error"></span>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" hidden>Submit</button>
            </form>
        </section>
        <section class="table-content">
            @include('pages.stock_management.grn.grn_create_table')
        </section>
    </div>
    {{-- @include('components.popups.add_product_popup') --}}
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

            var originalDate = $('#order_date').val();
            if( originalDate ){
                var formattedDate = $.datepicker.formatDate("DD, d M, yy", new Date(originalDate));
                $('#order_date').val(formattedDate);
            }

        });
    </script>
    <script type="text/javascript" src="{{ asset('js/stock_management/grn.js') }}"></script>
    <script type="text/javascript">
        var base_url = {!! json_encode(url('/')) !!}
        var mainGrnId = "{{ $mainGrnId  }}"
        let config = new Object();
        config.links = new Object;
        config.links.grnsave = "{{ route( 'grn.save' ) }}";
        config.links.poProduct = "{{ route( 'grn.po.product' ) }}";
        new Grn(config);
    </script>
@endpush
