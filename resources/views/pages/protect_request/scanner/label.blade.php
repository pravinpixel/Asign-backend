<div class="modal-content">
    {{-- @php--}}
    {{-- echo "
    <pre>";--}}
{{--    print_r($label);--}}
{{--    echo "</pre>";--}}
    {{-- @endphp--}}
    <form id="scanner_form" class="scanner_form label_forms" method="post" enctype="multipart/form-data">
        <input type="hidden" id="type" name="type" value="{{ $label['type'] }}">
        <input type="hidden" id="is_form_valid" name="is_form_valid" value="{{ $data['valid'] ?? '' }}">
        <input type="hidden" id="is_approved" name="is_approved" value="false">
        <input type="hidden" id="title" name="title" value="{{ $label['title'] ?? '' }}">
        <input type="hidden" id="child_step" name="child_step" value="{{ $label['child_step'] ?? 'on_update' }}">
        <input type="hidden" id="formtype" name="formtype"
            value="{{ $label['formtype'] == "" ? 'no_submit' : $label['formtype'] }}">

        <div class="modal-header">
            <h1 class="modal-title" id="popup_form_label">{{ $label['title'] }}</h1>
            <button id="close_scanner_modal" type="button" class="btn-close"></button>
        </div>
        <div class="modal-body" id="scanner_form_body">
            @include('pages.protect_request.scanner.forms.label_form')
        </div>
        <div class="modal-footer">
            @if($label['formtype'] != 'standalone')
            @if ($data['status'] == 'approved')
            <button type="button" class="btn cancel-btn" disabled>Back</button>
            @else
            <button type="button" class="btn cancel-btn" id="next_step" data-next="{{ $label['prev'] }}"
                data-direction="prev">Back</button>
            @endif

            <div>
                <button type="button" class="btn cancel-btn" id="void_btn" style="margin-right: 16px; display:none">Mark
                    as Void</button>
                @if($label['type'] == 'auth_label' || $label['type'] == 'auth_label_child')
                @if($label['current_index'] === $label['child_count'] - 1 || $label['child_count'] == 0)
                <button type="button" class="btn apply-btn" id="submit_btn_alt">Next</button>
                @elseif($label['current_index'] == $label['child_count'] && $label['child_step'] == "on_update")
                <button type="button" class="btn apply-btn" id="submit_btn_alt">Next</button>
                @else
                <button type="submit" class="btn apply-btn" id="submit_btn">Next</button>
                @endif
                @else
                <button type="submit" class="btn apply-btn" id="submit_btn">Next</button>
                @endif
            </div>
            @else
            <button type="button" class="btn cancel-btn" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
            <div>
                <button type="submit" class="btn apply-btn" id="submit_btn">Save</button>
            </div>
            @endif
        </div>
    </form>
</div>