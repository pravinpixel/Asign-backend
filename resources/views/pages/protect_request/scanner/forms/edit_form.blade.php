<div class="container-fluid">
    <div class="row">
        @if($data['object_img'])
        <div class="col-md-12">
            <section id="upload_view" class="upload_view">
                <div class="upload_view_img_wrapper">
                    <img id="upload_image" src="{{config('app.image_url') . $data['object_img']}}">
                </div>
            </section>
        </div>
        <div class="col-md-6">
            <div class="hstack gap-3 mt-4">
                <button id="object_rotate" type="button" class="btn cancel-btn craft_btn" data-method="rotate" data-option="90">
                    <img class="indic" src="{{ asset('icons/rotate_black.svg') }}" width="24"> Rotate
                </button>
                <button id="object_crop" type="button" class="btn cancel-btn craft_btn" data-method="crop">
                    <img class="indic" src="{{ asset('icons/crop_black.svg') }}" width="24"> Crop
                </button>
            </div>
        </div>
        @else
        <div class="col-md-12">
            <h4>Image not found</h4>
        </div>
        @endif
    </div>
</div>