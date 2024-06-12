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
        content: url(https://uat-api.asign.art/admin/public/assets/icons/down.png);
    }
    .accordion_head span.arrow_indicator.up::before{
        position: absolute;
        content: url(https://uat-api.asign.art/admin/public/assets/icons/up.png);
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
            <a href="{{url('/goods-received-notes/scan/GRN000001')}}">
                <img src="{{ asset('icons/arrow-left-alt.svg') }}" width="24" height="24" class="cP" />
            </a>
            <h4>GRN000001</h4>
        </main>
        <div>
            <button type="button" class="btn apply-btn">
                Print
            </button>
        </div>        
    </section>
    <section class="section-inner">
        <h1>GRN Summary</h1>
        <ul class="personal-info">
            <li>
                <span>PO/STO No.</span>
                <span>PO000001</span>
            </li>
            <li>
                <span>GRN No.</span>
                <span>GRN000001</span>
            </li>
            <li>
                <span>GRN Date</span>
                <span>23 Dec 2023</span>
            </li>
            <li>
                <span>GRN Location</span>
                <span>Mumbai - Lower Parel</span>
            </li>
            <li>
                <span>Created by</span>
                <span>Priyadarshi Patel</span>
            </li>
        </ul>
        <h1 class="mt-4">Sender Details</h1>
        <ul class="personal-info">
            <li>
                <span>Sender Name</span>
                <span>Manipal</span>
            </li>
            <li>
                <span>Transporter Name</span>
                <span>FedEx</span>
            </li>
        </ul>
    </section>
    <section class="table-content">        
        @include('components.tables.grn_summary_table')
    </section>
</div>
@endsection
@push('scripts')
<script type="text/javascript">
$(document).ready(function () {
    let $accordion_tbl = $("#accordion_tbl");
    $accordion_tbl.find("tr").eq('.accordion_body').hide();
    //$accordion_tbl.find("tr").eq(0).show();    
    $accordion_tbl.find(".accordion_head").click(function(){ 
        $accordion_tbl.find("tr.accordion_head").removeClass("focus").addClass("un_focus");
        $accordion_tbl.find("tr.accordion_body").hide();       
        $(this).toggleClass("focus un_focus");
        $(this).find("span.arrow_indicator").toggleClass("down up");
        $(this).next("tr").fadeToggle("fast");
    });
});
</script>
@endpush
