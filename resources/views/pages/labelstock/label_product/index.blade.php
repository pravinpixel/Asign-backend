@extends('layouts.index')
@section('title', 'Stock View User List')
@section('style')
@parent
@endsection
@section('content')
<section class="section main-header py-0">
  <div class="profile-breadcrumb">
        <ul>
          <li>
          
            <img src="{{ asset('icons/label.svg') }}" width="20" height="20" />
            <a href="{{url('label-stock')}}">Stock </a><a>/</a><a href="">{{$labels[0]->location->name??''}} </a><a>/</a> <a href="">{{$labels[0]->agent->name??''}}</a>
          </li>
          <li></li>
        </ul>
      </div>
    </section>
  <section class="addsectionCtr border-bottom-0">
    <div class="row align-items-center">
      <div class="col-md-12">
        <div class="hstack gap-3">
          <div>
            <img src="{{ asset('icons/arrow-left.png') }}" width="24" height="24" class="cP"
              onclick="window.location='{{url('label-stock/view')}}/{{ $ids['product_id']}}/{{$ids['location_id']}}'"/>
          </div>
          <div class="addhead">{{$labels[0]->agent->name??''}}</div>
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
          <input type="hidden" id="agent_id" name="agent_id" value="{{ $ids['agent_id'] }}">
          <input type="hidden" id="product_id" name="product_id" value="{{ $ids['product_id'] }}">
          <input type="hidden" id="location_id" name="location_id" value="{{ $ids['location_id'] }}">
         
          <tbody  id="table">

          </tbody>

      </table>
  </section>
  @include('components.tables.asign_paginate')
@endsection
@push('scripts')
<script type="text/javascript">
  var url="{{ url('label-stock/product') }}";
  var view_url = "{{ url('label-stock/product-view') }}";
$('#table').on('click', '.row-class', function() {
    var labelId = $(this).data('id');
    var productId = $(this).data('product-id');
    window.location.href = view_url + '/' + labelId + '/' + productId;
})
</script>
 <script type="text/javascript" src="{{ asset('js/product.js') }}"></script>
@endpush