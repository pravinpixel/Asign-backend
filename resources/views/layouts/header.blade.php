<!--begin::Header-->
<div id="kt_app_header" class="app-header">
  <!--begin::Header container-->
  <div class="app-container container-fluid d-flex align-items-stretch justify-content-between"
  id="kt_app_header_container" style="background-color: white;">
  <!--begin::Sidebar mobile toggle-->
  <div class="d-flex align-items-center d-lg-none ms-n3 me-1 me-md-2" title="Show sidebar menu">
    <div class="btn btn-icon btn-active-color-primary w-35px h-35px" id="kt_app_sidebar_mobile_toggle">
      <!--begin::Svg Icon | path: icons/duotune/abstract/abs015.svg-->
      <span class="svg-icon svg-icon-2 svg-icon-md-1">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
        xmlns="http://www.w3.org/2000/svg">
        <path
        d="M21 7H3C2.4 7 2 6.6 2 6V4C2 3.4 2.4 3 3 3H21C21.6 3 22 3.4 22 4V6C22 6.6 21.6 7 21 7Z"
        fill="currentColor"/>
        <path opacity="0.3"
        d="M21 14H3C2.4 14 2 13.6 2 13V11C2 10.4 2.4 10 3 10H21C21.6 10 22 10.4 22 11V13C22 13.6 21.6 14 21 14ZM22 20V18C22 17.4 21.6 17 21 17H3C2.4 17 2 17.4 2 18V20C2 20.6 2.4 21 3 21H21C21.6 21 22 20.6 22 20Z"
        fill="currentColor"/>
      </svg>
    </span>
    <!--end::Svg Icon-->
  </div>
</div>
<!--end::Sidebar mobile toggle-->
<!--begin::Mobile logo-->
<div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
  <a href="" class="d-lg-none">
    <img alt="Logo" src="{{ asset('new/images/logo/default-small.svg') }}" class="h-30px"/>
  </a>
</div>
<!--end::Mobile logo-->
<!--begin::Header wrapper-->
<div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1"
id="kt_app_header_wrapper" style="background-color: white;">

<!--begin::Menu wrapper-->
<div class="app-header-menu app-header-mobile-drawer align-items-stretch" data-kt-drawer="true"
data-kt-drawer-name="app-header-menu" data-kt-drawer-activate="{default: true, lg: false}"
data-kt-drawer-overlay="true" data-kt-drawer-width="250px" data-kt-drawer-direction="end"
data-kt-drawer-toggle="#kt_app_header_menu_toggle" data-kt-swapper="true"
data-kt-swapper-mode="{default: 'append', lg: 'prepend'}"
data-kt-swapper-parent="{default: '#kt_app_body', lg: '#kt_app_header_wrapper'}">
<!--begin::Menu-->
<div class="menu-title mt-2">
        <h3 class="mt-5">
           AsignArt
        </h3>
    </div>
<div
class="menu menu-rounded menu-column menu-lg-row my-5 my-lg-0 align-items-stretch fw-semibold px-2 px-lg-0"
id="kt_app_header_menu" data-kt-menu="true">
</div>
<!--end::Menu-->
</div>
<!--end::Menu wrapper-->

