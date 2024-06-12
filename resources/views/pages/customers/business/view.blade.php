@extends('layouts.index')
@section('title', 'Business view')
@section('style')
    @parent
@endsection
@section('content')
    <section class="section main-header py-0">
        <div class="profile-breadcrumb">
            <ul>
                <li>
                    <img src="{{ asset('icons/profile-2user.svg') }} " width="18"/> <a href="{{url('customer')}}">Customers</a>
                </li>
                <li>
                    /
                </li>
                <li>
                    @if(Request()->route()->getPrefix()=='/customer')
                        <a href="{{url('customer')}}">All Customer</a>
                    @else
                        <a href="{{url('customer/business')}}">Business</a>
                    @endif
                </li>
                <li>
                    /
                </li>
                <li>
                    <a href="javascript:void(0)" style=" text-transform: capitalize;">@if(isset($data->full_name))
                            {{$data->full_name}}
                        @endif</a>
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
                        <a href="{{url('customer/business')}}"><i class='bx bx-arrow-back'></i></a>
                    @endif
                </div>
                <div class="d-flex align-items-center justify-content-center">
                    <img class="rounded-circle" style="width:72px;height:72px"
                         src="@if(isset($data->profile_image)) {{$data->profile_image}} @else {{asset('images/No Reultimage.png')}}@endif"
                         alt="{{$data->full_name}}"/>
                </div>
                <div class="d-flex flex-column name-profile">
                    <div class="d-flex flex-row gap-4">
                        <span class="logged-username">{{$data->full_name}}</span>
                        <div class="open-profile" id="pause_verify_open_profile">
                            <button id="pause_verify_profile_status_btn" class="button-all {{$data->status}}"
                                    style=" text-transform: capitalize;">{{$data->status}}</button>
                        </div>
                    </div>
                    <span class="logged-role">Business</span>
                    <div class="d-flex align-items-center">
                        <span class="logged-register">Registered: {{date('d M Y', strtotime($data->created_at))}}</span>
                        <span class="dot mx-2"></span>
                        <span class="logged-register">Last login: @if($data->last_login_at !=NULL)
                                {{date('d M Y', strtotime($data->last_login_at))}}
                            @else
                                N/A
                            @endif</span>
                    </div>
                </div>

            </div>

            <div class="modal fade" id="delete" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered confirmationPopup">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Delete Business</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete the Business?
                            This action cannot be undone.
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn cancel-btn" data-bs-dismiss="modal" id="delete_cancel">
                                Cancel
                            </button>
                            <button type="button" class="btn apply-btn" data-bs-dismiss="modal" id="delete_save">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 pe-3 show-profile-button1 3213">
                <div id="button-section" style="display: none;">
                    @include('pages.customers.verify-header')
                </div>
                @include('pages.customers.popup-modal')

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

            </div>
        </div>
    </section>

    <section class="section main-artist bgcolornew">
        <ul class="nav nav-tabs profile-nav-tabs gap-3" id="myTab" role="tablist">
            <li class="nav-item profile-nav-tabs-list" role="presentation">
                <button class="nav-link profile-nav-tabs-button active px-4" id="home-tab" data-bs-toggle="tab"
                        data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">
                    ACCOUNT
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link profile-nav-tabs-button" id="profile-tab" data-bs-toggle="tab"
                        data-bs-target="#profile" type="button"
                        role="tab" aria-controls="profile" aria-selected="false">PROFILE
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link profile-nav-tabs-button" id="protect-tab" data-bs-toggle="tab"
                        data-bs-target="#protect"
                        type="button" role="tab" aria-controls="protect" aria-selected="false">
                    <div class="hstack">
                        <div>ASIGN PROTECT+</div>
                        <div><span id="total_count"></span></div>
                    </div>
                </button>
            </li>
            <li class="nav-item profile-nav-tabs-list" role="presentation">
                <button class="nav-link profile-nav-tabs-button" id="studio-tab" data-bs-toggle="tab"
                        data-bs-target="#studio" type="button"
                        role="tab" aria-controls="studio" aria-selected="false">
                    <div class="hstack">
                        <div>MY STUDIO</div>
                        <div><span id="total_count1"></span></div>
                    </div>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link profile-nav-tabs-button" id="sales-tab" data-bs-toggle="tab"
                        data-bs-target="#sales" type="button" role="tab" aria-controls="sales" aria-selected="false">
                    SALES
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link profile-nav-tabs-button" id="purchase-tab" data-bs-toggle="tab"
                        data-bs-target="#purchase" type="button" role="tab" aria-controls="purchase"
                        aria-selected="false">PURCHASES
                </button>
            </li>
        </ul>
        <div class="tab-content tab-div-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <section class="section-inner">
                    <h1>Personal Information</h1>
                    <ul class="personal-info">
                        <li>
                            <span>Contact Person</span>
                            <span>{{$data->full_name}}</span>
                        </li>
                        <li>
                            <span>Mobile</span>
                            <span>(+91) {{$data->mobile}}</span>
                        </li>
                        <li>
                            <span>Email ID</span>
                            <span>
              @if($data->email!=NULL)
                                    {{$data->email}}
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
                            <span>@if($data->address_line1)
                                    {{$data->address_line1}}
                                @else
                                    -
                                @endif</span>
                        </li>
                        <li>
                            <span>Website</span>
                            <span>@if($data->website)
                                    <a href="https://{{$data->website}}" target="_blank">{{$data->website}}</a>
                                @else
                                    -
                                @endif</span>
                        </li>

                        <li>
                            <span>GST</span>
                            <span> @if($data->gst_no !=Null)
                                    {{$data->gst_no}}
                                    <i class='bx bxs-check-circle custom-info-icon1'></i>
                                @else
                                    <i class='bx bxs-info-circle custom-info-icon'></i>
                                @endif</span>
                        </li>
                        <li>
                            <span>CIN</span>
                            <span>
              @if($data->cin_no !=Null)
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
                        <li>
                            <span>Contract with Asign</span>
                            <span>
              @if($data->is_accept_terms ==1)
                                    <i class='bx bxs-check-circle custom-info-icon1'></i>
                                @else
                                    <i class='bx bxs-info-circle custom-info-icon'></i>
                                @endif
            </span>
                        </li>
                        <li>
                            <span>Representation Contract</span>
                            <span id="representation_span"> @if($data->is_represent_contract == 1)
                                    <i class='bx bxs-check-circle custom-info-icon1'></i>
                                @else
                                    <i class='bx bxs-info-circle custom-info-icon'></i>
                                @endif
                </span>
                        </li>
                    </ul>
                </section>
            </div>
            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <section class="section-inner">
                    <h1>About</h1>
                    <ul class="personal-info">
                        <li>
                            <span>Display Name</span>
                            <span>{{$data->display_name}}</span>
                        </li>
                        <li>
                            <span>Established Date</span>
                            <span>
                             @if(isset($data->establishment_year))
                                    {{$data->establishment_year}}
                                @else
                                    -
                                @endif</span>
                        </li>
                        <li>
                            <span>City</span>
                            <span>@if($data->current_locations)
                                    {{$data->current_locations}}
                                @else
                                    -
                                @endif</span>
                        </li>
                        <li>
                            <span>Country</span>
                            <span>@if($data->country_name)
                                    {{$data->country_name}}
                                @else
                                    -
                                @endif</span>
                        </li>
                    </ul>
                </section>
                <section class="section-inner">
                    <h1>Biography</h1>
                    <div class="row">
                        <div class="col-md-12">
                            <p class="biography">
                                @if (strlen($data->full_biography) > 500)
                                    {{ substr($data->full_biography, 0, 500) }}
                                    <span class="read-more-show d-none fw-bolder read"><span
                                            style="color: black;">...</span><span role="button"> Read More</span></span>
                                    <span class="read-more-content">
                {{ substr($data->full_biography, 500, strlen($data->full_biography)) }}
                <span class="read-more-hide d-none fw-bolder read"><span role="button"> Read Less </span></span> </span>
                                @else
                                    {{ $data->full_biography }}
                                @endif
                            </p>

                        </div>
                    </div>
                </section>

                <!-- REJECT MODAL SECTION -->
                <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                     aria-hidden="true">
                    <div class="modal-dialog  modal-dialog-centered">
                        <div class="modal-content reject-reason-modal-width">
                            <div class="modal-header">
                                <h5 class="modal-title title-new" id="exampleModalLabel">Reject Reason</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form class="popup-form" id="reject-request-form">
                                    <input type="hidden" id="customer_artist_data">
                                    <div class="mb-3">
                                        <label for="reject_reason_id" class="form-label">Please share the reason for
                                            Representation Rejected</label>
                                        <div class="w100Select reason-select">
                                            <select id="reject_reason_id" name="reject_reason_id"
                                                    data-placeholder="Select Reason"
                                                    class="form-select select2Box" required>
                                                <option value=""></option>
                                                @if(isset($data['representation_rejected_reason']) && count($data['representation_rejected_reason']) > 0)
                                                    @foreach($data['representation_rejected_reason'] as $reason)
                                                        <option
                                                            value="{{$reason['id']}}">{{$reason['name']}}</option>
                                                    @endforeach
                                                @else
                                                    <option value="" disabled>No reasons available</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                  <textarea required name="rejection_message" class="form-control"
                                            id="exampleFormControlTextarea1" rows="4"
                                            placeholder="Add reason here ..." style="resize: none;"></textarea>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer" style="border-top: none;">
                                <button type="button" class="btn cancel-btn mx-2 product-popup-cancel-btn"
                                        data-bs-dismiss="modal">Discard
                                </button>
                                <button type="button" class="btn apply-btn product-popup-save-btn" id="rejection_btn"
                                        data-bs-toggle="tooltip" data-bs-placement="bottom" title=""
                                        onclick="submitRejectForm()">Save
                                </button>
                                <!-- <div id="popoverContent" class="popover-content">This is the popover content.</div> -->
                            </div>
                        </div>
                    </div>
                </div>

                <section class="section-inner-alt">
                    <div class="accordion accordion-artist" id="artist_accordion">
                        <div class="accordion-item" id="artist_accordion_item">
                            <h2 class="accordion-header">Represented Artists</h2>
                            <div class="accordion-item">
                                <div class="accordion-body">
                                    <table>
                                        <thead>
                                        <tr>
                                            <th width="12%">NAME</th>
                                            <th width="12%">CITY</th>
                                            <th>COUNTRY</th>
                                            <th>REPRESENTATION TYPE</th>
                                            <th>CONTRACT</th>
                                            <th width="20%">ASIGN VALIDATION</th>
                                        </tr>
                                        </thead>
                                        <tbody id="artist_accordion_body">
                                        @if(count($data->artists)>0)
                                            @foreach($data->artists as $artist_data)
                                                @if(isset($artist_data->artist))
                                                    <tr>
                                                        <td data-label="Code" style="text-transform: capitalize;">
                                                            @if(isset($artist_data->artist->full_name))
                                                                {{$artist_data->artist->full_name}}
                                                            @endif
                                                        </td>
                                                        <td data-label="Name"> @if(isset($artist_data->artist->city))
                                                                {{$artist_data->artist->city}}
                                                            @endif
                                                        </td>
                                                        <td data-label="Type"> @if(isset($artist_data->artist->country_name))
                                                                {{$artist_data->artist->country_name}}
                                                            @endif
                                                        </td>
                                                        <td data-label="City"
                                                            style="text-transform: capitalize;">@if(isset($artist_data->representation_type))
                                                                {{$artist_data->representation_type}}
                                                            @endif
                                                        </td>
                                                        <td data-label="Contract"> @if(isset($artist_data->is_accept_terms))
                                                                {{ $artist_data->is_accept_terms ? 'signed' : 'unsigned' }}
                                                            @endif
                                                        </td>
                                                        <td class="@if($data->status !== 'unverified' && $data->status !== 'moderation' && $data->status !== 'paused') disabled-representation-row @endif representation-row">
                                                            <div class="btn_Wrapper">
                                                                <button type="button"
                                                                        style="background-color: <?php echo $artist_data->is_verified === 1 ? '#93E088' : ($artist_data->is_verified === NULL ? '#FFFFFF' : '#FFFFFF'); ?>"
                                                                        class="btn btn-primary btn-sm  width-accept"
                                                                        id="accept_btn_{{ $artist_data->customer_artist_id }}"
                                                                        onclick="representationAcceptReject(<?php echo htmlspecialchars(json_encode($artist_data)); ?>,{},'accepted')">
                                                                    Accept
                                                                </button>
                                                                <button type="button"
                                                                        style="background-color: <?php echo $artist_data->is_verified === 0 ? '#FB6F6F' : ($artist_data->is_verified === NULL ? '#FFFFFF' : '#FFFFFF'); ?>"
                                                                        class="btn btn-primary btn-lg  width-reject"
                                                                        id="reject_btn_{{ $artist_data->customer_artist_id }}"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#rejectModal"
                                                                        data-customer_artist_data="<?php echo htmlspecialchars(json_encode($artist_data));?>">
                                                                    Reject
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="4" class="text-center">No Result</td>

                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">Events</h2>
                            <div class="accordion-item">
                                <div class="accordion-body">
                                    <table>
                                        <thead>
                                        <tr>
                                            <th scope="col" width="10%">IMAGES</th>
                                            <th scope="col">EVENT NAME</th>
                                            <th scope="col">VENUE</th>
                                            <th scope="col">CITY</th>
                                            <th width="40%" scope="col">DESCRIPTION</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(count($data->exhibitions)>0)
                                            @foreach($data->exhibitions as $exhibition_data)
                                                <tr>
                                                    <td data-label="Code"><img style="width:24px;height:24px"
                                                                               src="{{$exhibition_data->exhibition->cover_image}}"/>
                                                    </td>
                                                    <td data-label="Name">{{$exhibition_data->exhibition->name}}</td>
                                                    <td data-label="Type">{{$exhibition_data->exhibition->venue}}</td>
                                                    <td data-label="City"> {{$exhibition_data->exhibition->city}}</td>
                                                    <td data-label="City">
                                                    <span data-toggle="tooltip" data-placement="top"
                                                          title="{{$exhibition_data->exhibition-> description}}"> @if (strlen($exhibition_data->exhibition-> description) > 40)
                                                            {{ substr(strip_tags($exhibition_data->exhibition-> description??''), 0, 40) }}
                                                            ...
                                                        @else
                                                            {{$exhibition_data->exhibition-> description??''}}
                                                        @endif
                                                    </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="5" class="text-center">No Result</td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">Publications</h2>
                            <div class="accordion-item">
                                <div class="accordion-body">
                                    <table>
                                        <thead>
                                        <tr>
                                            <th scope="col" width="10%">IMAGES</th>
                                            <th scope="col">Award Names</th>
                                            <th scope="col">Press</th>
                                            <th scope="col">DATE</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(count($data->publications)>0)
                                            @foreach($data->publications as $publication)
                                                <tr>
                                                    <td data-label="Code"><img style="width:24px;height: 24px;"
                                                                               src="{{$publication->image}}"/></td>
                                                    <td data-label="Name">{{$publication->title}}</td>
                                                    <td data-label="Type">{{$publication->author}}</td>
                                                    <td data-label="City">{{date('d M Y', strtotime($publication->date))}}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="4" class="text-center">No Result</td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">Activity</h2>
                            <div class="accordion-item" style="padding: 0px 32px;">
                                <div class="accordion-body">
                                    @include('layouts.customer.collection.activity')
                                </div>
                            </div>
                        </div>
                    </div>
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

        var id = "{{ request()->route('id')}}";

    </script>
    <!-- <script type="text/javascript" src="{{ asset('js/customer/view.js') }}"></script> -->

    <script src="{{ asset('plugins/lodash/lodash.min.js') }}"></script>
    <script src="{{ asset('js/customer/protect-request.js') }}"></script>
    <script src="{{ asset('js/customer/studio.js') }}"></script>
    <script type="text/javascript">
        $(".select2Box").select2({
            placeholder: "Select Reject Reason",
            minimumResultsForSearch: Infinity,
        });
        $(".select2Box").on('change', function (e) {
            toggleButtonState();
        });

        $(document).on('click', '#myTab', function (e) {
            e.preventDefault();
            var activeTab = $(this).closest('section').find('ul').find('li .active').html();
            activeTab = activeTab.trim();
            if (activeTab === 'ACCOUNT')
                $('.showMenu').show()
            else
                $('.showMenu').hide();

            if (activeTab === 'PROFILE') {
                $('#button-section').show();
                $('.filter-setup ').hide();
            } else {
                $('#button-section').hide();
                $('.filter-setup ').show();
            }
        });

        $('#rejectModal').on('show.bs.modal', function (event) {
            $('#reject-request-form').trigger('reset');
            resetSelect2();
            toggleButtonState();
            var button = $(event.relatedTarget);
            var customer_artist_data = button.data('customer_artist_data');
            var modal = $(this);
            modal.find('#customer_artist_data').val(JSON.stringify(customer_artist_data));
        });
        var url = "{{ url('customer/collection') }}/{{ request()->route('id')}}";
        var account = "business";
        var delete_url = "{{ url('customer/business/delete',$data->id??'') }}";
        $('#delete_save').on('click', function () {
            $.ajax({
                url: delete_url,
                type: "post",
                data: '',
                success: function (response) {
                    window.location.href = "{{ url('customer/business') }}";
                    toastr.success(response.message);
                },
                error: function (response) {
                }
            });
        });

        // Representation customer artist Accept/Reject Functionallity
        function toggleButtonState() {
            var isValid = isFormValid(); // Implement this function to check form validity
            $('#rejection_btn').prop('disabled', !isValid);
            if (isValid) {
                $('#rejection_btn').addClass('btn-colorSave')
                $('#rejection_btn').removeClass('btn-colorDisable');
            } else {
                $('#rejection_btn').addClass('btn-colorDisable');
            }
        }

        var myForm = document.getElementById('reject-request-form');
        myForm.addEventListener('input', handleFormChange);
        myForm.addEventListener('change', handleFormChange);

        // Call toggleButtonState on form input change
        function handleFormChange(event) {
            toggleButtonState();
        }

        // Function to check form validity
        function isFormValid() {
            var isValid = true;
            $('#reject-request-form input:not([type="hidden"]), #reject-request-form select, #reject-request-form textarea').each(function () {
                // Additional check for select element value
                if ($(this).is('select')) {
                    var selectValue = $(this).val();
                    // If select option is '3' (others), make sure the textarea is not empty
                    if (selectValue === '' || selectValue === '3') {
                        if (selectValue === '') {
                            isValid = false;
                            return false;
                        } else {
                            var $textarea = $(this).closest('form').find('textarea[name="rejection_message"]');
                            if ($textarea.val().trim() === '') {
                                isValid = false;
                                return false;
                            }
                        }
                    } else {
                        isValid = true;
                    }
                }
            });
            return isValid;
        }


        // Change button background color based on form validity
        toggleButtonState();

        // Show popover message on hover
        $('#rejection_btn').on('mouseenter', function () {
            if ($(this).prop('disabled')) {
                var popoverText = "";
                var customMessages = {
                    'reject_reason_id': 'Please select reject reason to enable save',
                    'rejection_message': 'Please add a reason to enable save',
                };
                $('#reject-request-form input:not([type="hidden"]), #reject-request-form select, #reject-request-form textarea').each(function () {
                    if ($(this).val().trim() === '') {
                        var fieldName = $(this).attr('name');
                        popoverText = customMessages[fieldName] || ("Please fill in the " + fieldName + " field to enable save");
                        return false; // Exit the loop
                    }
                });
                // $('#popoverContent').text(popoverText);
                // $('#popoverContent').css('display', 'block');
                // $('#popoverContent').css('opacity', 1);
                $(this).attr('data-bs-original-title', popoverText);
                $(this).tooltip('show');
            } else {
                $(this).attr('data-bs-original-title', '');
                $(this).tooltip('hide');
            }
        });

        $('#rejection_btn').on('mouseleave', function () {
            if ($(this).prop('disabled')) {
                // $('#popoverContent').css('display', 'none');
                // $('#popoverContent').css('transition', 'opacity 0.3s ease');
                $(this).attr('data-bs-original-title', '');
                $(this).tooltip('hide');
            }
        });

        $('#pause_profile_btn').on('mouseenter', function () {
            if ($(this).prop('disabled')) {
                $('#pausePopoverContent').css('display', 'block');
                $('#pausePopoverContent').css('opacity', 1);
            }
        });

        $('#pause_profile_btn').on('mouseleave', function () {
            if ($(this).prop('disabled')) {
                $('#pausePopoverContent').css('display', 'none');
                $('#pausePopoverContent').css('transition', 'opacity 0.3s ease');
            }
        });


        function submitRejectForm() {
            // Retrieve customer_artist_id from the hidden input field
            var customer_artist_data = document.getElementById('customer_artist_data').value;
            // console.log("customer full data",JSON.parse(customer_artist_data))

            // Serialize form data
            var formData = $('#reject-request-form').serializeArray();

            // Create an object to hold the data
            var requestData = {};
            $.each(formData, function (index, field) {
                requestData[field.name] = field.value;
            });

            // Determine the rejection type
            var type = 'rejected';

            // Call representationAcceptReject function with customer_artist_id, form data, and rejection type
            representationAcceptReject(JSON.parse(customer_artist_data), requestData, type);
        }

        function representationAcceptReject(data, formData, type) {
            // console.log("=====is_verified",data.is_verified);
            let customer_artist_id = data.customer_artist_id
            formData.customer_id = data.customer_id;
            formData.user_id = <?php echo auth()->user()->id; ?>;
            formData.representation_id = customer_artist_id;
            formData.representation_type = 'artist';
            if (type === 'rejected') {
                var rejectReasonText = $('#reject_reason_id option:selected').text();
                formData.reject_reason_text = rejectReasonText;
            }
            if (type === 'accepted' && data.is_verified === 1) {
                formData.type = 'default';
                type = 'default';
            } else {
                formData.type = type;
            }

            // Construct the update_url
            var update_url = "{{ url('customer/artist/update') }}" + "/" + customer_artist_id;

            $.ajax({
                url: update_url,
                type: "post",
                data: formData,
                success: function (response) {
                    // Handle success response
                    toastr.success(response.message);
                    $('#reject-request-form').trigger('reset');
                    $('#rejectModal').modal('hide');
                    if (type === 'accepted') {
                        $('#accept_btn_' + customer_artist_id).css('background-color', '#93E088');
                        $('#reject_btn_' + customer_artist_id).css('background-color', '#FFFFFF');
                    } else if (type === 'rejected') {
                        $('#reject_btn_' + customer_artist_id).css('background-color', '#FB6F6F ');
                        $('#accept_btn_' + customer_artist_id).css('background-color', '#FFFFFF');
                    } else {
                        $('#accept_btn_' + customer_artist_id).css('background-color', '#FFFFFF');
                    }
                    artistRepresentationTable(response['data']['customerArtists'], response['data']['customerStatus']);

                    var status_res = response.data.customerStatus;
                    $('#pause_verify_profile_status_btn').removeClass()
                        .addClass('button-all ' + status_res)
                        .text(status_res);

                    if(response.data.customer.is_represent_contract != 1) {
                        $('#representation_span').html("<i class='bx bxs-info-circle custom-info-icon'></i>");
                    } else {
                        $('#representation_span').html("<i class='bx bxs-check-circle custom-info-icon1'></i>");
                    }
                    $('#button-section').html(response.data.header);

                    refreshActivityLog(response['data']['activityLogs']);
                },
                error: function (response) {
                    // Handle error response
                    console.log("error response", response);
                }
            });
        }

        // Function to refresh the activity log section
        function refreshActivityLog(activityLogs) {
            var activityLogSection = $('#customer_activity_log_section');
            activityLogSection.empty(); // Clear existing activity logs

            activityLogs.forEach(function (log) {
                var fn = log.tag === 'customer' ? log.customer.full_name : log.user.name;
                var words = fn.split(" ");
                var acronym = "";
                words.forEach(function (w) {
                    acronym += w[0];
                });
                var name = acronym.length >= 2 ? acronym[0] + acronym[1] : acronym[0];
                var createdAtDate = new Date(log.created_at);
                var formattedDate = createdAtDate.toLocaleDateString('en-US', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true
                });
                // Create DOM elements for each activity log
                var activityDiv = $('<div class="d-flex gap-3 mb-3"></div>');
                var profileDiv = $('<div class="profile-div orange"></div>');
                var profileAvatar = $('<span class="profile-avatar">' + name + '</span>');
                var profileInfo = $('<div class="d-flex align-items-center gap-1 profile-in"></div>');
                var activityName;
                var includesRejected = log.message.includes('Rejected Representation');
                if (includesRejected) {
                    var reason = log.message.split('Reason: ')[1] || '';
                    var truncatedReason = reason.length > 40 ? reason.substring(0, 40) + '...' : reason;
                    activityName = $('<div class="vstack gap-1" style="display: flex; justify-content: center;">' +
                        '<div><span class="activity-name">' + fn + '</span>' +
                        '<span class="activity-profile">Rejected Representation</span></div>' +
                        '<div data-bs-toggle="tooltip" data-bs-placement="top" title="' + reason + '">' + truncatedReason + '</div>' +
                        '</div>');
                } else {
                    activityName = $('<span class="activity-name">' + fn + '</span> <span class="activity-profile">' + log.message + '</span>');
                }
                var dot = $('<span class="dot mx-2 dot-activity"></span>');
                var activityDate = $('<span class="activity-date">' + formattedDate + '</span>');

                // Append DOM elements to activity log section
                profileDiv.append(profileAvatar);
                profileInfo.append(activityName, dot, activityDate);
                activityDiv.append(profileDiv, profileInfo);
                activityLogSection.append(activityDiv);
                $('[data-bs-toggle="tooltip"]').tooltip();
            });
        }



        function artistRepresentationTable(customerArtists, customer_status) {
            var tbody = $('#artist_accordion_body');
            // Clear existing table rows
            tbody.empty();


            // Check if there are any artists
            if (customerArtists.length > 0) {
                // Iterate over each artist and create table rows
                customerArtists.forEach(function (artistData) {
                    var artist = artistData.artist;
                    // Create table row HTML

                    var rowHtml = '<tr>' +
                        '<td data-label="Code">' + (artist && artist.full_name ? artist.full_name : '') + '</td>' +
                        '<td data-label="Name">' + (artist && artist.city ? artist.city : '') + '</td>' +
                        '<td data-label="Type">' + (artist && artist.country_name ? artist.country_name : '') + '</td>' +
                        '<td data-label="City">' + (artistData && artistData.representation_type ? artistData.representation_type : '') + '</td>' +
                        '<td data-label="Contract">' + (artistData.is_accept_terms == '1' ? 'signed' : 'unsigned') + '</td>' +
                        '<td class="representation-row' + (customer_status !== 'unverified' && customer_status !== 'paused' && customer_status !== 'moderation' ? ' disabled-representation-row' : '') + '">' +
                        '<div class="btn_Wrapper">' +
                        '<button type="button" style="background-color: ' + (artistData.is_verified === 1 ? '#93E088' : (artistData.is_verified === null ? '#FFFFFF' : '#FFFFFF')) + '" class="btn btn-primary btn-sm width-accept dynamic_btn_accept" id="accept_btn_' + artistData.customer_artist_id + '" onclick="representationAcceptReject(' + JSON.stringify(artistData).replace(/"/g, '&quot;') + ', {}, \'accepted\')">Accept</button>' +
                        '<button type="button" style="background-color: ' + (artistData.is_verified === 0 ? '#FB6F6F' : (artistData.is_verified === null ? '#FFFFFF' : '#FFFFFF')) + '" class="btn btn-primary btn-lg width-reject" id="reject_btn_' + artistData.customer_artist_id + '" data-bs-toggle="modal" data-bs-target="#rejectModal" data-customer_artist_data="' + JSON.stringify(artistData).replace(/"/g, '&quot;') + '">Reject</button>' +
                        '</div>' +
                        '</td>' +
                        '</tr>';
                    // Append the row to the table body
                    tbody.append(rowHtml);
                });
            } else {
                // If there are no artists, show a message in a single row
                var noResultRow = '<tr><td colspan="5" class="text-center">No Result</td></tr>';
                tbody.append(noResultRow);
            }

        }


        function getStatusClass(status) {
            switch (status) {
                case 'verified':
                    return 'verified';
                case 'unverified':
                    return 'unverified';
                case 'paused':
                    return 'paused';
                default:
                    return 'moderation';
            }
        }

        function resetSelect2() {
            // Destroy the existing select2 instance
            $(".select2Box").select2('destroy');
            // Reinitialize select2 with new options
            $(".select2Box").select2({
                placeholder: "Select Reject Reason",
                minimumResultsForSearch: Infinity
            });
        }


        $(document).on('click', '.paused-status', function (e) {
            e.preventDefault();

            var title = "Pause Profile";
            var body = "Are you sure you want to pause this profile?";

            if ($(this).attr('id') === 'rejectReview') {
                title = "Reject Review";
                body = "Rejecting this Review will keep the Profile Paused and notify the Customer. <br /><br /><br />  Are you sure you want to keep it Paused?";
            }
            $('#statusModal .modal-title').text(title);
            $('#statusModal .modal-body').html(body);
            $('#statusModal').modal('toggle');

        });

        $(document).on('click', '.change-status', function () {

            var _this = $(this);
            var text = _this.text();

            _this.prop('disabled', true).text('Loading...');
            $.ajax({
                url: baseUrl + "/customer/" + id,
                type: "PATCH",
                data: {
                    status: _this.attr('data-value')
                },
                success: function (response) {
                    var status_res = response.data.status;
                    $('#pause_verify_profile_status_btn').removeClass()
                        .addClass('button-all ' + status_res)
                        .text(status_res);
                    $('#button-section').html(response.data.header);
                    $('#statusModal').modal('hide');
                    refreshActivityLog(response['data']['activityLogs']);
                    if(status_res !== 'unverified' && status_res !== 'paused' && status_res !== 'moderation') {
                        $('.representation-row').addClass('disabled-representation-row');
                    } else {
                        $('.representation-row').removeClass('disabled-representation-row');
                    }
                },
                error: function (xhr) {
                    showErrorMessage(xhr);
                },
                complete: function (response) {
                    _this.attr('disabled', false).text(text);
                }
            });
        });


    </script>

@endpush
