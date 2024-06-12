@extends('layouts.index')
@section('title', 'Role Management Create')
@section('style')
@parent
@endsection
@section('content')
<div class="pages user-management">
	<section class="alt-header filterHeaderCtr">
		<section class="section main-header p-0">
			<div class="section-breadcrumb">
				<ul>
					<li>
						<img src="{{ asset('icons/crown-1.svg') }}" width="20" height="20" />
						@if(isset($role))
						<a href="">Master / </a> <a href=" {{url('masters/role-management')}}">Roles / </a> <a href="{{url('masters/role-management',$role->id)}}"> Edit Role</a>
						@else
						<a href="">Master / </a> <a href="{{url('masters/role-management')}}">Roles / </a> <a href="{{url('masters/role-management')}}"> Add Role</a>
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
						id="dynamic-exit" />
					</div>
					<div class="addhead">Add New Role</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="hstack gap-3 float-end">
					<div>
						<button type="button" class="btn cancel-btn" data-bs-toggle="modal" data-bs-target="#discardModal" id="dynamic-discard">Discard</button>
					</div>
					<div>      
						<button type="button" class="btn apply-btn"  id="dynamic-submit">Save</button>
					</div>
				</div>
			</div>
		</div>
	</section>
	<section class="alt-section mb-5">
		<form class="formFieldInput" id="dynamic-form">
			<input type="hidden" name="id" id="id" value="{{$role->id??''}}">
			<input type="hidden" name="title" id="title" value="">
			<div class="sectionInnerCtr pe-5">
				<div class="addhead pb-24">Basic Information</div>
				<div class="row gx-5 gy-3">
					<div class="col-md-6 pe-5">
						<div class="form-group">
							<label class="form-label">Role Name</label>
							<input type="text" class="form-control" placeholder="Enter Role Name" name="role_name" value="{{$role->name??''}}">
							<span class="field-error" id="role_name-error"></span>
						</div>
					</div>
				</div>
			</div>
			<!-- Permissions  -->
			<div class="sectionInnerCtr sectionInnerCtr-1 pe-5">
				<div class="addhead pb-24">Permissions</div>
				<div class="row align-items-center">
					<div class="col-md-4">
						<div class="addhead-1">Customer List</div>
					</div>
					<div class="col-md-8">
						<div class="hstack gap-4">
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" name="permissions[]" 
								value="customer.view"
								id="flexCheckIndeterminate2"
								@if(isset($role)) {{ $role->hasPermissionTo('customer.view') ? 'checked' : '' }} @endif >
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

							@if(isset($role) && access()->checkRole($role->id,'Asign Protect+')==6)  checked @endif
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
								<input class="form-check-input" type="checkbox" name="permissions[]"   value="authentication-request.edit"
								id="flexCheckIndeterminate2"
								@if(isset($role)) {{ $role->hasPermissionTo('authentication-request.edit') ? 'checked' : '' }} @endif >
								<label class="form-check-label" for="flexCheckDefault">
									Edit
								</label>
							</div> 
							<div class="redes-checkbox">
								<input class="form-check-input asign_request_view" type="checkbox"   name="permissions[]"
								id="flexCheckIndeterminate2"
								value="authentication-request.view"
								@if(isset($role)) {{ $role->hasPermissionTo('authentication-request.view') ? 'checked' : '' }} @endif >
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
								<input class="form-check-input" type="checkbox"  name="permissions[]"id="flexCheckIndeterminate2"
								value="inspection-request.edit"
								@if(isset($role)) {{ $role->hasPermissionTo('inspection-request.edit') ? 'checked' : '' }} @endif>
								<label class="form-check-label" for="flexCheckDefault">
									Edit
								</label>
							</div> 
							<div class="redes-checkbox">
								<input class="form-check-input inspection_requests_view" type="checkbox" name="permissions[]" id="flexCheckIndeterminate2"
								value="inspection-request.view"
								@if(isset($role)) {{ $role->hasPermissionTo('inspection-request.view') ? 'checked' : '' }} @endif>
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
								<input class="form-check-input" type="checkbox" name="permissions[]" id="flexCheckIndeterminate2"
								value="label-requests.edit"
								@if(isset($role)) {{ $role->hasPermissionTo('label-requests.edit') ? 'checked' : '' }} @endif>
								<label class="form-check-label" for="flexCheckDefault">
									Edit
								</label>
							</div> 
							<div class="redes-checkbox">
								<input class="form-check-input label_requests_view" type="checkbox" name="permissions[]" id="flexCheckIndeterminate2"
								value="label-requests.view"
								@if(isset($role)) {{ $role->hasPermissionTo('label-requests.view') ? 'checked' : '' }} @endif>
								<label class="form-check-label" for="flexCheckDefault">
									View
								</label>
							</div>
						</div>
					</div>
				</div>

			</div>
			<!-- stock  -->
			<div class="sectionInnerCtr sectionInnerCtr-1 pe-5 all_stock" >
				<div class="row addhead-1 pb-24">
					<div class="col-md-4">
						Stock
					</div>
					<div class="col-md-8">
						<div class="redes-checkbox">
							<input class="form-check-input module_stock" type="checkbox" value="module_stock"
							id="module_stock" onclick="groupcheck('module_stock','all_stock','module_stock')"
							@if(isset($role) && access()->checkRole($role->id,'stock')==17)  checked @endif 
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
								<input class="form-check-input stock_overview_create module_stock" type="checkbox" name="permissions[]" id="flexCheckIndeterminate2"
								value="stock-overview.create"disabled
								>
								<label class="form-check-label" for="flexCheckDefault">
									Create
								</label>
							</div> 
							<div class="redes-checkbox">
								<input class="form-check-input stock_overview_edit" type="checkbox" name="permissions[]" id="flexCheckIndeterminate2"
								value="stock-overview.edit"
								@if(isset($role)) {{ $role->hasPermissionTo('stock-overview.edit') ? 'checked' : '' }} @endif>
								<label class="form-check-label" for="flexCheckDefault">
									Edit
								</label>
							</div> 
							<div class="redes-checkbox">
								<input class="form-check-input stock_overview_view" type="checkbox" name="permissions[]" id="flexCheckIndeterminate2"value="stock-overview.view"
								@if(isset($role)) {{ $role->hasPermissionTo('stock-overview.view') ? 'checked' : '' }} @endif>
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
								<input class="form-check-input" type="checkbox" name="permissions[]" id="flexCheckIndeterminate2"value="purchase-order.create"
								@if(isset($role)) {{ $role->hasPermissionTo('purchase-order.create') ? 'checked' : '' }} @endif>
								<label class="form-check-label" for="flexCheckDefault">
									Create
								</label>
							</div> 
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox"name="permissions[]" id="flexCheckIndeterminate2" value="purchase-order.edit"
								@if(isset($role)) {{ $role->hasPermissionTo('purchase-order.edit') ? 'checked' : '' }} @endif>
								<label class="form-check-label" for="flexCheckDefault">
									Edit
								</label>
							</div> 
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" name="permissions[]" value="purchase-order.view"id="flexCheckIndeterminate2"
								@if(isset($role)) {{ $role->hasPermissionTo('purchase-order.view') ? 'checked' : '' }} @endif>
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
								<input class="form-check-input" type="checkbox" name="permissions[]" id="flexCheckIndeterminate2"value="stock-transfer-order.create"
								@if(isset($role)) {{ $role->hasPermissionTo('stock-transfer-order.create') ? 'checked' : '' }} @endif>
								<label class="form-check-label" for="flexCheckDefault">
									Create
								</label>
							</div> 
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" name="permissions[]" value="stock-transfer-order.edit"id="flexCheckIndeterminate2"
								@if(isset($role)) {{ $role->hasPermissionTo('stock-transfer-order.edit') ? 'checked' : '' }} @endif>
								<label class="form-check-label" for="flexCheckDefault">
									Edit
								</label>
							</div> 
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" name="permissions[]" value="stock-transfer-order.view"id="flexCheckIndeterminate2"
								@if(isset($role)) {{ $role->hasPermissionTo('stock-transfer-order.view') ? 'checked' : '' }} @endif>
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
								<input class="form-check-input" type="checkbox" name="permissions[]" id="flexCheckIndeterminate2"value="goods-received-note.create"
								@if(isset($role)) {{ $role->hasPermissionTo('goods-received-note.create') ? 'checked' : '' }} @endif>
								<label class="form-check-label" for="flexCheckDefault">
									Create
								</label>
							</div> 
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" name="permissions[]" id="flexCheckIndeterminate2"value="goods-received-note.edit"
								@if(isset($role)) {{ $role->hasPermissionTo('goods-received-note.edit') ? 'checked' : '' }} @endif>
								<label class="form-check-label" for="flexCheckDefault">
									Edit
								</label>
							</div> 
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" name="permissions[]" id="flexCheckIndeterminate2"value="goods-received-note.view"
								@if(isset($role)) {{ $role->hasPermissionTo('goods-received-note.view') ? 'checked' : '' }} @endif>
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
								<input class="form-check-input" type="checkbox" name="permissions[]" value="damages.create"id="flexCheckIndeterminate2"
								@if(isset($role)) {{ $role->hasPermissionTo('damages.create') ? 'checked' : '' }} @endif>
								<label class="form-check-label" for="flexCheckDefault">
									Create
								</label>
							</div> 
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" name="permissions[]" value="damages.edit"id="flexCheckIndeterminate2"
								@if(isset($role)) {{ $role->hasPermissionTo('damages.edit') ? 'checked' : '' }} @endif>
								<label class="form-check-label" for="flexCheckDefault">
									Edit
								</label>
							</div> 
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" name="permissions[]" value="damages.view"id="flexCheckIndeterminate2"
								@if(isset($role)) {{ $role->hasPermissionTo('damages.view') ? 'checked' : '' }} @endif>
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
								<input class="form-check-input" type="checkbox" name="permissions[]" value="stock-check.create"id="flexCheckIndeterminate2"
								@if(isset($role)) {{ $role->hasPermissionTo('stock-check.create') ? 'checked' : '' }} @endif>
								<label class="form-check-label" for="flexCheckDefault">
									Create
								</label>
							</div> 
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" name="permissions[]" value="stock-check.edit"id="flexCheckIndeterminate2"
								@if(isset($role)) {{ $role->hasPermissionTo('stock-check.edit') ? 'checked' : '' }} @endif>
								<label class="form-check-label" for="flexCheckDefault">
									Edit
								</label>
							</div> 
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" name="permissions[]"value="stock-check.view"id="flexCheckIndeterminate2"
								@if(isset($role)) {{ $role->hasPermissionTo('stock-check.view') ? 'checked' : '' }} @endif>
								<label class="form-check-label" for="flexCheckDefault">
									View
								</label>
							</div>
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" name="permissions[]" value="stock-check.control"id="flexCheckIndeterminate2"
								@if(isset($role)) {{ $role->hasPermissionTo('stock-check.control') ? 'checked' : '' }} @endif>
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
						@if(isset($role) && access()->checkRole($role->id,'label')==9)  checked @endif
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
								<input class="form-check-input" type="checkbox" name="permissions[]" value="label-request.create"id="flexCheckIndeterminate2"
								@if(isset($role)) {{ $role->hasPermissionTo('label-request.create') ? 'checked' : '' }} @endif>
								<label class="form-check-label" for="flexCheckDefault">
									Create
								</label>
							</div>
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" name="permissions[]" value="label-request.edit"id="flexCheckIndeterminate2"
								@if(isset($role)) {{ $role->hasPermissionTo('label-request.edit') ? 'checked' : '' }} @endif>
								<label class="form-check-label" for="flexCheckDefault">
									Edit
								</label>
							</div> 
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" name="permissions[]" value="label-request.view"id="flexCheckIndeterminate2"
								@if(isset($role)) {{ $role->hasPermissionTo('label-request.view') ? 'checked' : '' }} @endif>
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
								<input class="form-check-input" type="checkbox" name="permissions[]" value="label-issue.create"id="flexCheckIndeterminate2"
								@if(isset($role)) {{ $role->hasPermissionTo('label-issue.create') ? 'checked' : '' }} @endif>
								<label class="form-check-label" for="flexCheckDefault">
									Create
								</label>
							</div>
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" name="permissions[]" value="label-issue.edit"id="flexCheckIndeterminate2"
								@if(isset($role)) {{ $role->hasPermissionTo('label-issue.edit') ? 'checked' : '' }} @endif>
								<label class="form-check-label" for="flexCheckDefault">
									Edit
								</label>
							</div> 
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" name="permissions[]" value="label-issue.view"id="flexCheckIndeterminate2"
								@if(isset($role)) {{ $role->hasPermissionTo('label-issue.view') ? 'checked' : '' }} @endif>
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
								<input class="form-check-input" type="checkbox" name="permissions[]" value="label-return.create"id="flexCheckIndeterminate2"
								@if(isset($role)) {{ $role->hasPermissionTo('label-return.create') ? 'checked' : '' }} @endif>
								<label class="form-check-label" for="flexCheckDefault">
									Create
								</label>
							</div>
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" name="permissions[]" value="label-return.edit"id="flexCheckIndeterminate2"
								@if(isset($role)) {{ $role->hasPermissionTo('label-return.edit') ? 'checked' : '' }} @endif>
								<label class="form-check-label" for="flexCheckDefault">
									Edit
								</label>
							</div> 
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" name="permissions[]" value="label-return.view"id="flexCheckIndeterminate2"
								@if(isset($role)) {{ $role->hasPermissionTo('label-return.view') ? 'checked' : '' }} @endif>
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
								id="module_master" onclick="groupcheck('module_master','all_master','module_master')" 
						@if(isset($role) && access()->checkRole($role->id,'master')==4)  checked @endif
								>	
								<label class="form-check-label" for="flexCheckDefault">All
