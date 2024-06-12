@php($pageName = 'Asign Protect+ Approved')

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
                        <img alt="Asign Protect+" src="{{ asset('icons/protect_plus.svg') }}" width="18" height="18"/><a
                            href="">Asign Protect+ / {{ $pageName }}</a>
                    </li>
                    <li>
                        <span id="toggle_sidebar">


                        </span>
                    </li>
                </ul>
            </div>
            <div class="section-title">
                <h4>{{ $pageName }} <span>(<span id="total_count">{{ $total }}</span> Artwork)</span>
                </h4>
            </div>
            {{-- filter start --}}

            @php($filters = [
                ['id' => 'all', 'text' => 'All', 'value' => ''],
                ['id' => 'full_name', 'text' => 'Name', 'value' => 'full_name'],
                ['id' => 'aa_no', 'text' => 'Customer Id', 'value' => 'aa_no']
            ])

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
                            <input type="text" placeholder="Search name" class="form-control" name="search"
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

                    </div>
                </div>
                <div id="filter_panel" class="close">
                    <div class="row filter-focus mt-3 mb-2">
                        <div class="col-sm-12 col-md-3 mb-3  custom_select_1">
                            <select multiple="multiple" placeholder="Select Artist" name="full_name"
                                    class="custom_select">

                                @foreach ($customers as $r)
                                    @if($r->full_name )
                                        <option value="{{ $r->full_name }}">{{ $r->full_name  }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-3 mb-3 custom_select_1">
                            <select multiple="multiple" placeholder="Customer ID" name="aa_no" class="custom_select">
                                @foreach ($customers as $r)
                                    <option value="{{ $r->aa_no }}">{{ $r->aa_no }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-3 mb-3 custom_select_1">
                            <select multiple="multiple" placeholder="Customer Type" name="customer_type"
                                    class="custom_select">
                                <option value="artist">Artist</option>
                                <option value="business">Business</option>
                                <option value="collector">Collector</option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-3 mb-3 custom_select_1">
                            <select multiple="multiple" placeholder="Visibility" name="visibility"
                                    class="custom_select">
                                <option value="hidden">Hidden</option>
                                <option value="public">Public</option>
                                <option value="forsale">For Sale</option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-3 mb-3 custom_select_1">
                            <select multiple="multiple" name="sale_format" class="custom_select" search="true"
                                    placeholder="Sale Format">
                                <option value="na">NA</option>
                                <option value="offer">Offer</option>
                                <option value="on_request">On Request</option>
                                <option value="auction">Auction</option>
                                <option value="fixed_price">Fixed Price</option>
                            </select>
                        </div>

                    </div>
                </div>
            </div>
        </section>
        {{-- filter end --}}
        {{-- table start --}}

        <div class="table-content assign-approved-table">
            <table class="asign-table customer-table hide-select-arrow">
                <thead>
                    <tr>
                        <th style="width:200px;" scope="col" data-value="created_at">ARTWORK</th>
                        <th style="width:200px;" scope="col" data-value="full_name">ARTIST NAME</th>
                        <th style="width:200px;" scope="col" data-value="aa_no">CUSTOMER ID</th>
                        <th style="width:200px;" scope="col" data-value="asign_no">OBJECT NUMBER</th>
                        <th style="width:200px;" scope="col" data-value="account_type">CUSTOMER TYPE</th>
                        <th style="width:200px;" scope="col" data-value="created_at">VISIBILITY</th>
                        <th style="width:200px;" scope="col" data-value="created_at">SALE FORMAT</th>
                        <th style="width:200px;" scope="col" class="has_sort" data-value="approved_at">AGING</th>
                        <th style="width:200px;" scope="col" class="has_sort" data-value="likes">LIKES</th>
                        <th style="width:200px;" scope="col" class="has_sort" data-value="views">VIEWS</th>
                        <th style="width:200px;" scope="col" data-value="created_at">SHARES</th>
                    </tr>
                </thead>
                <tbody id="tableCtr" class="tbody-detail">
                    @include('pages.protect_approved.table')
                </tbody>
            </table>
        </div>

        {{-- <section class="table-content">
            <table class="asign-table customer-table hide-select-arrow">
                <thead>
                <tr>
                    <th width="20%" scope="col" data-value="created_at">ARTWORK</th>
                    <th width="9%" scope="col" data-value="full_name">ARTIST NAME</th>
                    <th width="12%" scope="col" data-value="aa_no">CUSTOMER ID</th>
                    <th width="12%" scope="col" data-value="asign_no">OBJECT NUMBER</th>
                    <th width="17%" scope="col" data-value="account_type">CUSTOMER TYPE</th>
                    <th width="15%" scope="col" data-value="created_at">VISIBILITY</th>
                    <th width="15%" scope="col" data-value="created_at">SALE FORMAT</th>
                    <th width="12%" scope="col" class="has_sort" data-value="approved_at">AGING</th>
                    <th width="12%" scope="col" class="has_sort" data-value="likes">LIKES</th>
                    <th width="12%" scope="col" class="has_sort" data-value="views">VIEWS</th>
                    <th width="12%" scope="col" data-value="created_at">SHARES</th>
                </tr>
                </thead>
                <tbody id="tableCtr" class="tbody-detail">
                @include('pages.protect_approved.table')
                </tbody>
            </table>
        </section> --}}
    </div>
    {{-- table end --}}
    <div id="pagination-div" class="section table-footer footer-form px-4">
        @include('components.pagination')
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('plugins/lodash/lodash.min.js') }}"></script>
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script>
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
                } else {
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

        $('.custom_select').SumoSelect({
            search: true,
        });

        $(document).on('click', '.clear-all', function (e) {
            $("#filter_panel").removeClass('open');
            $("#filter_panel").addClass('close');
            e.preventDefault();
            page = 1;
            per_page = 10;
            sort = '';
            search_input.val('');
            $('.has_sort').removeClass('asc').removeClass('desc');
            $('#filter_panel select').each(function () {
                $(this).val('').trigger('change');
                var sumoSelect = $(this)[0].sumo;
                if (sumoSelect) {
                    sumoSelect.reload();
                }
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
            $('.tbody-detail').html("<tr><td colspan='11' class='text-center'>Loading .....</td></tr>");
            let customer_type = $('select[name="customer_type"]').val();
            let visibility = $('select[name="visibility"]').val();
            let aa_no = $('select[name="aa_no"]').val();
            let full_name = $('select[name="full_name"]').val();
            let sale_format = $('select[name="sale_format"]').val();

            let data = {
                'search': search_input.val(),
                'search_column': search_column,
                'page': page,
                'per_page': per_page,
                'sort': sort,
                'customer_type': customer_type,
                'visibility': visibility,
                'aa_no': aa_no,
                'full_name': full_name,
                'sale_format': sale_format,
            };
            $.ajax({
                url: baseUrl + "/protect-approved",
                type: "GET",
                data: data,
                success: function (response) {
                    $('.tbody-detail').html(response.table);
                    $('#pagination-div').html(response.pagination);
                    $('#total_count').html(response.total);
                    if(response.total == 0){
                        $('.tbody-detail').html("<tr><td colspan='11' class='text-center'>No data found</td></tr>");
                    }

                },
                error: function (xhr) {
                    showErrorMessage(xhr);
                },
                complete: function (response) {
                }
            });

        }

        $(document).on('keyup', search_input, _debounceSearch);
    </script>
@endpush
