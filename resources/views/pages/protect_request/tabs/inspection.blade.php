@php
  $inspection = $data['inspection'];

  $disabled_inspection = true;
  $conservator_edit_access = access()->hasAccess('inspection-request.edit');
  if($data['status']['id'] == 'inspection') {
    $conservator_ids = $data['conservator_ids'] ? explode(',', $data['conservator_ids']) : [];
    if(in_array(auth()->user()->id, $conservator_ids)) {
        $disabled_inspection = false;
    }
    if(!$conservator_edit_access) {
      $disabled_inspection = true;
    }
  }
@endphp
<section class="inspectiontab-ctr">
  <form id="protect-request-inspection-form" enctype="multipart/form-data" method="post"{{--onchange="inspectionFormValidate()"--}}>
    @csrf
    <section class="w-411" style="width: 100% !important;">
      <div class="ff-medium fs-24 mb-4">
        Object Condition
      </div>
      {{-- Does the object match the image uploaded in the request? --}}
      <div class="inspectionq_omiu">
        <div class="qa-ctr mb-4">
          <div class="vstack gcondition-2 gap-2">
            <div class="ff-medium fs-14">
              Does the object match the image uploaded in the request?
            </div>
            <div class="hstack gap-5 ps-3">
              <div class="inspection-ctr">
                @if($disabled_inspection)
                  <input class="form-check-input" type="radio" id="object_match_yes"{{ !empty($inspection->is_object_match_imageupload) ? ' checked' : '' }} disabled>
                @else
                  <input class="form-check-input" type="radio" name="objectMatchImageUpload" id="object_match_yes" value="1"{{ !empty($inspection->is_object_match_imageupload) ? ' checked' : '' }}>
                @endif
                <label class="radio-label" for="object_match_yes">Yes</label>
              </div>
              <div class="inspection-ctr">
                @if($disabled_inspection)
                  <input class="form-check-input" type="radio" id="object_match_no"{{ isset($inspection->is_object_match_imageupload) && empty($inspection->is_object_match_imageupload) ? ' checked' : '' }} disabled>
                @else
                  <input class="form-check-input" type="radio" name="objectMatchImageUpload" id="object_match_no" value="0"{{ isset($inspection->is_object_match_imageupload) && empty($inspection->is_object_match_imageupload) ? ' checked' : '' }}>
                @endif
                <label class="radio-label" for="object_match_no">No</label>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="inspectionq_omiu_divhide_no inspectionq_omiu_reason" style="display: {{ isset($inspection->is_object_match_imageupload) && empty($inspection->is_object_match_imageupload) ? 'block' : 'none' }}">
        <div class="qa-ctr mb-4">
          <div class="vstack gap-2">
            <div class="ff-medium fs-14">
              Why? Please share the reason
            </div>
            <div>
              <div class="w100Select inspection-select-width inspection-select">
                <select data-placeholder="Select Reason" class="form-select select2Box1 object_match_imageupload_reason"{{($disabled_inspection) ? ' disabled' : ' name=objectMatchImageUploadReason'}}>
                  <option value="">- Select Reason -</option>
                  @php
                    foreach($data['inspection_object_condition_no'] as $key => $val) {
                      if($val['name'] == 'Other') {
                          $item = $data['inspection_object_condition_no'][$key];
                          unset($data['inspection_object_condition_no'][$key]);
                          array_push($data['inspection_object_condition_no'], $item); 
                          break;
                      }
                    }
                  @endphp
                  @foreach($data['inspection_object_condition_no'] as $object_match_imageupload_reason)
                    <option value="{{$object_match_imageupload_reason['id']}}"{{ (!empty($inspection->object_match_imageupload_reason) && $object_match_imageupload_reason['id'] == $inspection->object_match_imageupload_reason) ? ' selected' : ''}}>{{$object_match_imageupload_reason['name'] }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
        </div>

        <div class="inspectionq_omiu_reason_divhide" style="display: {{ (!empty($inspection->object_match_imageupload_reason) && $inspection->object_match_imageupload_reason == 15 && empty($inspection->is_object_match_imageupload)) ? 'block' : 'none' }}">
          <div class="qa-ctr mb-4" style="margin-top: -10px;">
            <div class="vstack gap-2">
              <div class="w-411">
                @if($disabled_inspection)
                  <textarea class="form-control" id="objectMatchImageUploadReasonNotes" rows="3" placeholder="Add text here ..." disabled>{{ !empty($inspection->object_match_imageupload_reason_notes) ? $inspection->object_match_imageupload_reason_notes : '' }}</textarea>
                @else
                  <textarea name="objectMatchImageUploadReasonNotes" class="form-control" id="objectMatchImageUploadReasonNotes" rows="3" placeholder="Add text here ...">{{ !empty($inspection->object_match_imageupload_reason_notes) ? $inspection->object_match_imageupload_reason_notes : '' }}</textarea>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="inspectionq_omiu_divhide" style="display: {{ !empty($inspection->is_object_match_imageupload) ? 'block' : 'none' }}">
        {{-- What is the condition of the object?  Select box --}}
        <div class="qa-ctr mb-4">
          <div class="vstack gap-2">
            <div class="ff-medium fs-14">
              What is the condition of the object?
            </div>
            <div>
              <div class="w100Select inspection-select-width inspection-select">
                <select data-placeholder="Select Condition of the object" class="form-select select2Box1 object_condition"{{($disabled_inspection) ? ' disabled' : ' name=objectCondition'}}>
                  <option value="">- Select Condition of the object -</option>
                  @php
                    foreach($data['inspection_object_condition'] as $key => $val) {
                      if($val['name'] == 'Other') {
                          $item = $data['inspection_object_condition'][$key];
                          unset($data['inspection_object_condition'][$key]);
                          array_push($data['inspection_object_condition'], $item); 
                          break;
                      }
                    }
                  @endphp
                  @foreach($data['inspection_object_condition'] as $object_condition)
                    <option value="{{$object_condition['id']}}"{{ (!empty($inspection->object_condition) && $object_condition['id'] == $inspection->object_condition) ? ' selected' : ''}}>{{$object_condition['name'] }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
        </div>

        {{-- Are there any noticeable damages? --}}
        <div class="inspectionq_ond">
          <div class="qa-ctr mb-4">
            <div class="vstack gap-2">
              <div class="ff-medium fs-14">
                Are there any noticeable damages?
              </div>
              <div class="hstack gap-5 ps-3">
                <div class="inspection-ctr">
                  @if($disabled_inspection)
                    <input class="form-check-input" type="radio" id="damage-yes"{{ !empty($inspection->is_object_noticeable_damages) ? ' checked' : '' }} disabled>
                  @else
                    <input class="form-check-input" type="radio" name="noticeableDamages" id="damage-yes" value="1"{{ !empty($inspection->is_object_noticeable_damages) ? ' checked' : '' }}>
                  @endif
                  <label class="radio-label" for="damage-yes">Yes</label>
                </div>
                <div class="inspection-ctr">
                  @if($disabled_inspection)
                    <input class="form-check-input" type="radio" id="damage-no"{{ isset($inspection->is_object_noticeable_damages) && empty($inspection->is_object_noticeable_damages) ? ' checked' : '' }} disabled>
                  @else
                    <input class="form-check-input" type="radio" name="noticeableDamages" id="damage-no" value="0"{{ isset($inspection->is_object_noticeable_damages) && empty($inspection->is_object_noticeable_damages) ? ' checked' : '' }}>
                  @endif
                  <label class="radio-label" for="damage-no">No</label>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="inspectionq_ond_divhide inspectionq_ond_reason" style="display: {{ !empty($inspection->is_object_noticeable_damages) ? 'block' : 'none' }}">
          <div class="inspectionq_ond_reason_divhide">
            <div class="qa-ctr mb-4">
              <div class="vstack gap-2">
                <div class="ff-medium fs-14">
                  What are the damages and where?
                </div>
                <div>
                  <div class="w100Select inspection-select-width inspection-select">
                    <select data-placeholder="Select Reason" class="form-select select2Box1 object_noticeable_damage_reason"{{($disabled_inspection) ? ' disabled' : ' name=objectNoticeableDamageReason'}}>
                      <option value="">- Select Reason -</option>
                      @php
                        foreach($data['inspection_object_noticeable_damage_yes'] as $key => $val) {
                          if($val['name'] == 'Other') {
                              $item = $data['inspection_object_noticeable_damage_yes'][$key];
                              unset($data['inspection_object_noticeable_damage_yes'][$key]);
                              array_push($data['inspection_object_noticeable_damage_yes'], $item); 
                              break;
                          }
                        }
                      @endphp
                      @foreach($data['inspection_object_noticeable_damage_yes'] as $object_noticeable_damage_reason)
                        <option value="{{$object_noticeable_damage_reason['id']}}"{{ (!empty($inspection->object_noticeable_damage_reason) && $object_noticeable_damage_reason['id'] == $inspection->object_noticeable_damage_reason) ? ' selected' : ''}}>{{$object_noticeable_damage_reason['name'] }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="inspectionq_ond_reason_notes_divhide" style="display: {{ (!empty($inspection->object_noticeable_damage_reason) && ($inspection->object_noticeable_damage_reason == 22 || $inspection->object_noticeable_damage_reason == 26 || $inspection->object_noticeable_damage_reason == 29) && !empty($inspection->is_object_noticeable_damages)) ? 'block' : 'none' }}">
            <div class="qa-ctr mb-4" style="margin-top: -10px;">
              <div class="vstack gap-2">
                <div class="w-411">
                  @if($disabled_inspection)
                    <textarea class="form-control" id="objectNoticeableDamageReasonNotes" rows="3" placeholder="Add text here ..." disabled>{{ !empty($inspection->object_noticeable_damage_reason_notes) ? $inspection->object_noticeable_damage_reason_notes : '' }}</textarea>
                  @else
                    <textarea name="objectNoticeableDamageReasonNotes" class="form-control" id="objectNoticeableDamageReasonNotes" rows="3" placeholder="Add text here ...">{{ !empty($inspection->object_noticeable_damage_reason_notes) ? $inspection->object_noticeable_damage_reason_notes : '' }}</textarea>
                  @endif
                </div>
              </div>
            </div>
          </div>

          <div class="inspectionq_ond_reason_images_divhide inspectionq_ond_damage_images" style="display: {{ (!empty($inspection->object_noticeable_damage_reason) && $inspection->object_noticeable_damage_reason == 29 && !empty($inspection->is_object_noticeable_damages)) ? 'block' : 'none' }}">
            <div class="qa-ctr mb-3">
              <div class="vstack gap-3">
                <div class="ff-medium fs-14">
                  Upload an image of the damage
                </div>
                <section>
                  <article class="edit-wrapper">
                    <div class="damage-image-preview image-preview wrap-image-viewer">
                      @if(!empty($data['inspection']['object_noticeable_damage_reason_images']) && $data['inspection']['object_noticeable_damage_reason_images'] != '[]')
                        <div class="image-preview-list damage-image-preview-list">
                          @foreach(json_decode($data['inspection']['object_noticeable_damage_reason_images']) as $object_damage_images)
                            <a href="{{\App\Helpers\UtilsHelper::getStoragePath().'artworks/inspection/'.$object_damage_images}}" class="inspection_view_box_1">
                              <img src="{{\App\Helpers\UtilsHelper::getStoragePath().'artworks/inspection/'.$object_damage_images}}" alt="{{$object_damage_images}}" class="img-fluid">
                            </a>
                            <!-- <a href="{{'http://localhost/asignartnew/storage/app/'.$object_damage_images}}" class="inspection_view_box_1">
                              <img src="{{'http://localhost/asignartnew/storage/app/'.$object_damage_images}}" alt="{{$object_damage_images}}" class="img-fluid">
                            </a> -->
                          @endforeach
                        </div>
                      @else
                        <div class="image-preview-list damage-image-preview-list alt"></div>
                      @endif
                      <div class="upload-btn-wrapper" href="#addImageModal-2"{{($disabled_inspection) ? " style=opacity:0.5" : ' data-bs-toggle=modal'}}>
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        <span>Add Image</span>
                      </div>
                    </div>
                  </article>
                </section>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{--Is the object in a condition to proceed with Asign Protect+?  --}}
      <div class="inspectionq_apc" style="display: {{ !empty($inspection->is_object_match_imageupload) ? 'block' : 'none' }}">
        <div class="qa-ctr mb-4">
          <div class="vstack gap-2">
            <div class="ff-medium fs-14">
              Is the object in a condition to proceed with Asign Protect+?
            </div>
            <div class="hstack gap-5 ps-3">
              <div class="inspection-ctr">
                @if($disabled_inspection)
                  <input class="form-check-input" type="radio" id="ap-yes"{{ !empty($inspection->is_object_asignprotect_condition) ? ' checked' : '' }} disabled>
                @else
                  <input class="form-check-input" type="radio" name="asignProtectCondition" id="ap-yes" value="1"{{ !empty($inspection->is_object_asignprotect_condition) ? ' checked' : '' }}>
                @endif
                <label class="radio-label" for="ap-yes">Yes</label>
              </div>
              <div class="inspection-ctr">
                @if($disabled_inspection)
                  <input class="form-check-input" type="radio" id="ap-no"{{ isset($inspection->is_object_asignprotect_condition) && empty($inspection->is_object_asignprotect_condition) ? ' checked' : '' }} disabled>
                @else
                  <input class="form-check-input" type="radio" name="asignProtectCondition" id="ap-no" value="0"{{ isset($inspection->is_object_asignprotect_condition) && empty($inspection->is_object_asignprotect_condition) ? ' checked' : '' }}>
                @endif
                <label class="radio-label" for="ap-no">No</label>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="inspectionq_apc_divhide_no inspectionq_apc_reason" style="display: {{ !empty($inspection->is_object_match_imageupload) && (isset($inspection->is_object_asignprotect_condition) && empty($inspection->is_object_asignprotect_condition)) ? 'block' : 'none' }}">
        <div class="inspectionq_apc_reason_divhide">
          <div class="qa-ctr mb-4">
            <div class="vstack gap-2">
              <div class="ff-medium fs-14">
                What is the reason?
              </div>
              <div>
                <div class="w100Select inspection-select-width inspection-select">
                  <select data-placeholder="Select Reason" class="form-select select2Box1 object_asignprotect_condition_reason"{{($disabled_inspection) ? ' disabled' : ' name=asignProtectConditionReason'}}>
                    <option value="">- Select Reason -</option>
                    @php
                      foreach($data['inspection_object_asign_protect_no'] as $key => $val) {
                        if($val['name'] == 'Other') {
                            $item = $data['inspection_object_asign_protect_no'][$key];
                            unset($data['inspection_object_asign_protect_no'][$key]);
                            array_push($data['inspection_object_asign_protect_no'], $item); 
                            break;
                        }
                      }
                    @endphp
                    @foreach($data['inspection_object_asign_protect_no'] as $inspection_object_asign_protect_reason)
                      <option value="{{$inspection_object_asign_protect_reason['id']}}"{{ (!empty($inspection->object_asignprotect_condition_reason) && $inspection_object_asign_protect_reason['id'] == $inspection->object_asignprotect_condition_reason) ? ' selected' : ''}}>{{$inspection_object_asign_protect_reason['name'] }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="inspectionq_apc_reason_notes_divhide" style="display: {{ (!empty($inspection->object_asignprotect_condition_reason) && $inspection->object_asignprotect_condition_reason == 38) ? 'block' : 'none' }}">
          <div class="qa-ctr mb-4" style="margin-top: -10px;">
            <div class="vstack gap-2">
              <div class="w-411">
                @if($disabled_inspection)
                  <textarea class="form-control" id="asignProtectConditionReasonNotes" rows="3" placeholder="Add text here ..." disabled>{{ !empty($inspection->object_asignprotect_condition_reason_notes) ? $inspection->object_asignprotect_condition_reason_notes : '' }}</textarea>
                @else
                  <textarea name="asignProtectConditionReasonNotes" class="form-control" id="asignProtectConditionReasonNotes" rows="3" placeholder="Add text here ...">{{ !empty($inspection->object_asignprotect_condition_reason_notes) ? $inspection->object_asignprotect_condition_reason_notes : '' }}</textarea>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- Is the surface condition suitable for label application? --}}
      <div class="inspectionq_apc_divhide inspectionq_ss" style="display: {{ !empty($inspection->is_object_match_imageupload) ? 'block' : 'none' }}">
        <div class="qa-ctr mb-4">
          <div class="vstack gap-2">
            <div class="ff-medium fs-14">
              Is the surface condition suitable for label application?
            </div>
            <div class="hstack gap-5 ps-3">
              <div class="inspection-ctr">
                @if($disabled_inspection)
                  <input class="form-check-input" type="radio" id="condition-yes"{{ !empty($inspection->is_object_surface_suitable) ? ' checked' : '' }} disabled>
                @else
                  <input class="form-check-input" type="radio" name="surfaceSuitable" id="condition-yes" value="1"{{ !empty($inspection->is_object_surface_suitable) ? ' checked' : '' }}>
                @endif
                <label class="radio-label" for="condition-yes">Yes</label>
              </div>
              <div class="inspection-ctr">
                @if($disabled_inspection)
                  <input class="form-check-input" type="radio" id="condition-no"{{ isset($inspection->is_object_surface_suitable) && empty($inspection->is_object_surface_suitable) ? ' checked' : '' }} disabled>
                @else
                  <input class="form-check-input" type="radio" name="surfaceSuitable" id="condition-no" value="0"{{ isset($inspection->is_object_surface_suitable) && empty($inspection->is_object_surface_suitable) ? ' checked' : '' }}>
                @endif
                <label class="radio-label" for="condition-no">No</label>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="inspectionq_ss_divhide" style="display: {{ !empty($inspection->is_object_match_imageupload) && !empty($inspection->is_object_surface_suitable) ? 'block' : 'none' }}">
        {{-- What is the surface type where the label will be applied? --}}
        <div class="qa-ctr mb-4">
          <div class="vstack gap-2">
            <div class="ff-medium fs-14">
              What is the surface type where the label will be applied?
            </div>
            <div class="hstack gap-5 ps-3">
              <div class="inspection-ctr">
                @if($disabled_inspection)
                  <input class="form-check-input" type="radio" id="surface-canvas"{{ (!empty($inspection->object_surface_type) && $inspection->object_surface_type == 'Canvas') ? ' checked' : '' }} disabled>
                @else
                  <input class="form-check-input" type="radio" name="surfaceLabelApplied" id="surface-canvas" value="Canvas"{{ (!empty($inspection->object_surface_type) && $inspection->object_surface_type == 'Canvas') ? ' checked' : '' }}>
                @endif
                <label class="radio-label" for="surface-canvas">Canvas</label>
              </div>
              <div class="inspection-ctr">
                @if($disabled_inspection)
                  <input class="form-check-input" type="radio" id="surface-frame"{{ (!empty($inspection->object_surface_type) && $inspection->object_surface_type == 'Frame') ? ' checked' : '' }} disabled>
                @else
                  <input class="form-check-input" type="radio" name="surfaceLabelApplied" id="surface-frame" value="Frame"{{ (!empty($inspection->object_surface_type) && $inspection->object_surface_type == 'Frame') ? ' checked' : '' }}>
                @endif
                <label class="radio-label" for="surface-frame">Frame</label>
              </div>
              <div class="inspection-ctr">
                @if($disabled_inspection)
                  <input class="form-check-input" type="radio" id="surface-stretcher"{{ (!empty($inspection->object_surface_type) && $inspection->object_surface_type == 'Stretcher') ? ' checked' : '' }} disabled>
                @else
                  <input class="form-check-input" type="radio" name="surfaceLabelApplied" id="surface-stretcher" value="Stretcher"{{ (!empty($inspection->object_surface_type) && $inspection->object_surface_type == 'Stretcher') ? ' checked' : '' }}>
                @endif
                <label class="radio-label" for="surface-stretcher">Stretcher</label>
              </div>
              <div class="inspection-ctr">
                @if($disabled_inspection)
                  <input class="form-check-input" type="radio" id="surface-objectstand"{{ (!empty($inspection->object_surface_type) && $inspection->object_surface_type == 'Object Stand') ? ' checked' : '' }} disabled>
                @else
                  <input class="form-check-input" type="radio" name="surfaceLabelApplied" id="surface-objectstand" value="Object Stand"{{ (!empty($inspection->object_surface_type) && $inspection->object_surface_type == 'Object Stand') ? ' checked' : '' }}>
                @endif
                <label class="radio-label" for="surface-objectstand">Object Stand</label>
              </div>
            </div>
          </div>
        </div>

        {{-- What material is the Frame?  Select box --}}
        <div class="inspectionq_mf_divhide inspectionq_mf" style="display: {{ (!empty($inspection->object_surface_type) && $inspection->object_surface_type == 'Frame') ? 'block' : 'none' }}">
          <div class="qa-ctr mb-4">
            <div class="vstack gap-2">
              <div class="ff-medium fs-14">
                What material is the Frame?
              </div>
              <div>
                <div class="w100Select inspection-select-width inspection-select">
                  <select data-placeholder="Select Frame Material" class="form-select select2Box1 material_frame"{{($disabled_inspection) ? ' disabled' : ' name=materialFrame'}}>
                    <option value="">- Select Frame Material -</option>
                    @php
                      foreach($data['inspection_surface_type_frame'] as $key => $val) {
                        if($val['name'] == 'Other') {
                            $item = $data['inspection_surface_type_frame'][$key];
                            unset($data['inspection_surface_type_frame'][$key]);
                            array_push($data['inspection_surface_type_frame'], $item); 
                            break;
                        }
                      }
                    @endphp
                    @foreach($data['inspection_surface_type_frame'] as $surface_type_frame)
                      <option value="{{$surface_type_frame['id']}}"{{ (!empty($inspection->object_material_frame) && $surface_type_frame['id'] == $inspection->object_material_frame) ? ' selected' : ''}}>{{$surface_type_frame['name']}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
          </div>

          <div class="inspectionq_mf_notes_divhide" style="display: {{ (!empty($inspection->object_material_frame) && $inspection->object_material_frame == 12 && !empty($inspection->is_object_surface_suitable)) ? 'block' : 'none' }}">
            <div class="qa-ctr mb-4" style="margin-top: -10px;">
              <div class="vstack gap-2">
                <div class="w-411">
                  @if($disabled_inspection)
                    <textarea class="form-control" id="materialFrameNotes" rows="3" placeholder="Add text here ..." disabled>{{ !empty($inspection->object_material_frame_notes) ? $inspection->object_material_frame_notes : '' }}</textarea>
                  @else
                    <textarea name="materialFrameNotes" class="form-control" id="materialFrameNotes" rows="3" placeholder="Add text here ...">{{ !empty($inspection->object_material_frame_notes) ? $inspection->object_material_frame_notes : '' }}</textarea>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- What material is the Stretcher?  Select box --}}
        <div class="inspectionq_ms_divhide inspectionq_ms" style="display: {{ (!empty($inspection->object_surface_type) && $inspection->object_surface_type == 'Stretcher') ? 'block' : 'none' }}">
          <div class="qa-ctr mb-4">
            <div class="vstack gap-2">
              <div class="ff-medium fs-14">
                What material is the Stretcher?
              </div>
              <div>
                <div class="w100Select inspection-select-width inspection-select">
                  <select data-placeholder="Select Stretcher Material" class="form-select select2Box1 material_stretcher"{{($disabled_inspection) ? ' disabled' : ' name=materialStretcher'}}>
                    <option value="">- Select Stretcher Material -</option>
                    @php
                      foreach($data['inspection_surface_type_strecher'] as $key => $val) {
                        if($val['name'] == 'Other') {
                            $item = $data['inspection_surface_type_strecher'][$key];
                            unset($data['inspection_surface_type_strecher'][$key]);
                            array_push($data['inspection_surface_type_strecher'], $item); 
                            break;
                        }
                      }
                    @endphp
                    @foreach($data['inspection_surface_type_strecher'] as $surface_type_strecher)
                      <option value="{{$surface_type_strecher['id']}}"{{ (!empty($inspection->object_material_stretcher) && $surface_type_strecher['id'] == $inspection->object_material_stretcher) ? ' selected' : ''}}>{{$surface_type_strecher['name']}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
          </div>

          <div class="inspectionq_ms_notes_divhide" style="display: {{ (!empty($inspection->object_material_stretcher) && $inspection->object_material_stretcher == 14 && !empty($inspection->is_object_surface_suitable)) ? 'block' : 'none' }}">
            <div class="qa-ctr mb-4" style="margin-top: -10px;">
              <div class="vstack gap-2">
                <div class="w-411">
                  @if($disabled_inspection)
                    <textarea class="form-control" id="materialStretcherNotes" rows="3" placeholder="Add text here ..." disabled>{{ !empty($inspection->object_material_stretcher_notes) ? $inspection->object_material_stretcher_notes : '' }}</textarea>
                  @else
                    <textarea name="materialStretcherNotes" class="form-control" id="materialStretcherNotes" rows="3" placeholder="Add text here ...">{{ !empty($inspection->object_material_stretcher_notes) ? $inspection->object_material_stretcher_notes : '' }}</textarea>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- What material is the Stand?  Select box --}}
        <div class="inspectionq_mst_divhide inspectionq_mst" style="display: {{ (!empty($inspection->object_surface_type) && $inspection->object_surface_type == 'Object Stand') ? 'block' : 'none' }}">
          <div class="qa-ctr mb-4">
            <div class="vstack gap-2">
              <div class="ff-medium fs-14">
                What material is the Stand?
              </div>
              <div>
                <div class="w100Select inspection-select-width inspection-select">
                  <select data-placeholder="Select Stand Material" class="form-select select2Box1 material_stand"{{($disabled_inspection) ? ' disabled' : ' name=materialStand'}}>
                    <option value="">- Select Stand Material -</option>
                    @php
                      foreach($data['inspection_surface_type_object_stand'] as $key => $val) {
                        if($val['name'] == 'Other') {
                            $item = $data['inspection_surface_type_object_stand'][$key];
                            unset($data['inspection_surface_type_object_stand'][$key]);
                            array_push($data['inspection_surface_type_object_stand'], $item); 
                            break;
                        }
                      }
                    @endphp
                    @foreach($data['inspection_surface_type_object_stand'] as $surface_type_object_stand)
                      <option value="{{$surface_type_object_stand['id']}}"{{ (!empty($inspection->object_material_objectstand) && $surface_type_object_stand['id'] == $inspection->object_material_objectstand) ? ' selected' : ''}}>{{$surface_type_object_stand['name']}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
          </div>

          <div class="inspectionq_mst_notes_divhide" style="display: {{ (!empty($inspection->object_material_objectstand) && $inspection->object_material_objectstand == 25 && !empty($inspection->is_object_surface_suitable)) ? 'block' : 'none' }}">
            <div class="qa-ctr mb-4" style="margin-top: -10px;">
              <div class="vstack gap-2">
                <div class="w-411">
                  @if($disabled_inspection)
                    <textarea class="form-control" id="materialStandNotes" rows="3" placeholder="Add text here ..." disabled>{{ !empty($inspection->object_material_objectstand_notes) ? $inspection->object_material_objectstand_notes : '' }}</textarea>
                  @else
                    <textarea name="materialStandNotes" class="form-control" id="materialStandNotes" rows="3" placeholder="Add text here ...">{{ !empty($inspection->object_material_objectstand_notes) ? $inspection->object_material_objectstand_notes : '' }}</textarea>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- Upload an image of where on the object the label can be applied --}}
        <div class="inspectionq_object_label qa-ctr mb-3">
          <div class="vstack gap-3">
            <div class="ff-medium fs-14">
            Upload an image of where on the object the label can be applied
            </div>
            <section>
              <article class="edit-wrapper">
                <div class="image-preview wrap-image-viewer">
                  @if(!empty($data['inspection']['object_label_images']) && $data['inspection']['object_label_images'] != '[]')
                    <div class="image-preview-list">
                      @foreach(json_decode($data['inspection']['object_label_images']) as $object_label_images)
                        <a href="{{\App\Helpers\UtilsHelper::getStoragePath().'artworks/inspection/'.$object_label_images}}" class="inspection_view_box">
                          <img src="{{\App\Helpers\UtilsHelper::getStoragePath().'artworks/inspection/'.$object_label_images}}" alt="{{$object_label_images}}" class="img-fluid">
                        </a>
                        <!-- <a href="{{'http://localhost/asignartnew/storage/app/'.$object_label_images}}" class="inspection_view_box">
                          <img src="{{'http://localhost/asignartnew/storage/app/'.$object_label_images}}" alt="{{$object_label_images}}" class="img-fluid">
                        </a> -->
                      @endforeach
                    </div>
                  @else
                    <div class="image-preview-list alt"></div>
                  @endif
                  <div class="upload-btn-wrapper" href="#imageObjectLabelModalToggle"{{($disabled_inspection) ? " style=opacity:0.5" : ' data-bs-toggle=modal'}}>
                    <i class="fa fa-plus" aria-hidden="true"></i>
                    <span>Add Image</span>
                    {{-- <input type="file" name="imageObjectLabel" class="image"/> --}}
                  </div>
                </div>
              </article>
            </section>
          </div>
        </div>
        
        {{-- Add additional notes regarding label placement --}}
        <div class="qa-ctr mb-4">
          <div class="vstack gap-2">
            <div class="ff-medium fs-14">
              Add additional notes regarding label placement
            </div>
            <div class="w-411">
              @if($disabled_inspection)
                <textarea class="form-control" id="objectAdditionalNotes" rows="3" placeholder="Add text here ..." disabled>{{ !empty($inspection->object_additional_notes) ? $inspection->object_additional_notes : '' }}</textarea>
              @else
                <textarea name="objectAdditionalNotes" class="form-control" id="objectAdditionalNotes" rows="3" placeholder="Add text here ...">{{ !empty($inspection->object_additional_notes) ? $inspection->object_additional_notes : '' }}</textarea>
              @endif
            </div>
          </div>
        </div>
      </div>

      <div class="inspectionq_ss_divhide_no inspectionq_ss_reason" style="display: {{ !empty($inspection->is_object_match_imageupload) && (isset($inspection->is_object_surface_suitable) && $inspection->is_object_surface_suitable == 0) ? 'block' : 'none' }}">
        <div class="inspectionq_ss_reason_divhide">
          <div class="qa-ctr mb-4">
            <div class="vstack gap-2">
              <div class="ff-medium fs-14">
                What is the reason?
              </div>
              <div>
                <div class="w100Select inspection-select-width inspection-select">
                  <select data-placeholder="Select Reason" class="form-select select2Box1 object_surface_suitable_reason"{{($disabled_inspection) ? ' disabled' : ' name=objectSurfaceSuitableReason'}}>
                    <option value="">- Select Reason -</option>
                    @php
                      foreach($data['inspection_object_surface_suitable_no'] as $key => $val) {
                        if($val['name'] == 'Other') {
                            $item = $data['inspection_object_surface_suitable_no'][$key];
                            unset($data['inspection_object_surface_suitable_no'][$key]);
                            array_push($data['inspection_object_surface_suitable_no'], $item); 
                            break;
                        }
                      }
                    @endphp
                    @foreach($data['inspection_object_surface_suitable_no'] as $object_surface_suitable_reason)
                      <option value="{{$object_surface_suitable_reason['id']}}"{{ (!empty($inspection->object_surface_suitable_reason) && $object_surface_suitable_reason['id'] == $inspection->object_surface_suitable_reason) ? ' selected' : ''}}>{{$object_surface_suitable_reason['name'] }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="inspectionq_ss_reason_notes_divhide" style="display: {{ (!empty($inspection->object_surface_suitable_reason) && $inspection->object_surface_suitable_reason == 47) ? 'block' : 'none' }}">
          <div class="qa-ctr mb-4" style="margin-top: -10px;">
            <div class="vstack gap-2">
              <div class="w-411">
                @if($disabled_inspection)
                  <textarea class="form-control" id="objectSurfaceSuitableReasonNotes" rows="3" placeholder="Add text here ..." disabled>{{ !empty($inspection->object_surface_suitable_reason_notes) ? $inspection->object_surface_suitable_reason_notes : '' }}</textarea>
                @else
                  <textarea name="objectSurfaceSuitableReasonNotes" class="form-control" id="objectSurfaceSuitableReasonNotes" rows="3" placeholder="Add text here ...">{{ !empty($inspection->object_surface_suitable_reason_notes) ? $inspection->object_surface_suitable_reason_notes : '' }}</textarea>
                @endif
              </div>
            </div>
          </div>
        </div>

        <div class="inspectionq_ss_reason_images_divhide inspectionq_ss_images" style="display: {{ !empty($inspection->is_object_match_imageupload) && (isset($inspection->is_object_surface_suitable) && $inspection->is_object_surface_suitable == 0) ? 'block' : 'none' }}">
          <div class="qa-ctr mb-3">
            <div class="vstack gap-3">
              <div class="ff-medium fs-14">
                Upload an image of the object
              </div>
              <section>
                <article class="edit-wrapper">
                  <div class="object-image-preview image-preview wrap-image-viewer">
                    @if(!empty($data['inspection']['object_surface_suitable_reason_images']) && $data['inspection']['object_surface_suitable_reason_images'] != '[]')
                      <div class="image-preview-list object-image-preview-list">
                        @foreach(json_decode($data['inspection']['object_surface_suitable_reason_images']) as $object_surface_suitable_reason_image)
                          <a href="{{\App\Helpers\UtilsHelper::getStoragePath().'artworks/inspection/'.$object_surface_suitable_reason_image}}" class="inspection_view_box_2">
                            <img src="{{\App\Helpers\UtilsHelper::getStoragePath().'artworks/inspection/'.$object_surface_suitable_reason_image}}" alt="{{$object_surface_suitable_reason_image}}" class="img-fluid">
                          </a>
                          <!-- <a href="{{'http://localhost/asignartnew/storage/app/'.$object_surface_suitable_reason_image}}" class="inspection_view_box_2">
                            <img src="{{'http://localhost/asignartnew/storage/app/'.$object_surface_suitable_reason_image}}" alt="{{$object_surface_suitable_reason_image}}" class="img-fluid">
                          </a> -->
                        @endforeach
                      </div>
                    @else
                      <div class="image-preview-list object-image-preview-list alt"></div>
                    @endif
                    <div class="upload-btn-wrapper" href="#addImageModal-3"{{($disabled_inspection) ? " style=opacity:0.5" : ' data-bs-toggle=modal'}}>
                      <i class="fa fa-plus" aria-hidden="true"></i>
                      <span>Add Image</span>
                    </div>
                  </div>
                </article>
              </section>
            </div>
          </div>
        </div>

        <div class="qa-ctr mb-4">
          <div class="vstack gap-2">
            <div class="ff-medium fs-14">
              Add additional notes regarding label placement
            </div>
            <div class="w-411">
              @if($disabled_inspection)
                <textarea class="form-control" id="objectAdditionalReasonNotes" rows="3" placeholder="Add text here ..." disabled>{{ !empty($inspection->object_additional_reason_notes) ? $inspection->object_additional_reason_notes : '' }}</textarea>
              @else
                <textarea name="objectAdditionalReasonNotes" class="form-control" id="objectAdditionalReasonNotes" rows="3" placeholder="Add text here ...">{{ !empty($inspection->object_additional_reason_notes) ? $inspection->object_additional_reason_notes : '' }}</textarea>
              @endif
            </div>
          </div>
        </div>
      </div>
    </section>


    {{-- line start --}}
    <hr class="hrline-inspection" />
    {{-- line end --}}


    <section class="w-411">
      <div class="ff-medium fs-24 mb-4">
        Site Condition
      </div>

      {{-- Is there adequate physical space for the team to comfortably complete their tasks? --}}
      <div class="inspectionq_aps">
        <div class="qa-ctr mb-4">
          <div class="vstack gap-2">
            <div class="ff-medium fs-14">
              Is there adequate physical space for the team to comfortably complete their tasks?
            </div>
            <div class="hstack gap-5 ps-3">
              <div class="inspection-ctr">
                @if($disabled_inspection)
                  <input class="form-check-input" type="radio" id="physical-yes"{{ !empty($inspection->is_site_adequatephysical_taskcomplete) ? ' checked' : '' }} disabled>
                @else
                  <input class="form-check-input" type="radio" name="adequatePhysicalSpace" id="physical-yes" value="1"{{ !empty($inspection->is_site_adequatephysical_taskcomplete) ? ' checked' : '' }}>
                @endif
                <label class="radio-label" for="physical-yes">Yes</label>
              </div>
              <div class="inspection-ctr">
                @if($disabled_inspection)
                  <input class="form-check-input" type="radio" id="physical-no"{{ isset($inspection->is_site_adequatephysical_taskcomplete) && empty($inspection->is_site_adequatephysical_taskcomplete) ? ' checked' : '' }} disabled>
                @else
                  <input class="form-check-input" type="radio" name="adequatePhysicalSpace" id="physical-no" value="0"{{ isset($inspection->is_site_adequatephysical_taskcomplete) && empty($inspection->is_site_adequatephysical_taskcomplete) ? ' checked' : '' }}>
                @endif
                <label class="radio-label" for="physical-no">No</label>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="inspectionq_aps_divhide inspectionq_aps_reason" style="display: {{ isset($inspection->is_site_adequatephysical_taskcomplete) && empty($inspection->is_site_adequatephysical_taskcomplete) ? 'block' : 'none' }}">
        <div class="inspectionq_aps_reason_divhide">
          <div class="qa-ctr mb-4">
            <div class="vstack gap-2">
              <div class="ff-medium fs-14">
                What is the reason?
              </div>
              <div>
                <div class="w100Select inspection-select-width inspection-select">
                  <select data-placeholder="Select Reason" class="form-select select2Box1 site_adequatephysical_taskcomplete_reason"{{($disabled_inspection) ? ' disabled' : ' name=adequatePhysicalSpaceReason'}}>
                    <option value="">- Select Reason -</option>
                    @php
                      foreach($data['inspection_site_adequatephysical_taskcomplete_no'] as $key => $val) {
                        if($val['name'] == 'Other') {
                            $item = $data['inspection_site_adequatephysical_taskcomplete_no'][$key];
                            unset($data['inspection_site_adequatephysical_taskcomplete_no'][$key]);
                            array_push($data['inspection_site_adequatephysical_taskcomplete_no'], $item); 
                            break;
                        }
                      }
                    @endphp
                    @foreach($data['inspection_site_adequatephysical_taskcomplete_no'] as $inspection_site_adequatephysical_taskcomplete_reason)
                      <option value="{{$inspection_site_adequatephysical_taskcomplete_reason['id']}}"{{ (!empty($inspection->site_adequatephysical_taskcomplete_reason) && $inspection_site_adequatephysical_taskcomplete_reason['id'] == $inspection->site_adequatephysical_taskcomplete_reason) ? ' selected' : ''}}>{{$inspection_site_adequatephysical_taskcomplete_reason['name'] }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="inspectionq_aps_reason_notes_divhide" style="display: {{ (!empty($inspection->site_adequatephysical_taskcomplete_reason) && $inspection->site_adequatephysical_taskcomplete_reason == 8 && empty($inspection->is_site_adequatephysical_taskcomplete)) ? 'block' : 'none' }}">
          <div class="qa-ctr mb-4" style="margin-top: -10px;">
            <div class="vstack gap-2">
              <div class="w-411">
                @if($disabled_inspection)
                  <textarea class="form-control" id="adequatePhysicalSpaceReasonNotes" rows="3" placeholder="Add text here ..." disabled>{{ !empty($inspection->site_adequatephysical_taskcomplete_reason_notes) ? $inspection->site_adequatephysical_taskcomplete_reason_notes : '' }}</textarea>
                @else
                  <textarea name="adequatePhysicalSpaceReasonNotes" class="form-control" id="adequatePhysicalSpaceReasonNotes" rows="3" placeholder="Add text here ...">{{ !empty($inspection->site_adequatephysical_taskcomplete_reason_notes) ? $inspection->site_adequatephysical_taskcomplete_reason_notes : '' }}</textarea>
                @endif
              </div>
            </div>
          </div>
        </div>

        <div class="inspectionq_aps_alternativespace_divhide inspectionq_aps_alternativespace" style="display: {{ empty($inspection->is_site_adequatephysical_taskcomplete) ? 'block' : 'none' }}">
          <div class="qa-ctr mb-4">
            <div class="vstack gap-2">
              <div class="ff-medium fs-14">
                Is there an alternative space that can be used?
              </div>
              <div class="hstack gap-5 ps-3">
                <div class="inspection-ctr">
                  @if($disabled_inspection)
                    <input class="form-check-input" type="radio" id="physical-alternative-yes"{{ !empty($inspection->is_site_adequatephysical_alternativespace) ? ' checked' : '' }} disabled>
                  @else
                    <input class="form-check-input" type="radio" name="adequatePhysicalAlternativeSpace" id="physical-alternative-yes" value="1"{{ !empty($inspection->is_site_adequatephysical_alternativespace) ? ' checked' : '' }}>
                  @endif
                  <label class="radio-label" for="physical-alternative-yes">Yes</label>
                </div>
                <div class="inspection-ctr">
                  @if($disabled_inspection)
                    <input class="form-check-input" type="radio" id="physical-alternative-no"{{ isset($inspection->is_site_adequatephysical_alternativespace) && empty($inspection->is_site_adequatephysical_alternativespace) ? ' checked' : '' }} disabled>
                  @else
                    <input class="form-check-input" type="radio" name="adequatePhysicalAlternativeSpace" id="physical-alternative-no" value="0"{{ isset($inspection->is_site_adequatephysical_alternativespace) && empty($inspection->is_site_adequatephysical_alternativespace) ? ' checked' : '' }}>
                  @endif
                  <label class="radio-label" for="physical-alternative-no">No</label>
                </div>
              </div>
            </div>
          </div>

          <div class="inspectionq_aps_alternativespace_notes_divhide" style="display: {{ (!empty($inspection->is_site_adequatephysical_alternativespace) && empty($inspection->is_site_adequatephysical_taskcomplete)) ? 'block' : 'none' }}">
            <div class="qa-ctr mb-4" style="margin-top: -10px;">
              <div class="vstack gap-2">
                <div class="w-411">
                  @if($disabled_inspection)
                    <textarea class="form-control" id="adequatePhysicalAlternativespaceNotes" rows="3" placeholder="Add text here ..." disabled>{{ !empty($inspection->site_adequatephysical_alternativespace_notes) ? $inspection->site_adequatephysical_alternativespace_notes : '' }}</textarea>
                  @else
                    <textarea name="adequatePhysicalAlternativespaceNotes" class="form-control" id="adequatePhysicalAlternativespaceNotes" rows="3" placeholder="Add text here ...">{{ !empty($inspection->site_adequatephysical_alternativespace_notes) ? $inspection->site_adequatephysical_alternativespace_notes : '' }}</textarea>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- Is the site accessible for the team to have a smooth workflow? --}}
      <div class="inspectionq_sw">
        <div class="qa-ctr mb-4">
          <div class="vstack gap-2">
            <div class="ff-medium fs-14">
              Is the site accessible for the team to have a smooth workflow?
            </div>
            <div class="hstack gap-5 ps-3">
              <div class="inspection-ctr">
                @if($disabled_inspection)
                  <input class="form-check-input" type="radio" id="accessible-yes"{{ !empty($inspection->is_site_smoothworkflow) ? ' checked' : '' }} disabled>
                @else
                  <input class="form-check-input" type="radio" name="smoothWorkflow" id="accessible-yes" value="1"{{ !empty($inspection->is_site_smoothworkflow) ? ' checked' : '' }}>
                @endif
                <label class="radio-label" for="accessible-yes">Yes</label>
              </div>
              <div class="inspection-ctr">
                @if($disabled_inspection)
                  <input class="form-check-input" type="radio" id="accessible-no"{{ isset($inspection->is_site_smoothworkflow) && empty($inspection->is_site_smoothworkflow) ? ' checked' : '' }} disabled>
                @else
                  <input class="form-check-input" type="radio" name="smoothWorkflow" id="accessible-no" value="0"{{ isset($inspection->is_site_smoothworkflow) && empty($inspection->is_site_smoothworkflow) ? ' checked' : '' }}>
                @endif
                <label class="radio-label" for="accessible-no">No</label>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="inspectionq_sw_divhide inspectionq_sw_reason" style="display: {{ isset($inspection->is_site_smoothworkflow) && empty($inspection->is_site_smoothworkflow) ? 'block' : 'none' }}">
        <div class="inspectionq_sw_reason_divhide">
          <div class="qa-ctr mb-4">
            <div class="vstack gap-2">
              <div class="ff-medium fs-14">
                What is the reason?
              </div>
              <div>
                <div class="w100Select inspection-select-width inspection-select">
                  <select data-placeholder="Select Reason" class="form-select select2Box1 site_smoothworkflow_reason"{{($disabled_inspection) ? ' disabled' : ' name=smoothWorkflowReason'}}>
                    <option value="">- Select Reason -</option>
                    @php
                      foreach($data['inspection_site_smoothworkflow_no'] as $key => $val) {
                        if($val['name'] == 'Other') {
                            $item = $data['inspection_site_smoothworkflow_no'][$key];
                            unset($data['inspection_site_smoothworkflow_no'][$key]);
                            array_push($data['inspection_site_smoothworkflow_no'], $item); 
                            break;
                        }
                      }
                    @endphp
                    @foreach($data['inspection_site_smoothworkflow_no'] as $inspection_site_smoothworkflow_reason)
                      <option value="{{$inspection_site_smoothworkflow_reason['id']}}"{{ (!empty($inspection->site_smoothworkflow_reason) && $inspection_site_smoothworkflow_reason['id'] == $inspection->site_smoothworkflow_reason) ? ' selected' : ''}}>{{$inspection_site_smoothworkflow_reason['name'] }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="inspectionq_sw_reason_notes_divhide" style="display: {{ (!empty($inspection->site_smoothworkflow_reason) && $inspection->site_smoothworkflow_reason == 22 && empty($inspection->is_site_smoothworkflow)) ? 'block' : 'none' }}">
          <div class="qa-ctr mb-4" style="margin-top: -10px;">
            <div class="vstack gap-2">
              <div class="w-411">
                @if($disabled_inspection)
                  <textarea class="form-control" id="smoothWorkflowReasonNotes" rows="3" placeholder="Add text here ..." disabled>{{ !empty($inspection->site_smoothworkflow_reason_notes) ? $inspection->site_smoothworkflow_reason_notes : '' }}</textarea>
                @else
                  <textarea name="smoothWorkflowReasonNotes" class="form-control" id="smoothWorkflowReasonNotes" rows="3" placeholder="Add text here ...">{{ !empty($inspection->site_smoothworkflow_reason_notes) ? $inspection->site_smoothworkflow_reason_notes : '' }}</textarea>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- What are the points of entry and exit for efficient navigation during the labelling process? (mention any spaces that should be avoided, etc.) --}}
      <div class="fs-14 ff-medium mb-3">
        What are the points of entry and exit for efficient navigation during the labelling
        process? (mention any spaces that should be avoided, etc.)
      </div>
      {{--Entry Points text area --}}
      <div class="qa-ctr mb-4">
        <div class="vstack gap-2">
          <div class="ff-medium fs-14">
            Entry Points
          </div>
          <div class="w-411">
            @if($disabled_inspection)
              <textarea class="form-control" id="entryPoints" rows="3" disabled>{{ !empty($inspection->site_entry_points) ? $inspection->site_entry_points : '' }}</textarea>
            @else
              <textarea name="entryPoints" class="form-control" id="entryPoints" rows="3" placeholder="Entrance gate on the main road">{{ !empty($inspection->site_entry_points) ? $inspection->site_entry_points : '' }}</textarea>
            @endif
          </div>
        </div>
      </div>
      {{--Exit Points text area --}}
      <div class="qa-ctr mb-4">
        <div class="vstack gap-2">
          <div class="ff-medium fs-14">
            Exit Points
          </div>
          <div class="w-411">
            @if($disabled_inspection)
              <textarea class="form-control" id="exitPoints" rows="3" disabled>{{ !empty($inspection->site_exit_points) ? $inspection->site_exit_points : '' }}</textarea>
            @else
              <textarea name="exitPoints" class="form-control" id="exitPoints" rows="3" placeholder="Entrance gate on the main road">{{ !empty($inspection->site_exit_points) ? $inspection->site_exit_points : '' }}</textarea>
            @endif
          </div>
        </div>
      </div>

      {{-- Is the lighting adequate? --}}
      <div class="inspectionq_la">
        <div class="qa-ctr mb-4">
          <div class="vstack gap-2">
            <div class="ff-medium fs-14">
              Is the lighting adequate?
            </div>
            <div class="hstack gap-5 ps-3">
              <div class="inspection-ctr">
                @if($disabled_inspection)
                  <input class="form-check-input" type="radio" id="adequate-yes"{{ !empty($inspection->is_site_lighting_adequate) ? ' checked' : '' }} disabled>
                @else
                  <input class="form-check-input" type="radio" name="lightingAdequate" id="adequate-yes" value="1"{{ !empty($inspection->is_site_lighting_adequate) ? ' checked' : '' }}>
                @endif
                <label class="radio-label" for="adequate-yes">Yes</label>
              </div>
              <div class="inspection-ctr">
                @if($disabled_inspection)
                  <input class="form-check-input" type="radio" id="adequate-no"{{ isset($inspection->is_site_lighting_adequate) && empty($inspection->is_site_lighting_adequate) ? ' checked' : '' }} disabled>
                @else
                  <input class="form-check-input" type="radio" name="lightingAdequate" id="adequate-no" value="0"{{ isset($inspection->is_site_lighting_adequate) && empty($inspection->is_site_lighting_adequate) ? ' checked' : '' }}>
                @endif
                <label class="radio-label" for="adequate-no">No</label>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="inspectionq_la_divhide inspectionq_la_reason" style="display: {{ isset($inspection->is_site_lighting_adequate) && empty($inspection->is_site_lighting_adequate) ? 'block' : 'none' }}">
        <div class="inspectionq_la_reason_divhide">
          <div class="qa-ctr mb-4">
            <div class="vstack gap-2">
              <div class="ff-medium fs-14">
                What is the reason?
              </div>
              <div>
                <div class="w100Select inspection-select-width inspection-select">
                  <select data-placeholder="Select Reason" class="form-select select2Box1 site_lighting_adequate_reason"{{($disabled_inspection) ? ' disabled' : ' name=lightingAdequateReason'}}>
                    <option value="">- Select Reason -</option>
                    @php
                      foreach($data['inspection_site_lighting_adequate_no'] as $key => $val) {
                        if($val['name'] == 'Other') {
                            $item = $data['inspection_site_lighting_adequate_no'][$key];
                            unset($data['inspection_site_lighting_adequate_no'][$key]);
                            array_push($data['inspection_site_lighting_adequate_no'], $item); 
                            break;
                        }
                      }
                    @endphp
                    @foreach($data['inspection_site_lighting_adequate_no'] as $inspection_site_lighting_adequate_reason)
                      <option value="{{$inspection_site_lighting_adequate_reason['id']}}"{{ (!empty($inspection->site_lighting_adequate_reason) && $inspection_site_lighting_adequate_reason['id'] == $inspection->site_lighting_adequate_reason) ? ' selected' : ''}}>{{$inspection_site_lighting_adequate_reason['name'] }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="inspectionq_la_reason_notes_divhide" style="display: {{ (!empty($inspection->site_lighting_adequate_reason) && $inspection->site_lighting_adequate_reason == 13 && empty($inspection->is_site_lighting_adequate)) ? 'block' : 'none' }}">
          <div class="qa-ctr mb-4" style="margin-top: -10px;">
            <div class="vstack gap-2">
              <div class="w-411">
                @if($disabled_inspection)
                  <textarea class="form-control" id="lightingAdequateReasonNotes" rows="3" placeholder="Add text here ..." disabled>{{ !empty($inspection->site_lighting_adequate_reason_notes) ? $inspection->site_lighting_adequate_reason_notes : '' }}</textarea>
                @else
                  <textarea name="lightingAdequateReasonNotes" class="form-control" id="lightingAdequateReasonNotes" rows="3" placeholder="Add text here ...">{{ !empty($inspection->site_lighting_adequate_reason_notes) ? $inspection->site_lighting_adequate_reason_notes : '' }}</textarea>
                @endif
              </div>
            </div>
          </div>
        </div>

        <div class="inspectionq_la_alternativespace_divhide inspectionq_la_alternativespace" style="display: {{ empty($inspection->is_site_lighting_adequate) ? 'block' : 'none' }}">
          <div class="qa-ctr mb-4">
            <div class="vstack gap-2">
              <div class="ff-medium fs-14">
                Is there an alternate space or lighting source available? 
              </div>
              <div class="hstack gap-5 ps-3">
                <div class="inspection-ctr">
                  @if($disabled_inspection)
                    <input class="form-check-input" type="radio" id="lighting-alternative-yes"{{ !empty($inspection->is_site_lighting_adequate_alternativespace) ? ' checked' : '' }} disabled>
                  @else
                    <input class="form-check-input" type="radio" name="lightingAdequateAlternativeSpace" id="lighting-alternative-yes" value="1"{{ !empty($inspection->is_site_lighting_adequate_alternativespace) ? ' checked' : '' }}>
                  @endif
                  <label class="radio-label" for="lighting-alternative-yes">Yes</label>
                </div>
                <div class="inspection-ctr">
                  @if($disabled_inspection)
                    <input class="form-check-input" type="radio" id="lighting-alternative-no"{{ isset($inspection->is_site_lighting_adequate_alternativespace) && empty($inspection->is_site_lighting_adequate_alternativespace) ? ' checked' : '' }} disabled>
                  @else
                    <input class="form-check-input" type="radio" name="lightingAdequateAlternativeSpace" id="lighting-alternative-no" value="0"{{ isset($inspection->is_site_lighting_adequate_alternativespace) && empty($inspection->is_site_lighting_adequate_alternativespace) ? ' checked' : '' }}>
                  @endif
                  <label class="radio-label" for="lighting-alternative-no">No</label>
                </div>
              </div>
            </div>
          </div>

          <div class="inspectionq_la_alternativespace_notes_divhide" style="display: {{ (!empty($inspection->is_site_lighting_adequate_alternativespace) && empty($inspection->is_site_lighting_adequate)) ? 'block' : 'none' }}">
            <div class="qa-ctr mb-4" style="margin-top: -10px;">
              <div class="vstack gap-2">
                <div class="w-411">
                  @if($disabled_inspection)
                    <textarea class="form-control" id="lightingAdequateAlternativespaceNotes" rows="3" placeholder="Add text here ..." disabled>{{ !empty($inspection->site_lighting_adequate_alternativespace_notes) ? $inspection->site_lighting_adequate_alternativespace_notes : '' }}</textarea>
                  @else
                    <textarea name="lightingAdequateAlternativespaceNotes" class="form-control" id="lightingAdequateAlternativespaceNotes" rows="3" placeholder="Add text here ...">{{ !empty($inspection->site_lighting_adequate_alternativespace_notes) ? $inspection->site_lighting_adequate_alternativespace_notes : '' }}</textarea>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- Is the surrounding work space environment in a good condition?  --}}
      <div class="inspectionq_swe">
        <div class="qa-ctr mb-4">
          <div class="vstack gap-2">
            <div class="ff-medium fs-14">
              Is the surrounding work space environment in a good condition?
            </div>
            <div class="hstack gap-5 ps-3">
              <div class="inspection-ctr">
                @if($disabled_inspection)
                  <input class="form-check-input" type="radio" id="environment-yes"{{ !empty($inspection->is_site_surrounding_workspace) ? ' checked' : '' }} disabled>
                @else
                  <input class="form-check-input" type="radio" name="surroundingWorkSpace" id="environment-yes" value="1"{{ !empty($inspection->is_site_surrounding_workspace) ? ' checked' : '' }}>
                @endif
                <label class="radio-label" for="environment-yes">Yes</label>
              </div>
              <div class="inspection-ctr">
                @if($disabled_inspection)
                  <input class="form-check-input" type="radio" id="environment-no"{{ isset($inspection->is_site_surrounding_workspace) && empty($inspection->is_site_surrounding_workspace) ? ' checked' : '' }} disabled>
                @else
                  <input class="form-check-input" type="radio" name="surroundingWorkSpace" id="environment-no" value="0"{{ isset($inspection->is_site_surrounding_workspace) && empty($inspection->is_site_surrounding_workspace) ? ' checked' : '' }}>
                @endif
                <label class="radio-label" for="environment-no">No</label>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="inspectionq_swe_divhide inspectionq_swe_reason" style="display: {{ isset($inspection->is_site_surrounding_workspace) && empty($inspection->is_site_surrounding_workspace) ? 'block' : 'none' }}">
        <div class="inspectionq_swe_reason_divhide">
          <div class="qa-ctr mb-4">
            <div class="vstack gap-2">
              <div class="ff-medium fs-14">
                What is the reason?
              </div>
              <div>
                <div class="w100Select inspection-select-width inspection-select">
                  <select data-placeholder="Select Reason" class="form-select select2Box1 site_surrounding_workspace_reason"{{($disabled_inspection) ? ' disabled' : ' name=surroundingWorkSpaceReason'}}>
                    <option value="">- Select Reason -</option>
                    @php
                      foreach($data['inspection_site_surrounding_workspace_no'] as $key => $val) {
                        if($val['name'] == 'Other') {
                            $item = $data['inspection_site_surrounding_workspace_no'][$key];
                            unset($data['inspection_site_surrounding_workspace_no'][$key]);
                            array_push($data['inspection_site_surrounding_workspace_no'], $item); 
                            break;
                        }
                      }
                    @endphp
                    @foreach($data['inspection_site_surrounding_workspace_no'] as $inspection_site_surrounding_workspace_reason)
                      <option value="{{$inspection_site_surrounding_workspace_reason['id']}}"{{ (!empty($inspection->site_surrounding_workspace_reason) && $inspection_site_surrounding_workspace_reason['id'] == $inspection->site_surrounding_workspace_reason) ? ' selected' : ''}}>{{$inspection_site_surrounding_workspace_reason['name'] }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="inspectionq_swe_reason_notes_divhide" style="display: {{ (!empty($inspection->site_surrounding_workspace_reason) && $inspection->site_surrounding_workspace_reason == 21 && empty($inspection->is_site_surrounding_workspace)) ? 'block' : 'none' }}">
          <div class="qa-ctr mb-4" style="margin-top: -10px;">
            <div class="vstack gap-2">
              <div class="w-411">
                @if($disabled_inspection)
                  <textarea class="form-control" id="surroundingWorkSpaceReasonNotes" rows="3" placeholder="Add text here ..." disabled>{{ !empty($inspection->site_surrounding_workspace_reason_notes) ? $inspection->site_surrounding_workspace_reason_notes : '' }}</textarea>
                @else
                  <textarea name="surroundingWorkSpaceReasonNotes" class="form-control" id="surroundingWorkSpaceReasonNotes" rows="3" placeholder="Add text here ...">{{ !empty($inspection->site_surrounding_workspace_reason_notes) ? $inspection->site_surrounding_workspace_reason_notes : '' }}</textarea>
                @endif
              </div>
            </div>
          </div>
        </div>

        <div class="inspectionq_swe_alternativespace_divhide inspectionq_swe_alternativespace" style="display: {{ empty($inspection->is_site_surrounding_workspace) ? 'block' : 'none' }}">
          <div class="qa-ctr mb-4">
            <div class="vstack gap-2">
              <div class="ff-medium fs-14">
                Is there an alternative space within the same location that can be used?
              </div>
              <div class="hstack gap-5 ps-3">
                <div class="inspection-ctr">
                  @if($disabled_inspection)
                    <input class="form-check-input" type="radio" id="surrounding_workspace-alternative-yes"{{ !empty($inspection->is_site_surrounding_workspace_alternativespace) ? ' checked' : '' }} disabled>
                  @else
                    <input class="form-check-input" type="radio" name="surroundingWorkSpaceAlternativeSpace" id="surrounding_workspace-alternative-yes" value="1"{{ !empty($inspection->is_site_surrounding_workspace_alternativespace) ? ' checked' : '' }}>
                  @endif
                  <label class="radio-label" for="surrounding_workspace-alternative-yes">Yes</label>
                </div>
                <div class="inspection-ctr">
                  @if($disabled_inspection)
                    <input class="form-check-input" type="radio" id="surrounding_workspace-alternative-no"{{ isset($inspection->is_site_surrounding_workspace_alternativespace) && empty($inspection->is_site_surrounding_workspace_alternativespace) ? ' checked' : '' }} disabled>
                  @else
                    <input class="form-check-input" type="radio" name="surroundingWorkSpaceAlternativeSpace" id="surrounding_workspace-alternative-no" value="0"{{ isset($inspection->is_site_surrounding_workspace_alternativespace) && empty($inspection->is_site_surrounding_workspace_alternativespace) ? ' checked' : '' }}>
                  @endif
                  <label class="radio-label" for="surrounding_workspace-alternative-no">No</label>
                </div>
              </div>
            </div>
          </div>

          <div class="inspectionq_swe_alternativespace_notes_divhide" style="display: {{ (!empty($inspection->is_site_surrounding_workspace_alternativespace) && empty($inspection->is_site_surrounding_workspace)) ? 'block' : 'none' }}">
            <div class="qa-ctr mb-4" style="margin-top: -10px;">
              <div class="vstack gap-2">
                <div class="w-411">
                  @if($disabled_inspection)
                    <textarea class="form-control" id="surroundingWorkSpaceAlternativespaceNotes" rows="3" placeholder="Add text here ..." disabled>{{ !empty($inspection->site_surrounding_workspace_alternativespace_notes) ? $inspection->site_surrounding_workspace_alternativespace_notes : '' }}</textarea>
                  @else
                    <textarea name="surroundingWorkSpaceAlternativespaceNotes" class="form-control" id="surroundingWorkSpaceAlternativespaceNotes" rows="3" placeholder="Add text here ...">{{ !empty($inspection->site_surrounding_workspace_alternativespace_notes) ? $inspection->site_surrounding_workspace_alternativespace_notes : '' }}</textarea>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- Is the surrounding work space environment in a good condition?  --}}
      <div class="inspectionq_sp">
        <div class="qa-ctr mb-4">
          <div class="vstack gap-2">
            <div class="ff-medium fs-14">
              Are the safety protocols in place?
            </div>
            <div class="hstack gap-5 ps-3">
              <div class="inspection-ctr">
                @if($disabled_inspection)
                  <input class="form-check-input" type="radio" id="protocols-yes"{{ !empty($inspection->is_site_safety_protocols) ? ' checked' : '' }} disabled>
                @else
                  <input class="form-check-input" type="radio" name="safetyProtocols" id="protocols-yes" value="1"{{ !empty($inspection->is_site_safety_protocols) ? ' checked' : '' }}>
                @endif
                <label class="radio-label" for="protocols-yes">Yes</label>
              </div>
              <div class="inspection-ctr">
                @if($disabled_inspection)
                  <input class="form-check-input" type="radio" id="protocols-no"{{ isset($inspection->is_site_safety_protocols) && empty($inspection->is_site_safety_protocols) ? ' checked' : '' }} disabled>
                @else
                  <input class="form-check-input" type="radio" name="safetyProtocols" id="protocols-no" value="0"{{ isset($inspection->is_site_safety_protocols) && empty($inspection->is_site_safety_protocols) ? ' checked' : '' }}>
                @endif
                <label class="radio-label" for="protocols-no">No</label>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{--Emergency exit location text area --}}
      <div class="inspectionq_sp_divhide" style="display: {{ !empty($inspection->is_site_safety_protocols) ? 'block' : 'none' }}">
        <div class="qa-ctr mb-4">
          <div class="vstack gap-2">
            <div class="ff-medium fs-14">
              Emergency exit location
            </div>
            <div class="w-411">
              @if($disabled_inspection)
                <textarea class="form-control" id="emergencyExit" rows="3" disabled>{{ !empty($inspection->site_emergency_exit) ? $inspection->site_emergency_exit : '' }}</textarea>
              @else
                <textarea name="emergencyExit" class="form-control" id="emergencyExit" rows="3" placeholder="Add text here...">{{ !empty($inspection->site_emergency_exit) ? $inspection->site_emergency_exit : '' }}</textarea>
              @endif
            </div>
          </div>
        </div>
        {{--Security requirements (address any potential hazards or challenges, safety compliance etc.) text area --}}
        <div class="qa-ctr mb-4">
          <div class="vstack gap-2">
            <div class="ff-medium fs-14">
              Security requirements (address any potential hazards or challenges, safety compliance etc.)
            </div>
            <div class="w-411">
              @if($disabled_inspection)
                <textarea class="form-control" id="securityRequirements" rows="3" disabled>{{ !empty($inspection->site_security_requirements) ? $inspection->site_security_requirements : '' }}</textarea>
              @else
                <textarea name="securityRequirements" class="form-control" id="securityRequirements" rows="3" placeholder="Add text here...">{{ !empty($inspection->site_security_requirements) ? $inspection->site_security_requirements : '' }}</textarea>
              @endif
            </div>
          </div>
        </div>
      </div>

      <div class="inspectionq_sp_divhide_no" style="display: {{ isset($inspection->is_site_safety_protocols) && empty($inspection->is_site_safety_protocols) ? 'block' : 'none' }}">
        <div class="qa-ctr mb-4">
          <div class="vstack gap-2">
            <div class="ff-medium fs-14">
              Share your observations on security.
            </div>
            <div class="w-411">
              @if($disabled_inspection)
                <textarea class="form-control" id="observationSecurity" rows="3" disabled>{{ !empty($inspection->site_observation_security) ? $inspection->site_observation_security : '' }}</textarea>
              @else
                <textarea name="observationSecurity" class="form-control" id="observationSecurity" rows="3" placeholder="Add text here...">{{ !empty($inspection->site_observation_security) ? $inspection->site_observation_security : '' }}</textarea>
              @endif
            </div>
          </div>
        </div>
      </div>

      {{-- Is there a washroom available for the team to use during their visit? --}}
      <div class="inspectionq_laa">
        <div class="qa-ctr mb-4">
          <div class="vstack gap-2">
            <div class="ff-medium fs-14">
              Is there a washroom available for the team to use during their visit?
            </div>
            <div class="hstack gap-5 ps-3">
              <div class="inspection-ctr">
                @if($disabled_inspection)
                  <input class="form-check-input" type="radio" id="washroom-yes"{{ !empty($inspection->is_site_washroom_available) ? ' checked' : '' }} disabled>
                @else
                  <input class="form-check-input" type="radio" name="washroomAvailable" id="washroom-yes" value="1"{{ !empty($inspection->is_site_washroom_available) ? ' checked' : '' }}>
                @endif
                <label class="radio-label" for="washroom-yes">Yes</label>
              </div>
              <div class="inspection-ctr">
                @if($disabled_inspection)
                  <input class="form-check-input" type="radio" id="washroom-no"{{ isset($inspection->is_site_washroom_available) && empty($inspection->is_site_washroom_available) ? ' checked' : '' }} disabled>
                @else
                  <input class="form-check-input" type="radio" name="washroomAvailable" id="washroom-no" value="0"{{ isset($inspection->is_site_washroom_available) && empty($inspection->is_site_washroom_available) ? ' checked' : '' }}>
                @endif
                <label class="radio-label" for="washroom-no">No</label>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{--Where is it located and how can it be accessed? text area --}}
      <div class="inspectionq_laa_divhide" style="display: {{ !empty($inspection->is_site_washroom_available) ? 'block' : 'none' }}">
        <div class="qa-ctr mb-4">
          <div class="vstack gap-2">
            <div class="ff-medium fs-14">
              Where is it located and how can it be accessed?
            </div>
            <div class="w-411">
              @if($disabled_inspection)
                <textarea class="form-control" id="locatedAndAccessed" rows="3" disabled>{{ !empty($inspection->site_washroom_located_accessed) ? $inspection->site_washroom_located_accessed : '' }}</textarea>
              @else
                <textarea name="locatedAndAccessed" class="form-control" id="locatedAndAccessed" rows="3" placeholder="Add text here...">{{ !empty($inspection->site_washroom_located_accessed) ? $inspection->site_washroom_located_accessed : '' }}</textarea>
              @endif
            </div>
          </div>
        </div>
      </div>

      <div class="inspectionq_laa_divhide_no" style="display: {{ isset($inspection->is_site_washroom_available) && empty($inspection->is_site_washroom_available) ? 'block' : 'none' }}">
        <div class="qa-ctr mb-4">
          <div class="vstack gap-2">
            <div class="ff-medium fs-14">
              Where is the nearest washroom that can be used?
            </div>
            <div class="w-411">
              @if($disabled_inspection)
                <textarea class="form-control" id="nearestWashroom" rows="3" disabled>{{ !empty($inspection->site_washroom_located_nearest) ? $inspection->site_washroom_located_nearest : '' }}</textarea>
              @else
                <textarea name="nearestWashroom" class="form-control" id="nearestWashroom" rows="3" placeholder="Add text here...">{{ !empty($inspection->site_washroom_located_nearest) ? $inspection->site_washroom_located_nearest : '' }}</textarea>
              @endif
            </div>
          </div>
        </div>
      </div>

      {{-- Is the network coverage adequate to work using phones and portable dongles? --}}
      <div class="inspectionq_nc">
        <div class="qa-ctr mb-4">
          <div class="vstack gap-2">
            <div class="ff-medium fs-14">
              Is the network coverage adequate to work using phones and portable dongles?
            </div>
            <div class="hstack gap-5 ps-3">
              <div class="inspection-ctr">
                @if($disabled_inspection)
                  <input class="form-check-input" type="radio" id="network-yes"{{ !empty($inspection->is_site_network_coverage) ? ' checked' : '' }} disabled>
                @else
                  <input class="form-check-input" type="radio" name="networkCoverage" id="network-yes" value="1"{{ !empty($inspection->is_site_network_coverage) ? ' checked' : '' }}>
                @endif
                <label class="radio-label" for="network-yes">Yes</label>
              </div>
              <div class="inspection-ctr">
                @if($disabled_inspection)
                  <input class="form-check-input" type="radio" id="network-no"{{ isset($inspection->is_site_network_coverage) && empty($inspection->is_site_network_coverage) ? ' checked' : '' }} disabled>
                @else
                  <input class="form-check-input" type="radio" name="networkCoverage" id="network-no" value="0"{{ isset($inspection->is_site_network_coverage) && empty($inspection->is_site_network_coverage) ? ' checked' : '' }}>
                @endif
                <label class="radio-label" for="network-no">No</label>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="inspectionq_nc_divhide_no" style="display: {{ isset($inspection->is_site_network_coverage) && empty($inspection->is_site_network_coverage) ? 'block' : 'none' }}">
        <div class="qa-ctr mb-4">
          <div class="vstack gap-2">
            <div class="ff-medium fs-14">
              Is there any alternate space with available network?
            </div>
            <div class="w-411">
              @if($disabled_inspection)
                <textarea class="form-control" id="alternateAvailableNetwork" rows="3" disabled>{{ !empty($inspection->site_alternate_available_network) ? $inspection->site_alternate_available_network : '' }}</textarea>
              @else
                <textarea name="alternateAvailableNetwork" class="form-control" id="alternateAvailableNetwork" rows="3" placeholder="Add text here...">{{ !empty($inspection->site_alternate_available_network) ? $inspection->site_alternate_available_network : '' }}</textarea>
              @endif
            </div>
          </div>
        </div>
      </div>

      {{--Additional Notes (additional details relevant to the labelling process or object condition) text area --}}
      <div class="qa-ctr mb-4">
        <div class="vstack gap-2">
          <div class="ff-medium fs-14">
            Additional Notes (additional details relevant to the labelling process or object condition)
          </div>
          <div class="w-411">
            @if($disabled_inspection)
              <textarea class="form-control" id="siteAdditionalNotes" rows="3" disabled>{{ !empty($inspection->site_additional_notes) ? $inspection->site_additional_notes : '' }}</textarea>
            @else
              <textarea name="siteAdditionalNotes" class="form-control" id="siteAdditionalNotes" rows="3" placeholder="Add text here...">{{ !empty($inspection->site_additional_notes) ? $inspection->site_additional_notes : '' }}</textarea>
            @endif
          </div>
        </div>
      </div>
    </section>
    @if($data['inspection_site_condition_request_data_in_inspection']['request_count'] > 0)
    {{-- line start --}}
    <hr class="hrline-inspection" />
    {{-- line end --}}
    <div class="form-check remember-checkbox mb-5 mt-4">
      <div class="form-check redes-checkbox redes-checkbox-1">
        @if($disabled_inspection)
          <input class="form-check-input" type="checkbox" id="applySiteCondition"{{ !empty($inspection->is_site_condition_checked) ? ' checked' : '' }} disabled>
        @else
          <input class="form-check-input" type="checkbox" name="applySiteCondition" id="applySiteCondition" value="1"{{ !empty($inspection->is_site_condition_checked) ? ' checked' : '' }}>
        @endif
        <label class="form-check-label" for="applySiteCondition">Apply the above Site Condition answers to all open Requests at the same location</label>
      </div>
    </div>
    @endif
  </form>
