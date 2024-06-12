
@extends('layouts.index')

@section('title', 'Profile')

@section('style')
@parent
<style>
   .top {
    background-color: white;
    border-radius: 1%;
    padding: 2%;

}

</style>

@endsection

@section('content')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
   <!--begin::Toolbar container-->
   <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
    <!--begin::Page title-->
    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
     <!--begin::Title-->
     <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Change Password</h1>
     <!--end::Title-->
     <!--begin::Breadcrumb-->
     <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
      <!--begin::Item-->
      <li class="breadcrumb-item text-muted">
       <a href="{{url('dashboard')}}" class="text-muted text-hover-primary">Home</a>
      </li>
      <!--end::Item-->
      <!--begin::Item-->
      <li class="breadcrumb-item">
       <span class="bullet bg-gray-400 w-5px h-2px"></span>
      </li>

      <li class="breadcrumb-item text-muted">Change Password</li>
      <!--end::Item-->
     </ul>
     <!--end::Breadcrumb-->
    </div>
    <!--end::Page title-->
    <!--begin::Actions-->
    <div class="d-flex align-items-center gap-2 gap-lg-3">
     <!--begin::Filter menu-->
     <div class="m-0">
      <!--begin::Menu toggle-->
       <!--end::Menu 1-->
      </div>
      <!--end::Filter menu-->
     </div>
     <!--end::Actions-->
    </div>
    <!--end::Toolbar container-->
   </div>
   <!--end::Toolbar-->

<div style="margin-top:3%;" class="top">

    <!--begin::Password-->
        <!--begin::Edit-->
        <div  id="kt_signin_password_edit" class="flex-row-fluid ">
    <!--begin::Form-->
    <form id="kt_signin_change_password" action="{{ route('change.password.post') }}"  method="POST" class="form" novalidate="novalidate">
        @csrf
        <div class="row mb-1">
            <div class="col-lg-4">
                <div class="fv-row mb-0">
                    <label for="currentpassword" class=" required form-label fs-6 fw-bold mb-3">Current Password</label>
                    <input type="password"  placeholder="Current Password" class=" form-control @error('currentpassword') is-invalid @enderror"  name="currentpassword" id="currentpassword" required />
                    <span toggle="#currentpassword" class="fa fa-fw fa-eye-slash field-icon toggle-password"></span>
                    @error('currentpassword')
            <span class="invalid-feedback" role="alert">
              <strong >{{ $message }}</strong>
            </span>
            @enderror
                </div>
            </div>
            <div class="col-lg-4">
    <div class="fv-row mb-0">
        <label for="newpassword" class=" required form-label fs-6 fw-bold mb-3">New Password</label>
        <input type="password"  placeholder="New Password"class="form-control @error('newpassword') is-invalid @enderror" name="newpassword" id="newpassword" required/>
        <span toggle="#newpassword" class="fa fa-fw fa-eye-slash field-icon toggle-password"></span>
        @error('newpassword')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
<div class="col-lg-4">
    <div class="fv-row mb-0">
        <label for="confirmnewpassword" class=" required form-label fs-6 fw-bold mb-3">Confirm New Password</label>
        <input type="password" placeholder="Confirm New Password" class="form-control @error('newpassword_confirmation') is-invalid @enderror" name="newpassword_confirmation" id="confirmnewpassword" />
        <span toggle="#confirmnewpassword" class="fa fa-fw fa-eye-slash field-icon toggle-password"></span>
        @error('newpassword_confirmation')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
</div>
        <br>
        <div class="d-flex">
            <button id="kt_password_submit" type="submit" class="btn btn-primary me-2 px-6">Update Password</button>
        </div>
    </form>
    <!--end::Form-->
</div>

        <!--end::Edit-->
    <!--end::Password-->
    </div>

@endsection
@section('script')
@parent
<script>
    
    // Function to show the eye icon
    function showEyeIcon(inputId) {
        $(`[toggle="${inputId}"]`).show();
    }

    // Hide all eye icons initially
    $('[toggle^="#"]').hide();

    

    // Add event listeners to hide/show eye icons based on input focus/blur
    $('input[type="password"]').on('focus', function () {
        const inputId = `#${$(this).attr('id')}`;
        showEyeIcon(inputId);
    });
  
</script> 

@endsection