@extends('layouts.index')
@section('title', 'Stock View')
@section('style')
@parent
@endsection
@section('content')
@php
if(count($datas)>0){
  $aget_stock=0;
   foreach($datas as $data){
    $aget_stock +=$data->balance;

   }
}       
@endphp
<section class="section main-header py-0">
  <div class="profile-breadcrumb">
        <ul>
          <li>
            <img src="{{ asset('icons/label.svg') }}" width="20" height="20" />
            <a href="{{url('label-stock')}}">Stock</a><a>/</a> <a href="">{{$location}}</a>
          </li>
          <li>
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
              onclick="window.location='{{url('label-stock')}}'"/>
          </div>
          <div class="addhead">{{$location}}</div>
        </div>
      </div>
    </div>
  </section>
  <section class="alt-sectionCtr pt-0">
    <div class="row">
      <div class="col-md-6">
        <div class="headerBorderBox">
          <div class="vstack gap-2">
            <div class="headerBorderBox-head">Store Balance</div>
            <div class="headerBorderBox-sub">{{$store_balance}}</div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="headerBorderBox">
          <div class="vstack gap-2">
            <div class="headerBorderBox-head">Agent Stock</div>
            <div class="headerBorderBox-sub">{{$aget_stock??0}}</div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="section table-content">
      <table class="asign-table customer-table">
          <thead>
              <tr>
                  <th scope="col" width="22%">Issued to</th>
                  <th scope="col">In Hand</th>
                  <th scope="col">Consumed</th>
                  <th scope="col">Returned</th>
                  <th scope="col">Balance</th>
              </tr>
          </thead>
          <tbody id="tableCtr">
            @if(count($datas)>0)
            @foreach($datas as $data)
          <tr onclick="window.location='{{url('label-stock/product',$data->agent_id)}}/{{$data->product_id}}/{{$data->location_id}}' ">
            <td>{{$data->agent->name}}</td>
            <td>{{$data->in_hand}}</td>
            <td>{{$data->consumed}}</td>
            <td>{{$data->returned}}</td>
            <td>{{$data->balance}}</td>
          </tr> 
          @endforeach
          @else
          <tr>
            <td colspan="5" style="text-align: center;cursor:default;">No Data Found!</td>
          </tr>
          @endif
          </tbody>
        
      </table>
  </section>
@endsection