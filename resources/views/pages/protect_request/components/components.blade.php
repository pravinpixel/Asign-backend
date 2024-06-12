@foreach($data['components'] as $k => $component)
    @php
        $k = isset($key) ? $key : $k;
        isset($component_status[$component['id']]) && $component_status[$component['id']]['verify'] == 'true' ? $checked = 'checked' : $checked = '';
        isset($disabled_object) && $disabled_object == 'disabled' ? $disabled_object = 'disabled' : $disabled_object = '';
    @endphp
    <div class="accordion-item" data-component-id="{{$component['id']}}">
        <div class="accordion-header">
            <div class="accordion-header-alt">
                <div>
                    <div class="form-check" data-value="{{$component['id']}}" data-type="components">
                        @if($disabled_object)
                            <input disabled {{$checked}} class="form-check-input"
                                   type="checkbox" id="gallery_one">
                        @else
                            <input {{$checked}} class="form-check-input objectDetails"
                                   type="checkbox" name="gallery_one" id="gallery_one">
                        @endif
                    </div>
                </div>
                <div>
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#components_tab_{{$component['id']}}" aria-expanded="false"
                            aria-controls="components_tab_{{$component['id']}}">
                        &nbsp; Component {{$k+1}}
                    </button>
                </div>
            </div>
        </div>
        <div id="components_tab_{{$component['id']}}" class="accordion-collapse collapse">
            <div class="accordion-body">
                <ul class="personal-info black-txt">
                    <div class="ul-image">
                        @if($component['cover_image'])
                            <img src="{{$component['cover_image']}}" alt="image">
                            <p>Component {{$k+1}}</p>
                        @endif
                    </div>
                    <li>
                        <span>Asign Object Number</span>
                        <span>{{$component['asign_no']}}</span>
                    </li>
                    <li>
                        <span>Accession Number</span>
                        <span>{{$component['accession_no']}}</span>
                    </li>
                </ul>
                <ul class="personal-info black-txt">
                    <div class="ul-title">Medium</div>
                    <li>
                        <span>Medium</span>
                        @php($mediums = [])
                        @if($component['medium_data'])
                            @php($mediums = array_column($component['medium_data']->toArray(), 'name') ?? [])
                        @endif
                        <span>{{$mediums ? implode(', ', $mediums) : ''}}</span>
                    </li>
                    <li>
                        <span>Surface</span>
                        @php($surface = [])
                        @if($component['surface_data'])
                            @php($surface = array_column($component['surface_data']->toArray(), 'name') ?? [])
                        @endif
                        <span>{{$surface ? implode(', ', $surface) : ''}}</span>
                    </li>
                    <li>
                        <span>Technique</span>
                        @php($technique = [])
                        @if($component['technique_data'])
                            @php($technique = array_column($component['technique_data']->toArray(), 'name') ?? [])
                        @endif
                        <span>{{$technique ? implode(', ', $technique) : ''}}</span>
                    </li>
                </ul>
                <ul class="personal-info black-txt">
                    <div class="ul-title">Measurements</div>
                    <li>
                        <span>Shape</span>
                        <span>{{$component['shape']['name'] ?? ''}}</span>
                    </li>
                    <li>
                        <span>Measurement Type</span>
                        <span>{{$component['measurement_type']['name'] ?? ''}}</span>
                    </li>
                    <li>
                        <span>Height</span>
                        <span>{{$component['height'] ?  $component['height']  .' '. $component['dimension_size'] : '' }}</span>
                    </li>
                    <li>
                        <span>Width</span>
                        <span>{{$component['width'] ?  $component['width']  .' '. $component['dimension_size'] : '' }}</span>
                    </li>
                    <li>
                        <span>Depth</span>
                        <span>{{$component['depth'] ?  $component['depth']  .' '. $component['dimension_size'] : '' }}</span>
                    </li>
                    <li>
                        <span>Diameter</span>
                        <span>{{$component['diameter'] ?  $component['diameter']." ".$component['dimension_size'] : '' }}</span>
                    </li>
                    <li>
                        <span>Weight</span>
                        <span>{{$component['weight'] ?  $component['weight'].$component['weight_size'] : '' }}</span>
                    </li>
                </ul>
                <ul class="personal-info black-txt">
                    <div class="ul-title">Signature & Inscriptions</div>
                    <li>
                        <span>Signature</span>
                        <span>{{$component['is_signature'] ? 'Yes' : 'No'}}</span>
                    </li>
                    <li>
                        <span>Description</span>
                        <span>{{$component['description']}}</span>
                    </li>
                    <li>
                        <span>Inscriptions</span>
                        <span>{{$component['is_inscription'] ? 'Yes' : 'No'}}</span>
                    </li>
                </ul>
                <ul class="personal-info black-txt">
                    <div class="ul-title">Location</div>
                    <li>
                        <span>Save Location As</span>
                        <span>{{$component['location_as']}}</span>
                    </li>
                    <li>
                        <span>Sub-Location</span>
                        <span>{{$component['sub_location']}}</span>
                    </li>
                    <li>
                        <span>Address</span>
                        <span>{{$component['address_line1']}}</span>
                    </li>
                    <li>
                        <span>City</span>
                        <span>{{$component['city']}}</span>
                    </li>
                    <li>
                        <span>State</span>
                        <span>{{$component['state']['name'] ?? ''}}</span>
                    </li>
                    <li>
                        <span>Country</span>
                        <span>{{$component['country']['name'] ?? ''}}</span>
                    </li>
                    <li>
                        <span>Pincode</span>
                        <span>{{$component['pin_code']}}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

@endforeach

