<div class="section-filter filter-index">
    <div class="filter-setup">
        <div class="search-bar">
            <div class="input-group filter-with">
                <div class="addon-search">
                    <i class='bx bx-search fs-4'></i>
                </div>
                <input type="text" id="purchase_search" name="search" placeholder="Search Order Number, Location etc." class="form-control" aria-label="Text input with dropdown button">
            </div>
        </div>
        <div class="filter-bar" id="fil">
            <button id="toggle_search" type="button" class="btn btn-light">
                <div class="hstack">
                    <div>
                        <i class='bx bx-filter-alt' style="padding-right: 10px;">
                        </i>Filters
                    </div>
                    <div>
                        <span class="" id="filter_count"></span>
                    </div>
                </div>
            </button>
        </div>
        <div class="filter-bar">
            <span class="clear-all" id="clear-all">Clear All</span>
        </div>
        <div class="dropbar-bar"
            @if (!access()->hasAccess('purchase-order.create'))  data-toggle="tooltip" data-placement="bottom" title="You don’t have Create permission"
                @endif >
            <a href="{{ url('label-orders/add') }}" type="button" class="btn apply-btn"
                @if (!access()->hasAccess('label-order.add')) disabled
                @endif>
                Create PO
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
            <div class="col-sm-12 col-md-3 custom_select_1">
                <select multiple="multiple" placeholder="Select Manufacturer" class="custom_select" name="manufacturer">
                    @foreach ($manufacturers as $manufacturer)
                        <option value="{{ $manufacturer }}">{{ $manufacturer }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-12 col-md-3 custom_select_1">
                <select multiple="multiple" placeholder="Select Location" class="custom_select" name="delivery_location">
                    @foreach ($delivery_locations as $delivery_location)
                        <option value="{{ $delivery_location }}">{{ $delivery_location }}</option>
                    @endforeach
                </select>
            </div>

        </div>
    </div>
</div>