<!--begin::Navbar-->
<div class="app-navbar flex-shrink-0">

  <!--begin::User menu-->
  <div class="app-navbar-item ms-1 ms-md-3" style="padding-right: 25px;" id="kt_header_user_menu_toggle">
    @if(auth()->user()->role_id=='15')
    <div class="cursor-pointer symbol symbol-30px symbol-md-40px"
    data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent"
    data-kt-menu-placement="bottom-end">

    <a href="#">  <span class="badge rounded-pill badge-notification bg-danger">
      {{ auth()->user()->count}}</span>
      <i class="fas fa-bell" style="font-size: 40px;"></i>
        </a>

  </div>
  @endif
  {{-- notification popup --}}
  <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-400px" data-kt-menu="true" style="padding-top: 15%;">
    <div class="modal-header">
        <h5 class="modal-title" style="margin-left:160px; color: blue;">Notification</h5>
    </div>


      <div class="card card-notification shadow-none">
        @foreach(auth()->user()->notifications as $notification)
           @if($notification->is_read == 0)
        <div class="scrollbar-overlay" style="max-height:19rem" data-simplebar="init"><div class="simplebar-wrapper" style="margin: 0px;"><div class="simplebar-height-auto-observer-wrapper"><div class="simplebar-height-auto-observer"></div></div><div class="simplebar-mask"><div class="simplebar-offset" style="right: 0px; bottom: 0px;"><div class="simplebar-content-wrapper" tabindex="0" role="region" aria-label="scrollable content" style="height: auto; overflow: hidden scroll;"><div class="simplebar-content" style="padding: 0px;">
          <div class="list-group list-group-flush fw-normal fs--1">
            <div class="list-group-item">
              <a class="notification notification-flush notification-unread"  href="{{url('notification', $notification->id)}}">
                <div class="notification-avatar">
{{--                  <div class="avatar avatar-2xl me-3">--}}
{{--                    <img class="rounded-circle" src="../../assets/new/img/team/1-thumb.png" alt="">--}}
{{--                  </div>--}}
                </div>
                <div class="notification-body">
                  <p class="mb-1"><strong>{{ $notification->module }}</strong>
                    <br>{{ $notification->message }}</p>
                  <span class="notification-time"><span class="me-2" role="img" aria-label="Emoji">💬</span>{{$notification->created_at}}</span>
                </div>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
     </div>
    </div>
  </div>
</tr>
@endif
@endforeach
      </div>

    <hr style="width:100%;text-align:left;margin-left:0">
    <div class="modal-footer">
        <a href="{{ url('notification/') }}" class="modal-title" style="margin-right:170px; color: blue;">View all</a>
    </div>
</div>

<!--end::User account menu-->
<!--end::Menu wrapper-->
</div>
  <div class="app-navbar-item ms-1 ms-md-3" id="kt_header_user_menu_toggle">
    <!--begin::Menu wrapper-->
    <div class="cursor-pointer symbol symbol-30px symbol-md-40px"
    data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent"
    data-kt-menu-placement="bottom-end">
    <img src="{{ asset('images/avatar/blank.png') }}" alt="user"/>
  </div>
  <!--begin::User account menu-->
  <div
  class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px"
  data-kt-menu="true">
  <!--begin::Menu item-->
  <div class="menu-item px-3">
    <div class="menu-content d-flex align-items-center px-3">
      <!--begin::Avatar-->
      <div class="symbol symbol-50px me-5">
        <img alt="Logo" src="{{ asset('images/avatar/blank.png') }}"/>
      </div>
      <!--end::Avatar-->
      <!--begin::Username-->
      <div class="d-flex flex-column">
        <div class="fw-bold d-flex align-items-center fs-5">{{ Auth::user()->name }}
          <span class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">{{ Auth::user()->name }}</span>
        </div>
        <a href="#" class="fw-semibold text-muted text-hover-primary fs-7">{{ Auth::user()->email }}</a>
      </div>
      <!--end::Username-->
    </div>
  </div>
  <!--end::Menu item-->
  <!--begin::Menu separator-->
  <div class="separator my-2"></div>
  <!--end::Menu separator-->
  <!--begin::Menu item-->
  <div class="menu-item px-5">
    <a href="{{ url('auth') }}" class="menu-link px-5">My Profile</a>
  </div>
  <!--end::Menu item-->

  <!--begin::Menu item-->
  <div class="menu-item px-5">
    <a href="{{ url('change') }}" class="menu-link px-5">Change Password</a>
  </div>
  <!--end::Menu item-->

  <!--begin::Menu item-->
  <div class="menu-item px-5">
    <a class="menu-link px-5" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">Sign Out</a>
    <form id="frm-logout" action="{{ route('logout') }}" method="POST" style="display: none;">
      {{ csrf_field() }}
    </form>
  </div>
  <!--end::Menu item-->

</div>
<!--end::User account menu-->
<!--end::Menu wrapper-->
</div>
<!--end::User menu-->

</div>
<!--end::Navbar-->
</div>
<!--end::Header wrapper-->
</div>
<!--end::Header container-->
</div>
<!--end::Header-->

