<link rel="stylesheet" type="text/css" href="{{ asset('css/scanner/modals/image_remove_confirmation.css') }}">

<div class="modal-content" id="image_remove_confirm_modal">
	<div class="modal-header">
		<h5 class="modal-title">Remove Image</h5>
		<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	</div>
	<div class="modal-body content-new py-0">
		<p>Are you sure you want to remove the uploaded image?</p>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn cancel-btn" data-bs-dismiss="modal" id="conform_exit">Cancel</button>
		<button type="button" class="btn apply-btn" id="next_step" data-next="image_match_confirmation" data-bs-dismiss="modal">Remove</button>
	</div>
</div>