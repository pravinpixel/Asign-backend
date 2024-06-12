<div class="section-filter filter-index">
    <div class="filter-setup">
        <div class="search-bar">
            <div class="input-group filter-with">
                <div class="addon-search">
                    <i class='bx bx-search fs-4'></i>
                </div>
                <input type="text" id="grn_search" name="search" placeholder="Search Order Number, Location etc." class="form-control" aria-label="Text input with dropdown button">
            </div>
        </div>
        <div class="filter-bar">
            <button id="toggle_search" type="button" class="btn btn-light"><i class='bx bx-filter-alt' style="padding-right: 10px;"></i>Filters</button>
        </div>
        <div class="filter-bar">
            <span class="clear-all" id="clear-all" style="display: none;">Clear All</span>
        </div>
        <div class="dropbar-bar" @if (!access()->hasAccess('goods-received-note.create'))  data-toggle="tooltip" data-placement="bottom" title="You don’t have Create permission"
            @endif >
            <a class="btn apply-btn create_grn_id"  @if (!access()->hasAccess('goods-received-note.create')) disabled
                @endif >
                Create GRN
            </a>
        </div>
        <div class="dropdown-bar">
            <i class='bx bx-dots-vertical-rounded fs-4 dropdown-toggle' data-bs-toggle="dropdown" aria-expanded="false"></i>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" id="download-excel" href="#">Export</a></li>
            </ul>
        </div>
    </div>
    <div id="filter_panel" class="close">
        <div class="row filter-focus mt-3 mb-2">
            {{-- <div class="col-sm-12 col-md-3 custom_select_1">
                <select multiple="multiple" placeholder="Select Sender" class="custom_select" name="manufacturer">
                    @foreach ($manufacturers as $manufacturer)
                        <option value="{{ $manufacturer }}">{{ $manufacturer }}</option>
                    @endforeach
                </select>
            </div> --}}
            <div class="col-sm-12 col-md-3 custom_select_1">
                <select multiple="multiple" placeholder="GRN Location" class="custom_select" name="delivery_location">
                    @foreach ($delivery_locations as $delivery_location)
                        <option value="{{ $delivery_location }}">{{ $delivery_location }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>

