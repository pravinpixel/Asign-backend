@extends('layouts.index')
@section('title', 'User Management List')

@push('head')
@endpush

@section('content')
<div class="pages customers-list">
<section class="main-header">
	<div class="section-breadcrumb">
		<ul>
			<li>
				<img src="{{ asset('icons/profile.png') }}" width="20" height="20" />
                <a href=""></a> <a href="">Users</a>
			</li>
			<li>

			</li>
		</ul>
	</div>
	<div class="section-title">
		 <h4>Users<span id="total"></span></h4>
	</div>

</section>
<div class="section-filter filter-index">
        <div class="filter-setup">
            <div class="search-bar">
                <div class="input-group filter-with">
                    <button class="btn btn-light dropdown-toggle filter-dropy" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span id='selcted_radio' style="text-transform: capitalize;"></span>
                       <img class="indic" src="{{ asset('icons/arrow-down-filter.png') }}" width="20">
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
                        <li class="li-one  padding-li">
                            <div class="custom-check">
                                <input class="form-check-input" type="radio" name="flexRadioDefault1" id="code" value="code">
                                <label class="radio-label font-drop" for="code">
                                    Code
                                </label>
                            </div>
                        </li>

                        <li class="li-one  padding-li">
                            <div class="custom-check">
                                <input class="form-check-input" type="radio" name="flexRadioDefault1" id="mobile_number" value="mobile_number">
                                <label class="radio-label font-drop" for="mobile_number">
                                    Mobile Number
                                </label>
                            </div>
                        </li>
                        <li class="li-one  padding-li">
                            <div class="custom-check">
                                <input class="form-check-input" type="radio" name="flexRadioDefault1" id="email" value="email">
                                <label class="radio-label font-drop" for="email">
                                    Email ID
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
            <div class="filter-bar">
                <button id="toggle_search" type="button" class="btn btn-light">
                    <div class="hstack">
                    <img class="indic" src="{{ asset('icons/filter.svg') }}" width="24"
                    style="margin-right: 8px;position:relative;top:-1px;">Filters
                    <span class="" id="filter_count"></span>
                    </div>
                </button>
            </div>
            <!-- <div class="filter-bar">
                <button id="toggle_search" type="button" class="btn btn-light">
                    <i class='bx bx-filter-alt' style="padding-right: 10px;"></i>Filters
                 <span class="" id="filter_count"></span>
             </button>
            </div> -->
             <div class="filter-bar">
            <span class="clear-all" id="clear-all" style="display: none;">Clear All</span>
             </div>

            <div class="dropbar-bar"
            @if (!access()->hasAccess('user.create'))  data-toggle="tooltip" data-placement="bottom" title="You don’t have Create permission"
                @endif >

                <button type="button" class="btn cancel-btn" onclick="window.location='{{url('user-management/add_edit')}}'"
                @if (!access()->hasAccess('user.create')) disabled
                @endif >
                  <img src="{{ asset('icons/add.png') }}" class="pe-2" > Add User
            </button>

            </div>
        </div>

    <div id="filter_panel" class="close">
        <div class="row filter-focus mt-3 mb-2">
            <div class="col-sm-12 col-md-3 custom_select_1">
                <select multiple="multiple" placeholder="Select Role" class="custom_select" name="role" id="role">
                @foreach($roles as $role)
                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                @endforeach
                </select>
            </div>
            <div class="col-sm-12 col-md-3 custom_select_1">
                <select multiple="multiple" placeholder="Select Location" class="custom_select" name="location" id="location">
                @foreach($locations as $location)
                        <option value="{{ $location->location }}">{{ $location->location }}</option>
                 @endforeach
                </select>
            </div>

        </div>
    </div>
</div>
<section class="table-content">
    <table class="asign-table customer-table">
        <thead>
            <tr>
                <th scope="col" width="10%" class="has_sort" data-value="code">Code</th>
                <th scope="col" width="20%" class="has_sort" data-value="name">User Name</th>
                <th scope="col" class="has_sort" data-value="role_id">Role</th>
                <th scope="col" class="has_sort" data-value="branch_office_id">Location</th>
                <th scope="col"  data-value="mobile_number">Phone</th>
                <th scope="col"  data-value="email">Email</th>
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
    // clear all btn hide and show
    $(document).ready(function () {
        $('.clear-all').hide();
        $('#filter_panel select, input[name="search"]').on('change keyup', function () {
            toggleClearAllButtonVisibility();
        });
        function toggleClearAllButtonVisibility() {
            let filtersCount = $('#filter_panel select').filter(function () {
                return $(this).val().length > 0;
            }).length;
            let searchValue = $('input[name="search"]').val();
            if (filtersCount > 0 || searchValue.trim() !== '') {
                $('.clear-all').show();
            } else {
                $('.clear-all').hide();
            }
        }
    });
    $(document).on('click', '#clear-all', function (e) {
        $('.clear-all').hide();
        $("#filter_panel").removeClass('open');
        $("#filter_panel").addClass('close');
        e.preventDefault();
        page = 1;
        per_page = 10;
        sort = '';
        search_input.val('');
        $('.protect .has_sort').removeClass('asc').removeClass('desc');
        $('#filter_panel select').each(function () {
            $(this).val('').trigger('change');
            var sumoSelect = $(this)[0].sumo;
            if (sumoSelect) {
                sumoSelect.reload();
            }
        });
        _debounceSearch();
    });
</script>
<script type="text/javascript">
var url="{{ url('user-management/list') }}";
var view_url="{{ url('user-management/edit') }}/";
var hasLabelRequestEditAccess = {{ access()->hasAccess('user.edit') ? 'true' : 'false' }};
$('#table').on('click', 'tr', function() {
    if(hasLabelRequestEditAccess){
    window.location.href = view_url + this.id;
    }
});
</script>
 <script type="text/javascript" src="{{ asset('js/user-management/index.js') }}"></script>
@endpush
