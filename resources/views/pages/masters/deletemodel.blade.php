<!-- Modal -->
<div class="modal fade" id="discardModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered confirmationPopup">
      <form method="POST" id="deleteForm">
          @csrf
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title">Delete</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                Are you sure you want to delete this field? This action cannot be undone.
              </div>
              <input type="hidden" name="id"  id="id" value="{{$id ?? ''}}">
              <div class="modal-footer">
                  <button type="button" class="btn cancel-btn" data-bs-dismiss="modal">Cancel</button>
                  <button type="button" id="conform_delete" class="btn apply-btn">Delete</button>
              </div>
          </div>
      </form>
  </div>
</div>