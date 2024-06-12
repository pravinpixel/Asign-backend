<div class="modal fade custom-modal location-modal" id="addLocationModal" tabindex="-1" aria-labelledby="bulkModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 mb-0" id="bulkModalLabel">Add Location</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="popup-form formFieldInput" id="location-form">

                    {{--<div class="mb-3">--}}
                    {{--<label for="location_as" class="form-label">Location As</label>--}}
                    {{--<input type="text" class="form-control" id="location_as" name="location_as" placeholder="Enter Location As">--}}
                    {{--</div>--}}

                    <div class="mb-3">
                        <label for="sub_location" class="form-label">Sub Location</label>
                        <input type="text" class="form-control" id="sub_location" name="sub_location" maxlength="1000" required
                               placeholder="Enter Sub Location">
                        <span class="field-error" id="sub_location-error"></span>
                    </div>

                    <div class="mb-3">
                        <label for="address_line1" class="form-label">Address</label>
                        <input type="text" class="form-control" id="address_line1" name="address_line1" maxlength="1000" required
                               placeholder="Enter Address">
                        <span class="field-error" id="address_line1-error"></span>
                    </div>

                    {{--<div class="mb-3">--}}
                    {{--<label for="address_line2" class="form-label">Address</label>--}}
                    {{--<input type="text" class="form-control" id="address_line2" name="address_line2" placeholder="Enter Address">--}}
                    {{--</div>--}}

                    <div class="mb-3">
                        <label for="country" class="form-label">Country</label>
                        <div class="w100Select">
                            <select id="country" data-placeholder="Select Country" name="country_id" class="form-select select2Box" required>
                                <option value=""></option>
                                @foreach($master['country'] as $country)
                                    <option value="{{$country['id']}}"  {{$country['id'] == '102' ? 'selected' : ''}}>{{$country['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <span class="field-error" id="country_id-error"></span>

                    </div>

                    <div class="mb-3">
                        <label for="state" class="form-label">State</label>
                        <div class="w100Select">
                            <select id="state"  data-placeholder="Select State" name="state_id" class="form-select select2Box" required>
                                <option value=""></option>
                                @foreach($master['state'] as $state)
                                    <option value="{{$state['id']}}">{{$state['name']}}</option>
                                @endforeach
                            </select>
                            <span class="field-error" id="state_id-error"></span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="city" class="form-label">City</label>
                        <input type="text" class="form-control" id="city" required name="city" maxlength="50"
                               placeholder="Enter City">
                        <span class="field-error" id="city-error"></span>

                    </div>

                    <div class="mb-3">
                        <label for="pin_code" class="form-label">Pincode</label>
                        <input type="text" class="form-control" id="pin_code" required maxlength="10" name="pin_code" placeholder="Enter Pincode">
                        <span class="field-error" id="pin_code-error"></span>
                    </div>
                    <div class="py-3 text-end">
                        <button type="button" data-bs-dismiss="modal" class="btn cancel-btn mx-2">Cancel</button>
                        <button type="submit" class="btn apply-btn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
