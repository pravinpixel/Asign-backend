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
            <a href="">Master / </a> <a href="">Roles / </a> <a href=""> Add Role</a>
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
              onclick="window.location='{{url('masters/role-management/list')}}'"/>
          </div>
          <div class="addhead">Add New Role</div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="hstack gap-3 float-end">
          <div>
            <button type="button" class="btn cancel-btn" data-bs-toggle="modal" data-bs-target="#discardModal">Discard</button>
          </div>
          <div>      
            <button type="button" class="btn apply-btn" data-bs-toggle="modal" data-bs-target="#saveModal">Save</button>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="alt-section mb-5">
    <form class="formFieldInput">
      <div class="sectionInnerCtr pe-5">
        <div class="addhead pb-24">Basic Information</div>
        <div class="row gx-5 gy-3">
          <div class="col-md-6 pe-5">
            <div class="form-group">
              <label class="form-label">Role Name</label>
              <input type="text" class="form-control" placeholder="Enter Role Name" name="role_name">
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
								<input class="form-check-input" type="checkbox" value="" id="flexCheckIndeterminate">
							 	<label class="form-check-label" for="flexCheckDefault">
									View
								</label>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- Assign protect+ stages  -->
			<div class="sectionInnerCtr sectionInnerCtr-1 pe-5">
				<div class="addhead-1 pb-24">Asign Protect+ Stages</div>
				
				<div class="row align-items-center mb-3">
					<div class="col-md-4">
						<div class="addhead-2">1. Authentication Requests</div>
					</div>
					<div class="col-md-8">
						<div class="hstack gap-4">
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" value="" id="flexCheckIndeterminate">
							 	<label class="form-check-label" for="flexCheckDefault">
									Edit
								</label>
							</div> 
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" value="" id="flexCheckIndeterminate">
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
								<input class="form-check-input" type="checkbox" value="" id="flexCheckIndeterminate">
							 	<label class="form-check-label" for="flexCheckDefault">
									Edit
								</label>
							</div> 
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" value="" id="flexCheckIndeterminate">
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
								<input class="form-check-input" type="checkbox" value="" id="flexCheckIndeterminate">
							 	<label class="form-check-label" for="flexCheckDefault">
									Edit
								</label>
							</div> 
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" value="" id="flexCheckIndeterminate">
							 	<label class="form-check-label" for="flexCheckDefault">
									View
								</label>
							</div>
						</div>
					</div>
				</div>

			</div>
			<!-- stock  -->
			<div class="sectionInnerCtr sectionInnerCtr-1 pe-5">
				<div class="addhead-1 pb-24">Stock</div>
				
				<div class="row align-items-center mb-3">
					<div class="col-md-4">
						<div class="addhead-2">1. Stock Overview</div>
					</div>
					<div class="col-md-8">
						<div class="hstack gap-4">
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" value="" id="flexCheckIndeterminate" disabled>
							 	<label class="form-check-label" for="flexCheckDefault">
									Create
								</label>
							</div> 
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" value="" id="flexCheckIndeterminate">
							 	<label class="form-check-label" for="flexCheckDefault">
									Edit
								</label>
							</div> 
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" value="" id="flexCheckIndeterminate">
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
								<input class="form-check-input" type="checkbox" value="" id="flexCheckIndeterminate">
							 	<label class="form-check-label" for="flexCheckDefault">
									Create
								</label>
							</div> 
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" value="" id="flexCheckIndeterminate">
							 	<label class="form-check-label" for="flexCheckDefault">
									Edit
								</label>
							</div> 
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" value="" id="flexCheckIndeterminate">
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
								<input class="form-check-input" type="checkbox" value="" id="flexCheckIndeterminate">
							 	<label class="form-check-label" for="flexCheckDefault">
									Create
								</label>
							</div> 
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" value="" id="flexCheckIndeterminate">
							 	<label class="form-check-label" for="flexCheckDefault">
									Edit
								</label>
							</div> 
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" value="" id="flexCheckIndeterminate">
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
								<input class="form-check-input" type="checkbox" value="" id="flexCheckIndeterminate">
							 	<label class="form-check-label" for="flexCheckDefault">
									Create
								</label>
							</div> 
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" value="" id="flexCheckIndeterminate">
							 	<label class="form-check-label" for="flexCheckDefault">
									Edit
								</label>
							</div> 
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" value="" id="flexCheckIndeterminate">
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
								<input class="form-check-input" type="checkbox" value="" id="flexCheckIndeterminate">
							 	<label class="form-check-label" for="flexCheckDefault">
									Create
								</label>
							</div> 
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" value="" id="flexCheckIndeterminate">
							 	<label class="form-check-label" for="flexCheckDefault">
									Edit
								</label>
							</div> 
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" value="" id="flexCheckIndeterminate">
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
								<input class="form-check-input" type="checkbox" value="" id="flexCheckIndeterminate" disabled>
							 	<label class="form-check-label" for="flexCheckDefault">
									Create
								</label>
							</div> 
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" value="" id="flexCheckIndeterminate">
							 	<label class="form-check-label" for="flexCheckDefault">
									Edit
								</label>
							</div> 
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" value="" id="flexCheckIndeterminate">
							 	<label class="form-check-label" for="flexCheckDefault">
									View
								</label>
							</div>
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" value="" id="flexCheckIndeterminate">
							 	<label class="form-check-label" for="flexCheckDefault">
									Control
								</label>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- Labels  -->
			<div class="sectionInnerCtr sectionInnerCtr-1 pe-5">
				<div class="addhead-1 pb-24">Labels</div>
				
				<div class="row align-items-center mb-3">
					<div class="col-md-4">
						<div class="addhead-2">1. Label Request</div>
					</div>
					<div class="col-md-8">
						<div class="hstack gap-4">
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" value="" id="flexCheckIndeterminate">
							 	<label class="form-check-label" for="flexCheckDefault">
									Create
								</label>
							</div>
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" value="" id="flexCheckIndeterminate">
							 	<label class="form-check-label" for="flexCheckDefault">
									Edit
								</label>
							</div> 
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" value="" id="flexCheckIndeterminate">
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
								<input class="form-check-input" type="checkbox" value="" id="flexCheckIndeterminate">
							 	<label class="form-check-label" for="flexCheckDefault">
									Create
								</label>
							</div>
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" value="" id="flexCheckIndeterminate">
							 	<label class="form-check-label" for="flexCheckDefault">
									Edit
								</label>
							</div> 
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" value="" id="flexCheckIndeterminate">
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
								<input class="form-check-input" type="checkbox" value="" id="flexCheckIndeterminate">
							 	<label class="form-check-label" for="flexCheckDefault">
									Create
								</label>
							</div>
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" value="" id="flexCheckIndeterminate">
							 	<label class="form-check-label" for="flexCheckDefault">
									Edit
								</label>
							</div> 
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" value="" id="flexCheckIndeterminate">
							 	<label class="form-check-label" for="flexCheckDefault">
									View
								</label>
							</div>
						</div>
					</div>
				</div>

			</div>
			<!-- masters  -->
			<div class="sectionInnerCtr sectionInnerCtr-1 pe-5">
				<div class="row align-items-center">
					<div class="col-md-4">
						<div class="addhead-1">Masters</div>
					</div>
					<div class="col-md-8">
						<div class="hstack gap-4">
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" value="" id="flexCheckIndeterminate">
							 	<label class="form-check-label" for="flexCheckDefault">
									Edit
								</label>
							</div>
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" value="" id="flexCheckIndeterminate">
							 	<label class="form-check-label" for="flexCheckDefault">
									View
								</label>
							</div>
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" value="" id="flexCheckIndeterminate">
							 	<label class="form-check-label" for="flexCheckDefault">
									Delete
								</label>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- users  -->
			<div class="sectionInnerCtr sectionInnerCtr-1 pe-5">
				<div class="row align-items-center">
					<div class="col-md-4">
						<div class="addhead-1">Users</div>
					</div>
					<div class="col-md-8">
						<div class="hstack gap-4">
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" value="" id="flexCheckIndeterminate">
							 	<label class="form-check-label" for="flexCheckDefault">
									Add
								</label>
							</div>
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" value="" id="flexCheckIndeterminate">
							 	<label class="form-check-label" for="flexCheckDefault">
									Edit
								</label>
							</div>
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" value="" id="flexCheckIndeterminate">
							 	<label class="form-check-label" for="flexCheckDefault">
									View
								</label>
							</div>
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" value="" id="flexCheckIndeterminate">
							 	<label class="form-check-label" for="flexCheckDefault">
									Delete
								</label>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- Roles -->
			<div class="sectionInnerCtr sectionInnerCtr-1 pe-5 border-bottom-0">
				<div class="row align-items-center">
					<div class="col-md-4">
						<div class="addhead-1">Roles</div>
					</div>
					<div class="col-md-8">
						<div class="hstack gap-4">
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" value="" id="flexCheckIndeterminate">
							 	<label class="form-check-label" for="flexCheckDefault">
									Add
								</label>
							</div>
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" value="" id="flexCheckIndeterminate">
							 	<label class="form-check-label" for="flexCheckDefault">
									Edit
								</label>
							</div>
							<div class="redes-checkbox">
								<input class="form-check-input" type="checkbox" value="" id="flexCheckIndeterminate">
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
        <h5 class="modal-title">Exit User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      	Are you sure you want to exit without saving changes?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn cancel-btn" data-bs-dismiss="modal">Exit</button>
        <button type="button" class="btn apply-btn" data-bs-dismiss="modal">Save</button>
      </div>
    </div>
  </div>
</div>

<!--Save Modal -->
<div class="modal fade" id="saveModal" tabindex="-1" aria-hidden="true">
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
        <button type="button" class="btn cancel-btn" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn apply-btn" data-bs-dismiss="modal">Confirm</button>
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
</script>
@endpush