@php
    $override_rejection_disabled = true;
    $approve_rejection_disabled = true;
    $reject_disabled = true;
    $approve_disabled = true;
    $user_id = auth()->user()->id;
    $show_button = true;

    $auth_edit_access = access()->hasAccess('authentication-request.edit');
    $inspection_edit_access = access()->hasAccess('inspection-request.edit');
    $asign_edit_access = access()->hasAccess('label-requests.edit');

    if ($data['status']['id'] == 'authentication') {
    if (
    !empty($data['authenticator_ids']) &&
    !empty($data['conservator_ids']) &&
    !empty($data['field_agent_ids']) &&
    !empty($data['service_provider_ids'])
    ) {
    if (
    !empty($data['inspection_date']) &&
    !empty($data['inspection_time']) &&
    !empty($data['visit_date']) &&
    !empty($data['visit_time'])
    ) {
    $authenticator_ids = explode(',', $data['authenticator_ids']);
    if (in_array($user_id, $authenticator_ids)) {
    $verify_status = $data['verify_status'];
    $verify_auction = array_column($verify_status['auction'] ?? [], 'verify');
    $verify_exhibition = array_column($verify_status['exhibition'] ?? [], 'verify');
    $verify_publication = array_column($verify_status['publication'] ?? [], 'verify');
    if (
    in_array('true', $verify_auction) ||
    in_array('true', $verify_exhibition) ||
    in_array('true', $verify_publication)
    ) {
    $reject_disabled = false;
    $approve_disabled = false;
    }
    }
    if (!$auth_edit_access) {
    $reject_disabled = true;
    $approve_disabled = true;
    }
    }
    }
    }

    if ($data['status']['id'] == 'inspection') {
    if (
    !empty($data['inspection_date']) &&
    !empty($data['inspection_time']) &&
    !empty($data['visit_date']) &&
    !empty($data['visit_time'])
    ) {
    $conservator_ids = $data['conservator_ids'] ? explode(',', $data['conservator_ids']) : [];
    if (in_array($user_id, $conservator_ids)) {
    if (!empty($data['inspection'])) {
    $inspection_data = $data['inspection'];

    // Object Condition
    $objectMatchImageUpload = $inspection_data->is_object_match_imageupload;
    $object_match_imageupload_reason = $inspection_data->object_match_imageupload_reason;
    $objectMatchImageUploadReasonNotes = $inspection_data->object_match_imageupload_reason_notes;
    $object_condition = $inspection_data->object_condition;

    $noticeableDamages = $inspection_data->is_object_noticeable_damages;
    $object_noticeable_damage_reason = $inspection_data->object_noticeable_damage_reason;
    $objectNoticeableDamageReasonNotes = $inspection_data->object_noticeable_damage_reason_notes;

    $asignProtectCondition = $inspection_data->is_object_asignprotect_condition;
    $object_asignprotect_condition_reason = $inspection_data->object_asignprotect_condition_reason;
    $asignProtectConditionReasonNotes = $inspection_data->object_asignprotect_condition_reason_notes;

    $surfaceSuitable = $inspection_data->is_object_surface_suitable;
    $surfaceLabelApplied = $inspection_data->object_surface_type;
    $material_frame = $inspection_data->object_material_frame;
    $materialFrameNotes = $inspection_data->object_material_frame_notes;
    $material_stretcher = $inspection_data->object_material_stretcher;
    $materialStretcherNotes = $inspection_data->object_material_stretcher_notes;
    $material_stand = $inspection_data->object_material_objectstand;
    $materialStandNotes = $inspection_data->object_material_objectstand_notes;
    $objectAdditionalNotes = $inspection_data->object_additional_notes;

    $object_surface_suitable_reason = $inspection_data->object_surface_suitable_reason;
    $objectSurfaceSuitableReasonNotes = $inspection_data->object_surface_suitable_reason_notes;
    $objectAdditionalReasonNotes = $inspection_data->object_additional_reason_notes;

    // Site Condition
    $adequatePhysicalSpace = $inspection_data->is_site_adequatephysical_taskcomplete;
    $site_adequatephysical_taskcomplete_reason =
    $inspection_data->site_adequatephysical_taskcomplete_reason;
    $adequatePhysicalSpaceReasonNotes =
    $inspection_data->site_adequatephysical_taskcomplete_reason_notes;
    $adequatePhysicalAlternativeSpace = $inspection_data->is_site_adequatephysical_alternativespace;
    $adequatePhysicalAlternativespaceNotes =
    $inspection_data->site_adequatephysical_alternativespace_notes;

    $smoothWorkflow = $inspection_data->is_site_smoothworkflow;
    $site_smoothworkflow_reason = $inspection_data->site_smoothworkflow_reason;
    $smoothWorkflowReasonNotes = $inspection_data->site_smoothworkflow_reason_notes;

    $entryPoints = $inspection_data->site_entry_points;
    $exitPoints = $inspection_data->site_exit_points;

    $lightingAdequate = $inspection_data->is_site_lighting_adequate;
    $site_lighting_adequate_reason = $inspection_data->site_lighting_adequate_reason;
    $lightingAdequateReasonNotes = $inspection_data->site_lighting_adequate_reason_notes;
    $lightingAdequateAlternativeSpace = $inspection_data->is_site_lighting_adequate_alternativespace;
    $lightingAdequateAlternativespaceNotes =
    $inspection_data->site_lighting_adequate_alternativespace_notes;

    $surroundingWorkSpace = $inspection_data->is_site_surrounding_workspace;
    $site_surrounding_workspace_reason = $inspection_data->site_surrounding_workspace_reason;
    $surroundingWorkSpaceReasonNotes = $inspection_data->site_surrounding_workspace_reason_notes;
    $surroundingWorkSpaceAlternativeSpace =
    $inspection_data->is_site_surrounding_workspace_alternativespace;
    $surroundingWorkSpaceAlternativespaceNotes =
    $inspection_data->site_surrounding_workspace_alternativespace_notes;

    $safetyProtocols = $inspection_data->is_site_safety_protocols;
    $emergencyExit = $inspection_data->site_emergency_exit;
    $securityRequirements = $inspection_data->site_security_requirements;
    $observationSecurity = $inspection_data->site_observation_security;

    $washroomAvailable = $inspection_data->is_site_washroom_available;
    $locatedAndAccessed = $inspection_data->site_washroom_located_accessed;
    $nearestWashroom = $inspection_data->site_washroom_located_nearest;

    $networkCoverage = $inspection_data->is_site_network_coverage;
    $alternateAvailableNetwork = $inspection_data->site_alternate_available_network;

    $siteAdditionalNotes = $inspection_data->site_additional_notes;

    $applySiteCondition = $inspection_data->is_site_condition_checked;

    $checkConditionForApprove =
    $objectMatchImageUpload == 1 &&
    $object_condition &&
    ($noticeableDamages == 0 ||
    ($noticeableDamages == 1 &&
    $object_noticeable_damage_reason &&
    ($object_noticeable_damage_reason != 22 ||
    $object_noticeable_damage_reason != 26 ||
    $object_noticeable_damage_reason != 29)) ||
    ($noticeableDamages == 1 &&
    $object_noticeable_damage_reason &&
    ($object_noticeable_damage_reason == 22 ||
    $object_noticeable_damage_reason == 26 ||
    $object_noticeable_damage_reason == 29) &&
    $objectNoticeableDamageReasonNotes != '')) &&
    $asignProtectCondition == 1 &&
    (($surfaceSuitable == 1 &&
    $surfaceLabelApplied != null &&
    ($surfaceLabelApplied == 'Canvas' ||
    (($material_frame && $material_frame != 12) ||
    ($material_frame && $material_frame == 12 && $materialFrameNotes != '') ||
    ($material_stretcher && $material_stretcher != 14) ||
    ($material_stretcher &&
    $material_stretcher == 14 &&
    $materialStretcherNotes != '') ||
    ($material_stand && $material_stand != 25) ||
    ($material_stand && $material_stand == 25 && $materialStandNotes != ''))) &&
    $objectAdditionalNotes != '') ||
    ((($surfaceSuitable == 0 &&
    $object_surface_suitable_reason &&
    $object_surface_suitable_reason != '47') ||
    ($surfaceSuitable == 0 &&
    $object_surface_suitable_reason &&
    $object_surface_suitable_reason == '47' &&
    $objectSurfaceSuitableReasonNotes != '')) &&
    $objectAdditionalReasonNotes != '')) &&
    ($adequatePhysicalSpace == 1 ||
    (($adequatePhysicalSpace == 0 &&
    $site_adequatephysical_taskcomplete_reason &&
    $site_adequatephysical_taskcomplete_reason != 8 &&
    $adequatePhysicalAlternativeSpace == 1 &&
    $adequatePhysicalAlternativespaceNotes != '') ||
    ($adequatePhysicalAlternativeSpace == 0 && $adequatePhysicalAlternativeSpace != '')) ||
    (($adequatePhysicalSpace == 0 &&
    $site_adequatephysical_taskcomplete_reason &&
    $site_adequatephysical_taskcomplete_reason == 8 &&
    $adequatePhysicalSpaceReasonNotes != '' &&
    $adequatePhysicalAlternativeSpace == 1 &&
    $adequatePhysicalAlternativespaceNotes != '') ||
    ($adequatePhysicalAlternativeSpace == 0 && $adequatePhysicalAlternativeSpace != ''))) &&
    ($smoothWorkflow == 1 ||
    (($smoothWorkflow == 0 &&
    $site_smoothworkflow_reason &&
    $site_smoothworkflow_reason != 22) ||
    ($smoothWorkflow == 0 &&
    $site_smoothworkflow_reason &&
    $site_smoothworkflow_reason == 22 &&
    $smoothWorkflowReasonNotes != ''))) &&
    $entryPoints != '' &&
    $exitPoints != '' &&
    ($lightingAdequate == 1 ||
    (($lightingAdequate == 0 &&
    $site_lighting_adequate_reason &&
    $site_lighting_adequate_reason != 13 &&
    $lightingAdequateAlternativeSpace == 1 &&
    $lightingAdequateAlternativespaceNotes != '') ||
    ($lightingAdequateAlternativeSpace == 0 && $lightingAdequateAlternativeSpace != '')) ||
    (($lightingAdequate == 0 &&
    $site_lighting_adequate_reason &&
    $site_lighting_adequate_reason == 13 &&
    $lightingAdequateReasonNotes != '' &&
    $lightingAdequateAlternativeSpace == 1 &&
    $lightingAdequateAlternativespaceNotes != '') ||
    ($lightingAdequateAlternativeSpace == 0 && $lightingAdequateAlternativeSpace != ''))) &&
    ($surroundingWorkSpace == 1 ||
    (($surroundingWorkSpace == 0 &&
    $site_surrounding_workspace_reason &&
    $site_surrounding_workspace_reason != 21 &&
    $surroundingWorkSpaceAlternativeSpace == 1 &&
    $surroundingWorkSpaceAlternativespaceNotes != '') ||
    ($surroundingWorkSpaceAlternativeSpace == 0 &&
    $surroundingWorkSpaceAlternativeSpace != '')) ||
    (($surroundingWorkSpace == 0 &&
    $site_surrounding_workspace_reason &&
    $site_surrounding_workspace_reason == 21 &&
    $surroundingWorkSpaceReasonNotes != '' &&
    $surroundingWorkSpaceAlternativeSpace == 1 &&
    $surroundingWorkSpaceAlternativespaceNotes != '') ||
    ($surroundingWorkSpaceAlternativeSpace == 0 &&
    $surroundingWorkSpaceAlternativeSpace != ''))) &&
    (($safetyProtocols == 1 && $emergencyExit != '' && $securityRequirements != '') ||
    ($safetyProtocols == 0 && $safetyProtocols != '' && $observationSecurity != '')) &&
    (($washroomAvailable == 1 && $locatedAndAccessed != '') ||
    ($washroomAvailable == 0 && $washroomAvailable != '' && $nearestWashroom != '')) &&
    ($networkCoverage == 1 ||
    ($networkCoverage == 0 && $networkCoverage != '' && $alternateAvailableNetwork != '')) &&
    $siteAdditionalNotes != '';// && $applySiteCondition == 1;

    if ($checkConditionForApprove) {
    $approve_disabled = false;
    }

    $checkConditionForReject =
    ($objectMatchImageUpload != 1 &&
    $object_match_imageupload_reason &&
    $object_match_imageupload_reason != 15) ||
    ($objectMatchImageUpload != 1 &&
    $object_match_imageupload_reason == 15 &&
    $objectMatchImageUploadReasonNotes != '') ||
    (($objectMatchImageUpload == 1 &&
    $object_asignprotect_condition_reason &&
    $object_asignprotect_condition_reason != 38) ||
    ($objectMatchImageUpload == 1 &&
    ($object_asignprotect_condition_reason =
    38 && $asignProtectConditionReasonNotes != '') &&
    ($asignProtectCondition != 1 || $asignProtectCondition == 'undefined')));

    if ($checkConditionForReject) {
    $reject_disabled = false;
    }
    }
    }

    if (!$inspection_edit_access) {
    $reject_disabled = true;
    $approve_disabled = true;
    }
    }
    }
    $show_labelling_button = false;
    if ($data['status']['id'] == 'asign-protect') {
    $show_labelling_button = true;

    $start_label_text = "Start Labelling";
   if($data['reference_img_url']) {
    $start_label_text = "Continue Labelling";
   }

    $field_agent_ids = $data['field_agent_ids'] ? explode(',', $data['field_agent_ids']) : [];
    if (in_array($user_id, $field_agent_ids)) {
    $reject_disabled = false;
    $approve_disabled = false;
    }
    if (!$asign_edit_access) {
    $reject_disabled = true;
    $approve_disabled = true;
    }
    if(isset($data['button_verify'])){
        if(!$data['button_verify']){
            $approve_disabled = true;
        }
    }
    }

    if ($data['status']['label'] == 'Review') {
    if ($user_id == $data['reviewer_id']) {
    $override_rejection_disabled = false;
    $approve_rejection_disabled = false;
    }
    }

    if ($data['status']['id'] == 'approved' || $data['status']['id'] == 'rejected') {
    $show_button = false;
    }
