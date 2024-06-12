@extends('layouts.index')
@section('title', 'Label Damaged')
@section('style')
@parent
<style type="text/css">
    .purchase-orders > section.main-header{
        padding: 24px 32px 24px;
    }
    .purchase-orders > section.section{
        padding: 0px 32px;
    }
    .purchase-orders .section-filter{
        padding-top: 0px;
    }
    .purchase-orders .addon-search{
        height: 100%;
        padding: 10px 8px 0px 16px;
    }
    .pagination {
        display: block!important;
    }
    body.sidebar_expanded .main-content{
        background-color: #F6F6F6;
    }
</style>
@endsection
@section('content')
<div class="pages purchase-orders">
    <section class="main-header">
        <div class="section-breadcrumb">
            <ul>
                <li>
                    <img src="{{ asset('icons/clipboard-text.svg') }}" width="18" height="18" /><a href="#">Label Management</a>
                </li>
                <li>/</li>
                <li>
                    <a href="{{url('/label-damaged')}}">Damaged Labels</a>
                </li>
                <li></li>
            </ul>
        </div>
        <div class="section-title">
            <h4>Damaged Labels</h4>
        </div>
    </section>
    <div class="section-filter filter-index">
        <div class="filter-setup">
            <div class="search-bar">
                <div class="input-group filter-with">
                    <div class="addon-search">
                        <i class='bx bx-search fs-4'></i>
                    </div>
                    <input id="search_damage" name="search" type="text" placeholder="Search Order Number, Location etc." class="form-control" aria-label="Text input with dropdown button">
                </div>
            </div>
            <div class="dropbar-bar"  @if (!access()->hasAccess('damages.create'))  data-toggle="tooltip" data-placement="bottom" title="You don’t have Create permission" 
                @endif >
                <a href="{{url('/label-damaged/create')}}" type="button" class="btn apply-btn"  @if (!access()->hasAccess('damages.create')) disabled 
                    @endif >
                    Add Damaged Label
                </a>
            </div>
            <div class="dropdown-bar">
                <i class='bx bx-dots-vertical-rounded fs-4 dropdown-toggle' data-bs-toggle="dropdown" aria-expanded="false"></i>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item"  id="export_button" href="#">Export</a></li>
                </ul>
            </div>
        </div>
    </div>
    <section class="table-content" id="table_content">
        @include('pages.label.damaged.tables.index_table', ["labels"=>$labels,"sortup"=>$sortup])
    </section>
</div>
<section id="paginations" class="section table-footer footer-form px-4 pagination">
    @include('pages.label.damaged.tables.table_paginate', [
        "paginate"=>$paginate
    ])
</section>
@endsection
@push('scripts')
<script type="text/javascript" src="{{ asset('js/label/label_damaged_list.js') }}"></script>
<script type="text/javascript">
    let config = new Object();
    config.links = new Object;
    config.links.searchPaginate = "{{ route('label-damaged.list') }}";

    new DamagedList(config);
</script>
<script>
    $('#export_button').click(function() {
        var search = $('input[name="search"]').val();
     $.ajax({
         url: baseUrl + "/label-damaged/export",
         type: 'GET',
         data: {
             search: search,
         },
         cache: false,
         xhrFields: {
             responseType: 'blob'
         },
         success: function(response) {
             console.log(response);
             var link = document.createElement('a');
             link.href = window.URL.createObjectURL(response);
             link.download = 'Label-damaged.xlsx';
             link.click();
         },
         error: function(xhr, status, error) {
             console.log(error);
         }
     });
 });
 </script>
@endpush
