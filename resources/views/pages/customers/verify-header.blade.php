@php
    $disabled_verify = '';
    $disabled_pause = '';
    $show_reject_review = false;

    if($data->status == 'verified') {
        $disabled_verify = 'disabled';
    }elseif($data->status == 'paused'){
        $disabled_pause = 'disabled';
    }elseif ($data->status == 'review'){
        $show_reject_review = true;
    }elseif($data->status == 'unverified'){
        $disabled_verify = 'disabled';
        $disabled_pause = 'disabled';
    }
@endphp

<button type="button" class="btn apply-btn change-status" {{$disabled_verify}} data-value="verified">
    Verify
</button>
@if(!$show_reject_review)
<button type="button" class="btn paused-status {{$disabled_pause ? 'apply-btn' : 'cancel-btn'}}" {{$disabled_pause}} data-value="paused">
    Pause Profile
</button>
@else
    <button type="button" class="btn cancel-btn paused-status" id="rejectReview" data-value="paused">
        Reject Review
    </button>
@endif