</section>

 <!-- Modal -->

 <div class="modal fade" id="imageObjectLabelModalToggle" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true" aria-labelledby="imageObjectLabelModalToggleLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="imageObjectLabelModalToggleLabel">Add Image</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          {{-- First Step --}}
          <div id="inspection-addimage-popup-1">
            <div id="add-image-title" class="fs-14 mb-3">
              Upload the photo and tag where the label needs to be place
            </div>
            <form class="formFieldInput">
              <div class="mb-3">
                <div class="drop-zone">
                  <div class="vstack justify-content-center gap-4 add-image-before">
                    <div class="drop-zone__prompt ff-medium">
                        Drop files to upload
                    </div>
                    <div class="drop-zone__prompt-2 fs-14">
                      Or
                    </div>
                    <div class="drop-zone__prompt-3 mt-3">
                      <span> Select Files</span>
                    </div>
                  </div>
                  <input type="file" name="image-object-label" id="upload_lable_file" class="drop-zone__input" accept="image/*">
                </div>
              </div>
              <div class='d-flex justify-content-end mt-4'>
                <button type="button" id="discard-btn" class="btn discard-btn cancel-btn me-4" data-bs-dismiss="modal" aria-label="Close">Discard</button>
                <button type="button" id="change-image-btn" class="btn change-image-btn cancel-btn me-4" style="display: none">Change Image</button>
                <button type="button" class="btn first-next-btn apply-btn" onclick="secondStep();">Next</button>
              </div>
            </form>
          </div>
          {{-- Second Step --}}
          <!-- <div id="inspection-addimage-popup-2" style="display: none;">
              <div class="fs-14 mb-3">
                Upload an image of the object with the area where the label will be stuck.
              </div>
              <div class="popupimage-ctr">
                <img src="{{ asset('images/sample.jpg') }}" class="w-100"/>
              </div>
              <div class='d-flex justify-content-end mt-4'>
                <button type="button" class="btn change-image-btn cancel-btn me-4" onclick="firstStep();">Change Image</button>
                <button type="button" class="btn second-next-btn apply-btn" onclick="secondStep();">Next</button>
              </div>
          </div> -->
          <div id="inspection-addimage-popup-2" style="display: none;">
            <div class="fs-14 mb-3">
              Click on the part of the object where you want the label to be stuck. Choose a location for both the label types.
            </div>
            <div class="popupimage-ctr thumb" id="thumb-img" style="position: relative;">
              <div class="drop-zone drop-zone1" style="padding: 0;border: 0px dashed #cfcfcf;">
                <div id="container"></div>
                <div class="delete-pop" id="del-pop-ctr" onclick="deleteLastAppend();" style="display:none;">
                  <img src="{{ asset('icons/popup_delete.png') }}" class="w-100 cP" />
                </div>
              </div>
            </div>
            <nav id="context-menu" class="context-menu">
                <ul class="context-menu__items">
                    <li class="context-menu__item authenticity_label">
                        <a href="#" class="context-menu__link" data-action="Authenticity Label">Authenticity Label</a>
                    </li>
                    <li class="context-menu__item inventory_label">
                        <a href="#" class="context-menu__link" data-action="Inventory Label">Inventory Label</a>
                    </li>
                </ul>
            </nav>
            <div class='d-flex justify-content-end mt-4'>
              <button type="button" class="btn back-btn cancel-btn me-4" onclick="firstStep();">Back</button>
              <button type="button" class="btn save-btn apply-btn">Save</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>

