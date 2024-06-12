<div class="modal fade custom-modal component-modal" id="addComponentModal" tabindex="-1" aria-labelledby="bulkModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="bulkModalLabel">Add Component</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex align-items-start">
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <button class="nav-link active" id="about_tab" data-bs-toggle="pill" data-bs-target="#about_content" type="button" role="tab" aria-controls="about_content" aria-selected="true">ABOUT</button>
                        <button class="nav-link" id="medium_tab" data-bs-toggle="pill" data-bs-target="#medium_content" type="button" role="tab" aria-controls="medium_content" aria-selected="false">MEDIUM</button>
                        <button class="nav-link" id="measurement_tab" data-bs-toggle="pill" data-bs-target="#measurement_content" type="button" role="tab" aria-controls="measurement_content" aria-selected="false">MEASUREMENT</button>
                        <button class="nav-link" id="signature_tab" data-bs-toggle="pill" data-bs-target="#signature_content" type="button" role="tab" aria-controls="signature_content" aria-selected="false">SIGNATURE & INSCRIPTIONS</button>
                        <button class="nav-link" id="location_tab" data-bs-toggle="pill" data-bs-target="#location_content" type="button" role="tab" aria-controls="location_content" aria-selected="false">LOCATION</button>
                    </div>
                    <div class="tab-content" id="v-pills-tabContent">
                        <div class="tab-pane fade show active" id="about_content" role="tabpanel" aria-labelledby="about_tab" tabindex="0">
                            <form class="popup-form">
                                <div class="mb-5 mt-5">
                                    <div class="upload-widget">
                                        <label class="uploader" ondragover="return false">
                                            <span>Add Image</span>
                                            <img src="" class="">
                                            <input type="file" accept="image/*" name="file">
                                        </label>
                                    </div>                                    
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
                        <div class="tab-pane fade" id="medium_content" role="tabpanel" aria-labelledby="medium_tab" tabindex="0">
                            <form class="popup-form">
                                <div class="mb-3">
                                    <label for="medium" class="form-label">Medium</label>
                                    <select id="medium" class="form-select">
                                        <option value="" selected disabled>Select Medium</option>
                                        <option value="1">One</option>
                                        <option value="2">Two</option>
                                        <option value="3">Three</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="surface" class="form-label">Surface</label>
                                    <select id="surface" class="form-select">
                                        <option value="" selected disabled>Select Surface</option>
                                        <option value="1">One</option>
                                        <option value="2">Two</option>
                                        <option value="3">Three</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="tech" class="form-label">Technique</label>
                                    <input type="text" class="form-control" id="tech" placeholder="Enter Technique">
                                </div>
                                <div class="mb-3 text-end">
                                    <button class="btn btn-outline-dark mx-2">Cancel</button>
                                    <button type="submit" class="btn btn-dark">Save</button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="measurement_content" role="tabpanel" aria-labelledby="measurement_tab" tabindex="0">
                            <form class="popup-form">
                                <div class="mb-3">
                                    <label for="shape" class="form-label">Shape</label>
                                    <select id="shape" class="form-select">
                                        <option value="" selected disabled>Select Shape</option>
                                        <option value="1">One</option>
                                        <option value="2">Two</option>
                                        <option value="3">Three</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="meaurement_type" class="form-label">Measurement Type</label>
                                    <select id="meaurement_type" class="form-select">
                                        <option value="" selected disabled>Select Measurement Type</option>
                                        <option value="1">One</option>
                                        <option value="2">Two</option>
                                        <option value="3">Three</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="width" class="form-label">Width</label>
                                    <input type="text" class="form-control" id="width" placeholder="Enter Width">
                                </div>
                                <div class="mb-3">
                                    <label for="depth" class="form-label">Depth</label>
                                    <input type="text" class="form-control" id="depth" placeholder="Enter Depth">
                                </div>
                                <div class="mb-5">
                                    <label for="diameter" class="form-label">Diameter</label>
                                    <input type="text" class="form-control" id="diameter" placeholder="Enter Diameter">
                                </div>
                                <div class="mb-3">
                                    <label for="weight" class="form-label">Weight</label>
                                    <input type="text" class="form-control" id="weight" placeholder="Enter Weight">
                                </div>
                                <div class="mb-3 text-end">
                                    <button class="btn btn-outline-secondary mx-2">Cancel</button>
                                    <button type="submit" class="btn btn-dark">Save</button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="signature_content" role="tabpanel" aria-labelledby="signature_tab" tabindex="0">
                            <form class="popup-form">
                                <div class="mb-3">
                                    <label for="signature" class="form-label">Signature</label>
                                    <input type="text" class="form-control" id="signature" placeholder="Enter Signature">
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <input type="text" class="form-control" id="description" placeholder="Enter Description">
                                </div>
                                <div class="mb-3">
                                    <label for="inscription" class="form-label">Inscription</label>
                                    <select id="inscription" class="form-select">
                                        <option value="" selected disabled>Select Inscription</option>
                                        <option value="1">One</option>
                                        <option value="2">Two</option>
                                        <option value="3">Three</option>
                                    </select>
                                </div>
                                <div class="mb-3 text-end">
                                    <button class="btn btn-outline-secondary mx-2">Cancel</button>
                                    <button type="submit" class="btn btn-dark">Save</button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="location_content" role="tabpanel" aria-labelledby="location_tab" tabindex="0">
                            <form class="popup-form">
                                <div class="mb-3">
                                    <label for="save_location_as" class="form-label">Save Location As</label>
                                    <input type="text" class="form-control" id="save_location_as" placeholder="Enter Save Location">
                                </div>
                                <div class="mb-3">
                                    <label for="sub_location" class="form-label">Sub-location</label>
                                    <input type="text" class="form-control" id="sub_location" placeholder="Enter Sub-location">
                                </div>
                                <div class="mb-3">
                                    <label for="address_line" class="form-label">Address</label>
                                    <input type="text" class="form-control" id="address_line" placeholder="Enter Address">
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
                                    <select id="state" class="form-select">
                                        <option value="" selected disabled>Select State</option>
                                        <option value="1">One</option>
                                        <option value="2">Two</option>
                                        <option value="3">Three</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="country" class="form-label">Country</label>
                                    <select id="country" class="form-select">
                                        <option value="" selected disabled>Select Country</option>
                                        <option value="1">One</option>
                                        <option value="2">Two</option>
                                        <option value="3">Three</option>
                                    </select>
                                </div>
                                <div class="mb-5">
                                    <label for="pincode" class="form-label">Pincode</label>
                                    <input type="password" class="form-control" id="pincode" placeholder="Enter Pincode">
                                </div>
                                <div class="mb-3 text-end">
                                    <button class="btn btn-outline-secondary mx-2">Cancel</button>
                                    <button type="submit" class="btn btn-dark">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>