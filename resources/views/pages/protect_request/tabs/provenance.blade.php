@php
    $disabled_provenance = true;
    $auth_edit_access = access()->hasAccess('authentication-request.edit');
    if($data['status']['id'] == 'authentication') {
        $authenticator_ids = $data['authenticator_ids'] ? explode(',', $data['authenticator_ids']) : [];
        if(in_array(auth()->user()->id, $authenticator_ids)) {
            $disabled_provenance = false;
        }
         if(!$auth_edit_access) {
            $disabled_provenance = true;
         }
    }

    $inspection = $data['inspection'];
@endphp

<section class="section-inner">
    <h1>Provenance</h1>

    <ol type="1" class="ol-info">
        @foreach($data['provenances'] as $p)
            <li>{{$p->provenance}}</li>
        @endforeach
    </ol>

</section>
<section class="section-inner-alt">
    <h1>Auction History</h1>
    <section class="section table-content">
        <table class="asign-table customer-table">
            <thead>
            <tr>
                <th scope="col" width="5%"></th>
                <th scope="col">Auction House</th>
                <th scope="col">Name of Auction</th>
                <th scope="col">Start Date</th>
                <th scope="col">End Date</th>
                <th scope="col">Auction Number</th>
                <th scope="col">Lot Number</th>
                <th scope="col">Location</th>
            </tr>
            </thead>
            <tbody id="tableCtr">
            @php
                $auction_status = $data['verify_status']['auction'] ?? [];
            @endphp
            @foreach($data['auctions'] as $auction)
                @php
                    isset($auction_status[$auction->id]) && $auction_status[$auction->id]['verify'] == 'true' ? $checked = 'checked' : $checked = '';
                @endphp

                <tr data-value="{{$auction->id}}" data-type="auction">
                    <td>
                        <div class="form-check">
                            @if($disabled_provenance)
                                <input disabled {{$checked}} class="form-check-input" type="checkbox">
                            @else
                                <input {{$checked}} class="form-check-input provenance" type="checkbox" name="auction">
                            @endif
                        </div>
                    </td>
                    <td>{{$auction->house}}</td>
                    <td>{{$auction->name}}</td>
                    <td>{{\App\Helpers\UtilsHelper::displayDate($auction->start_date)}}</td>
                    <td>{{\App\Helpers\UtilsHelper::displayDate($auction->end_date)}}</td>
                    <td>{{$auction->auction_no}}</td>
                    <td>{{$auction->lot_no}}</td>
                    <td>{{$auction->location}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </section>
</section>
<section class="section-inner-alt">
    <h1>Exhibition History</h1>

    <section class="section table-content">
        <table class="asign-table customer-table">
            <thead>
            <tr>
                <th scope="col" width="5%"></th>
                <th scope="col">Exhibited By</th>
                <th scope="col">Exhibition Title</th>
                <th scope="col">Venue</th>
                <th scope="col">Location</th>
                <th scope="col">Start Date</th>
                <th scope="col">End Date</th>
            </tr>
            </thead>
            <tbody id="tableCtr">
            @php
                $exhibition_status = $data['verify_status']['exhibition'] ?? [];
            @endphp

            @foreach($data['exhibitions'] as $exhibition)
                @php
                    isset($exhibition_status[$exhibition->id]) && $exhibition_status[$exhibition->id]['verify'] == 'true' ? $checked = 'checked' : $checked = '';
                @endphp
                <tr data-value="{{$exhibition->id}}" data-type="exhibition">
                    <td>
                        <div class="form-check">
                            @if($disabled_provenance)
                                <input disabled {{$checked}} class="form-check-input" type="checkbox">
                            @else
                                <input {{$checked}} class="form-check-input provenance" type="checkbox"
                                       name="exhibition">
                            @endif
                        </div>
                    </td>
                    <td>{{$exhibition->hosted_by}}</td>
                    <td>{{$exhibition->name}}</td>
                    <td>{{$exhibition->venue}}</td>
                    <td>{{$exhibition->city}}</td>
                    <td>{{\App\Helpers\UtilsHelper::displayDate($exhibition->from_date)}}</td>
                    <td>{{\App\Helpers\UtilsHelper::displayDate($exhibition->to_date)}}</td>
                </tr>
            @endforeach

            </tbody>
        </table>
    </section>


</section>
<section class="section-inner-alt">
    <h1>Publication History</h1>

    <section class="section table-content">
        <table class="asign-table customer-table">
            <thead>
            <tr>
                <th scope="col" width="5%"></th>
                <th scope="col">Publication Name</th>
                <th scope="col">Author</th>
                <th scope="col">Page Number</th>
                <th scope="col">Date</th>
                <th scope="col">Published By</th>
            </tr>
            </thead>
            <tbody id="tableCtr">
            @php
                $publication_status = $data['verify_status']['publication'] ?? [];
            @endphp
            @foreach($data['publications'] as $publication)
                @php
                    isset($publication_status[$publication->id]) && $publication_status[$publication->id]['verify'] == 'true' ? $checked = 'checked' : $checked = '';
                @endphp
                <tr data-value="{{$publication->id}}" data-type="publication">
                    <td>
                        <div class="form-check">
                            @if($disabled_provenance)
                                <input disabled {{$checked}} class="form-check-input" type="checkbox">
                            @else
                                <input {{$checked}} class="form-check-input provenance" type="checkbox"
                                       name="publication">
                            @endif
                        </div>
                    </td>
                    <td>{{$publication->name}}</td>
                    <td>{{$publication->author}}</td>
                    <td>{{$publication->page_no}}</td>
                    <td>
                        {{\App\Helpers\UtilsHelper::displayDate($publication->date)}}
                    </td>
                    <td>{{$publication->published_by}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </section>

</section>

<section class="section-inner-alt">
    <h1>Authenticator Checklist</h1>

    <section class="provenancetab-ctr">
        <form id="protect-request-provenance-form" enctype="multipart/form-data"
              method="post" {{-- onchange="provenanceFormValidate()"--}}>
            @csrf
            <section class="w-411" style="width: 100% !important;">
                {{-- Is the provenance provided sufficient for objective verification? --}}
                <div class="provenanceq_pov">
                    <div class="qa-ctr mb-4">
                        <div class="vstack gcondition-2 gap-2">
                            <div class="ff-medium fs-14">
                                Is the provenance provided sufficient for objective verification?
                            </div>
                            <div class="hstack gap-5 ps-3">
                                <div class="provenance-ctr">
                                    @if($disabled_provenance)
                                        <input class="form-check-input" type="radio" id="yes"
                                               {{ !empty($inspection->is_provenance_objective_verification) ? ' checked' : '' }} disabled>
                                    @else
                                        <input class="form-check-input" type="radio"
                                               name="provenanceObjectiveVerification"
                                               id="provenance_objective_verification_yes"
                                               value="1"{{ !empty($inspection->is_provenance_objective_verification) ? ' checked' : '' }}>
                                    @endif
                                    <label class="radio-label" for="provenance_objective_verification_yes">Yes</label>
                                </div>
                                <div class="provenance-ctr">
                                    @if($disabled_provenance)
                                        <input class="form-check-input" type="radio" id="no"
                                               {{ isset($inspection->is_provenance_objective_verification) && empty($inspection->is_provenance_objective_verification) ? ' checked' : '' }} disabled>
                                    @else
                                        <input class="form-check-input" type="radio"
                                               name="provenanceObjectiveVerification"
                                               id="provenance_objective_verification_no"
                                               value="0"{{ isset($inspection->is_provenance_objective_verification) && empty($inspection->is_provenance_objective_verification) ? ' checked' : '' }}>
                                    @endif
                                    <label class="radio-label" for="provenance_objective_verification_no">No</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="provenanceq_pov_divhide provenanceq_paubou"
                     style="display: {{ !empty($inspection->is_provenance_objective_verification) ? 'block' : 'none' }}">
                    {{-- Has this Art been uploaded to Discover by another User? --}}
                    <div class="qa-ctr mb-4">
                        <div class="vstack gcondition-2 gap-2">
                            <div class="ff-medium fs-14">
                                Has this Art been uploaded to Discover by another User?
                            </div>
                            <div class="hstack gap-5 ps-3">
                                <div class="provenance-ctr">
                                    @if($disabled_provenance)
                                        <input class="form-check-input" type="radio" id="yes"
                                               {{ !empty($inspection->is_provenance_art_upload) ? ' checked' : '' }} disabled>
                                    @else
                                        <input class="form-check-input" type="radio"
                                               name="provenanceArtUploadByOtherUser" id="art_upload_yes"
                                               value="1"{{ !empty($inspection->is_provenance_art_upload) ? ' checked' : '' }}>
                                    @endif
                                    <label class="radio-label" for="art_upload_yes">Yes</label>
                                </div>
                                <div class="provenance-ctr">
                                    @if($disabled_provenance)
                                        <input class="form-check-input" type="radio" id="no"
                                               {{ isset($inspection->is_provenance_art_upload) && empty($inspection->is_provenance_art_upload) ? ' checked' : '' }} disabled>
                                    @else
                                        <input class="form-check-input" type="radio"
                                               name="provenanceArtUploadByOtherUser" id="art_upload_no"
                                               value="0"{{ isset($inspection->is_provenance_art_upload) && empty($inspection->is_provenance_art_upload) ? ' checked' : '' }}>
                                    @endif
                                    <label class="radio-label" for="art_upload_no">No</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="provenanceq_paubou_divhide"
                     style="display: {{ !empty($inspection->is_provenance_art_upload) && !empty($inspection->is_provenance_objective_verification) ? 'block' : 'none' }}">
                    {{-- Asign Object Number of the Object already on Asign --}}
                    <div class="qa-ctr mb-4">
                        <div class="vstack gcondition-2 gap-2">
                            <div class="ff-medium fs-14">
                                Asign Object Number of the Object already on Asign
                            </div>
                            <div class="w-411">
                                @if($disabled_provenance)
                                    <input class="form-control" type="text" id="provenanceObjectNumberOfObject"
                                           placeholder="Enter Asign Object Number"
                                           {{ !empty($inspection->provenance_object_number) ? " value=$inspection->provenance_object_number" : '' }}{{ !empty($inspection->provenance_object_number) ? ' checked' : '' }} disabled>
                                    <input class="form-control" type="hidden"
                                           id="provenanceObjectNumberOfObjectHidden"{{ !empty($inspection->provenance_object_number) ? " value=$inspection->provenance_object_number" : '' }}>
                                @else
                                    <input class="form-control" type="text" id="object_number"
                                           name="provenanceObjectNumberOfObject"
                                           placeholder="Enter Asign Object Number"{{ !empty($inspection->provenance_object_number) ? " value=$inspection->provenance_object_number" : '' }}>
                                    <input class="form-control" type="hidden" id="object_number_hidden"
                                           name="provenanceObjectNumberOfObjectHidden"{{ !empty($inspection->provenance_object_number) ? " value=$inspection->provenance_object_number" : '' }}>
                                @endif

                                <div class="form-check remember-checkbox mb-2 mt-2 object_number_check"
                                     style="display: {{ !empty($inspection->provenance_object_number) ? 'block' : 'none' }}">
                                    <div class="form-check redes-checkbox redes-checkbox-1">
                                        @if($disabled_provenance)
                                            <input class="form-check-input" type="checkbox" id="confirmIsObject"
                                                   {{ !empty($inspection->is_provenance_confirm_object) ? ' checked' : '' }} disabled>
                                        @else
                                            <input class="form-check-input" type="checkbox" name="confirmIsObject"
                                                   id="confirmIsObject"
                                                   value="1"{{ !empty($inspection->is_provenance_confirm_object) ? ' checked' : '' }}>
                                        @endif
                                        <label class="form-check-label" for="confirm_isobject">Confirm that this is the
                                            object: <u
                                                class="object_number_url">{{ !empty($data['provenance_reason_confirm_object_link']) ? $data['provenance_reason_confirm_object_link'] : '' }}</u></label>
                                    </div>
                                </div>
                                <span class="field-error object_number_error" id="email-error"
                                      style="height:0px;display:none;">Enter a number different from your current Asign Object Number</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="provenanceq_povr_divhide"
                     style="display: {{ isset($inspection->is_provenance_objective_verification) && empty($inspection->is_provenance_objective_verification) ? 'block' : 'none' }}">
                    {{-- What is the Reason?  Select box --}}
                    <div class="qa-ctr mb-4">
                        <div class="vstack gap-2">
                            <div class="ff-medium fs-14">
                                What is the Reason?
                            </div>
                            <div>
                                <div class="w100Select inspection-select-width inspection-select">
                                    <select data-placeholder="Select Reason"
                                            class="form-select select2Box1 provenance_reason"{{($disabled_provenance) ? ' disabled' : ' name=provenanceReason'}}>
                                        <option value="">- Select Reason -</option>
                                        @php
                                            foreach($data['provenance_reason'] as $key => $val) {
                                                if($val['name'] == 'Other') {
                                                    $item = $data['provenance_reason'][$key];
                                                    unset($data['provenance_reason'][$key]);
                                                    array_push($data['provenance_reason'], $item);
                                                    break;
                                                }
                                            }
                                        @endphp
                                        @foreach($data['provenance_reason'] as $provenance_reason)
                                            <option
                                                value="{{$provenance_reason['id']}}"{{ (!empty($inspection->provenance_reason) && $provenance_reason['id'] == $inspection->provenance_reason) ? ' selected' : ''}}>{{$provenance_reason['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Additional Notes --}}
                <div class="qa-ctr mb-4">
                    <div class="vstack gap-2">
                        <div class="ff-medium fs-14">
                            Additional Notes
                        </div>
                        <div class="w-411">
                            @if($disabled_provenance)
                                <textarea class="form-control" id="provenanceObjectAdditionalNotes" rows="3"
                                          placeholder="Add text here ..."
                                          disabled>{{ !empty($inspection->provenance_additional_notes) ? $inspection->provenance_additional_notes : '' }}</textarea>
                            @else
                                <textarea name="provenanceObjectAdditionalNotes" class="form-control"
                                          id="provenance_additionalnotes" rows="3"
                                          placeholder="Add text here ...">{{ !empty($inspection->provenance_additional_notes) ? $inspection->provenance_additional_notes : '' }}</textarea>
                            @endif
                        </div>
                    </div>
                </div>
            </section>
        </form>
    </section>
</section>
