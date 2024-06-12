<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>@yield('title', config('app.name') )</title>
    <meta charset="utf-8" />
    <meta name="description" content="AsignArt Admin" />
    <meta name="keywords" content="admin" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="Assign Art" />
    <meta property="og:site_name" content="AsignArt | Admin" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('images/logo/favicon.ico') }}" />

    @section('style')
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/style.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/responsive.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/global.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/profile.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/sumoselect.css')}}">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('css/jquery.datepicker2.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('css/select2.min.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('css/select2-material.css')}}" />

    @show

</head>

<body class="sidebar_expanded">
    <div class="wrapper">
        @include('layouts.sidebar')
        <main id="main_content" class="main-content home-page position-relative">
            @yield('content')
        </main>
    </div>
    <div class="modal fade artist-modal" id="dynamic" tabindex="-1" aria-labelledby="bulkModalLabel" aria-hidden="true">
        <!--begin::Modal dialog-->
        @include('layouts.modal.dynamic_modal')
        <!--end::Modal dialog-->
    </div>
    @include('layouts.modal.conform_model')
    <input type="hidden" id="master_edit" value="{{access()->hasAccess('master.edit')}}">
    <input type="hidden" id="master_delete" value="{{access()->hasAccess('master.delete')}}">
    @section('script')
    <script type="text/javascript" src="{{ asset('js/popper.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.abScrollBar-v1.2.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/loadingoverlay.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.sumoselect.js') }}" type="module"></script>
    <script type="text/javascript" src="{{ asset('js/select2.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('js/qcTimepicker.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.datepicker2.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('js/script.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
    <script type="text/javascript">
        let baseUrl = "{{url('/')}}";
    </script>
    <script type="text/javascript">

        $(document).ready(function() {

            $('.custom_select').SumoSelect({
                placeholder: 'Select Here',
                floatWidth: '100%',
                // renderLi : (li, originalOption) => {
                //     console.log(li, originalOption);
                //     return "<span>fggg</span>"
                // },
            });

            $('.has_right_menu').on('mouseover', function() {
                var _nloftp, _sb, _sbh, _wh, _ph, _alt_h, _order;
                _nloftp = $(this).offset().top;
                _sb = $(this).find('.subnav');
                _ph = $(this).closest('.article-submenu').position(); 
                _order = $(this).index() + 1;
                _alt_h = _ph.top + (_order * 64);

                return _sb.css({
                    'top': _alt_h - 64
                });
            });

            $('.filter-dropy').on('hidden.bs.dropdown', function(e) {
                let img = $(this).find("img");
                $(this).parent("div.input-group").removeClass("shadow-effect");
                img.attr("src", "https://uat-api.asign.art/admin/public/assets/icons/arrow-down.svg");
            });
            $('.filter-dropy').on('shown.bs.dropdown', function(e) {
                let img = $(this).find("img");
                $(this).parent("div.input-group").addClass("shadow-effect");
                img.attr("src", "https://uat-api.asign.art/admin/public/assets/icons/arrow-up.svg");
            });

            $("#search").focus(function(){
                $(this).closest("div.input-group").addClass("shadow-effect");
            }).blur(function(){
                $(this).closest("div.input-group").removeClass("shadow-effect");
            });
        });
    </script>
    <script type="text/javascript">
        // Tooltips
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
        // Dropdowns
        const dropdownElementList = document.querySelectorAll('.dropdown-toggle');
        const dropdownList = [...dropdownElementList].map(dropdownToggleEl => new bootstrap.Dropdown(dropdownToggleEl));
        // Popo over
        const popoverTriggerList = document.querySelectorAll('.table-content [data-bs-toggle="popover"]')
        const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl, {
            html: true,
            customClass: 'black-over'
        }));
        const popoverTriggerListAlt = document.querySelectorAll('.side-bar [data-bs-toggle="popover"]')
        const popoverListAlt = [...popoverTriggerListAlt].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl, {
            html: true,
            customClass: 'white-over'
        }));
        const myModal = new bootstrap.Modal('.modal', {
            keyboard: false
        })

        // Toaster
        toastr.options = {
            "progressBar": true,
        };

        // add csrf token in ajax header
        $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    @stack('scripts')
</body>

</html>
