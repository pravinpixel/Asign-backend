@extends('layouts.index')
@section('title',  'Stock Check')
@section('style')
    @parent
    <style type="text/css">
        .purchase-orders > section.main-header {
            padding: 24px 32px 24px;
        }

        .purchase-orders > section.section {
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
                        <img alt="" src="{{ asset('icons/clipboard-text.svg') }}" width="18" height="18"/><a href="#">Label
                            Management</a>
                    </li>
                    <li>/</li>
                    <li>
                        <a href="{{url('label-transfer')}}">Stock Transfer Order</a>
                    </li>
                    <li></li>
                </ul>
            </div>
            <div class="section-title">
                <h4>Stock Transfer Order</h4>
            </div>
        </section>

        {{--        filter start --}}

        <div class="section-filter filter-index">
            <div class="filter-setup">
                <div class="search-bar">
                    <div class="input-group filter-with">
                        <div class="addon-search">
                            <i class='bx bx-search fs-4'></i>
                        </div>
                        <input type="text"  placeholder="Search Order Number, Location etc. " class="form-control"
                               name="search"
                               aria-label="Text input with dropdown button">
                    </div>
                </div>
                <div class="dropbar-bar" @if (!access()->hasAccess('stock-transfer-order.create'))  data-toggle="tooltip"
                     data-placement="bottom" title="You don’t have Create permission"
                    @endif >
                    <a href="{{url('label-transfer/add')}}" type="button" class="btn apply-btn"
                       @if (!access()->hasAccess('stock-transfer-order.create')) disabled
                        @endif >
                        Create STO
                    </a>
                </div>
                <div class="dropdown-bar">
                    <i class='bx bx-dots-vertical-rounded fs-4 dropdown-toggle' data-bs-toggle="dropdown" aria-expanded="false"></i>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item"  id="export_button" href="#">Export</a></li>
                    </ul>
                </div>
            </div>
        </div>
        {{--        filter end  --}}
        {{--        table start --}}

        <section class="table-content">
            <table class="asign-table purchase-order-table">
                <thead>
                <tr>
                    <th scope="col" width="20%" class="has_sort" data-value="transfer_no" >REQUEST ID</th>
                    <th scope="col" width="20%" class="has_sort" data-value="date" >REQUEST DATE</th>
                    <th scope="col" width="20%" class="has_sort" data-value="source_id" >FROM</th>
                    <th scope="col" width="20%" class="has_sort" data-value="destination_id" >TO</th>
                    <th scope="col" width="20%" class="has_sort" data-value="status" >STATUS</th>
                </tr>
                </thead>
                <tbody id="tableCtr" class="tbody-detail">

                @include('pages.label.transfer.table')

                </tbody>
            </table>
        </section>
        {{--        table end --}}
    </div>

    <div id="pagination-div" class="section table-footer footer-form px-4">
        @include('components.pagination')
    </div>

@endsection
@push('scripts')
    <script src="{{ asset('plugins/lodash/lodash.min.js') }}"></script>

    <script>
        let page = 1;
        let per_page = 10;
        let sort = '';
        let search_input = $('input[name="search"]');

        let _debounceSearch = _.debounce(search, 1000);
        var hasStockTransferEditAccess = {{ access()->hasAccess('stock-transfer-order.edit') ? 'true' : 'false' }};

        function search() {

            $('.tbody-detail').html("<tr><td colspan='5' class='text-center'>Loading .....</td></tr>");

            let data = {
                'search': search_input.val(),
                'page': page,
                'per_page': per_page,
                'sort': sort,
            };
            $.ajax({
                url: baseUrl + `/label-transfer`,
                type: "GET",
                data: data,
                success: function (response) {
                    $('.tbody-detail').html(response.table);
                    $('#pagination-div').html(response.pagination);
                    $('#total_count').html(response.total);
                },
                error: function (xhr) {
                    console.log(xhr)
                },
                complete: function (response) {
                }
            });

        }

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

        $(document).on('click', '.row-class', function (e) {
            e.preventDefault();
            let tr = $(this).closest('tr');
            let id = tr.data('id');
            let status = tr.data('status');
            let url = baseUrl + "/label-transfer/" + id;
            if (hasStockTransferEditAccess) {
                window.location.href = url;
            }
        });

        $(document).on('keyup', search_input, _debounceSearch);

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


    </script>
<script>
   $('#export_button').click(function() {
    var search = $('input[name="search"]').val();
    $.ajax({
        url: baseUrl + "/label-transfer/export",
        type: 'GET',
        data: {
            search: search,
        },
        cache: false,
        xhrFields: {
            responseType: 'blob'
        },
        success: function(response) {
            console.log(response);
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(response);
            link.download = 'Stock_Check.xlsx';
            link.click();
        },
        error: function(xhr, status, error) {
            console.log(error);
        }
    });
});
</script>
@endpush
