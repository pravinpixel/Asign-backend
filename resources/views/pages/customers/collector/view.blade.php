@extends('layouts.index')
@section('title', 'Collector view')
@section('style')
@parent
@endsection
@section('content')
<section class="section main-header py-0">
    <div class="profile-breadcrumb">
        <ul>
            <li>
                <img src="{{ asset('icons/profile-2user.svg') }} " width="18" /> <a href="{{url('customer')}}">Customers</a>
            </li>
            <li>
                /
            </li>
            <li>
                @if(Request()->route()->getPrefix()=='/customer')
                <a href="{{url('customer')}}">All Customer</a>
                @else
                <a href="{{url('customer/collector')}}">Collectors</a>
                @endif
            </li>
            <li>
                /
            </li>
            <li>
                <a href="javascript:void(0)" style=" text-transform: capitalize;">{{$data->full_name}}</a>
            </li>
            <li>
               <!--  <span id="toggle_sidebar">
                    <img src="{{asset('icons/arrange-square.svg')}}" width="20" />
                </span> -->
            </li>
        </ul>
    </div>
</section>
<section class="section main-header bgcolornew with-top-border">
    <div class="logged-user d-flex justify-content-between p-0">
        <div class="profile-widget widget-md d-flex flex-row gap-4">
            <div>
                @if(Request()->route()->getPrefix()=='/customer')
                <a href="{{url('customer')}}"><i class='bx bx-arrow-back'></i></a>
                @else
                <a href="{{url('customer/collector')}}"><i class='bx bx-arrow-back'></i></a>
                @endif
            </div>
            <div class="d-flex align-items-center justify-content-center">
                <img class="rounded-circle" style="width:72px;height:72px" src="@if(isset($data->profile_image)) {{$data->profile_image}} @else {{asset('images/noimage.png')}}@endif" alt="{{$data->full_name}}" />
            </div>
            <div class="d-flex flex-column name-profile">
                <div class="d-flex flex-row gap-4">
                    <span class="logged-username">{{$data->full_name}}</span>
                    <div class="open-profile">
                        <button class="button-all @if($data->status=='verified') verified @else unverified @endif" style=" text-transform: capitalize;">{{$data->status}}</button>
                    </div>
                </div>
                <span class="logged-role">Collector</span>
                <div class="d-flex align-items-center">
                    <span class="logged-register">Registered:{{date('d M Y', strtotime($data->created_at))}}</span>
                    <span class="dot mx-2"></span>
                    <span class="logged-register">Last login: @if($data->last_login_at !=NULL){{date('d M Y', strtotime($data->last_login_at))}}
          @else
            N/A
          @endif</span>
                </div>
            </div>
        </div>
        <div class="filter-setup ">
            <div class="dropbar-bar">
                <i class='bx bx-dots-vertical-rounded fs-4 dropdown-toggle' data-bs-toggle="dropdown"
                   aria-expanded="false"></i>
                <ul class="dropdown-menu">
                        <li id="delete_artist" data-bs-toggle="modal"
                            data-bs-target="#delete"><a class="dropdown-item">Delete</a></li>
                </ul>
            </div>
        </div>
        <div class="modal fade" id="delete" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered confirmationPopup">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title">Delete Collector</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      Are you sure you want to delete the Collector?
                      This action cannot be undone.
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn cancel-btn" data-bs-dismiss="modal" id="delete_cancel">Cancel
                      </button>
                      <button type="button" class="btn apply-btn" data-bs-dismiss="modal" id="delete_save">Delete</button>
                  </div>
              </div>
          </div>
      </div>
        <div class="d-flex gap-2 pe-3 show-profile-button1 d-none">
            <button type="button" class="btn btn-primary btn-lg width-verify">Verify</button>
            <button type="button" class="btn btn-primary btn-lg  width-pause" data-bs-toggle="modal" data-bs-target="#exampleModal">Pause Profile</button>
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog  modal-dialog-centered">
                    <div class="modal-content newmodal-width">
                        <div class="modal-header" style="border-bottom: none;">
                            <h5 class="modal-title title-new" id="exampleModalLabel">Pause Profile</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body content-new py-0">
                            Are you sure you want to Pause this Profile?
                        </div>
                        <div class="modal-footer" style="border-top: none;">
                            <button type="button" class="btn cancel-btn mx-2 product-popup-cancel-btn" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn apply-btn product-popup-save-btn">Pause</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section main-artist bgcolornew">
    <ul class="nav nav-tabs profile-nav-tabs gap-3" id="myTab" role="tablist">
        <li class="nav-item profile-nav-tabs-list" role="presentation">
            <button class="nav-link profile-nav-tabs-button active px-4" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">ACCOUNT</button>
        </li>
        <li class="nav-item" role="presentation">
      <button class="nav-link profile-nav-tabs-button" id="protect-tab" data-bs-toggle="tab" data-bs-target="#protect" type="button" r
      ole="tab" aria-controls="protect" aria-selected="false">
        <div class="hstack">
            <div>ASIGN PROTECT+</div>
            <div><span id="total_count"></span></div>
        </div>
      </button>
    </li>
    <li class="nav-item profile-nav-tabs-list" role="presentation">
          <button class="nav-link profile-nav-tabs-button" id="studio-tab" data-bs-toggle="tab" data-bs-target="#studio" type="button"
           role="tab" aria-controls="studio" aria-selected="false">
           <div class="hstack">
            <div>MY STUDIO</div>
            <div><span id="total_count1"></span></div>
          </div>
          </button>
      </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link profile-nav-tabs-button" id="sales-tab" data-bs-toggle="tab" data-bs-target="#sales" type="button" role="tab" aria-controls="sales" aria-selected="false">SALES</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link profile-nav-tabs-button" id="purchase-tab" data-bs-toggle="tab" data-bs-target="#purchase" type="button" role="tab" aria-controls="purchase" aria-selected="false">PURCHASES</button>
        </li>
    </ul>
    <div class="tab-content tab-div-content" id="myTabContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <section class="section-inner">
                <h1>Personal Information</h1>
                <ul class="personal-info">
                    <li>
                        <span>Mobile</span>
                        <span>(+91) {{$data->mobile}}</span>
                    </li>
                    <li>
                        <span>Email ID</span>
                        <span>
                            @if($data->email!=NULL) {{$data->email}}
                            @endif
                            @if($data->is_email_verified!=NULL)
                            <i class='bx bxs-check-circle custom-info-icon1'></i>
                            @else
                            <i class='bx bxs-info-circle custom-info-icon'></i>
                            @endif
                        </span>
                    </li>
                    <li>
                        <span>Address</span>
                        <span>@if($data->address_line1) {{$data->address_line1}}
                        @else - @endif</span>
                    </li>
                    @if($data->register_as == 'individual')
                        <li>
                            <span>Aadhar</span>
                            <span>
                            @if($data->is_aadhaar_verify ==1)
                                <i class='bx bxs-check-circle custom-info-icon1'></i>
                            @else
                                <i class='bx bxs-info-circle custom-info-icon'></i>
                            @endif
                            </span>
                        </li>
                        <li>
                            <span>PAN</span>
                            <span> 
                                @if($data->is_pan_verify==1)
                                    <i class='bx bxs-check-circle custom-info-icon1'></i>
                                @else 
                                    <i class='bx bxs-info-circle custom-info-icon'></i>
                                @endif
                            </span>
                        </li>
                        @elseif($data->company_type == 'Private Limited')
                            <li>
                                <span>PAN</span>
                                <span>
                                    @if($data->is_pan_verify==1)
                                        <i class='bx bxs-check-circle custom-info-icon1'></i>
                                    @else 
                                    <i class='bx bxs-info-circle custom-info-icon'></i>
                                    @endif
                                </span>
                            </li>
                            <li>
                                <span>GST</span>
                                <span>
                                    @if($data->gst_no != '')
                                        <i class='bx bxs-check-circle custom-info-icon1'></i>
                                    @else 
                                        <i class='bx bxs-info-circle custom-info-icon'></i>
                                    @endif
                                </span>
                            </li>
                            <li>
                                <span>CIN</span>
                                <span>
                                    @if($data->cin_no != '')
                                        <i class='bx bxs-check-circle custom-info-icon1'></i>
                                    @else 
                                        <i class='bx bxs-info-circle custom-info-icon'></i>
                                    @endif
                                </span>
                            </li>
                        @else
                        <li>
                                <span>PAN</span>
                                <span>
                                    @if($data->is_pan_verify==1)
                                        <i class='bx bxs-check-circle custom-info-icon1'></i>
                                    @else 
                                    <i class='bx bxs-info-circle custom-info-icon'></i>
                                    @endif
                                </span>
                            </li>
                            <li>
                                <span>GST</span>
                                <span>
                                    @if($data->gst_no != '')
                                        <i class='bx bxs-check-circle custom-info-icon1'></i>
                                    @else 
                                        <i class='bx bxs-info-circle custom-info-icon'></i>
                                    @endif
                                </span>
                            </li>
                           
                        @endif
                        </ul>
                    </section>
                </div>
                <div class="tab-pane fade protect-tab" id="protect" role="tabpanel" aria-labelledby="protect-tab">
            @include('pages.customers.artwork.protect-request')
    </div> 
      <div class="tab-pane fade studio-tab" id="studio" role="tabpanel" aria-labelledby="studio-tab">
        
        @include('pages.customers.artwork.studio')
        
      </div>
<div class="tab-pane fade" id="purchase" role="tabpanel" aria-labelledby="purchase-tab">
    <section class="section-inner">
        ...
    </section>
</div>
</section>
@include('layouts.paginate')
@endsection
@push('scripts')
<script type="text/javascript">
  
  var id =  "{{ request()->route('id')}}";
  
</script>
<!-- <script type="text/javascript" src="{{ asset('js/customer/view.js') }}"></script> -->

<script src="{{ asset('plugins/lodash/lodash.min.js') }}"></script>
<script src="{{ asset('js/customer/protect-request.js') }}"></script>
<script src="{{ asset('js/customer/studio.js') }}"></script>
<script type="text/javascript">
  var url="{{ url('customer/collection') }}/{{ request()->route('id')}}";
  var account="collector";
  var delete_url = "{{ url('customer/collector/delete',$data->id??'') }}";
     $('#delete_save').on('click', function () {
            $.ajax({
                url: delete_url,
                type: "post",
                data: '',
                success: function (response) {
                  window.location.href = "{{ url('customer/collector') }}";
                    toastr.success(response.message);
                },
                error: function (response) {
                }
            });
        });
</script>
 <!-- <script type="text/javascript" src="{{ asset('js/customer/view.js') }}"></script> -->
@endpush