</label>		
							</div>
						</div>
					<div class="col-md-8">
						<div class="hstack gap-4">
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" name="permissions[]" value="master.create"id="flexCheckIndeterminate2"
								@if(isset($role)) {{ $role->hasPermissionTo('master.create') ? 'checked' : '' }} @endif
								>
								<label class="form-check-label" for="flexCheckDefault">
									Create
								</label>
							</div>
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" name="permissions[]" value="master.edit"id="flexCheckIndeterminate2"
								@if(isset($role)) {{ $role->hasPermissionTo('master.edit') ? 'checked' : '' }} @endif>
								<label class="form-check-label" for="flexCheckDefault">
									Edit
								</label>
							</div>
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" name="permissions[]" value="master.view"id="flexCheckIndeterminate2"
								@if(isset($role)) {{ $role->hasPermissionTo('master.view') ? 'checked' : '' }} @endif>
								<label class="form-check-label" for="flexCheckDefault">
									View
								</label>
							</div>
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" name="permissions[]" value="master.delete"id="flexCheckIndeterminate2"
								@if(isset($role)) {{ $role->hasPermissionTo('master.delete') ? 'checked' : '' }} @endif>
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
						@if(isset($role) && access()->checkRole($role->id,'user')==4)  checked @endif
								>
								<label class="form-check-label" for="flexCheckDefault">All
