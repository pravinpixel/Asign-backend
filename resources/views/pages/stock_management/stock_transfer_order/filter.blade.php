<div class="section-filter filter-index">
    <div class="filter-setup">
        <div class="search-bar">
            <div class="input-group filter-with">
                <div class="addon-search">
                    <i class='bx bx-search fs-4'></i>
                </div>
                <input type="text" name="search" placeholder="Search Order Number, Location etc." class="form-control" aria-label="Text input with dropdown button">
            </div>
        </div>
        <div class="filter-bar" id="fil">
            <button id="toggle_search" type="button" class="btn btn-light">
            <div class="hstack">
                <div>
                    <i class='bx bx-filter-alt' style="padding-right: 10px;"></i>
                    Filters
                </div>
                <div>
                    <span class="" id="filter_count"></span>
                </div>
            </div>
            </button>
        </div>
        <div class="filter-bar">
            <span class="clear-all" style="display: none;" id="clear-all">Clear All</span>
        </div>
        <div class="dropbar-bar"
            @if (!access()->hasAccess('stock-transfer-order.create'))  data-toggle="tooltip" data-placement="bottom" title="You don’t have Create permission"
            @endif >
            <a href="{{url('stock-transfer-orders/create')}}" type="button" class="btn apply-btn"
            @if (!access()->hasAccess('stock-transfer-order.create')) disabled
                @endif >
                Create STO
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
                <select multiple="multiple" placeholder="Select Stock source" class="custom_select" name="stock_source_id">
                    @foreach ($delivery_locations as $delivery_location)
                        <option value="{{ $delivery_location['id'] }}">{{ $delivery_location['location'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-12 col-md-3 custom_select_1">
                <select multiple="multiple" placeholder="Select Stock destination" class="custom_select" name = "stock_destination_id">
                    @foreach ($delivery_locations as $delivery_location)
                        <option value="{{ $delivery_location['id'] }}">{{ $delivery_location['location'] }}</option>
                    @endforeach
                </select>
            </div>
            {{-- <div class="col-sm-12 col-md-3 custom_select_1">
                <select multiple="multiple" placeholder="Select Transfer Reason" class="custom_select" name = "transfer_reason_id">
                    @foreach ($transfer_reasons as $transfer_reason)
                        <option value="{{ $transfer_reason }}">{{ $transfer_reason }}</option>
                    @endforeach
                </select>
            </div> --}}
        </div>
    </div>
</div>
