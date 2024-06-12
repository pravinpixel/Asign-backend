@foreach ($data as $item)

    @php
        $s = $status[$item->status];

        $value = $item->inspection_date ? \App\Helpers\UtilsHelper::displayDate($item->inspection_date) : '';
        $datepicker = '';
        $disabled = 'disabled';
        if ($item->status == 'authentication') {
            $value = "";
        }
        if ($item->status == 'inspection') {
            $datepicker = 'datepicker';
            $disabled = '';
        }
        $team_id = $item->{$s['role'] . '_ids'} ?? '';
        $team_ids = $team_id ? explode(',', $team_id) : [];
    @endphp

    <tr class="row-class" data-id="{{ $item->id }}">
        <td scope="row" class="disabled-td">
            <div class="form-check redes-checkbox">
                <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
            </div>
        </td>
        <td data-label="Code"><span>{{ $item->request_id }}</span></td>
        <td>{{ ucfirst($item->account_type) }}</td>
        <td data-label="Name">
            <p>
                <span class="status {{ $item->is_online ? 'online' : 'offline' }}"></span>
                <span>{{ $item->aa_no }}</span>
            </p>
        </td>
        <td data-label="Type">
            <span>{{ $item->city }}</span>
        </td>
        <td data-label="City">
            <span class="{{ $s['color'] }} statusCtr" style="font-size: 13px">{{ $s['label'] }}</span>
        </td>
        <td data-label="Mobile" class="disabled-td">

            @if ($edit && ($s['id'] == 'authentication'))
                <div class="selectBoxDesign">
                    <select class="select2Box" name="team" data-placeholder="Team">
                        <option value=""></option>
                        @foreach ($roles[$s['role']] as $role)
                            @php
                                if($role['city_access'] == null || $role['city_access'] == '')
                                    continue;
                                $city_access_arr = explode(',', $role['city_access']);
                                $city_access = in_array($item->city_id, $city_access_arr);
                            @endphp
                            @if ($city_access)
                                <option value="{{ $role['id'] }}"
                                    {{ in_array($role['id'], $team_ids) ? 'selected' : '' }}>{{ $role['name'] }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>

            @elseif($s['id'] == "approved")
                <span>
                 @php
                     $status_timeline = json_decode($item->status_timeline, true);
                     if(isset($status_timeline['asign-protect'])) {
                        $approver_id = $status_timeline['asign-protect']['user_id'];
                          $user = App\Models\User::find($approver_id);
                            if($user){
                                echo $user->name;
                            }
                     }
                 @endphp
                </span>
            @elseif($s['label'] == "Review" || $s['label'] == "Rejected")
                <span>
                        @php
                            $a = array_search($item->reviewer_id, array_column($roles['supervisor'], 'id'));
                            if ($a !== false)
                                echo $roles['supervisor'][$a]['name'];
                        @endphp
                    </span>
            @else
                <span>
                        @foreach ($team_ids as $t)
                        @php
                            $a = array_search($t, array_column($roles[$s['role']], 'id'));
                            if ($a !== false) {
                                echo $roles[$s['role']][$a]['name'];
                            }
                        @endphp
                    @endforeach
                    </span>
            @endif

        </td>
        <td data-label="Email" class="disabled-td">
            @if($value)
              <span class="d-inline-block" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-html="true"
              title="<div>{{ \App\Helpers\UtilsHelper::displayDate($value, 'l, d M, Y') }}</div>
                                <div>{{$item->inspection_time}}</div>">
                  @endif
                <div class="tableDatePicker">
                    <input type="text" {{ $disabled }} class="form-control table-datepicks {{ $datepicker }}"
                           value="{{ $value }}" name="date" placeholder="Date" readonly="readonly">
                </div>
            @if($disabled)
              </span>
            @endif

        </td>
        <td data-label="Verification">
            {{ \App\Helpers\UtilsHelper::convertDateTimeToDay($item->created_at) }}
        </td>
    </tr>

@endforeach
