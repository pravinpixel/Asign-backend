@extends('layouts.index')
@section('title', 'Label Issues')
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
                    <a href="{{url('/label-issues')}}">Label Issue</a>
                </li>
                <li></li>
            </ul>
        </div>
        <div class="section-title">
            <h4>Label Issue</h4>
        </div>
    </section>
    @include('components.tables.label_issue_filter')
    <section class="table-content">        
        @include('components.tables.label_request_table')
    </section>
</div>
{{-- @include('components.popups.add_image_popup') --}}
@endsection
