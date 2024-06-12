@extends('layouts.index')
@section('title', 'Label Issue Summary')
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
            <a href="{{url('/label-issues/scan/GRN000001')}}">
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
        <h1>Label Request Summary</h1>
        <ul class="personal-info">
            <li>
                <span>Request No.</span>
                <span>LR000001</span>
            </li>
            <li>
                <span>Created by</span>
                <span>Vaishali Turbhe</span>
            </li>
            <li>
                <span>Agent Name</span>
                <span>Vailshali Turbhe</span>
            </li>
            <li>
                <span>Location</span>
                <span>Mumbai</span>
            </li>
            <li>
                <span>Request Date</span>
                <span>23 Dec 2023</span>
            </li>
            <li>
                <span>Issue Date</span>
                <span>24 Dec 2023</span>
            </li>
            <li>
                <span>Return Date</span>
                <span>-</span>
            </li>
        </ul>
    </section>
    <section class="table-content">        
        @include('components.tables.label_issue_summary_table')
    </section>
</div>
@endsection
