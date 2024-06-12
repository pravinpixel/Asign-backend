@php
    $disablePrev = $paginate['from'] == 1 ? 'disabled' : '';
    $disableNext = $paginate['to'] >= $paginate['total'] ? 'disabled' : '';
    $perPage = $paginate['per_page'];
@endphp
<div>
    <div><span>Rows:</span></div>
    <div>
        <select class="form-select form-select-sm" aria-label="per_page" id="per_page">
            <option value="10" @if($perPage == "10") selected @endif>10</option>
            <option value="25" @if($perPage == "25") selected @endif>25</option>
            <option value="50" @if($perPage == "50") selected @endif>50</option>
            <option value="100" @if($perPage == "100") selected @endif>100</option>
        </select>
    </div>
    <div class="paginate">
        <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="pagination">
            <div class="btn-group" role="group" aria-label="First group">
                <button type="button" class="btn btn-outline-secondary btn-light btn-sm paginate-btn" data-move="prev" {{$disablePrev}}>
                    <i class='bx bx-chevron-left'></i>
                </button>
                <button type="button" class="btn btn-outline-secondary btn-light btn-sm">
                    <span id="from">{{$paginate['current_page']}}</span> of <span id="to">{{ ceil($paginate['total']/$perPage) }}</span>
                </button>
                <button type="button" class="btn btn-outline-secondary btn-light btn-sm paginate-btn" data-move="next" {{$disableNext}}>
                    <i class='bx bx-chevron-right'></i>
                </button>
            </div>
        </div>
    </div>
</div>
