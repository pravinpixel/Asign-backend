@extends('layouts.index')
@section('title', 'Purchase Order Summary')
@section('style')
    @parent
    <style type="text/css">
        .purchase-order-summary .m-header {
            padding: 24px 32px;
            display: flex;
            justify-content: flex-start;
            align-items: center;
            border-bottom: 1px solid rgba(29, 29, 29, 0.20);
        }

        .purchase-order-summary .m-header>div {
            margin-left: auto;
        }

        .purchase-order-summary .form-content {
            padding: 24px 32px 0px;
        }

        .accordion_head span.arrow_indicator {
            position: relative;
        }

        .accordion_head span.arrow_indicator.down::before {
            position: absolute;
            content: url(https://uat-api.asign.art/admin/public/assets/icons/down.png);
        }

        .accordion_head span.arrow_indicator.up::before {
            position: absolute;
            content: url(https://uat-api.asign.art/admin/public/assets/icons/up.png);
        }

        .accordion_body {
            display: none;
        }

        .accordion_body>td {
            padding: 0px;
        }
    </style>
@endsection
@section('content')
    <div class="pages purchase-order-summary">
        <section class="m-header">
            <main class="hstack gap-3">
                <a href="{{ url('/purchase-orders') }}">
                    <img src="{{ asset('icons/arrow-left-alt.svg') }}" width="24" height="24" class="cP" />
                </a>
                <h4>{{ $purchase_order->purchase_order_no }}</h4>
            </main>
            <div>
                <button type="button" class="btn apply-btn po_print_btn" style="cursor:pointer; background-color: white; color:black;">
                    {{-- <a href="{{ route('purchase.pdf', ['id' => $purchase_order->id]) }}" class="text-white" target="_blank" > --}}
                        Print
                    {{-- </a> --}}
                </button>

                {{-- <a href="{{ url("/goods-reveived-notes/create-grn/$purchase_order->id") }}" class="btn apply-btn ms-3 creategrn_id"> Create GRN</a> --}}
                {{-- <a href="{{ url('goods-received-notes/create') }}" class="btn apply-btn ms-3 creategrn_id">
                    Create GRN
                </a> --}}

                @if (!empty( $purchase_order['purchase_order_products']))
                    @foreach ($purchase_order['purchase_order_products'] as $poProduct)
                        @if($poProduct['grn_quantity'] != $poProduct['quantity'])
                            <a class="btn apply-btn ms-3 creategrn_id" style="cursor:pointer;"> Create GRN</a>
                            @break
                        @endif
                        {{-- @if( $loop->last )
                            <a class="btn apply-btn ms-3 creategrn_id" style="pointer-events: none; opacity: 0.5;"> Create GRN</a>
                        @endif --}}
                    @endforeach
                @else
                    <a class="btn apply-btn ms-3 creategrn_id" style="cursor:pointer;"> Create GRN</a>
                @endif
            </div>
        </section>
        <section class="section-inner">
            <h1>Purchase Order Summary</h1>
            <ul class="personal-info">
                <li>
                    <span>PO No.</span>
                    <span>{{ $purchase_order->purchase_order_no }}</span>
                </li>
                <li>
                    <span>Order Date</span>
                    <span>{{ $purchase_order->order_date }}</span>
                </li>
                <li>
                    <span>Manufacturer Name</span>
                    <span>{{ $purchase_order->manufacturer?->name ?? "-" }}</span>
                </li>
                <li>
                    <span>Delivery Location</span>
                    <span>{{ $purchase_order->branch_location?->location }}</span>
                </li>
                <li>
                    <span>Created by</span>
                    <span>{{ $purchase_order->user?->name }}</span>
                </li>
            </ul>
        </section>
        <section class="table-content">
            @include('pages.stock_management.purchase_order.summary_table')
        </section>
    </div>
@endsection
@push('scripts')
   <script type="text/javascript">
$(document).ready(function () {
    let $accordion_tbl = $("#accordion_tbl");
    $accordion_tbl.find("tr").eq('.accordion_body').hide();

    $accordion_tbl.find(".accordion_head").click(function(){
        $accordion_tbl.find("tr.accordion_head").removeClass("focus");
        $accordion_tbl.find("tr.accordion_body").hide();
        if($(this).hasClass("un_focus")){
            $(this).find("span.arrow_indicator").removeClass("down").addClass("up");
            $(this).removeClass("un_focus").addClass("focus");
            $(this).next("tr.accordion_body").fadeIn("fast");
        }
        else{
            $(this).find("span.arrow_indicator").removeClass("up").addClass("down");
            $(this).removeClass("focus").addClass("un_focus");
            $(this).next("tr.accordion_body").fadeOut("fast");
        }
    });
});
</script>
    <script type="text/javascript" src="{{ asset('js/stock_management/purchase_order.js') }}"></script>
    <script type="text/javascript">
        var base_url = {!! json_encode(url('/')) !!}
        var purchaseOrderId = "{{ $purchase_order->id }}"
        var printUrl = "{{ route('purchase.pdf', ['id' => $purchase_order->id]) }}"
        let config = new Object();
        config.links = new Object;
        config.links.grnid = "{{ route('grn.grnid') }}";

        new Po(config);
    </script>
@endpush
