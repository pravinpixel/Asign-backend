@foreach($data['locations'] as $k => $location)
    @php
        $k = isset($key) ? $key : $k;
        isset($location_status[$location['id']]) && $location_status[$location['id']]['verify'] == 'true' ? $checked = 'checked' : $checked = '';
        isset($disabled_object) && $disabled_object == 'disabled' ? $disabled_object = 'disabled' : $disabled_object = '';
    @endphp
    <div class="accordion-item">
        <div class="accordion-header">
            <div class="accordion-header-alt">
                <div>
                    <div class="form-check" data-value="{{$location['id']}}" data-type="locations">
                        @if($disabled_object)
                            <input disabled {{$checked}} class="form-check-input" type="checkbox" id="gallery_one">
                        @else
                            <input {{$checked}} class="form-check-input objectDetails" type="checkbox"
                                   name="gallery_one" id="gallery_one">
                        @endif
                    </div>
                </div>
                <div>
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#locationAccordion_tab_{{$location['id']}}" aria-expanded="false"
                            aria-controls="locationAccordion_tab_{{$location['id']}}">
                        &nbsp; {{$location['location_as'] != '' ? $location['location_as'] : 'Secondary Location '. $k+1}}
                    </button>
                </div>
            </div>
        </div>
        <div id="locationAccordion_tab_{{$location['id']}}" class="accordion-collapse collapse">
            <div class="accordion-body">
                <ul class="personal-info black-txt">

                    <li>
                        <span>Sub-Location</span>
                        <span>{{$location['sub_location'] ?? ''}}</span>
                    </li>
                    <li>
                        <span>Address</span>
                        <span>{{$location['address_line1'] ?? ''}}</span>
                    </li>
                    <li>
                        <span>City</span>
                        <span>{{$location['city'] ?? ''}}</span>
                    </li>
                    <li>
                        <span>State</span>
                        <span>{{$location['state']->name ?? ''}}</span>
                    </li>
                    <li>
                        <span>Country</span>
                        <span>{{$location['country']->name ?? ''}}</span>
                    </li>
                    <li>
                        <span>Pincode</span>
                        <span>{{$location['pin_code'] ?? ''}}</span>
                    </li>

                </ul>
            </div>
        </div>
    </div>

@endforeach

