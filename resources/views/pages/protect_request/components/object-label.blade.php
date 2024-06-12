<div class="container-fluid extended">
    <div class="row">
        <div class="col-md-6">
            <div class="form-check">
                <label class="form-check-label" for="object_radio1">
                    Object Labels
                </label>
            </div>
        </div>
        <div class="col-md-6">
        </div>
    </div>
</div>

<article class="edit-wrapper">
    @foreach($data['labelling']['labels'] as $k => $l)
    <ul class="personal-info black-txt">
        @if($l['is_parent'] == true)
        <li>
            <span>&nbsp;</span>
            <span class="child_label_extended" style="cursor:pointer" id="next_span" data-formtype="standalone"
                data-span="{{$k=='Inventory'? 'inventory_label_child' : 'auth_label_child' }}">+ADD CHILD LABEL</span>
        </li>
        @endif
        <li>
            <span>{{$k}} Label</span>
            <span class="labelStatus">{{$l['text']}}</span>
            <ul class="label-li">
                @isset($l['parent'])
                <li>Parent:{{$l['parent']}}</li>
                @endisset
                @isset($l['child'])
                @foreach($l['child'] as $s => $c)
                <li>Child {{++$s}}:{{$c}}</li>
                @endforeach
                @endisset
            </ul>
        </li>
        <li>
            <span>{{$k}} Label <br />Pictures</span>
            <div class="image-preview image-preview-1 wrap-image-viewer">
                @isset($l['images'])
                @foreach($l['images'] as $i)
                @if($i)
                <a href="{{config('app.image_url') . $i}}" class="view_box_label{{substr($k, 0, 1)}}"
                    title="{{$k}} Label Pictures">
                    <img src="{{config('app.image_url') . $i}}" alt="" class="img-fluid">
                </a>
                @endif
                @endforeach
                @endisset
            </div>
        </li>
    </ul>
    @endforeach
</article>