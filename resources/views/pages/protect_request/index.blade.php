@php($pageName = 'Asign Protect+ Requested')

@extends('layouts.index')
@section('title', $pageName)
@section('style')
    @parent
    <link rel="stylesheet" type="text/css" href="{{ asset('css/demo/asign_protect_request.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/jquery-ui.css') }}"/>
@endsection
@section('content')
    <div class="pages customers-list">
        <section class="main-header">
            <div class="section-breadcrumb">
                <ul>
                    <li>
                        <img alt="Asign Protect+" src="{{ asset('icons/protect_plus.svg') }}" width="18" height="18"/><a href="">Asign Protect+ / {{ $pageName }}</a>
                    </li>
                    <li>
                        <span id="toggle_sidebar">


                        </span>
                    </li>
                </ul>
            </div>
            <div class="section-title">
                <h4>{{ $pageName }} <span>(<span id="total_count">{{ $total }}</span> {{ $pageName }})</span>
                </h4>
            </div>

            {{-- filter start --}}

            @php($filters = [['id' => 'all', 'text' => 'All', 'value' => ''], ['id' => 'request_id', 'text' => 'RQ Code', 'value' => 'request_id'], ['id' => 'aa_no', 'text' => 'Customer Id', 'value' => 'aa_no'], ['id' => 'status', 'text' => 'Status', 'value' => 'status']])

            <div class="section-filter-1 filter-index">
                <div class="filter-setup">
                    <div class="search-bar">
                        <div class="input-group filter-with">
                            <button class="btn btn-light dropdown-toggle filter-dropy" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                <span id="search-text">All</span>
                                <img class="indic" src="{{ asset('icons/arrow-down.svg') }}" width="20">
                            </button>
                            <ul class="dropdown-menu width-ul">
                                @foreach ($filters as $filter)
                                    <li class="li-one padding-li">
                                        <div class="custom-check">
                                            <input class="form-check-input" value="{{ $filter['value'] }}" type="radio"
                                                   name="flexRadioDefault" id="{{ $filter['id'] }}">
                                            <label class="radio-label font-drop" for="{{ $filter['id'] }}">
                                                {{ $filter['text'] }}
                                            </label>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="divide">
                                <span></span><i class='bx bx-search fs-4'></i>
                            </div>
                            <input type="text" placeholder="Search all" class="form-control" name="search"
                                   aria-label="Text input with dropdown button">
                        </div>
                    </div>
                    <div class="filter-bar">
                        <button id="toggle_search" type="button" class="btn btn-light">
                            <div class="hstack">
                                <i class='bx bx-filter-alt' style="padding-right: 10px;"></i>Filters
                                <span id="filter_count"></span>
                            </div>
                        </button>
                    </div>
                    <div class="filter-bar">
                        <span class="clear-all" id="clear-all" style="display: none;">Clear All</span>
                    </div>
                    <div class="dropbar-bar disabled-btn-ctr">
{{--                        <button type="button" class="btn cancel-btn" data-bs-toggle="modal" data-bs-target="#bulkModal"--}}
{{--                        disabled>--}}
{{--                            Bulk Assign--}}
{{--                        </button>--}}
                    </div>
                </div>
                <div id="filter_panel" class="close">
                    <div class="row filter-focus mt-3 mb-2">
                        <div class="col-sm-12 col-md-3 custom_select_1">
                            <select multiple="multiple" placeholder="Select Type" name="type" class="custom_select">
                                <option value="artist">Artist</option>
                                <option value="business">Business</option>
                                <option value="collector">Collector</option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-3 custom_select_1">
                            <select multiple="multiple" placeholder="Select City" name="city" class="custom_select">
                                @foreach ($cities as $city)
                                    <option value="{{ $city->name }}">{{ $city->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-3 custom_select_1">
                            <select multiple="multiple" placeholder="Select Status" name="status" class="custom_select">
                                @foreach ($status as $k => $v)
                                    @if ($v['label'] == 'Review')
                                        @continue
                                    @endif
                                    <option value="{{ $k }}">{{ $v['label'] }}</option>
                                @endforeach
                                <option value="review">Review</option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-3 custom_select_1">
                            <select multiple="multiple" placeholder="Select Team" name="teams" class="custom_select">
                                @foreach ($roles as $role)
                                    @foreach ($role as $r)
                                        <option value="{{ $r['id'] }}">{{ $r['name'] }}</option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        {{-- filter end --}}

        {{-- table start --}}
        <section class="table-content">
            <table class="asign-table customer-table hide-select-arrow">
                <thead>
                <tr>
                    <th width="8%" scope="col">
                        <div class="form-check redes-checkbox redes-checkbox-1">
                            <input class="form-check-input" type="checkbox" value="" id="flexCheckIndeterminate">
                        </div>
                    </th>
                    <th width="10%" scope="col" class="has_sort" data-value="request_id">RQ Code</th>
                    <th width="9%" scope="col" class="has_sort" data-value="account_type">Type</th>
                    <th width="12%" scope="col" class="has_sort" data-value="aa_no">Customer ID</th>
                    <th width="12%" scope="col" class="has_sort" data-value="city">City</th>
                    <th width="17%" scope="col" class="has_sort" data-value="status">Status</th>
                    <th width="15%" scope="col" data-value="team">Team</th>
                    <th width="15%" scope="col" data-value="date">Date</th>
                    <th width="12%" scope="col" class="has_sort" data-value="created_at">Age</th>
                </tr>
                </thead>
                <tbody id="tableCtr" class="tbody-detail">
                @include('pages.protect_request.table')
                </tbody>
            </table>
        </section>
    </div>
    {{-- table end --}}
    {{-- pagination start --}}

{{--     <div id="pagination-div">

    </div> --}}
    {{-- @include('components.pagination') --}}
    {{-- pagination end --}}

    <div id="pagination-div" class="section table-footer footer-form px-4">
        @include('components.pagination')
    </div>


    {{-- Bulk assign popup start   --}}

    <div class="modal fade artist-modal" id="bulkModal" tabindex="-1" aria-labelledby="bulkModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="bulkModalLabel">Bulk Assign</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <hr/>
                <div class="modal-body modalSelectW100">
                    <div class="container p-0 d-flex align-items-start">
                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist"
                             aria-orientation="vertical">
                            <button class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill"
                                    data-bs-target="#v-pills-home" type="button" role="tab"
                                    aria-controls="v-pills-home" aria-selected="true">Assign
                                Team
                            </button>
                            <button class="nav-link" id="v-pills-profile-tab" data-bs-toggle="pill"
                                    data-bs-target="#v-pills-profile" type="button" role="tab"
                                    aria-controls="v-pills-profile" aria-selected="false">
                                Schedule Visit
                            </button>
                        </div>
                        <div class="tab-content w-100" id="v-pills-tabContent">
                            <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel"
                                 aria-labelledby="v-pills-home-tab" tabindex="0">
                                <div class="popupdorpdownSelect popupdorpdownSelect-yellow mb-3">
                                    <label>Authenticator</label>
                                    <select class="js-example-placeholder-multiple js-states form-control"
                                            multiple="multiple" data-placeholder="Select Authenticator">
                                        <optgroup class="optgroup-text" label="you can select up to 2 authenticator">
                                            <option>One</option>
                                            <hr/>
                                        </optgroup>
                                        @foreach ($roles['authenticator'] as $role)
                                            <option value="{{ $role['id'] }}">{{ $role['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="popupdorpdownSelect popupdorpdownSelect-blue mb-3">
                                    <label>Conservator</label>
                                    <select class="js-example-placeholder-multiple js-states form-control"
                                            multiple="multiple" data-placeholder="Select Conservator">
                                        <optgroup class="optgroup-text" label="you can select up to 2 authenticator">
                                            <option>One</option>
                                            <hr/>
                                        </optgroup>
                                        @foreach ($roles['conservator'] as $role)
                                            <option value="{{ $role['id'] }}">{{ $role['name'] }}</option>
                                        @endforeach

                                    </select>
                                </div>
                                <div class="popupdorpdownSelect popupdorpdownSelect-lavender mb-3">
                                    <label>Field Agent</label>
                                    <select class="js-example-placeholder-multiple js-states form-control"
                                            multiple="multiple" data-placeholder="Select Field Agent">
                                        <optgroup class="optgroup-text" label="you can select up to 2 authenticator">
                                            <option>One</option>
                                            <hr/>
                                        </optgroup>

                                        @foreach ($roles['field_agent'] as $role)
                                            <option value="{{ $role['id'] }}">{{ $role['name'] }}</option>
                                        @endforeach

                                    </select>
                                </div>
                                <div class="popupdorpdownSelect popupdorpdownSelect-lavender mb-3">
                                    <label>Other Service Provider</label>
                                    <select class="js-example-placeholder-multiple js-states form-control"
                                            multiple="multiple" data-placeholder="Select Service Provider">
                                        <optgroup class="optgroup-text" label="you can select up to 2 authenticator">
                                            <option>One</option>
                                            <hr/>
                                        </optgroup>
                                        @foreach ($roles['service_provider'] as $role)
                                            <option value="{{ $role['id'] }}">{{ $role['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div
                                    style="display: flex;flex-direction:row;gap:16px;justify-content:end; padding: 10px 0px;">
                                    <button type="button" class="btn cancel-btn" data-bs-dismiss="modal">Cancel</button>
                                    <button type="button" class="btn apply-btn">Apply</button>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="v-pills-profile" role="tabpanel"
                                 aria-labelledby="v-pills-profile-tab" tabindex="0">
                                <div class="popupdorpdownSelect mb-3">
                                    <label>Select Date</label>
                                    <input type="text" class="form-control datepicker" id="visit_date"
                                           placeholder="Date">

                                </div>
                                <div
                                    style="display: flex;flex-direction:row;gap:16px;justify-content:end; padding: 10px 0px;">
                                    <button type="button" class="btn cancel-btn" data-bs-dismiss="modal">Cancel</button>
                                    <button type="button" class="btn apply-btn">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bulk assign popup end   --}}

@endsection

@push('scripts')
    <script src="{{ asset('plugins/lodash/lodash.min.js') }}"></script>
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>

    <script>
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

        //$(".js-example-placeholder-multiple").select2();
        $(".js-example-placeholder-multiple").each(function () {
            var placeholder = $(this).attr('data-placeholder');
            $(this).select2({
                placeholder: placeholder,
                minimumResultsForSearch: Infinity,
            });
        });


        let filter_span = $('#filter_count');
        let search_span = $('#search-text');
        let search_input = $('input[name="search"]');
        let search_column = '';
        let page = 1;
        let per_page = 10;
        let sort = '';

        let _debounceSearch = _.debounce(search, 1000);

        $(document).on('click', '.has_sort', function (e) {
            e.preventDefault();
            let sort_value = $(this).data('value');
            let order = 'desc';
            if ($(this).hasClass('desc')) {
                order = 'asc';
            }
            $('.has_sort').removeClass('asc').removeClass('desc');
            $(this).addClass(order);

            order = order === 'desc' ? 'asc' : 'desc';
            sort = sort_value + '|' + order;
            _debounceSearch();
        });

        $(document).on('click', 'input[name="flexRadioDefault"]', function (e) {
            let text = 'All';
            if ($(this).is(":checked")) {
                text = $(this).closest('div').find('label').html();
            }
            text = text.trim();
            search_span.html(text);
            search_input.attr('placeholder', 'Search ' + text);
            search_column = $(this).val();
            _debounceSearch();
        });


        $(document).on('change', '.custom_select', function (e) {
            e.preventDefault();
            let filter_count = 0;
            $('#filter_panel select').each(function () {
                let v = Object.values($(this).val()).length;
                if (v > 0) {
                    filter_count++;
                    $(this).closest("div").addClass('filter_color');
                }
                else{
                    $(this).closest("div").removeClass('filter_color');
                }
            });
            if (filter_count > 0) {
                filter_span.addClass('span-conn');
                filter_span.closest('div').addClass('filter-bar2');
                $('#toggle_search').addClass('filter_color');
            } else {
                filter_count = '';
                filter_span.removeClass('span-conn');
                filter_span.closest('div').removeClass('filter-bar2');
                $('#toggle_search').removeClass('filter_color');
            }
            filter_span.html(filter_count);
            _debounceSearch();
        });

        $(document).on('click', '.clear-all', function (e) {
            $("#filter_panel").removeClass('open');
            $("#filter_panel").addClass('close');
            e.preventDefault();
            search_column = '';
            page = 1;
            per_page = 10;
            sort = '';
            search_input.val('');
            $('.has_sort').removeClass('asc').removeClass('desc');

            $('#filter_panel select').each(function () {
                $(this).val('').trigger('change');
                $(this)[0].sumo.unload();
                $(this).SumoSelect();

            });
            _debounceSearch();
        });

        $(document).on('change', '#per-page', function (e) {
            e.preventDefault();
            page = 1;
            per_page = $(this).val();
            search();
        });

        $(document).on('click', '.arrow-btn', function (e) {
            e.preventDefault();
            let value = $(this).data('value');
            if (value === 'dec') {
                if (page > 1)
                    page--;
            } else {
                page++;
            }

            search();
        });


        function search() {

            $('.tbody-detail').html("<tr><td colspan='9' class='text-center'>Loading .....</td></tr>");

            let type = $('select[name="type"]').val();
            let city = $('select[name="city"]').val();
            let status = $('select[name="status"]').val();
            let team = $('select[name="teams"]').val();

            let data = {
                'search': search_input.val(),
                'search_column': search_column,
                'page': page,
                'per_page': per_page,
                'sort': sort,
                'type': type,
                'city': city,
                'status': status,
                'team': team,
            };
            $.ajax({
                url: baseUrl + "/protect-request",
                type: "GET",
                data: data,
                success: function (response) {
                    $('.tbody-detail').html(response.table);
                    $('#pagination-div').html(response.pagination);
                    $('#total_count').html(response.total);
                    initCustom();
                },
                error: function (xhr) {
                    showErrorMessage(xhr);
                },
                complete: function (response) {
                }
            });

        }

        $(document).on('keyup', search_input, _debounceSearch);

        $(document).on('click', '.row-class td:not(.disabled-td)', function (e) {
            e.preventDefault();
            let id = $(this).closest('tr').data('id');
            window.open("{{ url('/protect-request') }}/" + id, '_blank');
        });

        function initCustom() {
            $(".select2Box").select2({
                placeholder: "Name",
                minimumResultsForSearch: Infinity,
            });

            $(".datepicker").datepicker({
                dateFormat: "d M, yy",
                minDate: 0
            });
            $('[data-bs-toggle="tooltip"]').tooltip();
        }

        initCustom();

        $(document).on('click', 'input[name="date"]', function (e) {
            e.preventDefault();
            var toolTip = $(this).closest('tr').find('[data-bs-toggle="tooltip"]');
           var visible = $(this).datepicker( "widget" ).is(":visible");
           if(visible) {
                toolTip.tooltip('hide');
           } else {
                toolTip.tooltip('show');
           }
        });

        $(document).on('change', 'input[name="date"], select[name="team"]', function (e) {
            e.preventDefault();
            var _this = $(this);
            var tr = _this.closest('tr');
            let id = tr.data('id');
            let team = tr.find('select[name="team"]').val();
            let date = changeDateFormat(tr.find('input[name="date"]').val());
            let data = {
                'team': team,
                'date': date,
            };
            $.ajax({
                url: baseUrl + "/protect-request/" + id + "/change-team",
                type: "PATCH",
                data: data,
                success: function (response) {
                    var date = response.data.date;
                    var time = response.data.time;
                    if(date) {
                        tr.find('[data-bs-toggle="tooltip"]').attr('data-bs-original-title', '<div>' + date + '</div><div>' + time + '</div>');
                    }
                },
                error: function (xhr) {
                    showErrorMessage(xhr);
                    _this.val('');
                },
                complete: function (response) {
                }
            });
        });

    if (window.performance) {
        if (performance.navigation.type === performance.navigation.TYPE_RELOAD) {
          $('.clear-all').trigger('click');
        }
    }

    </script>
@endpush
