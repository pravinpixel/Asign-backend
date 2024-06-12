<div class="section-filter filter-index">
    <div class="filter-setup">
        <div class="search-bar">
            <div class="input-group filter-with">
                <div class="addon-search">
                    <i class='bx bx-search fs-4'></i>
                </div>
                <input type="text" placeholder="Search Order Number, Location etc." class="form-control" aria-label="Text input with dropdown button">
            </div>
        </div>
        <div class="filter-bar">
            <button id="toggle_search" type="button" class="btn btn-light"><i class='bx bx-filter-alt' style="padding-right: 10px;"></i>Filters</button>
        </div>
        <div class="dropbar-bar">
            <a type="button" class="btn apply-btn">
                Create GRN
            </a>
        </div>
    </div>
    <div id="filter_panel" class="close">
        <div class="row filter-focus mt-3 mb-2">
            <div class="col-sm-12 col-md-3 login-select">
                <select multiple="multiple" placeholder="Select Login Status" class="custom_select">
                    <option value="O1" data-badge="">Logged In</option>
                    <option value="O2" data-badge="">
                        Logged Out
                    </option>
                </select>
            </div>
            <div class="col-sm-12 col-md-3 custom_select_1">
                <select multiple="multiple" placeholder="Select Type" class="custom_select">
                    <option value="O1" data-badge="">Artist</option>
                    <option value="O2" data-badge="">
                        Bussiness
                    </option>
                    <option value="O2" data-badge="">
                        Bussiness
                    </option>
                    <option value="O2" data-badge="">
                        Bussiness
                    </option>
                </select>
            </div>
            <div class="col-sm-12 col-md-3 custom_select_1">
                <select multiple="multiple" placeholder="Select City" class="custom_select">
                    <option value="O1" data-badge="">Mumbai</option>
                    <option value="O2" data-badge="">
                        Chennai
                    </option>
                </select>
            </div>
            <div class="col-sm-12 col-md-3 custom_select_1">
                <select multiple="multiple" placeholder="Select Verification Status" class="custom_select">
                    <option value="O1" data-badge="">Passed</option>
                    <option value="O2" data-badge="">
                        Verified
                    </option>
                </select>
            </div>
        </div>
    </div>
</div>
