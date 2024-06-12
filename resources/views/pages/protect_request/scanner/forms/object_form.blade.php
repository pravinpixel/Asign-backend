<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <p class="section-title">Object Image</p>
            @if($data['object_img'])
            <section class="upload_view">
                <div class="upload_view_img_wrapper">
                    <div class="upload_view_controls">
                        <span id="delete_img"><img class="indic" src="{{ asset('icons/delete_ic.svg') }}" width="40"></span>
                        <span id="next_span" data-span="edit_uploaded_image"><img class="indic" src="{{ asset('icons/edit_ic.svg') }}" width="40"></span>
                    </div>
                    <img id="upload_image" class="img-fluid" src="{{config('app.image_url') . $data['object_img']}}">
                </div>
            </section>
            <div>
                @if($data['percentage'] > 75)
                <p id="matching_p" class="matching_p"><img class="indic" src="{{ asset('icons/ok_percentage.svg') }}" width="20">{{$data['percentage']}}% Match</p>
                @elseif($data['percentage'] < 40)
                    <p id="matching_p" class="matching_p"><img class="indic" src="{{ asset('icons/below_percentage.svg') }}" width="20">Match Not Found</p>
                @else
                <p id="matching_p" class="matching_p"><img class="indic" src="{{ asset('icons/below_percentage.svg') }}" width="20"> {{$data['percentage']}}% Match</p>
                @endif
            </div>
            @else
            <section class="upload_file">
                <input type="file" name="object" id="object">
                <div class="upload-area" id="uploadfile">
                    <p>Upload an image that closely resembles the reference</p>
                    <button type="button" class="btn upload-btn">Select File</button>
                </div>
            </section>
            @endif
        </div>
        <div class="col-md-4">
            <p class="section-title">Reference Image</p>
            <div id='reference_view'>
                <img id="preview_image" class="img-fluid" src="{{config('app.image_url') . $data['reference_img']}}">
            </div>
        </div>
    </div>
</div>
