{{-- Object Identification Start --}}
{{--@php--}}
{{-- $object_status = $data['verify_status']['object-identification'] ?? [];--}}
{{-- isset($object_status[0]) && $object_status[0]['verify'] == 'true' ? $checked = 'checked' : $checked = '';--}}
{{--@endphp--}}

@php
    $display = 'display:none';
    if ($data['status']['id'] == 'asign-protect' && $data['reference_img_url']) {
    $display = '';
    }
@endphp

<section class="label_step_notify" style="{{$display}}">
    <input type="hidden" id="show-continue-notify" value="{{$display == '' ? '1' : '0'}}">
    <img src="{{ asset('icons/invalid.svg') }}" alt="invalid"/>
    <p>Your labelling process was paused. Click on Continue Labelling to complete. </p>
    <span><img id="close_notify" src="{{ asset('icons/close_x.svg') }}" alt="close"/></span>
</section>

<section class="section-inner">
    <div class="form-check">
        {{-- @if($disabled_object)--}}
        {{-- <input disabled {{$checked}} class="form-check-input" type="checkbox" id="object_radio">--}}
        {{-- @else--}}
        {{-- <input {{$checked}} class="form-check-input objectDetails" type="checkbox"--}}
        {{-- name="object_radio" id="object_radio">--}}
        {{-- @endif--}}
        <label class="form-check-label" for="object_radio1">
            Object Identification
        </label>
    </div>
    <article class="edit-wrapper">
        <ul class="personal-info black-txt">
            <li>
                <span>Asign Object Number</span>
                <span>{{$data['asign_no']}}</span>
            </li>
            <li>
                <span>Accession Number</span>
                <span>{{$data['accession_no']}}</span>
            </li>
            <li>
                <span>Inventory Number</span>
                <span>{{$data['inventory_no']}}</span>
            </li>
        </ul>
    </article>
</section>

{{-- Object Identification End --}}

{{-- Object Labels Start --}}

<section class="section-inner top-border" id="objectLabels">
    @include('pages.protect_request.components.object-label')
</section>

{{-- Object Labels End --}}


{{-- Image Start --}}
@php
    $disabled_object = true;

    if($data['status']['id'] == 'asign-protect') {
    $field_agent_ids = $data['field_agent_ids'] ? explode(',', $data['field_agent_ids']) : [];
    if(in_array(auth()->user()->id, $field_agent_ids))
    $disabled_object = false;

    $asign_edit_access = access()->hasAccess('label-requests.edit');
    if (!$asign_edit_access) {
    $disabled_object = true;
    }

    }
    $images_status = $data['verify_status']['images'] ?? [];
    isset($images_status[0]) && $images_status[0]['verify'] == 'true' ? $checked = 'checked' : $checked = '';
@endphp

<section class="section-inner top-border">
    <div class="form-check" data-value="0" data-type="images">
        @if($disabled_object)
            <input disabled {{$checked}} class="form-check-input" type="checkbox" id="image_radio">
        @else
            <input {{$checked}} class="form-check-input objectDetails" type="checkbox" name="image_radio"
                   id="image_radio">
        @endif
        <label class="form-check-label" for="image_radio">
            Images
        </label>
    </div>
    <article class="edit-wrapper">
        <div class="image-preview image-preview-1 wrap-image-viewer">
            @if($data['images'])
                @foreach($data['images'] as $image)
                    <a href="{{$image->value}}" class="view_box_1" title="{{$image->title}}">
                        <img src="{{$image->value}}" alt="" class="img-fluid">
                        <div class="image-modal">
                            <h6 class="image-lable"> {{\App\Helpers\UtilsHelper::imageLabel($image->tag)}}</h6>
                        </div>
                    </a>
                @endforeach
            @endif
            @if(!$disabled_object)
                <div class="upload-btn-wrapper" href="#addImageModal" data-bs-toggle="modal">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                    <span>Add Image</span>
                    {{-- <input type="file" name="image" class="image" id="additionalImage"  accept="image/*"/>--}}
                </div>
            @endif
        </div>
    </article>
</section>

@include('pages.protect_request.components.image-crop-popup')


{{-- Image End --}}


{{-- About Start --}}
@php
    $about_status = $data['verify_status']['about'] ?? [];
    isset($about_status[0]) && $about_status[0]['verify'] == 'true' ? $checked = 'checked' : $checked = '';
