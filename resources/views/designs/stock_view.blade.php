@extends('layouts.index')
@section('title', 'Stock View')
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
            <a href="">Stock / </a> <a href="">Chennai</a>
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
              onclick="window.location='{{url('stock/list')}}'"/>
          </div>
          <div class="addhead">Chennai</div>
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
            <div class="headerBorderBox-sub">19,500</div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="headerBorderBox">
          <div class="vstack gap-2">
            <div class="headerBorderBox-head">Agent Stock</div>
            <div class="headerBorderBox-sub">500</div>
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
          <tr onclick="window.location='{{url('stock/user-list/view/232')}}'">
            <td>Vaishali Turbhe</td>
            <td>500</td>
            <td>250</td>
            <td>250</td>
            <td>0</td>
          </tr> 
          <tr onclick="window.location='{{url('stock/user-list/view/232')}}'">
            <td>Soumya Singh</td>
            <td>500</td>
            <td>0</td>
            <td>0</td>
            <td>500</td>
          </tr> 
          </tbody>
        
      </table>
  </section>
</div>


@endsection