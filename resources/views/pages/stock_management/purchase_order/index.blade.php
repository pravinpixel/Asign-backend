@extends('layouts.index')
@section('title', 'Purchase Orders')
@section('style')
    @parent
    <style type="text/css">
        .purchase-orders>section.main-header {
            padding: 24px 32px 24px;
        }

        .purchase-orders>section.section {
            padding: 0px 32px;
        }

        .purchase-orders .section-filter {
            padding-top: 0px;
        }

        .purchase-orders .addon-search {
            height: 100%;
            padding: 10px 8px 0px 16px;
        }
    </style>
@endsection
@section('content')
    <div class="pages purchase-orders">
        <section class="main-header">
            <div class="section-breadcrumb">
                <ul>
                    <li>
                        <img src="{{ asset('icons/clipboard-text.svg') }}" width="18" height="18" /><a
                            href="#">Label Management</a>
                    </li>
                    <li>/</li>
                    <li>
                        <a href="{{ url('purchase-orders') }}">Purchase Orders</a>
                    </li>
                    <li></li>
                </ul>
            </div>
            <div class="section-title">
                <h4>Purchase Orders</h4>
            </div>
        </section>
        {{-- <section class=""> --}}
        @include('components.tables.purchase_order_filter')
        {{-- </section> --}}
        <section class="table-content">
            <table class="asign-table purchase-order-table" id="purchase_list_table">
                <thead>
                    <tr>
                        <th scope="col" class="has_sort" data-value="order_date" width="15%">ORDER DATE</th>
                        <th scope="col" class="has_sort" data-value="purchase_order_no" width="15%">ORDER NO</th>
                        <th scope="col" class="has_sort" data-value="name" width="35%">MANUFACTURER NAME
                        </th>
                        <th scope="col" class="has_sort" data-value="location" width="20%">DELIVERY LOCATION
                        </th>
                        <th scope="col" class="has_sort" data-value="status" width="15%">STATUS</th>
                    </tr>
                </thead>
                <tbody id="tableCtr" class="tbody-detail">
                    @include('pages.stock_management.purchase_order.list_table')
                </tbody>
            </table>
        </section>
    </div>
    {{-- <div id="pagination-div">
        @include('components.pagination')
    </div> --}}
    <div id="pagination-div" class="section table-footer footer-form px-4">
        @include('components.pagination')
    </div>


    {{-- @include('components.popups.add_image_popup') --}}
@endsection
@push('scripts')
    <script src="{{ asset('plugins/lodash/lodash.min.js') }}"></script>
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script type="text/javascript">
        var base_url = {!! json_encode(url('/')) !!}
        var purchaseorderAccess = {{ access()->hasAccess('purchase-order.edit') ? 'true' : 'false' }};
        $(".js-example-placeholder-multiple").select2();


        let filter_span = $('#filter_count');
        let filter = $('#fil');
        let search_span = $('#search-text');
        let search_input = $('input[name="search"]');
        let search_column = '';
        let page = 1;
        let per_page = 10;
        let sort = '';

        let _debounceSearch = _.debounce(search, 1000);

        $(document).on('click', '.has_sort', function(e) {
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

        $(document).on('click', 'input[name="flexRadioDefault"]', function(e) {
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


        $(document).on('change', '.custom_select', function(e) {
            e.preventDefault();
            let filter_count = 0;
            $('#filter_panel select').each(function() {
                let v = Object.values($(this).val()).length;
                if (v > 0) {
                    filter_count++;
                }
            });
            if (filter_count > 0) {
                filter_span.addClass('span-conn');
                filter.addClass('filter-bar2');
            } else {
                filter_count = '';
                filter_span.removeClass('span-conn');
                filter.removeClass('filter-bar2');
            }
            filter_span.html(filter_count);
            _debounceSearch();
        });

        $(document).on('change', '#per-page', function(e) {
            e.preventDefault();
            per_page = $(this).val();
            search();
        });

        $(document).on('click', '.arrow-btn', function(e) {
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

            let manufacturer = $('select[name="manufacturer"]').val();
            let delivery_location = $('select[name="delivery_location"]').val();

            let data = {
                'search': search_input.val(),
                'search_column': search_column,
                'page': page,
                'per_page': per_page,
                'sort': sort,
                'manufacturer': manufacturer,
                'delivery_location': delivery_location,
            };
            $.ajax({
                url: "{{ route('purchase-orders.list') }}",
                type: "GET",
                data: data,
                success: function(response) {
                    $('.tbody-detail').html(response.table);
                    $('#pagination-div').html(response.pagination);
                    $('#total_count').html(response.total);
                    initCustom();
                },
                error: function(xhr) {},
                complete: function(response) {}
            });

        }
        $(document).on('click', '.row-class td:not(.disabled-td)', function(e) {
            e.preventDefault();
            let id = $(this).closest('tr').data('id');
            if(purchaseorderAccess){
                window.location.href = "{{ url('/purchase-orders/summary') }}/" + id;

            }
        });
        $(document).on('keyup', search_input, _debounceSearch);



        $(document).on('click', '.create-po', function(e) {
            e.preventDefault();
            window.location.href = "{{ url('/purchase-orders/create') }}/";
        });

        function initCustom() {
            $(".select2Box").select2({
                placeholder: "Name",
                minimumResultsForSearch: Infinity,
            });

            $(".datepicker").datepicker({
                dateFormat: "d M, yy"
            });

        }

        $(document).on('click', '#download-excel', function(e) {
            e.preventDefault();

            $(this).html('Downloading ...');

            let manufacturer = $('select[name="manufacturer"]').val();
            let delivery_location = $('select[name="delivery_location"]').val();
            let data = {
                'export': 'excel',
                'search': search_input.val(),
                'search_column': search_column,
                'sort': sort,
                'manufacturer': manufacturer,
                'delivery_location': delivery_location,
            };
            let url = "{{ route('purchase-orders.list') }}";
            $.ajax({
                url: url,
                type: "GET",
                data: data,
                cache: false,
                xhrFields:{
                    responseType: 'blob'
                },
                success: function(response) {
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(response);
                    link.download = 'purchase_order.xlsx';
                    link.click();
                },
                error: function(xhr) {
                    toastr.error('No data found');
                },
                complete: function(response) {
                    $('#download-excel').html('Export');
                }
            });
        });


        initCustom();
    </script>
    <script type="text/javascript">
        // clear all btn hide and show
        $(document).ready(function() {
            $('.clear-all').hide();
            $('#filter_panel select, input[name="search"]').on('change keyup', function() {
                toggleClearAllButtonVisibility();
            });

            function toggleClearAllButtonVisibility() {
                let filtersCount = $('#filter_panel select').filter(function() {
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
                $("#filter_panel").removeClass('open');
                $("#filter_panel").addClass('close');

                $('#filter_panel select').each(function () {
                    $(this).val('').trigger('change');
                    $(this)[0].sumo.unload();
                    $(this).SumoSelect();

                });
                _debounceSearch();
            });
    </script>
@endpush
