@extends('layouts.index')
@section('title', 'Stock Transfer Orders')
@section('style')
@parent
<style type="text/css">
    .purchase-orders .addon-search{
        height: 100%;
        padding: 10px 8px 0px 16px;
    }
</style>
@endsection
@section('content')
<div class="pages purchase-orders">
    <section class="main-header filterHeaderCtr">
        <div class="section-breadcrumb">
            <ul>
                <li>
                    <img src="{{ asset('icons/clipboard-text.png') }}" width="20" height="20" /><a href="#">Stock Management</a>
                </li>
                <li>
                    <span id="toggle_sidebar">
                        <img src="{{ asset('icons/arrange-square.svg') }}" width="20" />
                    </span>
                </li> 
            </ul>
        </div>
        <div class="section-title">
           <h4>Stock Transfer Order</h4>
        </div>        
    </section>
    @include('components.tables.stock_transfer_order_filter')
    <section class="table-content">        
        @include('components.tables.stock_transfer_order_table')
    </section>
</div>
@endsection
