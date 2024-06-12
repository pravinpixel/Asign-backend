
@extends('layouts.index')

@section('title', 'Profile')

@section('style')
@parent
@endsection

@section('content')


<!--begin::Basic info-->
<div style="margin-top:13%; " class="card mb-5 mb-xl-10">
<!--begin::Card header-->
<div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">
<!--begin::Card title-->
<div class="card-title m-0">
	<h3 class="fw-bold m-0">Profile Details</h3>
</div>
<!--end::Card title-->
</div>
<!--begin::Card header-->
<!--begin::Content-->
<div id="kt_account_settings_profile_details" class="collapse show">
<!--begin::Form-->
<form id="kt_account_profile_details_form" action="{{ route('profile.update') }}"  method="POST" class="form" enctype="multipart/form-data">
	@csrf
	<!--begin::Card body-->
	<div class="card-body border-top p-9">
		<!--begin::Input group-->
		{{-- <div class="row mb-6">
			<!--begin::Label-->
			<label class="col-lg-4 col-form-label fw-semibold fs-6">Avatar</label>
			<!--end::Label-->
			
			<!--begin::Col-->
			<div class="col-lg-8">
				<!--begin::Image input-->
				<div class="image-input image-input-outline" data-kt-image-input="true" >
    <!-- Preview existing avatar -->
    <div class="image-input-wrapper w-125px h-125px" >
        <img class="w-125px h-125px" src="{{$user->profile_image}}" style="background-image: url(public/assets/images/avatar/blank.png);" alt="profile_image">
    </div>

    <!-- Change avatar button -->
    <label id="changeAvatarInput" class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
        <i class="bi bi-pencil-fill fs-7"></i>
        <input type="file" name="profile_image" accept=".png, .jpg, .jpeg" style="display: none;" />
        <input type="hidden" name="avatar_remove" />
    </label>

    <!-- Remove avatar button -->
    <span id="removeAvatarBtn"  class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove avatar">
    <i class="bi bi-x fs-2"></i>
</span>
</div>
       <!--end::Image input-->				
			</div>
			<!--end::Col-->
		</div> --}}
		<div class="col-md-12 row mb-6 fv-row">
			<div class="col-md-3 mt-4">
			  <label class="required form-label">Profile Image </label>
			</div>
			<div class="col-md-7">
				<div class="card-body text-center pt-0">
					@if( isset($user->profile_image) && !empty( $user->profile_image))
					@php
					$url =$user->profile_image;
					@endphp
					@endif
					<div class="image-input image-input-empty image-input-outline image-input-placeholder mb-3" data-kt-image-input="true"
					@if( isset($user->profile_image) && !empty( $user->profile_image ) )  style="background-image: url({{ asset($url) }})"  @else style="background-image: url(../../../assets/media/svg/files/blank-image.svg)" @endif >
					<div class="image-input-wrapper w-150px h-150px"></div>
				<label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
				  <i class="bi bi-pencil-fill fs-7"></i>
				  <input type="file" name="profile_image" accept=".png, .jpg, .jpeg" />
				  <input type="hidden" name="avatar_remove" />
				</label>
				<span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
				data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel avatar">
				<i class="bi bi-x fs-2"></i>
			  </span>
			  <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
			  data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove avatar">
			  <i class="bi bi-x fs-2"></i>
			</span>
			
		  </div>
	
		</div>
	  </div>
	  <span class="field-error" style="color:red" id="cover_image-error"></span>
	</div>
		<!--end::Input group-->
		<!--begin::Input group-->
		<div class="row mb-6">
			<!--begin::Label-->
			<label class="col-lg-4 col-form-label required fw-semibold fs-6">Name</label>
			<!--end::Label-->
			<!--begin::Col-->
			<div class="col-lg-8 fv-row">
				<input type="text" name="name" class="form-control form-control-lg form-control-solid" placeholder="Name" value="{{ $user->name }}" />
				@error('name')
               <div class="text-danger">{{ $message }}</div>
                @enderror
			</div>
			<!--end::Col-->
		</div>
		<!--end::Input group-->
		<!--begin::Input group-->
		<div class="row mb-6">
			<!--begin::Label-->
			<label class="col-lg-4 col-form-label required fw-semibold fs-6">Email</label>
			<!--end::Label-->
			<!--begin::Col-->
			<div class="col-lg-8 fv-row">
				<input type="email" name="email" class="form-control form-control-lg form-control-solid" placeholder="Email Id" value="{{ $user->email }}" />
				@error('email')
               <div class="text-danger">{{ $message }}</div>
                @enderror
			</div>
			<!--end::Col-->
		</div>
		<!--end::Input group-->
		<!--begin::Input group-->
		<div class="row mb-6">
			<!--begin::Label-->
			<label class="col-lg-4 col-form-label fw-semibold fs-6">
				<span class="required">Contact Number</span>
				<i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="Phone number must be active"></i>
			</label>
			<!--end::Label-->
			<!--begin::Col-->
			<div class="col-lg-8 fv-row">
				<input type="text" name="mobile_number" class="form-control form-control-lg form-control-solid" placeholder="Phone number" value="{{ $user->mobile_number }}" pattern="[0-9]*"/>
				@error('mobile_number')
               <div class="text-danger">{{ $message }}</div>
                @enderror
			</div>
			<!--end::Col-->
		</div>
		<!--end::Input group-->																																																																												
	</div>
	<!--end::Card body-->
	<!--begin::Actions-->
	<div class="card-footer d-flex justify-content-end py-6 px-9">
		
		<button type="submit" class="btn btn-primary" id="kt_account_profile_details_submit">Save Changes</button>
	</div>
	<!--end::Actions-->
</form>
<!--end::Form-->
</div>
<!--end::Content-->
</div>
@endsection
@section('script')
@parent

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const removeButton = document.querySelector('[data-kt-image-input-action="remove"]');
        const imageWrapper = document.querySelector('.image-input-wrapper');

        if (removeButton && imageWrapper) {
            removeButton.addEventListener("click", function() {
                imageWrapper.innerHTML = '<img class="w-125px h-125px" src="assets/images/avatar/blank.png" alt="Profile Image">'; // Clear the image wrapper
                // Also clear the file input if needed
                const inputFile = document.querySelector('[name="profile_image"]');
                if (inputFile) {
                    inputFile.value = ''; // Clear the input value
                }
            });
        }
    });
</script>


@endsection
