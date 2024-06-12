<link rel="stylesheet" type="text/css" href="{{asset('css/select2.min.css')}}" />
<link rel="stylesheet" type="text/css" href="{{asset('css/select2-material.css')}}" />

<div class="modal fade artist-modal" id="bulkModal" tabindex="-1" aria-labelledby="bulkModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="bulkModalLabel">Bulk Assign</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <hr />
            <div class="modal-body modalSelectW100">
                <div class="container p-0 d-flex align-items-start">
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <button class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill" data-bs-target="#v-pills-home" 
                        type="button" role="tab" aria-controls="v-pills-home" aria-selected="true">Assign Team</button>
                        <button class="nav-link" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile"
                         type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false">Shedule Visit</button>
                    </div>
                    <div class="tab-content w-100" id="v-pills-tabContent">
                        <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab" 
                        tabindex="0">
                            <div class="popupdorpdownSelect popupdorpdownSelect-yellow mb-3">
                                <label>Authenticator</label>
                                <select class="js-example-placeholder-multiple js-states form-control" multiple="multiple">
                                    <optgroup class="optgroup-text" label="you can select up to 2 authenticator">
                                        <option>One</option>
                                        <hr />
                                    </optgroup>
                                        <option>Two</option>
                                        <option>Three</option>
                                        <option>Four</option>
                                </select>
                            </div>
                            <div class="popupdorpdownSelect popupdorpdownSelect-blue mb-3">
                                <label>Conservator</label>
                                <select class="js-example-placeholder-multiple js-states form-control" multiple="multiple">
                                    <optgroup class="optgroup-text" label="you can select up to 2 authenticator">
                                        <option>One</option>
                                        <hr />
                                    </optgroup>
                                        <option>Two</option>
                                        <option>Three</option>
                                        <option>Four</option>
                                </select>
                            </div>
                             <div class="popupdorpdownSelect popupdorpdownSelect-lavender mb-3">
                                <label>Field Agent</label>
                                <select class="js-example-placeholder-multiple js-states form-control" multiple="multiple">
                                    <optgroup class="optgroup-text" label="you can select up to 2 authenticator">
                                        <option>One</option>
                                        <hr />
                                    </optgroup>
                                        <option>Two</option>
                                        <option>Three</option>
                                        <option>Four</option>
                                </select>
                            </div>
                            <div class="popupdorpdownSelect popupdorpdownSelect-lavender mb-3">
                                <label>Other Service Provider</label>
                                <select class="js-example-placeholder-multiple js-states form-control" multiple="multiple">
                                    <optgroup class="optgroup-text" label="you can select up to 2 authenticator">
                                        <option>One</option>
                                        <hr />
                                    </optgroup>
                                        <option>Two</option>
                                        <option>Three</option>
                                        <option>Four</option>
                                </select>
                            </div>
                        <div style="display: flex;flex-direction:row;gap:16px;justify-content:end;">
                            <button type="button" class="btn cancel-btn" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn apply-btn">Apply</button>
                        </div>
                    </div>
                        <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab" 
                        tabindex="0">
                      <div class="popupdorpdownSelect mb-3">
                                <label>Authenticator</label>
                                <select class="js-example-placeholder-multiple js-states form-control" multiple="multiple">
                                    <optgroup class="optgroup-text" label="you can select up to 2 authenticator">
                                        <option>One</option>
                                        <hr />
                                    </optgroup>
                                        <option>Two</option>
                                        <option>Three</option>
                                        <option>Four</option>
                                </select>
                            </div>
                            <div class="popupdorpdownSelect mb-3">
                                <label>Authenticator</label>
                                <select class="js-example-placeholder-multiple js-states form-control" multiple="multiple">
                                    <optgroup class="optgroup-text" label="you can select up to 2 authenticator">
                                        <option>One</option>
                                        <hr />
                                    </optgroup>
                                        <option>Two</option>
                                        <option>Three</option>
                                        <option>Four</option>
                                </select>
                            </div>
                        <div style="display: flex;flex-direction:row;gap:16px;justify-content:end;">
                            <button type="button" class="btn cancel-btn" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn apply-btn">Apply</button>
                        </div>
                    </div>
                    </div>
                </div>
        </div>
    </div>
</div>

    <script src="{{asset('js/select2.min.js')}}"></script>
    <script src="{{asset('js/qcTimepicker.min.js')}}"></script>
    <script src="{{asset('js/jquery.datepicker2.min.js')}}"></script>
    <script src="{{asset('js/jquery.uploader.min.js')}}"></script>