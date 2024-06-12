@php
    $user_id = auth()->user()->id;
    $authenticator =  $data['authenticator_ids'] ? explode(',', $data['authenticator_ids']) : [];
    $conservator =  $data['conservator_ids'] ? explode(',', $data['conservator_ids']) : [];
    $field_agent =  $data['field_agent_ids'] ? explode(',', $data['field_agent_ids']) : [];
    $service_provider =  $data['service_provider_ids'] ? explode(',', $data['service_provider_ids']) : [];

    $inspection_date = \App\Helpers\UtilsHelper::displayDate($data['inspection_date'], 'l, d M, Y');
    $visit_date = \App\Helpers\UtilsHelper::displayDate($data['visit_date'], 'l, d M, Y');


    $disabled_request = '';
    $disabled_date = '';

    if (in_array($user_id, $conservator) || in_array($user_id, $field_agent) || in_array($user_id, $service_provider)) {
         $disabled_request = 'disabled';
         $disabled_date = 'disabled';
    }

    if($data['status']['id'] != 'authentication' ) {
        $disabled_request = 'disabled';
        $disabled_date = 'disabled';
    }

    if($data['status']['id'] == 'inspection' && in_array($user_id, $conservator)) {
        $disabled_request = 'disabled';
        $disabled_date = '';
    }

    $auth_edit_access = access()->hasAccess('authentication-request.edit');
    if(!$auth_edit_access) {
        $disabled_request = 'disabled';
        $disabled_date = 'disabled';
    }

@endphp
<section class="section-inner">
    <ul class="personal-info">
        <li>
            <span>Customer Name</span>
            <span>{{$data['customer_name']}}</span>
        </li>
        <li>
            <span>Customer Type</span>
            <span>{{ucfirst($data['customer_account_type'])}}</span>
        </li>
        <li>
            <span>Phone Number</span>
            <span>(+91) {{$data['customer_mobile']}}</span>
        </li>
        <li>
            <span>Address</span>
            <span>{{$data['customer_address']}}</span>
        </li>
    </ul>
