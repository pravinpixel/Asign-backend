@extends('layouts.index')
@section('title', 'Role Management List')
@section('style')
@parent
@endsection
@section('content')
<div class="pages customers-list">
<section class="main-header">
    <div class="section-breadcrumb">
                <ul>
            <li>
                <img src="{{ asset('icons/crown-1.svg') }} " width="18" /><a href="#">Masters</a>
            </li>
            <li>
            /
            </li>
            <li>
                <a href="{{url('masters/role-management')}}">Roles</a>
            </li>
            <li>
            </li>
        </ul>
    </div>
     <div class="section-title">
        <h4>Roles<span id="total"></span></h4>
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
        <input type="text" placeholder="Search Name" class="form-control" aria-label="Text input with dropdown button" id="search" name="search">
      </div>
    </div>
    <!-- <div class="filter-bar">
    <span class="clear-all" id="clear-all">Clear All</span>
    </div> -->
     <div class="dropbar-bar"  @if (!access()->hasAccess('role.create'))  data-toggle="tooltip" data-placement="bottom" title="You don’t have Create permission" 
                @endif>
            <button type="button" class="btn cancel-btn" onclick="window.location='{{url('masters/role-management/add_edit')}}'"  @if (!access()->hasAccess('role.create')) disabled 
                @endif >
                  <img src="{{ asset('icons/add.png') }}" class="pe-2"> Add Role
            </button>
        </div>
  </div>
  <div id="filter_panel" class="close">
  </div>
</div>
<section class="table-content">
    <table class="asign-table customer-table">
        <thead>
            <tr>
                <th scope="col" width="40%"  class="has_sort" data-value="name">Role Name</th>
                <th scope="col"  >Limited Users</th>
            </tr>
        </thead>
        <tbody id="table">
        
        </tbody>
       
    </table>
</section>
</div>
@include('layouts.paginate')
@endsection
@push('scripts')
<script type="text/javascript">
var url="{{ url('masters/role-management/list') }}";
var view_url="{{ url('masters/role-management') }}/";
var hasLabelRequestEditAccess = {{ access()->hasAccess('role.edit') ? 'true' : 'false' }};
 $('#table').on('click', 'tr', function() {
   if(hasLabelRequestEditAccess){
    window.location.href = view_url + this.id;
   }
  });
</script>
 <script type="text/javascript" src="{{ asset('js/masters/role.js') }}"></script>
@endpush