@endphp
<form id="objectDetailForm" class="close-editable">
    <section class="section-inner top-border">
        <div class="form-check" data-value="0" data-type="about">
            @if($disabled_object)
                <input disabled {{$checked}} class="form-check-input" type="checkbox" id="about_radio">
            @else
                <input {{$checked}} class="form-check-input objectDetails" type="checkbox" name="about_radio"
                       id="about_radio">
            @endif
            <label class="form-check-label" for="about_radio">
                About
            </label>
        </div>
        <article class="edit-wrapper" id="aboutObject">
            @include('pages.protect_request.components.about')
        </article>
    </section>

    {{-- About End --}}

    {{-- Medium and Measurements Start --}}
    @php
        $medium_status = $data['verify_status']['medium'] ?? [];
        isset($medium_status[0]) && $medium_status[0]['verify'] == 'true' ? $checked = 'checked' : $checked = '';
    @endphp

    <section class="section-inner top-border">
        <div class="form-check" data-value="0" data-type="medium">
            @if($disabled_object)
                <input disabled {{$checked}} class="form-check-input" type="checkbox" id="medium_radio">
            @else
                <input {{$checked}} class="form-check-input objectDetails" type="checkbox" name="medium_radio"
                       id="medium_radio">
            @endif
            <label class="form-check-label" for="medium_radio">
                Medium and Measurements
            </label>
        </div>
        <article class="edit-wrapper" id="mediumObject">
            @include('pages.protect_request.components.medium')
        </article>
    </section>

    {{-- Medium and Measurements End --}}

    {{-- Characteristics Start --}}
    @php
        $characteristics_status = $data['verify_status']['characteristics'] ?? [];
        isset($characteristics_status[0]) && $characteristics_status[0]['verify'] == 'true' ? $checked = 'checked' : $checked = '';
    @endphp
    <section class="section-inner top-border">
        <div class="form-check" data-value="0" data-type="characteristics">
            @if($disabled_object)
                <input disabled {{$checked}} class="form-check-input" type="checkbox" id="charater_radio">
            @else
                <input {{$checked}} class="form-check-input objectDetails" type="checkbox" name="charater_radio"
                       id="charater_radio">
            @endif
            <label class="form-check-label" for="charater_radio">
                Characteristics
            </label>
        </div>
        <article class="edit-wrapper">
            <ul class="personal-info black-txt">
                <li>
                    <span>Technique</span>
                    <span>{{$data['techniques'] ? $data['techniques']->pluck('name')->implode(', ') : ''}}</span>
                </li>
                <li>
                    <span>Style</span>
                    <span>{{$data['styles'] ? $data['styles']->pluck('name')->implode(', ') : ''}}</span>
                </li>
                <li>
                    <span>Movement</span>
                    <span>{{$data['movement']['name'] ?? ''}}</span>
                </li>
                <li>
                    <span>Subject</span>
                    <span>{{$data['subject']['name'] ?? ''}}</span>
                </li>
            </ul>
        </article>
    </section>

    {{-- Characteristics End --}}

    {{-- Signature & Inscriptions Start --}}
    @php
        $signature_status = $data['verify_status']['signature'] ?? [];
        isset($signature_status[0]) && $signature_status[0]['verify'] == 'true' ? $checked = 'checked' : $checked = '';
    @endphp
    <section class="section-inner top-border">
        <div class="form-check" data-value="0" data-type="signature">
            @if($disabled_object)
                <input disabled {{$checked}} class="form-check-input" type="checkbox" id="sign_radio">
            @else
                <input {{$checked}} class="form-check-input objectDetails" type="checkbox" name="sign_radio"
                       id="sign_radio">
            @endif
            <label class="form-check-label" for="sign_radio">
                Signature & Inscriptions
            </label>
        </div>
        <article class="edit-wrapper" id="signatureObject">
            @include('pages.protect_request.components.signature')
        </article>
    </section>

    <input type="submit" hidden>

</form>

{{-- Signature & Inscriptions End --}}

{{-- Location Start --}}
@php
    $location_status = $data['verify_status']['locations'] ?? [];
    isset($location_status[0]) && $location_status[0]['verify'] == 'true' ? $checked = 'checked' : $checked = '';
@endphp
<section class="section-inner top-border">
    <div class="form-check" data-value="0" data-type="locations">
        @if($disabled_object)
            <input disabled {{$checked}} class="form-check-input" type="checkbox" id="location_radio">
        @else
            <input {{$checked}} class="form-check-input objectDetails" type="checkbox" name="location_radio"
                   id="location_radio">
        @endif
        <label class="form-check-label" for="location_radio">
            Location
        </label>
        @if($disabled_object)
            <button disabled type="button" class="btn btn-link">
                <i class="fa fa-plus" aria-hidden="true"></i> ADD LOCATION
            </button>
        @else
            <button type="button" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#addLocationModal">
                <i class="fa fa-plus" aria-hidden="true"></i> ADD LOCATION
            </button>
        @endif

    </div>
    <article class="edit-wrapper-plain">
        <div class="accordion accordion-flush custom-accordion" id="locationAccordion">
            @include('pages.protect_request.components.locations')
            @include('pages.protect_request.components.locations', ['data' => ['locations' => $data['secondary_locations']]])
        </div>
    </article>
    @include('pages.protect_request.components.location-popup')

</section>

{{-- Location End --}}

{{-- Component Start --}}
@php
    $component_status = $data['verify_status']['components'] ?? [];
    isset($component_status[0]) && $component_status[0]['verify'] == 'true' ? $checked = 'checked' : $checked = '';
@endphp
<section class="section-inner top-border">
    <div class="form-check" data-value="0" data-type="components">
        @if($disabled_object)
            <input disabled {{$checked}} class="form-check-input" type="checkbox" id="component_radio">
        @else
            <input {{$checked}} class="form-check-input objectDetails" type="checkbox" name="component_radio"
                   id="component_radio">
        @endif
        <label class="form-check-label" for="component_radio">
            Components
        </label>

        @if($disabled_object)
            <button disabled type="button" class="btn btn-link">
                <i class="fa fa-plus" aria-hidden="true"></i> ADD COMPONENT
            </button>
        @else
            <button type="button" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#addComponentModal">
                <i class="fa fa-plus" aria-hidden="true"></i> ADD COMPONENT
            </button>
        @endif
    </div>
    <article class="edit-wrapper-plain">
        <div class="accordion accordion-flush custom-accordion" id="components">
            @include('pages.protect_request.components.components')
        </div>
    </article>

    @include('pages.protect_request.components.component-popup')


</section>

@if(!$disabled_object)
    <div class="roundedSideSticky" id="edit-icon">
        <img src="{{ asset('icons/edit-2.svg') }}"/>
    </div>
    <div class="roundedSideSticky" id="save-icon" style="display: none">
        <img src="{{ asset('icons/Save.svg') }}"/>
    </div>
@endif
{{-- Component End --}}
