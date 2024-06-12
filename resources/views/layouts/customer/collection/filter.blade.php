<div class="section-filter filter-index" style="padding: 32px">
    <div class="filter-setup">
  <div class="search-bar">
    <div class="input-group filter-with">
      <button class="btn btn-light dropdown-toggle filter-dropy" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <span id='selcted_radio' style="text-transform: capitalize;">Title</span>
         <img class="indic" src="{{ asset('icons/arrow-down.svg') }}" width="20">
      </button>
      <ul class="dropdown-menu width-ul">
        <li class="li-one padding-li">
          <div class="custom-check">
            <input class="form-check-input" type="radio" name="flexRadioDefault1"  id="all"  value="all">
            <label class="radio-label font-drop" for="all">
              All
            </label>
          </div>
        </li>
        <li class="li-one  padding-li">
          <div class="custom-check">
            <input class="form-check-input" type="radio" name="flexRadioDefault1" id="title" value="title" checked>
            <label class="radio-label font-drop" for="title">
              Title
            </label>
          </div>
        </li>
        <li class="li-one  padding-li">
          <div class="custom-check">
            <input class="form-check-input" type="radio" name="flexRadioDefault1" id="type" value="type">
            <label class="radio-label font-drop" for="type">
              Type
            </label>
          </div>
        </li>
        <li class="li-one  padding-li">
          <div class="custom-check">
            <input class="form-check-input" type="radio" name="flexRadioDefault1" id="location" value="location">
            <label class="radio-label font-drop" for="location">
              Location
            </label>
          </div>
        </li>
      </ul>
      <div class="divide">
        <span></span><i class='bx bx-search fs-4'></i>
      </div>
      <input type="text" placeholder="Search Title" class="form-control" aria-label="Text input with dropdown button" id="search" name="search">
    </div>
  </div>
  <div class="filter-bar">
    <button id="toggle_search" type="button" class="btn btn-light"><i class='bx bx-filter-alt' style="padding-right: 10px;"></i>Filters
      <span class="" id="filter_count"></span>
    </button>
  </div>
  <div class="dropbar-bar">
    <!-- <i class='bx bx-dots-vertical-rounded fs-4 dropdown-toggle' data-bs-toggle="dropdown" aria-expanded="false"></i>
    <ul class="dropdown-menu">
      <li><a class="dropdown-item" href="#">Action</a></li>
      <li><a class="dropdown-item" href="#">Another action</a></li>
      <li><a class="dropdown-item" href="#">Something else here</a></li>
      <li><a class="dropdown-item" href="#">Separated link</a></li>
    </ul> -->
  </div>
</div>
<div id="filter_panel" class="close">
    <div class="row filter-focus mt-3 mb-2">
        <div class="col-sm-12 col-md-3 " id="verifytitle">
            <select multiple="multiple" placeholder="Select Object Type" class="custom_select" name="art_type" id="art_type">
              @if(isset($titles))
              @foreach($titles as $title)
                <option value="{{$title->type_id}}"style="text-transform: capitalize;">{{$title->type_name}}</option>
                @endforeach
                @endif
            </select>
        </div>
        <div class="col-sm-12 col-md-3 custom_select_1" id="verifylocation">
            <select multiple="multiple" placeholder="Select Location" class="custom_select" name="art_location" id="art_location">
              @if(isset($locations))
              @foreach($locations as $location)
              @if($location !=null)
                <option value="{{$location}}" data-badge="">{{$location}}</option>
              @endif
              @endforeach
              @endif
            </select>
        </div>
        <div class="col-sm-12 col-md-3 custom_select_1" id="verifystatus">
            <select multiple="multiple" placeholder="Select Status" class="custom_select" name="art_status" id="art_status">
               <option value="verified" data-badge="">Verified</option>
                    <option value="unverified" data-badge="">
                        Unverified
                    </option>
            </select>
        </div>
    </div>
</div>
</div>