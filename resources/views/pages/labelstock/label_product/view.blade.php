@extends('layouts.index')
@section('title', 'User Request')
@section('style')
@parent
@endsection
@section('content')
<section class="section main-header py-0">
  <div class="profile-breadcrumb">
        <ul>
          <li>
            <img src="{{ asset('icons/label.svg') }}" width="20" height="20" />
            <a href="{{url('label-stock')}}">Stock </a><a>/</a><a href="">{{$label->location->name??''}}  </a><a>/</a> <a href="">{{$label->agent->name??''}}</a>
          </li>
          <li></li>
        </ul>
      </div>
    </section>
  <section class="addsectionCtr border-bottom-0 position-static">
    <div class="row align-items-center">
      <div class="col-md-12">
        <div class="hstack gap-3">
          <div>
            <img src="{{ asset('icons/arrow-left.png') }}" width="24" height="24" class="cP"
              onclick="window.location='{{url('label-stock/product')}}/{{ $label['agent_id'] }}/{{ $product_id }}/{{ $label['location_id'] }}'"/>
          </div>
          <div class="addhead">{{ $label['request_id'] }}</div>
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
            <div class="headerBorderBox-sub">{{$labelProduct[0]['issued_qty']}}</div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="headerBorderBox">
          <div class="vstack gap-2">
            <div class="headerBorderBox-head">Consumed</div>
            <div class="headerBorderBox-sub">{{$labelProduct[0]['consumed_qty']}}</div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="headerBorderBox">
          <div class="vstack gap-2">
            <div class="headerBorderBox-head">Returned</div>
            <div class="headerBorderBox-sub">{{$labelProduct[0]['returned_qty']}}</div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="headerBorderBox">
          <div class="vstack gap-2">
            <div class="headerBorderBox-head">Balance</div>
            <div class="headerBorderBox-sub">{{$labelProduct[0]['balance_qty']}}</div>
          </div>
        </div>
      </div>
    </div>
  </section>
<div class="section-filter filter-index filter-index-1 sectionfilter-bg">
    <div class="filter-setup justify-content-start">
        <div class="search-bar">
            <div class="input-group filter-with" style="width: 431px;">                
                <div class="divide" style="background: transparent;">
                    <i class='bx bx-search fs-4'></i>
                </div>
                <input type="text" placeholder="Search All" class="form-control" aria-label="Text input with dropdown button" name="search">
            </div>
        </div>
        <div class="filter-bar">
            <button id="toggle_search" type="button" class="btn btn-light"><i class='bx bx-filter-alt' style="padding-right: 10px;"></i>Filters</button>
        </div>
    </div>
    <div id="filter_panel" class="close">
        <div class="row filter-focus mt-3 mb-2">
            <div class="col-sm-12 col-md-3 ">
                <select multiple="multiple" name="field_sort" placeholder="Select  Status" class="custom_select">
                    <option value="issued" data-badge="">Issued</option>
                    <option value="consumed" data-badge="">Consumed
                    </option>
                     <option value="damaged" data-badge="">Damaged
                    </option>
                    <option value="adjust" data-badge="">Adjust
                    </option>
                     <option value="returned" data-badge="">Returned
                    </option>
                    
                </select>
            </div>
        </div>
    </div>
</div>
<section>
  <table class="asign-table purchase-order-table tableCommonStyle">
    <thead>
    <tr>
        <th scope="col" width="20%">Envelope code</th>
        <th scope="col" width="20%">label code</th>
        <th scope="col" width="20%">Issued Date</th>
        <th scope="col" width="20%">Consumed Date</th>
        <th scope="col" width="20%">status</th>
    </tr>
    </thead>
  
    <input type="hidden" id="product_id" name="product_id" value="{{ $product_id }}">
    <input type="hidden" id="id" name="id" value="{{ $label['id'] }}">
    <tbody  id="table">
      {{-- @if(count($datas)>0)
            @foreach($datas as $data)
        <tr>
            <td>{{$data->label->request_id }}</td>
            <td>{{$data->code }}</td>
            <td>{{ date('d-m-Y',strtotime($data->issued))}}</td>
            <td>{{ date('d-m-Y',strtotime($data->consumed))}}</td>
            <td>
                <span class="statusYellow statusCtr">{{$data->status}}</span>
            </td>
        </tr>
       @endforeach
          @endif --}}
    </tbody>
</table>
</section>
  @include('components.tables.asign_paginate')

@endsection
@push('scripts')
<script type="text/javascript">
  var url="{{ url('label-stock/product-view') }}";
</script>
 <script type="text/javascript" src="{{ asset('js/productview.js') }}"></script>
@endpush