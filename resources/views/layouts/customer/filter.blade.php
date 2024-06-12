<div class="section-filter filter-index">
    <div class="filter-setup">
        <div class="search-bar">
            <div class="input-group filter-with">
                <button class="btn btn-light dropdown-toggle filter-dropy" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span id='selcted_radio' style="text-transform: capitalize;"></span>
                    <img class="indic" src="{{ asset('icons/arrow-down-filter.png') }}" width="20">
                </button>
                <ul class="dropdown-menu width-ul">
                    <li class="li-one padding-li">
                        <div class="custom-check">
                            <input class="form-check-input" type="radio" name="flexRadioDefault1" id="all" value="all">
                            <label class="radio-label font-drop" for="all">
                                All
                            </label>
                        </div>
                    </li>
                    <li class="li-one  padding-li">
                        <div class="custom-check">
                            <input class="form-check-input" type="radio" name="flexRadioDefault1" id="name" value="full_name" checked>
                            <label class="radio-label font-drop" for="name">
                                Name
                            </label>
                        </div>
                    </li>
                    <li class="li-one  padding-li">
                        <div class="custom-check">
                            <input class="form-check-input" type="radio" name="flexRadioDefault1" id="code" value="aa_no">
                            <label class="radio-label font-drop" for="code">
                                Code
                            </label>
                        </div>
                    </li>
                    <li class="li-one  padding-li">
                        <div class="custom-check">
                            <input class="form-check-input" type="radio" name="flexRadioDefault1" id="city" value="city">
                            <label class="radio-label font-drop" for="city">
                                City
                            </label>
                        </div>
                    </li>
                    <li class="li-one  padding-li">
                        <div class="custom-check">
                            <input class="form-check-input" type="radio" name="flexRadioDefault1" id="mobile" value="mobile">
                            <label class="radio-label font-drop" for="mobile">
                                Mobile Number
                            </label>
                        </div>
                    </li>
                    <li class="li-one  padding-li">
                        <div class="custom-check">
                            <input class="form-check-input" type="radio" name="flexRadioDefault1" id="email" value="email">
                            <label class="radio-label font-drop" for="email">
                                Email ID
                            </label>
                        </div>
                    </li>
                </ul>
                <div class="divide">
                    <span></span><i class='bx bx-search fs-4'></i>
                </div>
                <input type="text" placeholder="Search name" class="form-control" aria-label="Text input with dropdown button" id="search" name="search">
            </div>
        </div>
        <div class="filter-bar">
            <button id="toggle_search" type="button" class="btn btn-light">
            <div class="hstack">
                    <div>
                        <img class="indic" src="{{ asset('icons/filter.svg') }}" 
                        width="24" style="position:relative;top:-1px;">
                        Filters
                    </div>
                    <div>
                        <span class="" id="filter_count"></span>
                    </div>
               </div>
            </button>
        </div>
        <div class="filter-bar">
            <span class="clear-all" id="clear-all" style="display: none;">Clear All</span>
        </div>
        <div class="dropbar-bar">
            <i class='bx bx-dots-vertical-rounded fs-4 dropdown-toggle' data-bs-toggle="dropdown" aria-expanded="false"></i>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" id="export_button" href="#">Export</a></li>
            </ul>
        </div>
    </div>
    <div id="filter_panel" class="close">
        <div class="row filter-focus mt-3 mb-2">
            <div class="col-sm-12 col-md-3 verifylogin" id="loginstatus">
                <select multiple="multiple" placeholder="Select Login Status" class="custom_select" id="login_status">
                    <option value="1" data-badge="">Logged In</option>
                    <option value="0" data-badge="">
                        Logged Out
                    </option>
                </select>
            </div>
            <div class="col-sm-12 col-md-3" id="citystatus">
                <select multiple="multiple" placeholder="Select City" class="custom_select" id="city_data" name="city_data">
                    @if(isset($cities))
                    @foreach($cities as $city)
                    <option value="{{$city->city}}" data-badge="" style="text-transform: capitalize;">{{$city->city}}</option>
                    @endforeach
                    @endif
                    </option>
                </select>
            </div>
            <div class="col-sm-12 col-md-3" id="verifystatus">
                <select multiple="multiple" placeholder="Select Verification Status" class="custom_select" id="status">
                    <option value="verified" data-badge="">Verified</option>
                    <option value="unverified" data-badge="">Unverified</option>
                    <option value="moderation" data-badge="">Moderation</option>
                    <option value="paused" data-badge="">Paused</option>
                </select>
            </div>
            <div class="col-sm-12 col-md-3" id="sortByPercentage">
                <select multiple="multiple" placeholder="Select completetion Percentage" class="custom_select" id="completion_percentage">
                    <option value="0-0" data-badge="">0%</option>
                    <option value="15-30" data-badge="">15% - 30%</option>
                    <option value="40-60" data-badge="">40% - 60%</option>
                    <option value="70-85" data-badge="">70% - 85%</option>
                    <option value="100-100" data-badge="">100%</option>
                </select>
            </div>
        </div>
    </div>
</div>