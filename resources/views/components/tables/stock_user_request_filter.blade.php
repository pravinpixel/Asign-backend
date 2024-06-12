<div class="section-filter filter-index filter-index-1">
    <div class="filter-setup justify-content-start">
        <div class="search-bar">
            <div class="input-group filter-with" style="width: 431px;">                
                <div class="divide" style="background: transparent;">
                    <i class='bx bx-search fs-4'></i>
                </div>
                <input type="text" placeholder="Name" class="form-control" aria-label="Text input with dropdown button" name="search">
            </div>
        </div>
        <div class="filter-bar">
            <button id="toggle_search" type="button" class="btn btn-light"><i class='bx bx-filter-alt' style="padding-right: 10px;"></i>Filters</button>
        </div>
    </div>
    <div id="filter_panel" class="close">
        <div class="row filter-focus mt-3 mb-2">
            <div class="col-sm-12 col-md-3 login-select">
                <select multiple="multiple" placeholder="Select Label Status" class="custom_select">
                    <option value="issued" data-badge="">Issued</option>
                    <option value="consumed" data-badge="">Consumed
                    </option>
                     <option value="damaged" data-badge="">Damaged
                    </option>
                    <option value="adjust" data-badge="">Adjust
                    </option>
                     <option value="returned" data-badge="">Returned
                    </option>
                </select>
            </div>
        </div>
    </div>
</div>