</label>			
							</div>
						</div>
					<div class="col-md-8">
						<div class="hstack gap-4">
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" name="permissions[]" value="user.create"id="flexCheckIndeterminate2"
								@if(isset($role)) {{ $role->hasPermissionTo('user.create') ? 'checked' : '' }} @endif>
								<label class="form-check-label" for="flexCheckDefault">
									Add
								</label>
							</div>
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" name="permissions[]" value="user.edit"id="flexCheckIndeterminate2"
								@if(isset($role)) {{ $role->hasPermissionTo('user.edit') ? 'checked' : '' }} @endif>
								<label class="form-check-label" for="flexCheckDefault">
									Edit
								</label>
							</div>
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" name="permissions[]"value="user.view"id="flexCheckIndeterminate2"
								@if(isset($role)) {{ $role->hasPermissionTo('user.view') ? 'checked' : '' }} @endif>
								<label class="form-check-label" for="flexCheckDefault">
									View
								</label>
							</div>
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" name="permissions[]"value="user.delete"id="flexCheckIndeterminate2"
								@if(isset($role)) {{ $role->hasPermissionTo('user.delete') ? 'checked' : '' }} @endif>
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
						@if(isset($role) && access()->checkRole($role->id,'role')==3)  checked @endif
								>
								<label class="form-check-label" for="flexCheckDefault">All
