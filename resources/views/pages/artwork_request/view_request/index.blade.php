@extends('layouts.index')
@section('title', 'Private View Request')
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
                        <img alt="" src="{{ asset('icons/clipboard-text.svg') }}" width="18" height="18"/><a href="#">Artwork
                            Request</a>
                    </li>
                    <li>/</li>
                    <li>
                        <a href="">Private View Request</a>
                    </li>
                    <li></li>
                </ul>
            </div>
            <div class="section-title">
                <h4></h4>
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
                        <input type="text" placeholder="Search Artwork, Customer " class="form-control"
                               name="search"
                               aria-label="Text input with dropdown button">
                    </div>
                </div>
               
                    <div class="dropbar-bar"  >
                       
                    </div>
                
                
            </div>
        </div>
       
        {{--        filter end  --}}
        {{--        table start --}}

        <section class="table-content">
            <table class="asign-table purchase-order-table">
                <thead>
                <tr>
                    <th scope="col" class="has_sort" data-value="request_id" width="15%">Request ID</th>
                    <th scope="col" class="has_sort" data-value="artwork_id" width="35%">Artwork</th>
                    <th scope="col" class="has_sort" data-value="customer_id" width="35%">Customer</th>
                    <th scope="col" class="has_sort" data-value="status" width="15%">STATUS</th>
                </tr>
                </thead>
                <tbody id="tableCtr" class="tbody-detail">

                @include('pages.artwork_request.view_request.tab')
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

    function search() {

        $('.tbody-detail').html("<tr><td colspan='4' class='text-center'>Loading .....</td></tr>");

        let data = {
            'search': search_input.val(),
            'page': page,
            'per_page': per_page,
            'sort': sort,
        };
        $.ajax({
            url: "{{ route('image-request.viewRequestList') }}" ,
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
@endpush
