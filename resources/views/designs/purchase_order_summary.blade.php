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
        content: url(https://uat-api.asign.art/admin/public/assets/icons/arrow-down.svg);
    }
    .accordion_head span.arrow_indicator.up::before{
        position: absolute;
        content: url(https://uat-api.asign.art/admin/public/assets/icons/arrow-up.svg);
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
        <main class="hstack gap-3">
            <a href="{{url('/purchase-orders')}}">
                <img src="{{ asset('icons/arrow-left-alt.svg') }}" width="24" height="24" class="cP" />
            </a>
            <h4>PO00001</h4>
        </main>
        <div>
            <button type="button" class="btn cancel-btn">
                Print
            </button>
            <a href="{{url('/goods-received-notes')}}" class="btn apply-btn ms-3">
                Create GRN
            </a>
        </div>        
    </section>
    <section class="section-inner">
        <h1>Purchase Order Summary</h1>
        <ul class="personal-info">
            <li>
                <span>PO No.</span>
                <span>PO000001</span>
            </li>
            <li>
                <span>Order Date</span>
                <span>20 Dec, 2023</span>
            </li>
            <li>
                <span>Manufacturer Name</span>
                <span>Manipal</span>
            </li>
            <li>
                <span>Delivery Location</span>
                <span>Chennai</span>
            </li>
            <li>
                <span>Created by</span>
                <span>Priyadarshi Patel</span>
            </li>
        </ul>
    </section>
    <section class="table-content">        
        @include('components.tables.purchase_order_summary_table')
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
@endpush
