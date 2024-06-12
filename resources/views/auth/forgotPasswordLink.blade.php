<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
 <title>@yield('title',  config('app.name') )</title>

 <meta charset="utf-8"/>
 <meta name="description" content="AsignArt Admin"/>
 <meta name="keywords" content="admin"/>
 <meta name="viewport" content="width=device-width, initial-scale=1"/>
 <meta property="og:locale" content="en_US"/>
 <meta property="og:type" content="article"/>
 <meta property="og:title" content="Assign Art"/>
 <meta property="og:site_name" content="AsignArt | Admin"/>
 <link rel="shortcut icon" href="{{ asset('images/logo/favicon.ico') }}"/>
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
 @section('style')
 <link href="{{asset('css/style.bundle.css')}}" rel="stylesheet" type="text/css" />
 <link rel="stylesheet" href="{{ asset('css/style.css') }}"/>
 @show

</head>
<body id="kt_app_body" data-kt-app-layout="light-sidebar" data-kt-app-sidebar-enabled="true"
data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-header="true"
data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true"
class="app-default">
<!--begin::App-->
<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
 <!--begin::Page-->
 <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
  <!--begin::Header-->
  <body id="kt_body" class="app-blank app-blank bgi-size-cover bgi-position-center bgi-no-repeat">


   <!--begin::Theme mode setup on page load-->

   <!--end::Theme mode setup on page load-->
   <!--begin::Root-->
   <div class="d-flex flex-column flex-root top" id="kt_app_root">
   

    <div  class="d-flex flex-column flex-lg-row flex-column-fluid ">


     <div class="d-flex flex-column-fluid flex-lg-row-auto justify-content-center justify-content-lg-end p-12 ">
      <!--begin::Wrapper-->
      <div class="bg-body d-flex flex-center rounded-4 w-md-600px p-10 log">

       <!--begin::Content-->
       <div class="w-md-400px ">
        <!--begin::Form-->
        <form  method="post" action="{{ route('reset.password.post') }}">
         @csrf
         <input type="hidden"  name="token"  value="{{$token}}" />
         <input type="hidden" name="email" value="{{ $email }}">
        
         

         <!--begin::Heading-->
         <img class="logo" src="{{ asset('images/logo/default.svg') }}" alt="logo">
         <div class="text-center mb-11">
          <!--begin::Title-->
          <h1 class="text-dark fw-bolder mb-3">Forgot Password</h1>
          <!--end::Title-->
          <div class="text-gray-400 fw-bold fs-4">
         @if(Session::has('message'))
         <div class="alert alert-success" role="alert">
                {{Session::get('message')}}
         </div>
         @endif
            @error('email')
            <div data-field="email" data-validator="notEmpty" style="color:red;font-size:15px">{{ $message }}</div>
            @enderror
          </div>

         </div>



         <!--begin::Input group=-->
        

         <div class="fv-row mb-8">
          <!--begin::Email-->
          <input type="password" id="password" placeholder="Type Your New Password" name="password" autocomplete="off" class=" form-control bg-transparent" required />
          @error('password')
            <div data-field="password" data-validator="notEmpty" style="color:red;font-size:15px">{{ $message }}</div>
            @enderror
          <!--end::Email-->
         </div>

         <div class="fv-row mb-8">
          <!--begin::Email-->
          <input type="password" id="password_confirmation" placeholder="Confirm Your New Password" name="password_confirmation" autocomplete="off" class=" form-control bg-transparent" required />
          @error('password_confirmation')
            <div data-field="password_confirmation" data-validator="notEmpty" style="color:red;font-size:15px">{{ $message }}</div>
            @enderror
          <!--end::Email-->
         </div>
         <!--end::Input group=-->
        
         <!--end::Input group=-->
         <!--begin::Wrapper-->
         <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
          <div></div>

         </div>
         <!--end::Wrapper-->
         <!--begin::Submit button-->
         <div class="d-grid mb-10">
          <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
           <!--begin::Indicator label-->
           <span class="indicator-label">Reset password</span>
           <!--end::Indicator label-->
           <!--begin::Indicator progress-->
           <span class="indicator-progress">Please wait...
            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
            <!--end::Indicator progress-->
           </button>
          </div>
          <!--end::Submit button-->
          <!-- begin::Sign up-->
      <!--     <div class="text-gray-500 text-center fw-semibold fs-6">Not a Member yet?
           <a href="../../demo1/dist/authentication/layouts/overlay/sign-up.html" class="link-primary">Sign up</a></div> -->
           <!--end::Sign up -->
           
          </form>
          <!--end::Form-->
         </div>
         <!--end::Content-->
        </div>
        <!--end::Wrapper-->
       </div>
       <!--end::Body-->
      </div>
      <!--end::Authentication - Sign-in-->
     </div>
     <!--end::Root-->

    </body>
    @section('script')
    <script>var defaultThemeMode = "light"; var themeMode; if ( document.documentElement ) { if ( document.documentElement.hasAttribute("data-theme-mode")) { themeMode = document.documentElement.getAttribute("data-theme-mode"); } else { if ( localStorage.getItem("data-theme") !== null ) { themeMode = localStorage.getItem("data-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-theme", themeMode); }</script>
    <script src="assets/plugins/global/plugins.bundle.js"></script>
    <script src="assets/js/scripts.bundle.js"></script>
    <script src="{{ asset('js/common.js') }}"></script>
    @show
   </body>
   </html>