<div class="modal fade" id="addImageModal-2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addImageModalLbl2" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md">
    <div class="modal-content">
      <form id="objectDamageImage">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="addImageModalLb2">Add Image</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <div class="drop-zone">
              <div class="vstack justify-content-center gap-4 add-image-before-default">
                <div class="drop-zone__prompt ff-medium">
                  Drop files to upload
                </div>
                <div class="drop-zone__prompt-2 fs-14">
                  Or
                </div>
                <div class="drop-zone__prompt-3 mt-3">
                  <span> Select Files</span>
                </div>
              </div>
              <input type="file" name="image-damage" class="image drop-zone__input" id="objectDamageAdditionalImage" accept="image/*" />
            </div>
          </div>
          <div class="d-flex justify-content-end mt-4">
            <button type="button" id="damage-discard-btn-alt" class="btn damage-discard-btn-alt cancel-btn me-4" data-bs-dismiss="modal" aria-label="Close">Discard</button>
            <button type="button" id="damage-change-image-btn-alt" class="btn damage-change-image-btn-alt cancel-btn me-4" style="display: none">Change Image</button>
            <button id="update" type="submit" class="btn apply-btn damage-apply-btn">Save</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="addImageModal-3" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addImageModalLbl3" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md">
    <div class="modal-content">
      <form id="objectImage">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="addImageModalLb3">Add Image</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <div class="drop-zone">
              <div class="vstack justify-content-center gap-4 add-image-before-default">
                <div class="drop-zone__prompt ff-medium">
                  Drop files to upload
                </div>
                <div class="drop-zone__prompt-2 fs-14">
                  Or
                </div>
                <div class="drop-zone__prompt-3 mt-3">
                  <span> Select Files</span>
                </div>
              </div>
              <input type="file" name="image-object" class="image drop-zone__input" id="objectAdditionalImage" accept="image/*" />
            </div>
          </div>
          <div class="d-flex justify-content-end mt-4">
            <button type="button" id="object-discard-btn-alt" class="btn object-discard-btn-alt cancel-btn me-4" data-bs-dismiss="modal" aria-label="Close">Discard</button>
            <button type="button" id="object-change-image-btn-alt" class="btn object-change-image-btn-alt cancel-btn me-4" style="display: none">Change Image</button>
            <button id="update" type="submit" class="btn apply-btn object-apply-btn">Save</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Site Condition Modals -->
