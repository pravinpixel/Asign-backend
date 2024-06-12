@extends('layouts.index')
@section('title', 'Stock Check')
@section('style')
@parent
<style type="text/css">
    .purchase-orders > section.main-header{
        padding: 20px 32px;
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
</style>
@endsection
@section('content')
<div class="pages purchase-orders">
    <section class="main-header">
        <div class="section-breadcrumb">
            <ul>
                <li>
                    <img src="{{ asset('icons/crown-1.svg') }}" width="20" height="20" /><a href="#">Stock Management</a>
                </li>
                <li>/</li>
                <li>
                    <a href="{{url('/stock-check')}}">Stock Check</a>
                </li>
                <li></li>
            </ul>
        </div>
        <div class="section-title">
            <h4>Stock Check</h4>
        </div>
    </section>
    @include('components.tables.stock_check_filter')
    <section class="table-content">
        @include('components.tables.stock_check_table')
    </section>
</div>
{{-- @include('components.popups.add_image_popup') --}}
@endsection