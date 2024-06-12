<div id="customer_activity_log_section">
@foreach($data->activity as $log)
@php
if($log->tag == "customer"){
 $fn=$log->customer->full_name;
}else{
 $fn=$log->user->name;
}
$words = explode(" ",$fn);
$acronym = "";
foreach ($words as $w) {
    $acronym .= $w[0];
}
if(strlen($acronym)>=2){
 $name=$acronym[0].$acronym[1];
}else{
 $name=$acronym[0];
}
$includesRejected = strpos($log->message, 'Rejected Representation') !== false;
$reason = '';
  if ($includesRejected) {
      $reasonIndex = strpos($log->message, 'Reason: ');
      if ($reasonIndex !== false) {
          $reason = substr($log->message, $reasonIndex + strlen('Reason: '));
      }
  }
@endphp
 <div class="d-flex gap-3 mb-3">
                          <div class="profile-div orange">
                            <span class="profile-avatar">  {{$name}}</span>
                          </div>
                          <div class="d-flex align-items-center gap-1 profile-in">
                          
                           <div class="vstack gap-1" style="display: flex;justify-content:center">
                            <div>
                              <span class="activity-name">{{ $fn }}</span>
                                @if ($includesRejected)
                                  <span class="activity-profile">Rejected Representation</span>
                                @else
                                    <span class="activity-profile">{{ $log->message }}</span>
                                @endif
                            </div>
                            @if ($includesRejected)
                            <div data-bs-toggle="tooltip" data-bs-placement="top" title="{{$reason}}">
                              @if (strlen($reason) > 40)
                              {{ substr(strip_tags($reason??''), 0, 40) }}...
                              @else
                              Reason: {{$reason}}
                              @endif
                            </div>
                            @endif
                           </div>
                            <span class="dot mx-2 dot-activity"></span>
                            <span class="activity-date">{{ date('d M Y h:i A', strtotime($log->created_at)) }}</span>
                          </div>
                        </div>
@endforeach
</div>