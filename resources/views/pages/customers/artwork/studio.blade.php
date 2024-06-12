@php($filters = [['id' => 'all_studio', 'text' => 'All', 'value' => ''], ['id' => 'name-studio', 'text' => 'Artwork Name', 'value' => 'title'], ['id' => 'asign_no_studio', 'text' => 'Object Number', 'value' => 'asign_no'], ['id' => 'type-studio', 'text' => 'Object Type', 'value' => 'object_type']])

<div class="section-filter filter-index" style="padding: 32px">
    <div class="filter-setup">
        <div class="search-bar">
            <div class="input-group filter-with">
                <button class="btn btn-light dropdown-toggle filter-dropy" type="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                    <span id="search-studio-text">All</span>
                    <img class="indic" src="{{ asset('icons/arrow-down.svg') }}" width="20">
                </button>
                <ul class="dropdown-menu width-ul" id="studio-dropdown">
                    @foreach ($filters as $filter)
                        <li class="li-one padding-li">
                            <div class="custom-check">
                                <input class="form-check-input" value="{{ $filter['value'] }}" type="radio"
                                       name="flexRadioDefault" id="{{ $filter['id'] }}">
                                <label class="radio-label font-drop" for="{{ $filter['id'] }}">
                                    {{ $filter['text'] }}
                                </label>
                            </div>
                        </li>
                    @endforeach
                </ul>
                <div class="divide">
                    <span></span><i class='bx bx-search fs-4'></i>
                </div>
                <input type="text" placeholder="Search all" class="form-control" name="search_studio"
                       aria-label="Text input with dropdown button">
            </div>
        </div>
        <div class="filter-bar">
            <button id="toggle_search_studio" type="button" class="btn btn-light">
                <div class="hstack">
                <i class='bx bx-filter-alt' style="padding-right: 10px;"></i>Filters
                <span id="filter_studio_count"></span>
                </div>
            </button>
        </div>
        <div class="filter-bar">
            <span class="clear-all" id="clear-all-studio">Clear All</span>
        </div>
        <div class="dropbar-bar">

        </div>
    </div>
    <div id="filter_panel_studio" class="close">
        <div class="row filter-focus mt-3 mb-2">
            <div class="col-sm-12 col-md-3 custom_select_studio">
                <select multiple="multiple" data-placeholder="Select Object Type" name="object_type"
                        class="custom_select">
                    @isset($objects)
                        @foreach ($objects as $object_type)
                            <option value="{{ $object_type['id'] }}">{{ $object_type['name'] }}</option>
                        @endforeach
                    @endisset
                </select>
            </div>
        </div>
    </div>
</div>


<section class="table-content">
    <table class="asign-table customer-table hide-select-arrow">
        <thead>
        <tr class="studio">
            <th width="10%" scope="col" data-value="request_id">Artwork</th>
            <th width="13%" scope="col" data-value="account_type">Artwork Name</th>
            <th width="13%" scope="col" data-value="asign_no">Object Number</th>
            <th width="13%" scope="col" data-value="city">Object Type</th>
            <th width="10%" scope="col" class="has_sort" data-value="approved_at">AGING</th>
            <th width="10%" scope="col" class="has_sort" data-value="likes">LIKES</th>
            <th width="10%" scope="col" class="has_sort" data-value="views">VIEWS</th>
            <th width="10%" scope="col" data-value="created_at">SHARES</th>
        </tr>
        </thead>
        <tbody id="tableCtr" class="studio-detail">

        </tbody>
    </table>
</section>

<div id="studio-pagination-div" class="section table-footer footer-form px-4">


</div>

