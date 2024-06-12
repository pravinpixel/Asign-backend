@php
    $edit = isset($edit) ? $edit : false;
@endphp

<ul class="personal-info black-txt">
    <div class="ul-title">Medium</div>
    @if($edit)
        @php
            $medium_ids = $data['medium_ids'] ? explode(',', $data['medium_ids']) : [];
            $surface_ids = $data['surface_ids'] ? explode(',', $data['surface_ids']) : [];
        @endphp

        <li>
            <span>Medium</span>
            <span class="w100Select">
                <span class="grey">
                    <select name="medium[]" data-placeholder="Select Medium" class="form-control select2Box" multiple>
                        <option value="">Select Medium</option>
                        @foreach($master['medium'] as $medium)
                            <option
                                value="{{$medium['id']}}" {{in_array($medium['id'], $medium_ids) ? 'selected' : ''}}>{{$medium['name']}}</option>
                        @endforeach
                    </select>
                </span>
            </span>
        </li>
        <li>
            <span>Surface</span>
            <span class="w100Select">
                <span class="grey">
                    <select name="surface[]" data-placeholder="Select Surface" class="form-control select2Box" multiple>
                        <option value="">Select Surface</option>
                      @foreach($master['surface'] as $surface)
                            <option
                                value="{{$surface['id']}}" {{in_array($surface['id'], $surface_ids) ? 'selected' : ''}}>{{$surface['name']}}</option>
                        @endforeach
                    </select>
                </span>
            </span>
        </li>

    @else
        <li>
            <span>Medium</span>
            <span>{{$data['mediums'] ? $data['mediums']->pluck('name')->implode(', ') : ''}}</span>
        </li>
        <li>
            <span>Surface</span>
            <span> {{$data['surfaces'] ? $data['surfaces']->pluck('name')->implode(', ') : ''}}</span>
        </li>
    @endif

</ul>
<ul class="personal-info black-txt select-box">
    <div class="ul-title">Measurements</div>
    @if($edit)
        <li>
            <span>Shape</span>
            <span class="w100Select">
            <select name="shape_id" data-placeholder="Select Shape" class="form-control select2Box">
                 <option value="">Select Shape</option>
                   @foreach($master['shape'] as $shape)
                    <option
                        value="{{$shape['id']}}" {{isset($data['shape']) && $shape['id'] == $data['shape']['id'] ? 'selected' : '' }}>{{$shape['name']}}</option>
                @endforeach
            </select>
            </span>
        </li>
        <li>
            <span>Measurement Type</span>
            <span class="w100Select">
            <select name="measurement_type_id" data-placeholder="Select  Measurement Type"
                    class="form-control select2Box">
                 <option value="">Select Measurement Type</option>
                   @foreach($master['measurement_type'] as $measurement_type)
                    <option
                        value="{{$measurement_type['id']}}" {{ isset($data['measurement_type']) && $measurement_type['id'] == $data['measurement_type']['id'] ? 'selected' : '' }}>{{$measurement_type['name']}}</option>
                @endforeach
            </select>
            </span>
        </li>
        <li>
            <span>Dimension</span>
            <span class="w100Select">
            <select name="dimension_size" data-placeholder="Select  Dimension"
                    class="form-control select2Box">
                 <option value="">Select Dimension</option>
                 <option value="in"  {{ $data['dimension_size'] == 'in' ? 'selected' : ''  }} >in</option>
                <option value="cm"  {{ $data['dimension_size'] == 'cm' ? 'selected' : ''  }}>cm</option>
            </select>
            </span>
        </li>

        <li>
            <span>Height</span>
            <span>
                <input type="text" maxlength="12" name="height" value="{{$data['height']}}" class="form-control">
            </span>
        </li>
        <li>
            <span>Width</span>
            <span>
                <input type="text" maxlength="12" name="width" value="{{$data['width']}}" class="form-control">
            </span>
        </li>
        <li>
            <span>Depth</span>
            <span>
                <input type="text" maxlength="12" name="depth" value="{{$data['depth']}}" class="form-control">
            </span>
        </li>
        <li>
            <span>Diameter</span>
            <span>
                <input type="text" maxlength="12" name="diameter" value="{{$data['diameter']}}" class="form-control">
            </span>
        </li>
        <li>
            <span>Weight</span>
            <span>{{$data['weight'] ?  $data['weight']." ".$data['weight_size'] : '' }}</span>
        </li>

    @else

        <li>
            <span>Shape</span>
            <span>{{$data['shape']['name'] ?? ''}}</span>
        </li>
        <li>
            <span>Measurement Type</span>
            <span>{{$data['measurement_type']['name'] ?? ''}}</span>
        </li>
        <li>
            <span>Height</span>
            <span>{{$data['height'] ?  $data['height']  .' '. $data['dimension_size'] : '' }}</span>
        </li>
        <li>
            <span>Width</span>
            <span>{{$data['width'] ?  $data['width']  .' '. $data['dimension_size'] : '' }}</span>
        </li>
        <li>
            <span>Depth</span>
            <span>{{$data['depth'] ?  $data['depth']  .' '. $data['dimension_size'] : '' }}</span>
        </li>
        <li>
            <span>Diameter</span>
            <span>{{$data['diameter'] ?  $data['diameter']." ".$data['dimension_size'] : '' }}</span>
        </li>
        <li>
            <span>Weight</span>
            <span>{{$data['weight'] ?  $data['weight']." ".$data['weight_size'] : '' }}</span>
        </li>

    @endif
</ul>