@endphp

<div class="alt-header-left">
    <h4>Request ID: {{ $data['request_id'] }}
        <span class="{{ $data['status']['color'] }} statusCtr">{{ $data['status']['label'] }}</span>
    </h4>
    <p>Customer ID: {{ $data['customer_id'] }}</p>
</div>
<div class="alt-header-right">
    <div class="hstack gap-1">
        @if ($show_button)
            @if ($data['status']['label'] == 'Review')
                <div class="p-1">
                    @if ($override_rejection_disabled)
                        <button type="button" disabled class="btn btn-outline-secondary btn-lg">Override Rejection
                        </button>
                    @else
                        <button type="button" data-bs-toggle="modal" data-bs-target="#rejectOverrideModal"
                                class="btn btn-outline-secondary btn-lg">Override Rejection
                        </button>
                    @endif
                </div>
                <div class="p-1">
                    @if ($approve_rejection_disabled)
                        <button type="button" disabled class="btn btn-dark btn-lg">Approve Rejection
                        </button>
                    @else
                        <button type="button" data-bs-toggle="modal" data-bs-target="#rejectApproveModal"
                                class="btn btn-dark btn-lg">Approve Rejection
                        </button>
                    @endif
                </div>
            @else
                <div class="p-1">
                    @if ($reject_disabled)
                        <button type="button" disabled class="btn btn-outline-secondary btn-lg reject-btn">Reject
                        </button>
                    @else
                        <button type="button" data-bs-toggle="modal" data-bs-target="#rejectProtectModal"
                                class="btn btn-outline-secondary btn-lg reject-btn">Reject
                        </button>
                    @endif
                </div>
                <div class="p-1">
                    @if($show_labelling_button)
                        <span class="d-inline-block" id="startLabel" data-bs-toggle="tooltip" data-bs-placement="bottom"  title="{{$start_label_text == "Start Labelling" && $approve_disabled ? 'Verify all object details to start labelling' : ''}}">
                    @if ($approve_disabled)
                                <button type="button" disabled class="btn btn-dark btn-lg startLabel">
                                   {{$start_label_text}}
                            </button>
                            @else
                                <button id="start_labelling" type="button" class="btn btn-dark btn-lg startLabel">
                                   {{$start_label_text}}
                                </button>
                            @endif
                        </span>
                    @else
                        @if ($approve_disabled)
                            <button type="button" disabled class="btn btn-dark btn-lg">Approve
                            </button>
                        @else
                            <button id="approveBtn" type="button" data-bs-toggle="modal" data-bs-target="#approveModal"
                                    class="btn btn-dark btn-lg">Approve
                            </button>
                        @endif
                    @endif
                </div>
            @endif
        @endif
        <div class="p-1">

        </div>
    </div>
    {{--    <span class="btn btn-dark btn-lg" id="start_labelling">Start Labelling</span>--}}
</div>