</section>
<section class="section-inner top-border">
    <h1>Team</h1>
    <form id="team_form">
        <div class="row">
            <div class="col-12 col-md-6">
                <ul class="personal-info vertics grey-txt">
                    <li>
                        <span>Authenticator</span>
                        <span class="pinkish">
                            <select class="colorSelect" {{$disabled_request}} name="authenticator_ids" multiple
                                    data-placeholder="Add Primary Authenticator"
                                    data-openplacehoder="Search for Authenticators"
                                    data-hideplacehoder="Add Primary Authenticator"
                                    data-allow-clear="1"
                                    data-color="pinkish">
                                @foreach($roles['authenticator'] as $role)
                                    <option value="{{$role['id']}}"
                                        {{in_array($role['id'], $authenticator) ? 'selected' : '' }}>
                                        {{$role['name']}}</option>
                                @endforeach
                            </select>
                        </span>
                    </li>
                    <li>
                        <span>Conservator</span>
                        <span class="violets">
                        <select class="colorSelect" {{$disabled_request}} name="conservator_ids" multiple
                                data-placeholder="Add Conservators"
                                data-openplacehoder="Search for Conservators"
                                data-hideplacehoder="Add Conservators"
                                data-allow-clear="1"
                                data-color="pinkish">
                            @foreach($roles['conservator'] as $role)
                                <option
                                    value="{{$role['id']}}" {{in_array($role['id'], $conservator) ? 'selected' : ''}}>{{$role['name']}}</option>
                            @endforeach
                        </select>
                    </span>
                    </li>
                    <li>
                        <span>Inspection Date</span>
                        <span>
                        <input {{$disabled_date}} value="{{$inspection_date}}" type="text"
                               class="datepicker form-control"
                               name="inspection_date" id="inspection_date"
                               placeholder="Select Date" readonly="readonly">
                    </span>
                    </li>
                    <li>
                        <span>Inspection Time</span>
                        <span>
                               <div class="w100Select">
                                   <select {{$disabled_request}}
                                           class="timepicker"
                                           name="inspection_time"
                                           data-placeholder="Select time"
                                           data-allow-clear="1">
                                    <option value=""></option>
                                       <optgroup class="optgroup-text" label="Select range"></optgroup>
                                        @foreach($master['time_range'] as $time)
                                           <option value="{{$time}}"
                                               {{ isset($data['inspection_time']) &&  $time == $data['inspection_time']
                                                ? 'selected' : '' }}
                                           >{{$time}}</option>
                                       @endforeach
                                   </select>
                                </div>
                        </span>
                    </li>
                </ul>
            </div>
            <div class="col-12 col-md-6">
                <ul class="personal-info vertics grey-txt">
                    <li>
                        <span>Field Agents</span>
                        <span class="blueish">
                        <select class="colorSelect" {{$disabled_request}} name="field_agent_ids" multiple
                                data-placeholder="Add Field Agents"
                                data-openplacehoder="Search for Field Agents"
                                data-hideplacehoder="Add Field Agents"
                                data-allow-clear="1">
                            @foreach($roles['field_agent'] as $role)
                                <option
                                    value="{{$role['id']}}"  {{in_array($role['id'], $field_agent) ? 'selected' : '' }}>{{$role['name']}}</option>
                            @endforeach
                        </select>
                    </span>
                    </li>
                    <li>
                        <span>Service Provider</span>
                        <span class="blueish">
                        <select class="colorSelect" {{$disabled_request}} name="service_provider_ids" multiple
                                data-placeholder="Add Service Provider"
                                data-openplacehoder="Search for Service Provider"
                                data-hideplacehoder="Add Service Provider"
                                data-allow-clear="1">
                            @foreach($roles['service_provider'] as $role)
                                <option
                                    value="{{$role['id']}}"  {{in_array($role['id'], $service_provider) ? 'selected' : '' }}>{{$role['name']}}</option>
                            @endforeach
                        </select>
                    </span>
                    </li>
                    <li>
                        <span>Visit Date</span>
                        <span>
                        <input {{$disabled_date}} value="{{$visit_date}}" type="text" class="datepicker form-control"
                               name="visit_date"
                               id="visit_date"
                               placeholder="Select Date"
                               readonly="readonly">
                    </span>
                    </li>
                    <li>
                        <span>Visit Time</span>
                        <span>
                            <div class="w100Select">
                                   <select {{$disabled_request}}
                                           class="timepicker"
                                           name="visit_time"
                                           data-placeholder="Select time"
                                           data-allow-clear="1">
                                       <optgroup class="optgroup-text" label="Select range"></optgroup>
                                    <option value=""></option>
                                       @foreach($master['time_range'] as $time)
                                           <option value="{{$time}}"
                                               {{ isset($data['visit_time']) &&  $time == $data['visit_time'] ? 'selected' : '' }}>
                                               {{$time}}
                                           </option>
                                       @endforeach
                                   </select>
                                </div>
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </form>
</section>
<section class="section-inner top-border">
    <h1>Artwork</h1>
    <div class="row">
        <div class="col-12 col-md-6">
            <article id='artwork_gallery'>
                <div class="artwork_thumbs">
                    <ul>
                        @foreach($data['images'] as $k => $image)
                            <li><img src="{{$image->value}}" alt="{{$image->title}}"/></li>
                        @endforeach
                    </ul>
                </div>
                <div class="artwork_preview">
                    @if($data['images']->count() > 0)
                        <img src="{{$data['images'][0]->value}}" alt="{{$data['images'][0]->title}}"/>
                    @endif
                </div>
            </article>
        </div>
        <div class="col-12 col-md-6">
            <article class="artwork_details">
                <h5>{{$data['artist']['display_name'] ?? $data['unknown_artist'] ?? ''}}</h5>
                <h1>{{$data['title']}}</h1>
                <p><img src="{{asset('icons/location_alt.png')}}" alt=""/> {{$data['location']}} </p>
                <ul class="personal-info black-txt">
                    <li>
                        <span>About</span>
                        <span>
                            Object Type: {{$data['type']['name'] ?? '' }} <br/>
                            In possession: {{$data['is_your_possession'] ? 'Yes' : 'No'}}
                        </span>
                    </li>
                    <li>
                        <span>Medium</span>
                        <span>
                            Material: {{$data['mediums'] ? $data['mediums']->pluck('name')->implode(', ') : ''}}<br/>
                            Surface: {{$data['surfaces'] ? $data['surfaces']->pluck('name')->implode(', ') : ''}}<br/>
                        </span>
                    </li>
                    <li>
                        <span>Primary Measurement</span>
                        <span>
                            Shape: {{$data['shape']['name'] ?? ''}} <br/>
                            Size: N/A<br/>
                            Diameter: {{$data['diameter'] ?  $data['diameter']." ".$data['dimension_size'] : '' }}<br/>
                            Weight: {{$data['weight'] ?  $data['weight'].$data['weight_size'] : '' }}<br/>
                        </span>
                    </li>
                </ul>
            </article>
        </div>
    </div>
</section>
<section class="section-inner activity top-border">
    <h1>Activity</h1>
    <div class="row">
        <div class="col-12 col-md-6">
            <article>
                <div class="mb-3 pos-rel">
                    <label for="comments" class="form-label">
                        Notes
                        <span><span>as</span>  {{auth()->user()->name}}({{auth()->user()->role->name ?? ''}})</span>
                    </label>
                    <textarea class="form-control" name="message" id="comments" rows="3"
                              placeholder="Write a comment..."></textarea>
                    <button type="button" class="btn btn-light comment-submit" id="commentBtn">Add Comment</button>

                </div>
            </article>
        </div>
    </div>
    <div class="row">
        <div class="col-6" id="activity-log">
            <div class="col-12">
                <article>
                    <ul>
                        @include('pages.protect_request.components.activity')
                    </ul>
                </article>
            </div>
        </div>
    </div>
</section>
