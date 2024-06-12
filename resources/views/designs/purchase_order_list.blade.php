@extends('layouts.index')
@section('title', 'Purchase Orders')
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
                    <img src="{{ asset('icons/clipboard-text.svg') }}" width="20" height="20" /><a href="#">Stock Management</a>
                </li>
                <li>/</li>
                <li>
                    <a href="{{url('purchase-orders')}}">Purchase Orders</a>
                </li>
                <li></li>
            </ul>
        </div>
        <div class="section-title">
            <h4>Purchase Orders</h4>
        </div>
    </section>
    <section class="section">
        @include('components.tables.purchase_order_filter')
    </section>
    <section class="table-content">
        @include('components.tables.purchase_order_table')
    </section>
</div>
{{-- @include('components.popups.add_image_popup') --}}
@endsection
@push('scripts')
<script type="text/javascript">
$(document).ready(function() {

});
</script>
@endpush