</label>			
							</div>
						</div>
					<div class="col-md-8">
						<div class="hstack gap-4">
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" name="permissions[]"value="role.create"id="flexCheckIndeterminate2"
								@if(isset($role)) {{ $role->hasPermissionTo('role.create') ? 'checked' : '' }} @endif>
								<label class="form-check-label" for="flexCheckDefault">
									Add
								</label>
							</div>
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" name="permissions[]"value="role.edit"id="flexCheckIndeterminate2"
								@if(isset($role)) {{ $role->hasPermissionTo('role.edit') ? 'checked' : '' }} @endif>
								<label class="form-check-label" for="flexCheckDefault">
									Edit
								</label>
							</div>
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" name="permissions[]"value="role.view"id="flexCheckIndeterminate2"
								@if(isset($role)) {{ $role->hasPermissionTo('role.view') ? 'checked' : '' }} @endif>
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

<!--Discard Modal -->
<div class="modal fade" id="discardModal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered confirmationPopup">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Discard Role</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				Are you sure you want to discard changes made to this Role?
			</div>
			<div class="modal-footer">
				<button type="button" class="btn cancel-btn" data-bs-dismiss="modal" id="conform_exit">Cancel</button>
				<button type="button" class="btn apply-btn" data-bs-dismiss="modal" id="exit_save">Discard</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="conform"  tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered confirmationPopup">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Confirm Role</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				Are you sure you want to create a new Role? 
			</div>
			<div class="modal-footer">
				<button type="button" class="btn cancel-btn" data-bs-dismiss="modal" id="conform_cancel">Cancel</button>
				<button type="button" class="btn apply-btn" data-bs-dismiss="modal" id="conform_save">Confirm</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="ExitdModal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered confirmationPopup">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Exit Roles</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				Are you sure you want to exit without saving changes?
			</div>
			<div class="modal-footer">
				<button type="button" class="btn cancel-btn" data-bs-dismiss="modal" id="save_role">Save</button>
				<button type="button" class="btn apply-btn" data-bs-dismiss="modal" id="exit_role">Exit</button>
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
	});
	function isEmpty(value) {
		return value === null || value === undefined || value === '';
	}
	$('#dynamic-exit').click(function () {
	var button = document.getElementById('dynamic-submit');
	if(!button.disabled) {
    $('#ExitdModal').modal('show');
    } else {
    window.location =index;
    }
    });
	$(document).ready(function () {
		var $form = $('#dynamic-form');        
		$('#dynamic-submit').prop('disabled', true);
		$('#dynamic-discard').prop('disabled', true);
		$('#save_role').prop('disabled', true);
		
		$form.on('change keyup paste', function(e) {
			let allValues = $(this).serializeArray();
			let formType = $("#id").val();
			if(formType){
				$('#dynamic-submit').prop('disabled', true);
				$('#dynamic-discard').prop('disabled', true);
				$('#save_role').prop('disabled', true);
			}

			var isAnyValueEmpty = allValues.some(function(obj) {
				for (var key in obj) {
					if(obj["name"]!=="id" && obj["name"]!=="title" ){

						if (obj.hasOwnProperty(key)
							&& isEmpty(obj[key]) ) {

							return true;
					}
					if($('.form-check-input:checked').length == 0){
						return true;
					}
				}                                     
			}
			return false;
		});

			$('#dynamic-submit').prop('disabled', isAnyValueEmpty);
			$('#dynamic-discard').prop('disabled', isAnyValueEmpty);
			$('#save_role').prop('disabled', isAnyValueEmpty);
		});
	});
	var index="{{ url('masters/role-management') }}";
	var url="{{ url('masters/role-management/list') }}";
	var save_model = "{{ route('role-management.save') }}";
	var check="{{ route('role-management.check') }}";

</script>
<script type="text/javascript" src="{{ asset('js/masters/role.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/permission.js') }}"></script>
@endpush