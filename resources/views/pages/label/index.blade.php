@extends('layouts.index')
@section('title',  $page['name'])
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

        body.sidebar_expanded .main-content {
            background-color: #F6F6F6;
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
                        <a href="{{url($page['link'])}}">{{$page['name']}}</a>
                    </li>
                    <li></li>
                </ul>
            </div>
            <div class="section-title">
                <h4>{{$page['name']}}</h4>
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
                        <input type="text" placeholder="Search Order Number, Location etc. " class="form-control"
                               name="search" id="search"
                               aria-label="Text input with dropdown button">
                    </div>
                </div>
                @if($page['link'] ==" label-request")
                    <div class="dropbar-bar" @if (!access()->hasAccess('label-request.create'))  data-toggle="tooltip"
                         data-placement="bottom" title="You don’t have Create permission"
                        @endif >
                        <a href="{{url($page['link'].'/add')}}" type="button" class="btn apply-btn"
                           @if (!access()->hasAccess('label-request.create')) disabled
                            @endif >
                            {{$page['add']}}
                        </a>
                    </div>
                @elseif($page['link'] ==" label-issues'")
                    <div class="dropbar-bar" @if (!access()->hasAccess('label-issue.create'))  data-toggle="tooltip"
                         data-placement="bottom" title="You don’t have Create permission"
                        @endif >
                        <a href="{{url($page['link'].'/add')}}" type="button" class="btn apply-btn"
                           @if (!access()->hasAccess('label-issue.create')) disabled
                            @endif >
                            {{$page['add']}}
                        </a>
                    </div>
                @else
                    <div class="dropbar-bar" @if (!access()->hasAccess('label-return.create'))  data-toggle="tooltip"
                         data-placement="bottom" title="You don’t have Create permission"
                        @endif >
                        <a href="{{url($page['link'].'/add')}}" type="button" class="btn apply-btn"
                           @if (!access()->hasAccess('label-return.create')) disabled
                            @endif >
                            {{$page['add']}}
                        </a>
                    </div>
                @endif
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
                    <th scope="col" class="has_sort" data-value="request_id" width="15%">REQUEST ID</th>
                    <th scope="col" class="has_sort" data-value="request_date" width="35%">REQUEST DATE</th>
                    <th scope="col" class="has_sort" data-value="name" width="35%">FROM</th>
                    <th scope="col" class="has_sort" data-value="status" width="15%">STATUS</th>
                </tr>
                </thead>
                <tbody id="tableCtr" class="tbody-detail">

                @include('pages.label.components.table')

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
        var hasLabelRequestEditAccess = {{ access()->hasAccess('label-request.edit') ? 'true' : 'false' }};
        var hasLabelIssueEditAccess = {{ access()->hasAccess('label-issue.edit') ? 'true' : 'false' }};
        var hasLabelReturnEditAccess = {{ access()->hasAccess('label-return.edit') ? 'true' : 'false' }};
        var pagelink='{{$page['link']}}';
        let page = 1;
        let per_page = 10;
        let sort = '';
        let search_input = $('input[name="search"]');

        let _debounceSearch = _.debounce(search, 1000);

        function search() {

            $('.tbody-detail').html("<tr><td colspan='4' class='text-center'>Loading .....</td></tr>");

            let data = {
                'search': search_input.val(),
                'page': page,
                'per_page': per_page,
                'sort': sort,
            };
            $.ajax({
                url: baseUrl + `/{{$page['link']}}`,
                type: "GET",
                data: data,
                success: function (response) {
                    $('.tbody-detail').html(response.table);
                    $('#pagination-div').html(response.pagination);
                    $('#total_count').html(response.total);
                },
                error: function (xhr) {

                    console.log(xhr)

                    // showErrorMessage(xhr);
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
            let url = baseUrl + "/{{$page['link']}}/" + id;
            if (status === 'closed') {
                url += "/summary";
            }
            if (status === 'issued' && !url.includes('label-return')) {
                url += "/summary";
            }
            if (hasLabelRequestEditAccess && pagelink == 'label-request') {
                window.location.href = url;
            }
            if (hasLabelIssueEditAccess && pagelink == 'label-issues') {
                window.location.href = url;
            }
            if (hasLabelReturnEditAccess && pagelink == 'label-return') {
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
        console.log("in");
        var search = $('#search').val();

        search = Array.isArray(search) ? search.join(',') : search;


        $.ajax({
            url: baseUrl + `/{{$page['link']}}`+"/export",
            type: 'GET',
            data: {
                search: search,
            },
            cache: false,
                    xhrFields:{
                        responseType: 'blob'
                    },
            success: function(response) {
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(response);
                        link.download = 'label.xlsx';
                        link.click();
            },
            error: function(xhr, status, error) {
            
            }
        });
    });
</script>

@endpush
