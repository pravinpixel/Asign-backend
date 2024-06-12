@extends('layouts.index')
@section('title', 'Label List')
@section('style')
    @parent
@endsection
@section('content')
    <div class="pages customers-list">
        <section class="main-header">
            <div class="section-title">
                <h4>Label List<span id="total">(<span id="total_count">{{ $total }}</span> Labels)</span></h4>
            </div>
        @php($filters = [['id' => 'all', 'text' => 'All', 'value' => ''], ['id' => 'scanned_product_id', 'text' => 'Scaned Product', 'value' => 'scanned_product_id']])
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
                        <div>
                        <i class='bx bx-filter-alt' style="padding-right: 10px;"></i>
                        Filters
                        </div>
                        <div>
                        <span id="filter_count"></span>
                        </div>
                    </div>
                    </button>
                </div>
                <div class="filter-bar">
                    <span class="clear-all" style="display:none;" id="clear-all">Clear All</span>
                </div>
                <div class="dropbar-bar disabled-btn-ctr">
                    
                </div>
            </div>
            <div id="filter_panel" class="close">
                <div class="row filter-focus mt-3 mb-2">
                    <div class="col-sm-12 col-md-3 custom_select_1">
                        <select multiple="multiple" placeholder="Select locations" name="location_id" class="custom_select">
                            @foreach ($locations as $k => $v)
                                <option value="{{ $v->id}}">{{ $v->location }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-3 custom_select_1">
                        <select multiple="multiple" placeholder="Select Products" name="product_id" class="custom_select">
                            @foreach ($products as $k => $v)
                                <option value="{{ $v->id}}">{{ $v->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-3 custom_select_1">
                        <select multiple="multiple" placeholder="Select Status" name="status" class="custom_select">
                            @foreach ($status as $k => $v)
                                <option value="{{ $k }}">{{ $v['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </div>

    <!-- #...table -->
    <section class="table-content">
        <table class="asign-table customer-table customer" id="master_table">
            <thead>
                <tr>
                    <th width="10%" scope="col" class="has_sort" data-value="location_id">Location</th>
                    <th width="20%" scope="col" class="has_sort" data-value="product_id">Product</th>
                    <th width="12%" scope="col" class="has_sort" data-value="scanned_product_id">Scaned Product</th>
                    <th width="17%" scope="col" class="has_sort" data-value="status">Status</th>
                </tr>
                </thead>
            <tbody class="tbody-detail">
                @include('pages.label_list.table')
            </tbody>
        </table>
    </section>
    <div id="pagination-div" class="section table-footer footer-form px-4">
        @include('components.pagination')
    </div>
   

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
 
        $(document).on('click', '.clear-all', function (e) {
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
                $("#filter_panel").removeClass('open');
                $("#filter_panel").addClass('close');
            });
            _debounceSearch();
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
            $('.tbody-detail').html("<tr><td colspan='4' class='text-center'>Loading .....</td></tr>");
            let location_id = $('select[name="location_id"]').val();
            let product_id = $('select[name="product_id"]').val();
            let status = $('select[name="status"]').val();
            let data = {
                'search': search_input.val(),
                'search_column': search_column,
                'page': page,
                'per_page': per_page,
                'sort': sort,
                'location_id': location_id,
                'product_id': product_id,
                'status': status,
            };
            $.ajax({
                url: baseUrl + "/label-status",
                type: "GET",
                data: data,
                success: function (response) {
                    $('.tbody-detail').html(response.table);
                    $('#pagination-div').html(response.pagination);
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