<div class="modal fade" id="siteConditionChecklist" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered confirmationPopup siteConditionConfirmationPopup">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Site Condition Checklist</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <p class="text-black ff-medium fs-14" style="color: #1D1D1D;">We found {{$data['inspection_site_condition_request_data_in_inspection']['request_count']}} more open Asign Protect+ Requests from the same location.</p>
              <div class="site_condition_checklists">
                <div class="inspection-ctr">
                  <input class="form-check-input" type="radio" name="applySiteConditionForRequest" id="sitecondition-0" value="0">
                  <label class="radio-label" for="sitecondition-0">Apply Site Condition changes to only this Request</label>
                </div>
                <div class="inspection-ctr">
                  <input class="form-check-input" type="radio" name="applySiteConditionForRequest" id="sitecondition-1" value="1">
                  <label class="radio-label" for="sitecondition-1">Apply Site Condition changes to all open Requests at this location</label>
                </div>
              </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn cancel-btn" data-bs-dismiss="modal" id="sc-cancel-btn">Cancel</button>
                <button type="button" class="btn apply-btn" id="sc-apply-btn">Apply</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="siteConditionChecklistRequest" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered confirmationPopup siteConditionConfirmationPopup">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Site Condition Checklist</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <p class="text-black ff-medium fs-14" style="color: #1D1D1D;">You recently completed the Site Condition Checklist for a Request at the same location. Do you want to apply the same changes to this Site?</p>
              
              @php
                $site_condition_request_data = $data['inspection_site_condition_request_data']['request_data'];
                $checked = (count($site_condition_request_data) <= 1) ? ' checked' : '';
              @endphp
              
              @if(!empty($site_condition_request_data[0])) 
                <div class="d-flex align-items-center site_condition_checklist_list">
                  <div class="inspection-ctr">
                    <input class="form-check-input" type="radio" name="applySiteConditionForSingleRequest" id="sitecondition-{{$site_condition_request_data[0]->id}}" value="{{$site_condition_request_data[0]->id}}" disabled{{$checked}}>
                    <label class="radio-label" for="sitecondition-{{$site_condition_request_data[0]->id}}">Request No: {{ $site_condition_request_data[0]->request_id }}</label>
                  </div>
                  <div class="inspection-ctr ms-auto" style="color: #696969;">{{ \App\Helpers\UtilsHelper::displayDate($site_condition_request_data[0]->inspection_date, 'd F Y') }}</div>
                </div>
              @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn cancel-btn" data-bs-dismiss="modal" id="sccr-cancel-btn">Don’t apply</button>
                <button type="button" class="btn apply-btn" id="sccr-apply-btn">Apply changes</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="siteConditionChecklistRequestLists" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered confirmationPopup siteConditionConfirmationPopup">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <p class="text-black ff-medium fs-14" style="color: #1D1D1D;">You recently completed the Site Condition Checklist for a Request at the same location. Do you want to apply the same changes to this Checklist? </p>
              @if(!empty($data['inspection_site_condition_request_data']['request_data']))
                @foreach($data['inspection_site_condition_request_data']['request_data'] as $request_data)
                  <div class="d-flex align-items-center site_condition_checklist_list site_condition_checklist_lists">
                    <div class="inspection-ctr">
                      <input class="form-check-input" type="radio" name="applySiteConditionForSingleRequest" id="sitecondition{{$request_data->id}}" value="{{$request_data->id}}">
                      <label class="radio-label" for="sitecondition{{$request_data->id}}">Request No: {{ $request_data->request_id }}</label>
                    </div>
                    <div class="inspection-ctr ms-auto" style="color: #696969;">{{ \App\Helpers\UtilsHelper::displayDate($request_data->inspection_date, 'd F Y') }}</div>
                  </div>
                @endforeach
              @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn cancel-btn" data-bs-dismiss="modal" id="sccrl-cancel-btn">Cancel</button>
                <button type="button" class="btn apply-btn" id="sccrl-apply-btn">Apply</button>
            </div>
        </div>
    </div>
