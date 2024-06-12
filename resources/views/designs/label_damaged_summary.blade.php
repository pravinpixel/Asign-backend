@extends('layouts.index')
@section('title', 'Label Damaged Summary')
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
    table.edit-asign-table tbody tr td{
        padding-top: 0px!important;
        padding-bottom: 0px!important;
        height: 70px;
    }
    table.edit-asign-table .select2-container--default .select2-selection--single{
        height: 70px !important;
        border-radius: 0px!important;
        border: 1px solid transparent;
    }
    table.edit-asign-table .select2-container--default .select2-selection--single .select2-selection__rendered{
        height: 70px !important;
        line-height: 70px !important;
    }
</style>
@endsection
@section('content')
<div class="pages purchase-order-summary">
    <section class="m-header">
        <main class="hstack gap-3">
            <a href="{{url('/label-damaged/create')}}">
                <img src="{{ asset('icons/arrow-left-alt.svg') }}" width="24" height="24" class="cP" />
            </a>
            <h4>DIL00001</h4>
        </main>
        <div>
            <button type="button" class="btn cancel-btn">
                Reset
            </button>  
        </div>             
    </section>
    <section class="section-inner">
        <div class="row">
            <div class="col-md-6">
                <div class="headerBorderBox">
                    <div class="vstack gap-2">
                        <div class="headerBorderBox-head">Product Type</div>
                        <div class="headerBorderBox-sub">Inventory Label</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="headerBorderBox">
                    <div class="vstack gap-2">
                        <div class="headerBorderBox-head">Quantity</div>
                        <div class="headerBorderBox-sub">0</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="table-content">        
        @include('components.tables.label_damaged_summary_table')
    </section>
</div>
@include('components.tables.asign_paginate')
@endsection
@push('scripts')
<script type="text/javascript">
    $(".select2Box").each(function(){
        var placeholder = $(this).attr('data-placeholder');
        $(this).select2({
            placeholder: placeholder,
            minimumResultsForSearch: Infinity,
        });
    });
</script>
@endpush
