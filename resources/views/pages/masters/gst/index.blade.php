@extends('layouts.index')
@section('title', 'GST')
@section('style')
<style type="text/css">
  a[disabled] {
    pointer-events: none;
    border: 0px solid #CFCFCF !important;
    background-color: unset !important;
    color: #696969;
}
</style>
@parent
@endsection
@section('content')
<div class="pages customers-list">
<section class="main-header">
    <div class="section-breadcrumb">
                <ul>
            <li>
                <img src="{{ asset('icons/crown-1.svg') }} " width="18" /><a href="">Masters</a>
            </li>
            <li>
                /
            </li>
            <li>
            <a href="">Others</a>
            </li>
            <li>
            /
            </li>
            <li>
                <a href="{{url('masters/gst')}}">GST</a>
            </li>
            <li>
            </li>
        </ul>
    </div>
     <div class="section-title">
        <h4>GST<span id="total"></span></h4>
    </div>
    <!-- #....Header -->
<!-- #...search -->
<!-- #...end search -->
</section>
<div class="section-filter filter-index">
  <div class="filter-setup">
    <div class="search-bar">
      <div class="input-group filter-with">
        <button class="btn btn-light dropdown-toggle filter-dropy" type="button" data-bs-toggle="dropdown" aria-expanded="false">
          <span id='selcted_radio' style="text-transform: capitalize;"></span>
          <img class="indic" src="{{ asset('icons/arrow-down.svg') }}" width="20">
        </button>
        <ul class="dropdown-menu width-ul">
          <li class="li-one padding-li">
            <div class="custom-check">
              <input class="form-check-input" type="radio" name="flexRadioDefault1" id="all" value="all">
              <label class="radio-label font-drop" for="all">
                All
              </label>
            </div>
          </li>
          <li class="li-one  padding-li">
            <div class="custom-check">
              <input class="form-check-input" type="radio" name="flexRadioDefault1" id="name" value="name" checked>
              <label class="radio-label font-drop" for="name">
                Name
              </label>
            </div>
          </li>
        </ul>
        <div class="divide">
          <span></span><i class='bx bx-search fs-4'></i>
        </div>
        <input type="text" placeholder="Search name" class="form-control" aria-label="Text input with dropdown button" id="search" name="search">
      </div>
    </div>
    <!-- <div class="filter-bar">
      <span class="clear-all" id="clear-all">Clear All</span> 
    </div> -->
    <div class="dropbar-bar" @if (!access()->hasAccess('master.create'))  data-toggle="tooltip" data-placement="bottom" title="You don’t have Create permission" 
      @endif>
      <button type="button" class="btn cancel-btn" id="add" onclick="getModal()"  @if (!access()->hasAccess('master.create')) disabled @endif>
        <img src="{{ asset('icons/add.png') }}">  Add GST
        </button>
    </div>
  </div>
  <div id="filter_panel" class="close">
  </div>
</div>
<!-- #...table -->
<section  class="table-content">
    <table class="asign-table customer-table customer" id="master_table">
        <thead>
            <tr>
                <th scope="col" width="20%" >SR NO.</th>
                <th scope="col" width="20%" class="has_sort" data-value="name">Name</th>
                <th scope="col" width="20%" class="has_sort" data-value="percentage ">Percentage </th>
                <th scope="col" width="20%" class="has_sort" data-value="status">Status</th>
                <th scope="col" width="20%" >Action</th>
            </tr>
        </thead>
        <tbody id="table">
          
        </tbody>
    </table>
</section>
<!-- #...end table -->
<!-- #..paginate -->
</div>
@include('layouts.paginate')
@include('pages.masters.deletemodel')
@endsection
@push('scripts')
<script type="text/javascript">
  var url="{{ url('masters/gst/list') }}";
  var edit="{{ asset('icons/edit-2.png') }}";
  var edit_model="{{ route('gst.add_edit') }}";
    var save_model = "{{ route('gst.save') }}";
    var check="{{ route('gst.check') }}";
    var delete_icon = "{{ asset('icons/image_2024_01_24T04_03_24_643Z.png') }}";
  var delete_url="{{ route('gst.delete') }}";
</script>
 <script type="text/javascript" src="{{ asset('js/masters/gst.js') }}"></script>
@endpush
