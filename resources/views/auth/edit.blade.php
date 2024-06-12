
@extends('layouts.index')

@section('title', 'Role')

@section('style')
@parent
<style>
  .card{
    margin-top: 10%;
  }
</style>
@endsection

@section('content')
<div class="card mb-5 mb-xl-10">
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
<form  method="post" action="{{url('auth/update')}}">
	@csrf
<!--begin::Card body-->
<div class="card-body border-top p-9">
<!--begin::Input group-->
<div class="row mb-6">
<!--begin::Label-->
<label class="col-lg-4 col-form-label fw-semibold fs-6">Avatar</label>
<!--end::Label-->
<!--begin::Col-->
<div class="col-lg-8">
<!--begin::Image input-->
<div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url('assets/media/svg/avatars/blank.svg')">
<!--begin::Preview existing avatar-->
<div class="image-input-wrapper w-125px h-125px" style="background-image: url(assets/media/avatars/300-1.jpg)"></div>
<!--end::Preview existing avatar-->
<!--begin::Label-->
<label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
<i class="bi bi-pencil-fill fs-7"></i>
<!--begin::Inputs-->
<input type="file" name="image" accept=".png, .jpg, .jpeg" />
<input type="hidden" name="avatar_remove" />
<!--end::Inputs-->
</label>
<!--end::Label-->
<!--begin::Cancel-->
<span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel avatar">
<i class="bi bi-x fs-2"></i>
</span>
<!--end::Cancel-->
<!--begin::Remove-->
<span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove avatar">
<i class="bi bi-x fs-2"></i>
</span>
<!--end::Remove-->
</div>
<!--end::Image input-->
<!--begin::Hint-->
<div class="form-text">Allowed file types: png, jpg, jpeg.</div>
<!--end::Hint-->
</div>
</div>
<!--end::Col-->
</div>
<!--end::Input group-->
<!--begin::Input group-->
<div class="row mb-6">
<!--begin::Label-->
<label class="col-lg-4 col-form-label required fw-semibold fs-3" >Name</label>
<!--end::Label-->
<!--begin::Col-->
<div class="col-lg-8">
<!--begin::Row-->
<div class="row">
<!--begin::Col-->
<div class="col-lg-6 fv-row">
<input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="name" value="{{$user->name}}" />
 @error('name')
<span class="invalid-feedback" role="alert">
  <strong>{{ $message }}</strong>
</span>
@enderror 
</div>
</div>
<!--end::Row-->
</div>
<!--end::Col-->
</div>

<div class="row mb-6">
<!--begin::Label-->
<label class="col-lg-4 col-form-label required fw-semibold fs-3">Email</label>
<!--end::Label-->
<!--begin::Col-->
<div class="col-lg-4 fv-row">
<input type="text" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Company name" value="{{$user->email}}" />
 @error('email')
<span class="invalid-feedback" role="alert">
  <strong>{{ $message }}</strong>
</span>
@enderror 
</div>
<!--end::Col-->
</div>
<div class="card-footer d-flex justify-content-end py-6 px-9">
<!-- <button type="reset" class="btn btn-light btn-active-light-primary me-2">Discard</button> -->
<button type="submit" class="btn btn-primary" id="kt_account_profile_details_submit">Save Changes</button>
</div>
<!--end::Actions-->
</form>
<!--end::Form-->
</div>
</div>
@endsection
@section('script')
@parent
@endsection
