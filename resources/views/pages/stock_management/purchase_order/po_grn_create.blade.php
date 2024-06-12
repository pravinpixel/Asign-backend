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

        .gray-colour{
            color: #B5B5B5 !important;
        }

        .remove_background{
            background-color: #ffffff !important;
        }

    </style>
@endsection
@section('content')
    <div class="pages purchase-order-create">
        @php
            $spiltSegment = request()->segments();
            $type = request()->segment(count($spiltSegment));
            $grnId =  request()->segment(count($spiltSegment) - 1);
            $orderId =  request()->segment(count($spiltSegment) - 2);
        @endphp
        <section class="m-header">
            <div class="hstack gap-3">
                <a href="{{ url('/purchase-orders') }}">
                    <img src="{{ asset('icons/arrow-left-alt.svg') }}" width="24" height="24" class="cP" />
                </a>
                <h4>{{ $grn_no }}</h4>
            </div>
            @if( !empty( $poData['purchase_order_products'] ) )
                @foreach ( $poData['purchase_order_products'] as $po_product )
                    @php
                        if($grnId && $po_product['id']){
                            $grn_quantity_currently_scanned_products = \App\Models\GrnProductDetail::where([['grn_id', $grnId], ['op_product_id', $po_product['id']]])->get()->count();
                        }
                    @endphp
                    @if( isset($grn_quantity_currently_scanned_products) && $grn_quantity_currently_scanned_products > 0 )
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
            @endif
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
                <input type="text" class="form-control form-control-lg" name="grn_id" value={{ $grnId }} hidden>
                <div class="row mb-4">
                    <div class="col col-md-6">
                        <div class="row">
                            <div class="col col-md-10">
                                <label for="grn" class="form-label gray-colour">GRN No. </label>
                                <input type="text" class="form-control form-control-lg gray-colour remove_background" name="grn_no"
                                    placeholder="Grn no" value={{ $grn_no }} readonly>
                                <span class="error" id="grnno_error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col col-md-6">
                        <div class="row">
                            <div class="col col-md-10">
                                <label for="transfer_no" class="form-label gray-colour">Purchase / Transfer Order No. </label>
                                <div class="w100Select">
                                    <input type="text" class="form-control form-control-lg gray-colour remove_background" name="order_no"
                                    placeholder="Grn no" value={{ $poData['purchase_order_no'] }} disabled>

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
                                    <select class="select2Box" data-placeholder="Select GRN Location" name="grn_location"
                                        id="grn_loc">
                                        <option></option>
                                        @foreach ($grn_locations as $location)
                                            <option value={{ $location['id'] }}
                                            {{
                                                $location['id'] == $poData->delivery_location ? 'selected' : ''
                                            }}
                                            >{{ $location['location'] }}</option>
                                        @endforeach
                                    </select>
                                    <span class="error" id="grn_location_error"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col col-md-6">
                        <div class="row">
                            <div class="col col-md-10 input-addon addon-right">
                                <label for="order_date" class="form-label">Created on</label>
                                <input type="text" class="datepicker form-control form-control-lg" name="created_on"
                                    id="order_date" placeholder="Select" value="{{ $poData->order_date}}">
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
                                <div class="w100Select">
                                    <select class="select2Box" data-placeholder="Select Sender Name" id="sender" name="sender_name">
                                        <option></option>
                                        @foreach ($sender_names as $sender)
                                            <option value={{ $sender['id'] }}
                                            {{
                                                $sender['id'] == $poData->manufacturer_name ? 'selected' : ''
                                            }}
                                            >{{ $sender['name'] }}</option>
                                        @endforeach
                                    </select>
                                    <span class="error" id="sender_name_error"></span>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col col-md-6">
                        <div class="row">
                            <div class="col col-md-10">
                                <label for="transporter" class="form-label">Transporter Name</label>
                                <div class="w100Select">
                                    <select class="select2Box" data-placeholder="Select Transporter Name" id="transporter_po_grn" name="transporter_name">
                                        <option></option>
                                        @foreach ($transporter_names as $transporter)
                                            <option value={{ $transporter['id'] }}
                                            {{-- {{
                                                $transporter['id'] == $poData->delivery_location ? 'selected' : ''
                                            }} --}}
                                            >{{ $transporter['name'] }}</option>
                                        @endforeach
                                    </select>
                                    <span class="error" id="transporter_name_error"></span>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" hidden>Submit</button>
            </form>
        </section>
        <section class="table-content">
            @include('pages.stock_management.purchase_order.po_grn_table')
        </section>
    </div>
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
                dateFormat: "DD, d M, yy"
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
        var grnId = "{{ $grnId }}"
        var requestType = "{{ $type }}"
        var orderId = "{{ $orderId }}"
        @if( $poData )
            var orderDetails = @json($poData);
        @endif

        let config = new Object();
        config.links = new Object;
        config.links.grnsave = "{{ route( 'grn.save' ) }}";
        config.links.poProduct = "{{ route( 'grn.po.product' ) }}";
        new Grn(config);

    </script>
@endpush
