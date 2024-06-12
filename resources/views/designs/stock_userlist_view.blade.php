@extends('layouts.index')
@section('title', 'Stock View User List')
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
  <section class="addsectionCtr border-bottom-0">
    <div class="row align-items-center">
      <div class="col-md-12">
        <div class="hstack gap-3">
          <div>
            <img src="{{ asset('icons/arrow-left.png') }}" width="24" height="24" class="cP"
              onclick="window.location='{{url('stock/view/34')}}'"/>
          </div>
          <div class="addhead">Vaishali Turbhe</div>
        </div>
      </div>
    </div>
  </section>
  <section class="section table-content">
      <table class="asign-table customer-table">
          <thead>
              <tr>
                  <th scope="col" width="15%">Requests</th>
                  <th scope="col">Date</th>
                  <th scope="col">received</th>
                  <th scope="col">Consumed</th>
                  <th scope="col">Returned</th>
                  <th scope="col">Balance</th>
              </tr>
          </thead>
          <tbody id="tableCtr">
          <tr onclick="window.location='{{url('stock/user/request/007')}}'">
            <td>00004</td>
            <td>21 Dec, 2023</td>
            <td>250</td>
            <td>250</td>
            <td>0</td>
            <td>0</td>
          </tr> 
          <tr onclick="window.location='{{url('stock/user/request/007')}}'">
            <td>000044</td>
            <td>21 Dec, 2023</td>
            <td>0</td>
            <td>0</td>
            <td>500</td>
            <td>500</td>
          </tr> 
          <tr onclick="window.location='{{url('stock/user/request/007')}}'">
            <td>00003</td>
            <td>21 Dec, 2023</td>
            <td>250</td>
            <td>250</td>
            <td>0</td>
            <td>0</td>
          </tr> 
          <tr onclick="window.location='{{url('stock/user/request/007')}}'">
            <td>00006</td>
            <td>21 Dec, 2023</td>
            <td>0</td>
            <td>0</td>
            <td>500</td>
            <td>500</td>
          </tr>
          </tbody>
        
      </table>
  </section>
</div>
  @include('components.tables.asign_paginate')

@endsection