@extends('layouts.index')
@section('title', 'Businesses')
@section('style')
@parent
<style type="text/css">
    .progress {
        background-color: #D9D9D9;
    }

    .progress-bar.bg-custom {
        background-color: #22C55E;
    }
</style>
@endsection
@section('content')
<div class="pages customers-list">
    <section class="main-header">
        <div class="section-breadcrumb">
            <ul>
                <li>
                    <img src="{{ asset('icons/profile-2user.svg') }}" width="18" height="18" /><a href="">Customers</a>
                </li>
                <li>
                    /
                </li>
                <li>
                    <a href="">Businesses</a>
                </li>
                <li>
                </li>
            </ul>
        </div>
        <div class="section-title">
            <h4>Businesses <span id="total"></span></h4>
        </div>
    </section>
    @include('layouts.customer.filter')
    <section class="table-content">
        @include('layouts.customer.table')
    </section>
</div>
@include('layouts.paginate')
@endsection
@push('scripts')
<script type="text/javascript">
    // clear all btn hide and show
    $(document).ready(function() {
        $('.clear-all').hide();
        $('#filter_panel select, input[name="search"]').on('change keyup', function() {
            toggleClearAllButtonVisibility();
        });

        $('.clear-all').on('click', function() {
            $('#filter_panel select').val('');
            $('input[name="search"]').val('');
            $(this).hide();
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
</script>
<script>
    $('#export_button').click(function() {
        console.log("in");
        var login = $('#login_status').val();
        var city = $('#city_data').val();
        var status = $('#status').val();

        login = Array.isArray(login) ? login.join(',') : login;
        city = Array.isArray(city) ? city.join(',') : city;
        status = Array.isArray(status) ? status.join(',') : status;


        $.ajax({
            url: "{{route('export.business.export')}}",
            type: 'GET',
            data: {
                login: login,
                city: city,
                status: status
            },
            cache: false,
            xhrFields: {
                responseType: 'blob'
            },
            success: function(response) {
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(response);
                link.download = 'bussiness.xlsx';
                link.click();
            },
            error: function(xhr, status, error) {

            }
        });
    });
</script>
<script type="text/javascript">
    var url = "{{url('customer/business/list')}}";
    var view_url = "{{url('customer/business')}}/";
</script>
<script type="text/javascript" src="{{ asset('js/customer/index.js') }}"></script>
@endpush