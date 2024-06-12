<form id="dynamic_popup_modal_form" method="post" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" value="{{ $info->id ?? '' }}">
    <input type="hidden" name="title" id="title" value="{{ $title ?? '' }}">
    <input type="hidden" name="status" id="status" value="{{ $status ?? 'incomplete' }}">
    <div class="form-group mb-3 singleUploadImage">
        <label for="formFile" class="form-label"> Fair Iamge</label>
        <div class="mb-3" id="drag_upload">
            <input type="file" id="imageInput" value="" />
        </div>
    </div>
    <div class="form-group mb-3">
        <label class="form-label">From Date:</label>
        <input type="date" name="from_date" class="form-control" id="fromDate" value="{{ $info->from_date ?? '' }}" />
        @if($errors->has('from_date'))
            <div class="field-error">{{ $errors->first('from_date') }}</div>
        @endif
        {{-- <span class="field-error" id="from_date-error"></span> --}}
    </div>
    <div class="form-group mb-3">
        <label class="form-label">To Date:</label>
        <input type="date" name="to_date" class="form-control" id="toDate" value="{{ $info->to_date ?? '' }}" />
        @if($errors->has('to_date'))
            <div class="field-error">{{ $errors->first('to_date') }}</div>
        @endif
    </div>
    <div class="form-group mb-3">
        <label class="form-label">Fair:</label>
        <input type="text" class="form-control" placeholder="Enter fair" name="name" value="{{ $info->name ?? '' }}">
        @if($errors->has('name'))
            <div class="field-error">{{ $errors->first('name') }}</div>
        @endif
        {{-- <span class="field-error" id="name-error"></span> --}}
    </div>
    <div class="form-group mb-3">
        <label class="form-label">Status</label>
        <div>
            <select class="form-select" name="status">
                <option value="" selected>Select status</option>
                <option value="1" @if(isset($info->status) && $info->status == 1) selected @endif>Active</option>
                <option value="0" @if(isset($info->status) && $info->status == 0) selected @endif >InActive</option>
            </select>
            <span class="field-error" id="status-error"></span>
        </div>
    </div>
    <div class="footerbtnDiv">
        <button type="submit" class="btn apply-btn">Save</button>
    </div>
</form>
<div id="dynamic_confirm">
    <p class="conform_content">If you receive json data from an external service, you should make sure if the data is a proper formed json. With this code you can quickly test the data.</p>
    <div class="footerbtnDiv" style="margin-top:24px;">
        <button type="button" class="btn cancel-btn" id="cancel_confirm">Cancel</button>
        <button type="button" class="btn apply-btn" id="submit_confirm">Save</button>
    </div>
</div>