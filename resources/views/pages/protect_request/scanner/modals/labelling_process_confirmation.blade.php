<link rel="stylesheet" type="text/css" href="{{ asset('css/scanner/modals/labelling_process_confirmation.css') }}">

<div class="modal-content" id="labelling_confirm_modal">
			<div class="modal-header">
				<h5 class="modal-title">Labelling Process</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body content-new py-0">
				<p>You have successfully added the Inventory and Authenticity labels.</p>
				
				<p>Do you want to Approve and complete the process
				or add child Inventory and Authenticity labels? After
				you Approve,no further changes can be made.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn cancel-btn" data-bs-dismiss="modal" id="conform_exit">Add Child Labels</button>
				<button type="button" class="btn apply-btn" id="next_step" data-next="remove_image_confirmation" data-bs-dismiss="modal">Approve</button>
			</div>
</div>
