<link rel="stylesheet" type="text/css" href="{{ asset('css/scanner/modals/remove_label_confirmation.css') }}">

<div class="modal-content" id="remove_label_confirm_modal">
			<div class="modal-header">
				<h5 class="modal-title">Remove Label</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body content-new py-0">
				<p>Deleting the Envelope code will also delete the Label code and remove both from the system.Are you sure you want to remove it?</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn cancel-btn" data-bs-dismiss="modal" id="conform_exit">Cancel</button>
				<button type="button" class="btn apply-btn" id="next_step" data-next="delete_label_confirmation" data-bs-dismiss="modal">Remove</button>
			</div>
</div>