</div>

@if(!$disabled_inspection)
  @push('scripts')
  <script>
      let $request_count = {{ $data['inspection_site_condition_request_data']['request_count'] }};

      @if($data['inspection_site_condition_request_data']['request_count'])
        localStorage.tabactive = 0;
        
        $('button[data-bs-toggle="tab"]').on('click', function(e) {            

            $(".site_condition_checklist_lists input[name$='applySiteConditionForSingleRequest']").prop('checked', false);
            
            if (this.id == 'nav-inspection-tab' && $(this).hasClass('active')) {
                if(localStorage.tabactive == 0) {
                  if($request_count > 1) {
                      $("#siteConditionChecklistRequestLists").modal('show');
                  } else {
                      $("#siteConditionChecklistRequest").modal('show');
                  }
                  localStorage.tabactive = 1;
                  //localStorage.tabactive1 = 1;
                }
            } else {
                $("#siteConditionChecklistRequest").modal('hide');
                $("#siteConditionChecklistRequestLists").modal('hide');
                localStorage.tabactive = 0;
                localStorage.tabactive1 = 0;
            }
        });

        /* if(localStorage.tabactive1 == 1) {
            if($request_count > 1) {
                $("#siteConditionChecklistRequestLists").modal('show');
            } else {
                $("#siteConditionChecklistRequest").modal('show');
            }
        } */
      @endif

      $(document).ready(function () {
          if(localStorage.tabactive1 == 1) {
              $('.nav-tabs .nav-link').removeClass('active');
              $('#nav-inspection-tab[data-bs-target="#nav-inspection"]').addClass('active');
              $('.tab-content .tab-pane').removeClass('active');
              $('.tab-content .tab-pane[aria-labelledby="nav-inspection-tab"]').addClass('active show');

              if(localStorage.tabactive2 == 0) {
                if($request_count > 1) {
                    $("#siteConditionChecklistRequestLists").modal('show');
                } else {
                    $("#siteConditionChecklistRequest").modal('show');
                }
                localStorage.tabactive2 == 1;
              }
          }
      });
  </script>
  @endpush
