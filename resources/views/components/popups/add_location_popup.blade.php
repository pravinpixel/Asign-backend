<div class="modal fade custom-modal location-modal" id="addLocationModal" tabindex="-1" aria-labelledby="bulkModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="bulkModalLabel">Add Location</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="popup-form">
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <select id="address" class="form-select">
                            <option value="" selected disabled>Select Address</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="city" class="form-label">City</label>
                        <select id="city" class="form-select">
                            <option value="" selected disabled>Select City</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="state" class="form-label">State</label>
                        <input type="password" class="form-control" id="state" placeholder="Enter State">
                    </div>
                    <div class="mb-3">
                        <label for="country" class="form-label">Country</label>
                        <input type="password" class="form-control" id="country" placeholder="Enter Country">
                    </div>
                    <div class="mb-5">
                        <label for="pincode" class="form-label">Pincode</label>
                        <input type="password" class="form-control" id="pincode" placeholder="Enter Pincode">
                    </div>
                    <div class="mb-3 text-end">
                        <button class="btn btn-outline-dark mx-2">Cancel</button>
                        <button type="submit" class="btn btn-dark">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>