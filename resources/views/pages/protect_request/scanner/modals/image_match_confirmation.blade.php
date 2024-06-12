<link rel="stylesheet" type="text/css" href="{{ asset('css/scanner/modals/image_match_confirmation.css') }}">

<div class="modal-content" id="image_match_confirm_modal">
			<div class="modal-header">
				<h5 class="modal-title">Image Match Results</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body content-new py-0">
				<p>Your image match was less than 75%.Do you want to continue to labelling anyway?</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn cancel-btn" data-bs-dismiss="modal" id="conform_exit">Back</button>
				<button type="button" class="btn apply-btn" id="next_step" data-next="remove_label_confirmation" data-bs-dismiss="modal">Yes</button>
			</div>
</div>
