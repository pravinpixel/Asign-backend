<div class="section-filter filter-index">
    <div class="filter-setup">
        <div class="search-bar">
            <div class="input-group filter-with">
                <div class="addon-search">
                    <i class='bx bx-search fs-4'></i>
                </div>
                <input type="text" placeholder="Search Order Number, Location etc. " class="form-control" aria-label="Text input with dropdown button">
            </div>
        </div>
        <div class="dropbar-bar"  @if (!access()->hasAccess('damages.create'))  data-toggle="tooltip" data-placement="bottom" title="You don’t have Create permission" 
            @endif >
            <a href="{{url('/label-damaged/create')}}" type="button" class="btn apply-btn"  @if (!access()->hasAccess('damages.create')) disabled 
                @endif >
                Add Damaged Label
            </a>
        </div>
    </div>
</div>