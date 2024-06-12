@extends('layouts.index')
@section('title', 'User Request')
@section('style')
@parent
@endsection
@section('content')
<div class="pages user-management">
  <section class="alt-header filterHeaderCtr">
    <section class="section main-header">
      <div class="section-breadcrumb">
        <ul>
          <li>
            <img src="{{ asset('icons/box-1.svg') }}" width="20" height="20" />
            <a href="">Stock / </a> <a href="">Chennai / </a> <a href="">Vaishali Turbhe</a>
          </li>
        </ul>
      </div>
    </section>
  </section>
  <section class="addsectionCtr border-bottom-0 position-static">
    <div class="row align-items-center">
      <div class="col-md-12">
        <div class="hstack gap-3">
          <div>
            <img src="{{ asset('icons/arrow-left.png') }}" width="24" height="24" class="cP"
              onclick="window.location='{{url('stock/view/34')}}'"/>
          </div>
          <div class="addhead">Request NO. 00004</div>
        </div>
      </div>
    </div>
  </section>

   <section class="alt-sectionCtr pt-0">
    <div class="row">
      <div class="col-md-3">
        <div class="headerBorderBox">
          <div class="vstack gap-2">
            <div class="headerBorderBox-head">Issued</div>
            <div class="headerBorderBox-sub">500</div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="headerBorderBox">
          <div class="vstack gap-2">
            <div class="headerBorderBox-head">Consumed</div>
            <div class="headerBorderBox-sub">250</div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="headerBorderBox">
          <div class="vstack gap-2">
            <div class="headerBorderBox-head">Returned</div>
            <div class="headerBorderBox-sub">0</div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="headerBorderBox">
          <div class="vstack gap-2">
            <div class="headerBorderBox-head">Balance</div>
            <div class="headerBorderBox-sub">250</div>
          </div>
        </div>
      </div>
    </div>
  </section>

	@include('components.tables.stock_user_request_filter')
	@include('components.tables.stcok_user_request_table')

</div>
  @include('components.tables.asign_paginate')

@endsection