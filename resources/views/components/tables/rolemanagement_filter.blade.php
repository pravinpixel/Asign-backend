<div class="section-filter-1 filter-index">
    <div class="filter-setup">
        <div class="search-bar">
            <div class="input-group filter-with">
                <button class="btn btn-light dropdown-toggle filter-dropy" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span>Name</span> <i class='bx bx-chevron-down'></i>
                </button>
                <ul class="dropdown-menu width-ul">
                    <li class="li-one padding-li">
                        <div class="custom-check">
                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="all">
                            <label class="radio-label font-drop" for="all">
                                All
                            </label>
                        </div>
                    </li>
                    <li class="li-one  padding-li">
                        <div class="custom-check">
                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="name">
                            <label class="radio-label font-drop" for="name">
                                Name
                            </label>
                        </div>
                    </li>
                    <li class="li-one  padding-li">
                        <div class="custom-check">
                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="code">
                            <label class="radio-label font-drop" for="code">
                                Code
                            </label>
                        </div>
                    </li>
                    <li class="li-one  padding-li">
                        <div class="custom-check">
                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="city">
                            <label class="radio-label font-drop" for="city">
                                City
                            </label>
                        </div>
                    </li>
                    <li class="li-one  padding-li">
                        <div class="custom-check">
                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="mobile">
                            <label class="radio-label font-drop" for="mobile">
                                Mobile Number
                            </label>
                        </div>
                    </li>
                    <li class="li-one  padding-li">
                        <div class="custom-check">
                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="email">
                            <label class="radio-label font-drop" for="email">
                                Email ID
                            </label>
                        </div>
                    </li>
                </ul>
                <div class="divide">
                    <span></span><i class='bx bx-search fs-4'></i>
                </div>
                <input type="text" placeholder="Search name" class="form-control" aria-label="Text input with dropdown button">
            </div>
        </div>
        <div class="dropbar-bar">
            <button type="button" class="btn cancel-btn" onclick="window.location='{{url('masters/role-management/create')}}'">
                  <img src="{{ asset('icons/add.png') }}" class="pe-2"> Add Role
            </button>
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