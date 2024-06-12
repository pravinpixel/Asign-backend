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
    table.edit-asign-table .select2-container--default .select2-selection--single,
    table.edit-asign-table .form-control{
        height: 70px !important;
        border-radius: 0px!important;
        border: 1px solid transparent!important;
        background-color: transparent!important;
    }
    table.edit-asign-table .select2-container--default .select2-selection--single .select2-selection__rendered{
        height: 70px !important;
        line-height: 70px !important;
    }
    table.edit-asign-table .select2-selection__arrow{
        width: 30px!important;
        height: 100%!important;
        right: 0px!important;
    }
    table.edit-asign-table .select2-selection__arrow > b{
        margin-top: 12px!important;
    }
    button.btn.outlined{
        padding : 6px 10px;
        background-color: transparent;
        border-radius: 4px;
        border: 1px solid #CFCFCF;
        font-size: 13px;
        color: #1D1D1D;
        transition: all 0.3s ease-in-out;
    }
    button.btn.outlined.off{
        pointer-events: none!important;
        border: 1px solid #CFCFCF;
        color: #B5B5B5;
    }
    button.btn.outlined:hover{
        background-color: #1D1D1D;
        border: 1px solid #1D1D1D;
        color: #fff;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered{
        padding: 0px 10px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__placeholder{
        color: #B5B5B5!important;
    }
    .pagination {
        display: block!important;
    }
    /*.edit-asign-table .select2-container--default .select2-selection--single .select2-selection__rendered{
     background-image: url(../icons/asc.png) #000;
}
.edit-asign-table td:hover .select2-container--default .select2-selection--single .select2-selection__rendered {
    background-image: url(../icons/asc.png);
    background-repeat: no-repeat;
    background-position: center right 12px;
    background-size: 20px;
}
.edit-asign-table td:hover .select2-container--default.select2-container--open .select2-selection--single .select2-selection__rendered {
    background-image: url(../icons/desc.png);
    background-repeat: no-repeat;
    background-position: center right 12px;
    background-size: 20px;
}*/
</style>
@endsection
@section('content')
<div class="pages purchase-order-summary">
    <section class="m-header">
        <main class="hstack gap-3">
            <a href="{{url('/label-damaged/create')}}">
                <img src="{{ asset('icons/arrow-left-alt.svg') }}" width="24" height="24" class="cP" />
            </a>
            <h4>{{$info->reference_id}}</h4>
        </main>
        <div>
            <button type="button" class="btn cancel-btn" id="confirm" disabled="true">
                Reset
            </button>  
        </div>
         <div class="dropbar-bar" style="margin-left: 15px">
            <!-- <i class='bx bx-dots-vertical-rounded fs-4 dropdown-toggle' data-bs-toggle="dropdown" aria-expanded="false"></i> -->
            {{-- <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Export</a></li>
            </ul> --}}
        </div>             
    </section>
    <div id="dynamic_summary">
        @include('pages.label.damaged.tables.summary_table', [
            "info"=>$info,
            "damage_details"=>$damage_details,
            "product_type"=>$product_type,            
            "product_id"=>$product_id,            
            "damage_id"=>$damage_id,            
            "product_id_url"=>$product_id_url,            
        ])           
    </div>
</div>
<section id="paginations" class="section table-footer footer-form px-4 pagination">
    @include('pages.label.damaged.tables.table_paginate', [
        "paginate"=>$paginate
    ])
</section>
@include('pages.label.components.confirm-modal')
@endsection
@push('scripts')
<script type="text/javascript" src="{{ asset('js/label/label_damaged_summary.js') }}"></script>
<script type="text/javascript">
    let config = new Object();
    config.links = new Object;
    config.links.saveSummary = "{{ route('label-damaged.update-summary') }}";
    config.links.fetchSummary = "{{ url('label-damaged/summary') }}";
    config.links.removeSummary = "{{ url('label-damaged/summary-delete') }}";
    config.links.removeAllSummary = "{{ url('label-damaged/summary-delete-all') }}";
    config.productID = {{ $product_id_url }};

    new DamageSummary(config);
</script>
@endpush
