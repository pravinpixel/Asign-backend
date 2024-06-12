

@php($filters = [['id' => 'all', 'text' => 'All', 'value' => ''], ['id' => 'title', 'text' => 'Object Title', 'value' => 'title'], ['id' => 'asign_no', 'text' => 'Object Number', 'value' => 'asign_no']])

<div class="section-filter filter-index" style="padding: 32px">
    <div class="filter-setup">
        <div class="search-bar">
            <div class="input-group filter-with">
                <button class="btn btn-light dropdown-toggle filter-dropy" type="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                    <span id="search-text">All</span>
                    <img class="indic" src="{{ asset('icons/arrow-down.svg') }}" width="20">
                </button>
                <ul class="dropdown-menu width-ul" id="protect-dropdown">
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
                <input type="text" placeholder="Search all" class="form-control" name="search"
                       aria-label="Text input with dropdown button">
            </div>
        </div>
        <div class="filter-bar">
            <button id="toggle_search" type="button" class="btn btn-light">
                <div class="hstack">
                <i class='bx bx-filter-alt' style="padding-right: 10px;"></i>Filters
                <span id="filter_count"></span>
                </div>
            </button>
        </div>
        <div class="filter-bar">
            <span class="clear-all" id="clear-all-protect">Clear All</span>
        </div>
        <div class="dropbar-bar">

        </div>
    </div>
    <div id="filter_panel" class="close">
        <div class="row filter-focus mt-3 mb-2">

            <div class="col-sm-12 col-md-3 custom_select_1">
                <select multiple="multiple" data-placeholder="Select City" name="city" class="custom_select">
                    @foreach ($cities as $city)
                        <option value="{{ $city->name }}">{{ $city->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-12 col-md-3 custom_select_1">
                <select multiple="multiple" data-placeholder="Select Status" name="status" class="custom_select">
                    @foreach ($status as $k => $v)
                        @if ($v['label'] == 'Review')
                            @continue
                        @endif
                        <option value="{{ $k }}">{{ $v['label'] }}</option>
                    @endforeach
                    <option value="review">Review</option>
                </select>
            </div>
            <div class="col-sm-12 col-md-3 custom_select_1">
                <select multiple="multiple" data-placeholder="Select ObjectType" name="object" class="custom_select">
                   @foreach ($objects as $object_type)
                           <option value="{{ $object_type['id'] }}">{{ $object_type['name'] }}</option>
                   @endforeach
                </select>
            </div>
        </div>
    </div>
</div>


<section class="table-content">
    <table class="asign-table customer-table hide-select-arrow">
        <thead>
        <tr class="protect">
            <th width="10%" scope="col" class="has_sort" >Artwork</th>
            <th width="9%" scope="col" class="has_sort" data-value="title">Object Title</th>
            <th width="12%" scope="col" class="has_sort" data-value="asign_no">Object Number</th>
            <th width="12%" scope="col" class="has_sort" data-value="name">Object Type</th>
            <th width="12%" scope="col" class="has_sort" data-value="city">Location</th>
            <th width="15%" scope="col" data-value="team">Asign Protect+ Status</th>
            <th width="15%" scope="col" data-value="team">IN. Label</th>
            <th width="15%" scope="col" data-value="team">AU. Label</th>
        </tr>
        </thead>
        <tbody id="tableCtr" class="tbody-detail">

        </tbody>
    </table>
</section>

<div id="pagination-div" class="section table-footer footer-form px-4">
{{--    @include('components.pagination', ['data' => null])--}}
</div>

