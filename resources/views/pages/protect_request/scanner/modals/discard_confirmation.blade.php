<link rel="stylesheet" type="text/css" href="{{ asset('css/scanner/modals/discard_confirmation.css') }}">

<div class="modal-content" id="discard_confirm_modal">
			<div class="modal-header">
				<h5 class="modal-title">Discard Changes</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body content-new py-0">
				<p>Your changes have not been saved. Are you sure you want to go back without saving?</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn cancel-btn" data-bs-dismiss="modal" id="conform_exit">Cancel</button>
				<button type="button" class="btn apply-btn" id="next_step" data-next="delete_label_confirmation" data-bs-dismiss="modal">Yes</button>
			</div>
</div>