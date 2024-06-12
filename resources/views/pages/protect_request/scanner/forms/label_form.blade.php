<div class="container-fluid">
    {{-- @php
    echo "
    <pre>";
            print_r($label);
        echo "</pre>";
    @endphp --}}
    <div class="row">
        <div class="col-md-12 column-gap">
            <h5 class="heading_18_black">1. Connect your Scanner</h5>
        </div>
        <div class="col-md-12 column-gap">
            <h5 class="heading_18_black">2. Scan {{$label['label']}} Envelope</h5>
            <div class="row g-3 align-items-center row-margin">
                <div class="col-md-3">
                    <label for="envelope" id="envelope_code" class="col-form-label">Envelope Code</label>
                </div>
                <div class="col-md-6">
                    <div class="text-wrapper">
                        <input type="text" id="envelope" name="envelope" class="form-control"
                            value="{{$data['envelope'] ?? ''}}" placeholder="Click to Scan"
                            aria-describedby="envelope_helper">
                        <span class="clear" id="clear_envelope"><img src="{{ asset('icons/clear.svg') }}" alt="img-del"
                                width="24"></span>
                    </div>
                </div>
                <div class="col-auto">
                    <span id="envelope_helper" class="form-text helper-span"></span>
                </div>
            </div>
        </div>
        <div class="col-md-12 column-gap">
            <h5 class="heading_18_black">3. Scan {{$label['label']}} Label</h5>
            <div class="row g-3 align-items-center row-margin">
                <div class="col-md-3">
                    <label for="label" id="label_code" class="col-form-label">Label Code</label>
                </div>
                <div class="col-md-6">
                    <input type="hidden" id="label_hidden" name="label_hidden" class="form-control"
                        value="{{$data['label'] ?? ''}}">
                    <div class="text-wrapper">
                        <input type="text" id="label" name="label" class="form-control" value="{{$data['label'] ?? ''}}"
                            placeholder="Click to Scan" aria-describedby="label_helper">
                        <span class="clear" id="clear_label"><img src="{{ asset('icons/clear.svg') }}" alt="img-del"
                                width="24"></span>
                    </div>
                </div>
                <div class="col-auto">
                    <span id="label_helper" class="form-text helper-span"></span>
                </div>
            </div>
        </div>
        <div class="col-md-12 column-gap">
            <h5 class="heading_18_black">4. Stick the {{$label['label']}} Label</h5>
        </div>

        <div class="col-md-12">
            <h5 class="heading_18_black">5. Upload Image of Label on Object</h5>
            <article class="edit-wrapper mt-3">
                <div class="image-preview flex-wrap" id="label-image-object">
                    @php($have_image = false)
                    @isset($data['images'])
                    @foreach($data['images'] as $image)
                    @php($have_image = true)
                    <a class="image position-relative">
                        <img src="{{config('app.image_url') . $image}}" alt="img" class="img-fluid label-image-list">
                        <div class="delete-inner cP">
                            <img class="deleteLabelImage" src="{{ asset('icons/delete_ic.svg') }}" alt="img-del"
                                width="40">
                            <img class="fetch_image_to_edit" data-from="{{$label['type']}}" data-img="{{$image}}"
                                data-imgtype="existing" src="{{ asset('icons/edit_ic.svg') }}" alt="img-del" width="40">
                        </div>
                    </a>
                    @endforeach
                    @endisset
                    <div class="upload-btn-wrapper cP {{ !$have_image ? 'w-100' : '' }} " id="width-100">
                        <!-- <div id="loaderImage"></div> -->
                        <section
                            class="vstack gap-3 align-items-center justify-content-center {{ $have_image ? 'd-none' : '' }}"
                            id="imageLabel1">
                            <div class="cP fs-18">Drop images to upload</div>
                            <div class="cP fs-14">Or</div>
                            <div class="cP btn cancel-btn">Select files</div>
                        </section>
                        <div id="imageLabel2" class="{{ !$have_image ? 'd-none' : '' }}">
                            <i class="fa fa-plus cP" aria-hidden="true"></i>
                            <span class="cP">Add Image</span>
                        </div>
                        <input type="file" id="upload-image-label-object" multiple="multiple" name="image"
                            class="image" />
                    </div>
                </div>
            </article>
        </div>
    </div>
</div>