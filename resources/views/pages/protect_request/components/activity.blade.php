@foreach($data['activities'] as $k => $activity)

    @php
        $has_online = false;
        $role = '-1';
        if($activity->tag == 'user') {
            $name = $activity->user?->name;
            $role = $activity->user?->role_id;
            $has_online = $activity->user_id == auth()->id();
        }
        else {
            $name = $activity->customer?->full_name;
        }
        $color = \App\Helpers\UtilsHelper::getRoleColor($role);
    @endphp

    <li class="d-flex gap-3 mb-3">
        <div class="profile-div {{$color}}">
            <span class="profile-avatar">
                {{\App\Helpers\UtilsHelper::displayNameAvatar($name)}}
            </span>
        </div>
        <div class="w-90">
            <div class="d-flex align-items-center profile-in @if(!$activity->message) h-100 @endif">
                <span class="activity-name">{{$name}}</span>
                <span class="activity-profile">{{$activity->title}}</span>
                <span class="dot mx-2 dot-activity"></span>
                <span class="activity-date">
                    {{\App\Helpers\UtilsHelper::displayDateTime($activity->created_at)}}
                </span>
            </div>
            @if($activity->message)
                <div class="commentsInner @if($has_online) grey @endif">
                    {{$activity->message}}
                </div>
            @endif
        </div>
    </li>
@endforeach

