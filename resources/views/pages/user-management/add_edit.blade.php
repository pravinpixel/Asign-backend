@extends('layouts.index')
@section('title', 'User Management Create')
@section('style')
    <style type="text/css">
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type="number"] {
            -moz-appearance: textfield;
        }
    </style>
    @parent
@endsection
@section('content')
    <div class="pages user-management">
        <section class="alt-header filterHeaderCtr">
            <section class="section main-header p-0">
                <div class="section-breadcrumb">
                    <ul>
                        <li>
                            <img src="{{ asset('icons/profile.png') }}" width="20" height="20"/>
                            @if(isset($user))
                                <a href="#">Master / </a> <a href="{{url('user-management')}}">Users / </a> <a
                                    href="{{url('user-management/edit',$user->id)}}"> Edit User</a>
                            @else
                                <a href="">Master / </a> <a href="">Users / </a> <a
                                    href="{{url('user-management/add_edit')}}">
                                    Add User</a>
                            @endif
                        </li>
                    </ul>
                </div>
            </section>
        </section>
        <section class="addsectionCtr">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="hstack gap-3">
                        <div>
                            <img src="{{ asset('icons/arrow-left.png') }}" width="24" height="24" class="cP"
                                 onclick="window.location='{{url('user-management')}}'"/>
                        </div>
                        @if(isset($user))
                            <div class="addhead">Edit User</div>
                        @else
                            <div class="addhead">Add New User</div>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="hstack gap-3 float-end">
                        <div>
                            <button type="button" class="btn cancel-btn" data-bs-toggle="modal"
                                    data-bs-target="#discardModal" id="dynamic-discard">Discard
                            </button>
                        </div>
                        <div>
                            <button type="button" class="btn apply-btn" id="dynamic-submit">Save</button>
                        </div>
                        <div class="filter-setup ">
                            <div class="dropbar-bar">
                                @if(isset($user))
                                <i class='bx bx-dots-vertical-rounded fs-4 dropdown-toggle' data-bs-toggle="dropdown"
                                   aria-expanded="false"></i>
                                   @endif
                                <ul class="dropdown-menu">
                                    @if(isset($user))

                                        <li id="delete_user"
                                            @if(access()->hasAccess('user.delete')) data-bs-toggle="modal"
                                            data-bs-target="#delete" @else
                                                data-toggle="tooltip" data-placement="bottom" title="You don’t have Delete
									permission"
                                            @endif><a class="dropdown-item">Delete</a></li>

                                    @else
                                        <li><a class="dropdown-item">Export</a></li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="alt-section mb-5">
            <form class="formFieldInput" id="dynamic-form" autocomplete="off">
                <input type="hidden" name="id" id="id" value="{{$user->id ??''}}">
                <input type="hidden" name="role" id="role" value="{{$user->role_id ??''}}">
                <input type="hidden" name="title" id="title" value="">
                <div class="sectionInnerCtr pe-5">
                    <div class="addhead pb-24">Basic Information</div>
                    <div class="row gx-5 gy-3">
                        <div class="col-md-6 pe-5">
                            <div class="form-group">
                                <label class="form-label">User Name</label>
                                <input type="text" class="form-control" placeholder="Enter User Name" name="user_name"
                                       value="{{$user->name?? ''}}">
                                <span class="field-error" id="user_name-error"></span>
                            </div>
                        </div>
                        <div class="col-md-6 ps-5">
                            <div class="form-group">
                                <label class="form-label">User Role</label>
                                <div class="w100Select">
                                    <select class="select2Box" name="role_id" id="role_id">
                                        <option selected value="">Choose Role</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" {{ isset($user) && $role->id == old('role_id',
										$user->role_id) ? 'selected' : '' }}>{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="field-error" id="role_id-error"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 pe-5">
                            <div class="form-group">
                                <label class="form-label">Mobile Number</label>
                                <input type="number" class="form-control" placeholder="Enter Mobile Number"
                                       name="mobile_number" value="{{$user->mobile_number ?? ''}}" maxlength="10"
                                       pattern="[0-9]*">
                                <span class="field-error" id="mobile_number-error"></span>
                            </div>
                        </div>
                        <div class="col-md-6 ps-5">
                            <div class="form-group">
                                <label class="form-label" for="email">Email ID</label>
                                <input id="email" type="email" class="form-control" placeholder="Enter Email ID"
                                       name="email"
                                       value="{{$user->email ?? ''}}">
                                <span class="field-error" id="email-error"></span>
                            </div>
                        </div>
                        <div class="col-md-6 pe-5">
                            <div class="form-group">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" placeholder="Enter Password" name="password"
                                value="{{ isset($user->hash_password) ? Crypt::decryptString($user->hash_password) : '' }}">
                                <input type="hidden" class="form-control" placeholder="Enter Password" name="old_password"
                                value="{{ isset($user->hash_password) ? Crypt::decryptString($user->hash_password) : '' }}">
                                <span class="field-error" id="password-error"></span>
                            </div>
                        </div>
                        <div class="col-md-6 ps-5">
                            <div class="form-group">
                                <label class="form-label">Re-type Password</label>
                                <input type="password" class="form-control" placeholder="Re-type Password To Confirm"
                                       name="retype_password">
                                <span class="field-error" id="retype_password-error"></span>
                            </div>
                        </div>
                        <div class="col-md-6 pe-5">
                            <div class="form-group">
                                <label class="form-label">Branch Office</label>
                                <div class="w100Select">
                                    <select class="select2Box1" name="branch_office_id" id="branch_office_id">
                                        <option selected value="">Choose Branch Office</option>
                                        @foreach($locations as $location)
                                            <option value="{{ $location->id }}" {{ isset($user) && $location->id ==
										old('branch_office_id', $user->branch_office_id) ? 'selected' : '' }}>{{
										$location->location }}</option>
                                        @endforeach
                                    </select>
                                    <span class="field-error" id="branch_office_id-error"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 ps-5"></div>
                    </div>

                </div>
                <!-- Permissions  -->
                <div class="sectionInnerCtr sectionInnerCtr-1 pe-5">
                    <div class="addhead pb-24">Permissions</div>
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <div class="addhead-1">City Access</div>
                        </div>
                        @php
                            if(isset($user)){
                            $selected = explode(',', $user->city_access);
                            }
                        @endphp
                        <div class="col-md-8">
                            <div class="hstack gap-4 flex-wrap">

                                <div class="redes-checkbox redes-checkbox-flex">
                                    <div class="hstack gap-2">
                                        <input class="form-check-input  all-check" type="checkbox"
                                               id="flexCheckIndeterminate1" @if(isset($user) && $user->city_access !=NUll &&
									count($selected) == count($cities)) checked @endif>
                                        <label class="form-check-label" for="flexCheckDefault">
                                            All
                                        </label>
                                    </div>
                                </div>

                                @foreach($cities as $city)
                                    <div class="redes-checkbox redes-checkbox-flex">
                                        <div class="hstack gap-2">
                                            <input class="form-check-input city-new" type="checkbox"
                                                   name="city_access[]"
                                                   value="{{$city->id}}" id="flexCheckIndeterminate1" @if(isset($user) &&
										$user->city_access !=NUll && in_array($city->id, $selected)) checked @endif>
                                            <label class="form-check-label" for="flexCheckDefault">
                                                {{$city->name}}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
                <!-- customer list  -->
                <div class="sectionInnerCtr sectionInnerCtr-1 pe-5">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <div class="addhead-1">Customer List</div>
                        </div>
                        <div class="col-md-8">
                            <div class="hstack gap-4">
                                <div class="redes-checkbox">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                           value="customer.view" id="flexCheckIndeterminate2">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        View
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Assign protect+ stages  -->
                <div class="sectionInnerCtr sectionInnerCtr-1 pe-5 all_asign">
                    <div class="row addhead-1 pb-24">
                        <div class="col-md-4">
                            Asign Protect+ Stages
                        </div>
                        <div class="col-md-8">
                            <div class="redes-checkbox">
                                <input class="form-check-input module_asign" type="checkbox" value="module_asign"
                                       id="module_asign" onclick="groupcheck('module_asign','all_asign','module_asign')"
                                       @if(isset($user) && access()->checkUser($user->id,'Asign Protect+')==6) checked @endif
                                >
                                <label class="form-check-label" for="flexCheckDefault">All
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row align-items-center mb-3">
                        <div class="col-md-4">
                            <div class="addhead-2">1. Authentication Requests</div>
                        </div>
                        <div class="col-md-8">
                            <div class="hstack gap-4">
                                <div class="redes-checkbox">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                           value="authentication-request.edit" id="flexCheckIndeterminate2">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Edit
                                    </label>
                                </div>
                                <div class="redes-checkbox">
                                    <input class="form-check-input asign_request_view" type="checkbox"
                                           name="permissions[]"
                                           id="flexCheckIndeterminate2" value="authentication-request.view">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        View
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row align-items-center mb-3">
                        <div class="col-md-4">
                            <div class="addhead-2">2. Inspection Requests</div>
                        </div>
                        <div class="col-md-8">
                            <div class="hstack gap-4">
                                <div class="redes-checkbox">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                           id="flexCheckIndeterminate2" value="inspection-request.edit">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Edit
                                    </label>
                                </div>
                                <div class="redes-checkbox">
                                    <input class="form-check-input inspection_requests_view" type="checkbox"
                                           name="permissions[]" id="flexCheckIndeterminate2"
                                           value="inspection-request.view">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        View
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <div class="addhead-2">3. Label Requests</div>
                        </div>
                        <div class="col-md-8">
                            <div class="hstack gap-4">
                                <div class="redes-checkbox">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                           id="flexCheckIndeterminate2" value="label-requests.edit">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Edit
                                    </label>
                                </div>
                                <div class="redes-checkbox">
                                    <input class="form-check-input label_requests_view" type="checkbox"
                                           name="permissions[]"
                                           id="flexCheckIndeterminate2" value="label-requests.view">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        View
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- stock  -->
                <div class="sectionInnerCtr sectionInnerCtr-1 pe-5 all_stock">
                    <div class="row addhead-1 pb-24">
                        <div class="col-md-4">
                            Stock
                        </div>
                        <div class="col-md-8">
                            <div class="redes-checkbox">
                                <input class="form-check-input module_stock" type="checkbox" value="module_stock"
                                       id="module_stock" onclick="groupcheck('module_stock','all_stock','module_stock')"
                                       @if(isset($user) && access()->checkUser($user->id,'stock')==17) checked @endif
                                >
                                <label class="form-check-label" for="flexCheckDefault">All
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row align-items-center mb-3">
                        <div class="col-md-4">
                            <div class="addhead-2">1. Stock Overview</div>
                        </div>
                        <div class="col-md-8">
                            <div class="hstack gap-4">
                                <div class="redes-checkbox">
                                    <input class="form-check-input module_stock" type="checkbox" name="permissions[]"
                                           id="flexCheckIndeterminate2" value="stock-overview.create" disabled>
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Create
                                    </label>
                                </div>
                                <div class="redes-checkbox">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                           id="flexCheckIndeterminate2" value="stock-overview.edit">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Edit
                                    </label>
                                </div>
                                <div class="redes-checkbox">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                           id="flexCheckIndeterminate2" value="stock-overview.view">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        View
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row align-items-center mb-3">
                        <div class="col-md-4">
                            <div class="addhead-2">2. Purchase Orders</div>
                        </div>
                        <div class="col-md-8">
                            <div class="hstack gap-4">
                                <div class="redes-checkbox">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                           id="flexCheckIndeterminate2" value="purchase-order.create">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Create
                                    </label>
                                </div>
                                <div class="redes-checkbox">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                           id="flexCheckIndeterminate2" value="purchase-order.edit">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Edit
                                    </label>
                                </div>
                                <div class="redes-checkbox">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                           value="purchase-order.view" id="flexCheckIndeterminate2">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        View
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row align-items-center mb-3">
                        <div class="col-md-4">
                            <div class="addhead-2">3. Stock Transfer Orders</div>
                        </div>
                        <div class="col-md-8">
                            <div class="hstack gap-4">
                                <div class="redes-checkbox">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                           id="flexCheckIndeterminate2" value="stock-transfer-order.create">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Create
                                    </label>
                                </div>
                                <div class="redes-checkbox">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                           value="stock-transfer-order.edit" id="flexCheckIndeterminate2">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Edit
                                    </label>
                                </div>
                                <div class="redes-checkbox">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                           value="stock-transfer-order.view" id="flexCheckIndeterminate2">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        View
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row align-items-center mb-3">
                        <div class="col-md-4">
                            <div class="addhead-2">4. Goods Received Note</div>
                        </div>
                        <div class="col-md-8">
                            <div class="hstack gap-4">
                                <div class="redes-checkbox">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                           id="flexCheckIndeterminate2" value="goods-received-note.create">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Create
                                    </label>
                                </div>
                                <div class="redes-checkbox">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                           id="flexCheckIndeterminate2" value="goods-received-note.edit">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Edit
                                    </label>
                                </div>
                                <div class="redes-checkbox">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                           id="flexCheckIndeterminate2" value="goods-received-note.view">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        View
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row align-items-center mb-3">
                        <div class="col-md-4">
                            <div class="addhead-2">5. Damages</div>
                        </div>
                        <div class="col-md-8">
                            <div class="hstack gap-4">
                                <div class="redes-checkbox">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                           value="damages.create" id="flexCheckIndeterminate2">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Create
                                    </label>
                                </div>
                                <div class="redes-checkbox">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                           value="damages.edit" id="flexCheckIndeterminate2">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Edit
                                    </label>
                                </div>
                                <div class="redes-checkbox">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                           value="damages.view" id="flexCheckIndeterminate2">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        View
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <div class="addhead-2">6. Stock Check</div>
                        </div>
                        <div class="col-md-8">
                            <div class="hstack gap-4">
                                <div class="redes-checkbox">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                           value="stock-check.create" id="flexCheckIndeterminate2">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Create
                                    </label>
                                </div>
                                <div class="redes-checkbox">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                           value="stock-check.edit" id="flexCheckIndeterminate2">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Edit
                                    </label>
                                </div>
                                <div class="redes-checkbox">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                           value="stock-check.view" id="flexCheckIndeterminate2">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        View
                                    </label>
                                </div>
                                <div class="redes-checkbox">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                           value="stock-check.control" id="flexCheckIndeterminate2">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Control
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Labels  -->
                <div class="sectionInnerCtr sectionInnerCtr-1 pe-5 all_label">
                    <div class="row addhead-1 pb-24">
                        <div class="col-md-4">
                            Labels
                        </div>
                        <div class="col-md-8">
                            <div class="redes-checkbox">
                                <input class="form-check-input module_label" type="checkbox" value="module_label"
                                       id="module_label" onclick="groupcheck('module_label','all_label','module_label')"
                                       @if(isset($user) && access()->checkUser($user->id,'label')==9) checked @endif
                                >
                                <label class="form-check-label" for="flexCheckDefault">All
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row align-items-center mb-3">
                        <div class="col-md-4">
                            <div class="addhead-2">1. Label Request</div>
                        </div>
                        <div class="col-md-8">
                            <div class="hstack gap-4">
                                <div class="redes-checkbox">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                           value="label-request.create" id="flexCheckIndeterminate2">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Create
                                    </label>
                                </div>
                                <div class="redes-checkbox">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                           value="label-request.edit" id="flexCheckIndeterminate2">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Edit
                                    </label>
                                </div>
                                <div class="redes-checkbox">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                           value="label-request.view" id="flexCheckIndeterminate2">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        View
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row align-items-center mb-3">
                        <div class="col-md-4">
                            <div class="addhead-2">2. Label Issue</div>
                        </div>
                        <div class="col-md-8">
                            <div class="hstack gap-4">
                                <div class="redes-checkbox">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                           value="label-issue.create" id="flexCheckIndeterminate2">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Create
                                    </label>
                                </div>
                                <div class="redes-checkbox">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                           value="label-issue.edit" id="flexCheckIndeterminate2">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Edit
                                    </label>
                                </div>
                                <div class="redes-checkbox">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                           value="label-issue.view" id="flexCheckIndeterminate2">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        View
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <div class="addhead-2">3. Label Return</div>
                        </div>
                        <div class="col-md-8">
                            <div class="hstack gap-4">
                                <div class="redes-checkbox">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                           value="label-return.create" id="flexCheckIndeterminate2">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Create
                                    </label>
                                </div>
                                <div class="redes-checkbox">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                           value="label-return.edit" id="flexCheckIndeterminate2">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Edit
                                    </label>
                                </div>
                                <div class="redes-checkbox">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                           value="label-return.view" id="flexCheckIndeterminate2">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        View
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- masters  -->
                <div class="sectionInnerCtr sectionInnerCtr-1 pe-5 all_master">
                    <div class="row align-items-center">
                        <div class="col-md-1 addhead-1">Masters
                        </div>
                        <div class="col-md-3">
                            <div class="redes-checkbox" style="padding-inline-start: 12px;">
                                <input class="form-check-input module_master" type="checkbox" value="module_master"
                                       id="module_master"
                                       onclick="groupcheck('module_master','all_master','module_master')"
                                       @if(isset($user) && access()->checkUser($user->id,'master')==4) checked @endif
                                >
                                <label class="form-check-label" for="flexCheckDefault">All
                                </label>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="hstack gap-4">
                                <div class="redes-checkbox">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                           value="master.create" id="flexCheckIndeterminate2">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Create
                                    </label>
                                </div>
                                <div class="redes-checkbox">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                           value="master.edit"
                                           id="flexCheckIndeterminate2">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Edit
                                    </label>
                                </div>
                                <div class="redes-checkbox">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                           value="master.view"
                                           id="flexCheckIndeterminate2">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        View
                                    </label>
                                </div>
                                <div class="redes-checkbox">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                           value="master.delete" id="flexCheckIndeterminate2">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Delete
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- users  -->
                <div class="sectionInnerCtr sectionInnerCtr-1 pe-5 all_user">
                    <div class="row align-items-center">
                        <div class="col-md-1 addhead-1">Users
                        </div>
                        <div class="col-md-3">
                            <div class="redes-checkbox">
                                <input class="form-check-input module_user" type="checkbox" value="module_user"
                                       id="module_user" onclick="groupcheck('module_user','all_user','module_user')"
                                       @if(isset($user) && access()->checkUser($user->id,'user')==4) checked @endif
                                >
                                <label class="form-check-label" for="flexCheckDefault">All
                                </label>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="hstack gap-4">
                                <div class="redes-checkbox">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                           value="user.create"
                                           id="flexCheckIndeterminate2">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Add
                                    </label>
                                </div>
                                <div class="redes-checkbox">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                           value="user.edit"
                                           id="flexCheckIndeterminate2">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Edit
                                    </label>
                                </div>
                                <div class="redes-checkbox">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                           value="user.view"
                                           id="flexCheckIndeterminate2">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        View
                                    </label>
                                </div>
                                <div class="redes-checkbox">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                           value="user.delete"
                                           id="flexCheckIndeterminate2">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Delete
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Roles -->
                <div class="sectionInnerCtr sectionInnerCtr-1 pe-5 border-bottom-0 all_role">
                    <div class="row align-items-center">
                        <div class="col-md-1 addhead-1">Roles
                        </div>
                        <div class="col-md-3">
                            <div class="redes-checkbox">
                                <input class="form-check-input module_role" type="checkbox" value="module_role"
                                       id="module_role" onclick="groupcheck('module_role','all_role','module_role')"
                                       @if(isset($user) && access()->checkUser($user->id,'role')==3) checked @endif
                                >
                                <label class="form-check-label" for="flexCheckDefault">All
                                </label>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="hstack gap-4">
                                <div class="redes-checkbox">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                           value="role.create"
                                           id="flexCheckIndeterminate2">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Add
                                    </label>
                                </div>
                                <div class="redes-checkbox">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                           value="role.edit"
                                           id="flexCheckIndeterminate2">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Edit
                                    </label>
                                </div>
                                <div class="redes-checkbox">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                           value="role.view"
                                           id="flexCheckIndeterminate2">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        View
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </div>

    <div class="modal fade" id="discardModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered confirmationPopup">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Discard User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to discard changes made to this?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn cancel-btn" data-bs-dismiss="modal" id="conform_exit">Exit</button>
                    <button type="button" class="btn apply-btn" data-bs-dismiss="modal" id="exit_save">Save</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="conform" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered confirmationPopup">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to create a new user?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn cancel-btn" data-bs-dismiss="modal" id="conform_cancel">Cancel
                    </button>
                    <button type="button" class="btn apply-btn" data-bs-dismiss="modal" id="conform_save">Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="delete" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered confirmationPopup">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete the User?
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
@endsection
@push('scripts')
    <script type="text/javascript">
        $(".select2Box").select2({
            placeholder: "Select User Role",
            minimumResultsForSearch: Infinity,
            dropdownCssClass: "custom-dropdown",
        });
        $(".select2Box1").select2({
            placeholder: "Select Branch Office",
            minimumResultsForSearch: Infinity,
        });

        function isEmpty(value) {
            return value === null || value === undefined || value === '';
        }

        $(document).ready(function () {
            var route = "{{request()->route()->getName()}}";
            permission($('#role').val(), $('#id').val());
            var $form = $('#dynamic-form');
            $('#dynamic-submit').prop('disabled', true);
            $('#dynamic-discard').prop('disabled', true);
            $form.on('change keyup paste', function (e) {
                let allValues = $(this).serializeArray();
                let formType = $("#id").val();
                if (formType) {
                    $('#dynamic-submit').prop('disabled', true);
                    $('#dynamic-discard').prop('disabled', true);
                }


                var isAnyValueEmpty = allValues.some(function (obj) {
                    for (var key in obj) {
                        if (route === "user-management.edit") {
                            if (obj["name"] !== "id"  && obj["name"] !== "title"
                                && obj["name"] !== "password" && obj["name"] !== "retype_password"
                                && obj["name"] !== "old_password") {
                                if (obj.hasOwnProperty(key)
                                    && isEmpty(obj[key])) {
                                    return true;
                                }
                                if ($('#flexCheckIndeterminate2:checked').length === 0) {
                                    return true;
                                }
                            }
                        } else {
                            if (obj["name"] !== "id" && obj["name"] !== "title" && obj["name"] !== "old_password") {
                                if (obj.hasOwnProperty(key)
                                    && isEmpty(obj[key])) {
                                    return true;
                                }
                                if ($('#flexCheckIndeterminate2:checked').length === 0) {
                                    return true;
                                }

                            }
                        }

                    }
                    return false;
                });

                if( $('#email-error').html() !== ''){
                    isAnyValueEmpty = true;
                }


                $('#dynamic-submit').prop('disabled', isAnyValueEmpty);
                $('#dynamic-discard').prop('disabled', isAnyValueEmpty);
            });
        });
        var index = "{{ url('user-management') }}";
        var url = "{{ url('user-management/list') }}";
        var save_model = "{{ route('user-management.save') }}";
        var check = "{{ route('user-management.check') }}";
        var delete_url = "{{ url('user-management/delete',$user->id??'') }}";
        var permission_url = "{{ url('user-management/permission') }}";
        $('#role_id').on('change', function () {
            var selectedValue = $(this).val();
            $('#role').val(selectedValue);
            var user_data = $('#id').val();
            permission(selectedValue, user_data);
        });

        function permission(role_data, user_data) {
            $.ajax({
                url: permission_url,
                type: "post",
                data: {
                    role: role_data,
                    user: user_data
                },
                success: function (response) {
                    $('input:checkbox').filter('#flexCheckIndeterminate2').each(function () {
                        $(this).prop('checked', false);

                    });
                    if (response.data.length > 0) {
                        $.each(response.data, function (index, checkbox) {
                            $('input[type="checkbox"]').each(function () {
                                if (checkbox.name == $(this).val()) {
                                    $(this).prop('checked', true);
                                }
                            });
                        });

                    } else {
                        $('input[type="checkbox"]').each(function () {
                            $(this).prop('checked', false);

                        });
                        $('#dynamic-submit').prop('disabled', true);
                        $('#dynamic-discard').prop('disabled', true);
                    }
                },
                error: function (response) {
                }
            });
        }

        $('#delete_save').on('click', function () {
            $.ajax({
                url: delete_url,
                type: "post",
                data: '',
                success: function (response) {
                    window.location = index;
                    toastr.success(response.message);
                },
                error: function (response) {
                }
            });
        });
        $('#delete_cancel').on('click', function () {
            $('#delete').modal('hide');
        });

        $(document).on('change keyup', 'input[name="email"]', function (e) {
            var email = $(this).val();
            $('#email-error').html('');
            if (!email.includes('@asign.art')) {
                $('#email-error').html('Email domain name should be asign.art');
                return false;
            }
            let email_arr = email.split('@asign.art');
            if (email_arr.length > 1) {
                if (email_arr[1] !== ''){
                    $('#email-error').html('Email domain name should be asign.art');
                }

            }

        });
        $(document).on('change keyup', 'input[name="password"], input[name="old_password"],input[name="retype_password"]', function (e) {
            var oldPassword = $('input[name="old_password"]').val();
            var password = $('input[name="password"]').val();
            var retype_password = $('input[name="retype_password"]').val();
            $('#retype_password-error').html('');
            if (oldPassword !== password) {
                $('#retype_password-error').html('Retype password is required.').css('color', 'red');
                $('button[type="button"]').prop('disabled', true);
                if (retype_password.trim() !== '') {
                $('button[type="button"]').prop('disabled', false);
                $('#retype_password-error').html('');
            }
            }
      });
    </script>
    <script type="text/javascript" src="{{ asset('js/user-management/index.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/permission.js') }}"></script>
@endpush