@endif

<script>
    function firstStep() {
        if(document.getElementById("upload_lable_file").files.length == 0) {
          toastr.error('No file selected.');
        } else {
          if (document.getElementById('inspection-addimage-popup-1')) {
              if (document.getElementById('inspection-addimage-popup-1').style.display == 'none') {
                  document.getElementById('inspection-addimage-popup-1').style.display = 'block';
                  document.getElementById('inspection-addimage-popup-2').style.display = 'none';
                  //document.getElementById('discard-btn').style.display = 'none';

                  document.getElementById('imageObjectLabelModalToggleLabel').innerHTML = 'Add Image';
              }
              else {
                  document.getElementById('inspection-addimage-popup-1').style.display = 'none';
                  document.getElementById('inspection-addimage-popup-2').style.display = 'block';
              }
          }
        }
    }

    function secondStep() {
        if(document.getElementById("upload_lable_file").files.length == 0) {
          toastr.error('No file selected.');
        } else {
          if (document.getElementById('inspection-addimage-popup-2')) {
            if (document.getElementById('inspection-addimage-popup-2').style.display == 'none') {
                document.getElementById('inspection-addimage-popup-2').style.display = 'block';
                //document.getElementById('inspection-addimage-popup-3').style.display = 'none';
                document.getElementById('inspection-addimage-popup-1').style.display = 'none';

                document.getElementById('imageObjectLabelModalToggleLabel').innerHTML = 'Label Location';
            }
            else {
                document.getElementById('inspection-addimage-popup-1').style.display = 'none';
                document.getElementById('inspection-addimage-popup-2').style.display = 'none';
                //document.getElementById('inspection-addimage-popup-3').style.display = 'block';
            }
          }

          if(!$('.popupimage-ctr #container div').hasClass('authenticity_label') || !$('.popupimage-ctr #container div').hasClass('inventory_label')) {
            $('.save-btn').attr('disabled', true);
          }
        }
    }
    // draging and drop script
    document.querySelectorAll(".drop-zone__input").forEach((inputElement) => {
        const dropZoneElement = inputElement.closest(".drop-zone");

        dropZoneElement.addEventListener("click", (e) => {
            inputElement.click();
        });
        
        inputElement.addEventListener("change", (e) => {
            if (inputElement.files.length) {
                updateThumbnail(dropZoneElement, inputElement.files[0]);
            } else {
              dropZoneElement.querySelector(".vstack").innerHTML = "<div class='drop-zone__prompt ff-medium'>Drop files to upload</div><div class='drop-zone__prompt-2 fs-14'>Or</div><div class='drop-zone__prompt-3 mt-3'><span> Select Files</span></div>";
              dropZoneElement.querySelector(".drop-zone__thumb").remove();
            }
        });

        dropZoneElement.addEventListener("dragover", (e) => {
            e.preventDefault();
            dropZoneElement.classList.add("drop-zone--over");
        });

        ["dragleave", "dragend"].forEach((type) => {
            dropZoneElement.addEventListener(type, (e) => {
                dropZoneElement.classList.remove("drop-zone--over");
            });
        });

        dropZoneElement.addEventListener("drop", (e) => {
            e.preventDefault();

            if (e.dataTransfer.files.length) {
                inputElement.files = e.dataTransfer.files;
                updateThumbnail(dropZoneElement, e.dataTransfer.files[0]);
            }

            dropZoneElement.classList.remove("drop-zone--over");
        });
    });

    function updateThumbnail(dropZoneElement, file) {
        let thumbnailElement = dropZoneElement.querySelector(".drop-zone__thumb");

        // First time - remove the prompt
        if (dropZoneElement.querySelector(".drop-zone__prompt")) {
            dropZoneElement.querySelector(".drop-zone__prompt").remove();
        }

        if (dropZoneElement.querySelector(".drop-zone__prompt-2")) {
            dropZoneElement.querySelector(".drop-zone__prompt-2").remove();
        }

        if (dropZoneElement.querySelector(".drop-zone__prompt-3")) {
            dropZoneElement.querySelector(".drop-zone__prompt-3").remove();
        }

        // First time - there is no thumbnail element, so lets create it
        if (!thumbnailElement) {
            thumbnailElement = document.createElement("div");
            thumbnailElement.classList.add("drop-zone__thumb");
            dropZoneElement.appendChild(thumbnailElement);
        }

        //thumbnailElement.dataset.label = file.name;
        document.getElementById('change-image-btn').style.display = 'block';
        /* document.getElementById('damage-change-image-btn-alt').style.display = 'block';
        document.getElementById('object-image-btn-alt').style.display = 'block'; */
        document.getElementById('add-image-title').innerHTML = 'Upload an image of the object with the area where the label will be stuck.';

        // Show thumbnail for image files
        if (file.type.startsWith("image/")) {
            const reader = new FileReader();

            reader.readAsDataURL(file);
            reader.onload = () => {
                thumbnailElement.style.backgroundImage = `url('${reader.result}')`;
            };
            
            document.getElementById('discard-btn').style.display = 'none';
            /* document.getElementById('damage-discard-btn-alt').style.display = 'none';
            document.getElementById('object-discard-btn-alt').style.display = 'none'; */
        } else {
            thumbnailElement.style.backgroundImage = null;
        }
    }
    // draging and drop script end
    (function () {
        "use strict";
        /********************************************** Context Menu Function Only *******************************/
        function clickInsideElement(e, className) {
            var el = e.srcElement || e.target;
            if (el.classList.contains(className)) {
                return el;
            } else {
                while (el = el.parentNode) {
                    if (el.classList && el.classList.contains(className)) {
                        return el;
                    }
                }
            }
            return false;
        }

        function getPosition(e) {
            var posx = 0, posy = 0;
            if (!e) var e = window.event;
            if (e.pageX || e.pageY) {
                posx = e.pageX;
                posy = e.pageY;
            } else if (e.clientX || e.clientY) {
                posx = e.clientX + document.body.scrollLeft + document.documentElement.scrollLeft;
                posy = e.clientY + document.body.scrollTop + document.documentElement.scrollTop;
            }
            return {
                x: posx,
                y: posy
            }
        }

        // Your Menu Class Name
        var taskItemClassName = "thumb";
        var contextMenuClassName = "context-menu",
            contextMenuItemClassName = "context-menu__item",
            contextMenuLinkClassName = "context-menu__link",
            contextMenuActive = "context-menu--active";
        var taskItemInContext, clickCoords, clickCoordsX, clickCoordsY, menu = document.querySelector("#context-menu"), menuItems = menu.querySelectorAll(".context-menu__item");
        var menuState = 0, menuWidth, menuHeight, menuPosition, menuPositionX, menuPositionY, windowWidth, windowHeight;

        function initMenuFunction() {
            contextListener();
            clickListener();
            keyupListener();
            resizeListener();
        }

        function contextListener() {
            document.addEventListener("contextmenu", function (e) {
                taskItemInContext = clickInsideElement(e, taskItemClassName);

                if (taskItemInContext) {
                    e.preventDefault();
                    toggleMenuOn();
                    positionMenu(e);
                } else {
                    taskItemInContext = null;
                    toggleMenuOff();
                }
            });
        }

        /**
         * Listens for click events.
         */
        function clickListener() {
            document.addEventListener("click", function (e) {
                var clickeElIsLink = clickInsideElement(e, contextMenuLinkClassName);

                if (clickeElIsLink) {
                    e.preventDefault();
                    menuItemListener(clickeElIsLink);
                    if($('.popupimage-ctr #container div').hasClass('authenticity_label')) {
                      $('#context-menu ul li.authenticity_label a').attr('disabled', 'disabled');
                    }
                    if($('.popupimage-ctr #container div').hasClass('inventory_label')) {
                      $('#context-menu ul li.inventory_label a').attr('disabled', 'disabled');
                    }

                    if($('.popupimage-ctr #container div').hasClass('authenticity_label') || $('.popupimage-ctr #container div').hasClass('inventory_label')) {
                      $('#inspection-addimage-popup-2 .delete-pop').show();
                      $('.save-btn').attr('disabled', false);
                    }/*  else {
                      $('#inspection-addimage-popup-2 .delete-pop').hide();
                    } */
                    //document.getElementById('authenticity_label')
                } else {
                    var button = e.which || e.button;
                    if (button === 1) {
                        toggleMenuOff();
                    }
                }
            });
        }

        /**
         * Listens for keyup events.
         */

        function keyupListener() {
            window.onkeyup = function (e) {
                if (e.keyCode === 27) {
                    toggleMenuOff();
                }
            }
        }

        /**
         * Window resize event listener
         */
        function resizeListener() {
            window.onresize = function (e) {
                toggleMenuOff();
            };
        }

        /**
         * Turns the custom context menu on.
         */
        function toggleMenuOn() {
            if (menuState !== 1) {
                menuState = 1;
                menu.classList.add(contextMenuActive);
            }
        }

        /**
         * Turns the custom context menu off.
         */
        function toggleMenuOff() {
            if (menuState !== 0) {
                menuState = 0;
                menu.classList.remove(contextMenuActive);
            }
        }


        function positionMenu(e) {
            clickCoords = getPosition(e);
            clickCoordsX = clickCoords.x;
            clickCoordsY = clickCoords.y;
            menuWidth = menu.offsetWidth + 4;
            menuHeight = menu.offsetHeight + 4;

            windowWidth = window.innerWidth;
            windowHeight = window.innerHeight;

            var rect = e.target.getBoundingClientRect();
            var x = e.clientX - rect.left; //x position within the element.
            var y = e.clientY - rect.top;  //y position within the element.

            if ((windowWidth - clickCoordsX) < menuWidth) {
                menu.style.left = x + 35 + "px";
                // menu.style.left = (windowWidth - menuWidth) - 0 + "px";
            } else {
                menu.style.left = x + 35 + "px";
            }

            // menu.style.top = clickCoordsY + "px";

            if (Math.abs(windowHeight - clickCoordsY) < menuHeight) {
                menu.style.top = y + 80 + "px";
                // menu.style.top = (windowHeight - menuHeight) - 0 + "px";
            } else {
                menu.style.top = y + 80 + "px";
            }
        }

        function menuItemListener(link) {
            var menuSelectedPhotoId = taskItemInContext.getAttribute("data-id");
            //console.log('Your Selected Photo: ' + menuSelectedPhotoId)
            var moveToAlbumSelectedId = link.getAttribute("data-action");
            // if (moveToAlbumSelectedId == 'remove') {
            //     console.log('You Clicked the remove button')
            // } else if (moveToAlbumSelectedId && moveToAlbumSelectedId.length > 7) {
            //console.log('Clicked Album Name: ' + moveToAlbumSelectedId);
            if (moveToAlbumSelectedId === "Authenticity Label") {

                const ele = document.getElementById('container');
                const newDiv = document.createElement('div');
                newDiv.classList.add("authenticity_label");
                newDiv.innerHTML =
                    '<div style="position:absolute;background:#696969B2;color:white;width: 20px;height: 20px;display: flex;align-items: center;justify-content: center;border-radius: 5px;left: ' +
                    parseFloat(menu.style.left.split("px")[0] - 36) + "px" + ';top:' + parseFloat(menu.style.top.split("px")[0] - 77) + "px" + '">A</div>';
                ele.appendChild(newDiv);
            } else {
                const ele = document.getElementById('container');
                const newDiv = document.createElement('div');
                newDiv.classList.add("inventory_label");
                newDiv.innerHTML =
                    '<div style="position:absolute;background:#696969B2;color:white;width: 20px;height: 20px;display: flex;align-items: center;justify-content: center;border-radius: 5px;left: ' +
                    parseFloat(menu.style.left.split("px")[0] - 36) + "px" + ';top:' + parseFloat(menu.style.top.split("px")[0] - 77) + "px" + '">I</div>';
                ele.appendChild(newDiv);
            }

            toggleMenuOff();
        }

        initMenuFunction();

    })();

    function deleteLastAppend(){
      $('.popupimage-ctr #container').children().last().remove();

      if(!$('.popupimage-ctr #container div').hasClass('authenticity_label')) {
        $('#context-menu ul li.authenticity_label a').attr('disabled', false);
      }
      if(!$('.popupimage-ctr #container div').hasClass('inventory_label')) {
        $('#context-menu ul li.inventory_label a').attr('disabled', false);
      }

      if(!$('.popupimage-ctr #container div').hasClass('authenticity_label') && !$('.popupimage-ctr #container div').hasClass('inventory_label')) {
        $('#inspection-addimage-popup-2 .delete-pop').hide();
        $('.save-btn').attr('disabled', true);
      }
    }
</script>
