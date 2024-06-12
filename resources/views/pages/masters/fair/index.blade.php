@extends('layouts.index')
@section('title', 'Fair')
@section('style')
<style type="text/css">
  a[disabled] {
    pointer-events: none;
    border: 0px solid #CFCFCF !important;
    background-color: unset !important;
    color: #696969;
}
</style>
@parent
@endsection
@section('content')
<div class="pages customers-list">
<section class="main-header">
    <div class="section-breadcrumb">
        <ul>
            <li>
                <img src="{{ asset('icons/crown-1.svg') }} " width="18" /><a href="#">Masters</a>
            </li>
            <li>
                /
            </li>
            <li>
                <a href="{{url('masters/fair')}}">Fair</a>
            </li>
            <li>
            </li>
        </ul>
    </div>
    <div class="section-title">
        <h4>Fair<span id="total"></span></h4>
    </div>
    <!-- #....Header -->
    <!-- #...search -->
    
    <!-- #...end search -->
</section>
<div class="section-filter filter-index">
        <div class="filter-setup">
            <div class="search-bar">
                <div class="input-group filter-with">
                    <button class="btn btn-light dropdown-toggle filter-dropy" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span id='selcted_radio' style="text-transform: capitalize;"></span>
                       <img class="indic" src="{{ asset('icons/arrow-down.svg') }}" width="20">
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
                                <input class="form-check-input" type="radio" name="flexRadioDefault1" id="name" value="name" checked>
                                <label class="radio-label font-drop" for="name">
                                    Name
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
            <!-- <div class="filter-bar">
            <span class="clear-all" id="clear-all">Clear All</span>
            </div> -->
            <div class="dropbar-bar"  @if (!access()->hasAccess('master.create'))  data-toggle="tooltip" data-placement="bottom" title="You don’t have Create permission" 
      @endif>
                <button type="button" class="btn cancel-btn" id="add" onclick="getModal()" @if (!access()->hasAccess('master.create')) disabled @endif>
                    <img src="{{ asset('icons/add.png') }}">         Add fair
                    </button>
            </div>
        </div>
        <div id="filter_panel" class="close">
        </div>
    </div>
<section class="table-content">
    <table class="asign-table customer-table customer" id="master_table">
        <thead>
            <tr>
                <th scope="col" width="20%" >SR NO.</th>
                <th scope="col" width="45%" class="has_sort" data-value="name"">Name</th>
                <th scope="col" width="15%" class="has_sort" data-value="status">Status</th>
                <th scope="col" width="20%">Action</th>
            </tr>
        </thead>
        <tbody id="table_body">
        </tbody>
    </table>
</section>
</div>
@include('pages.masters.deletemodel')
@include('layouts.paginate')
@include('partials.popup_modal')
@endsection
@push('scripts')
<script type="text/javascript">
    var url = "{{ url('masters/fair/list') }}";
    var edit = "{{ asset('icons/edit-2.png') }}";
    var edit_model = "{{ route('fair.add_edit') }}";
    var save = "{{ route('fair.save') }}";
    var fair = "{{ route('fair.check') }}";
    var delete_icon = "{{ asset('icons/image_2024_01_24T04_03_24_643Z.png') }}";
    var delete_url = "{{ route('fair.delete') }}";
</script>
<script>
    $('#paginations').removeClass('pagination');
    var per_page = 10;
    var page = 1;
    var search = '';
    var search_key = 'name';
    var sort = '';
    $(document).on('click', '.has_sort', function (e) {
    e.preventDefault();
    let sort_value = $(this).data('value');
    let order = 'desc';
    if ($(this).hasClass('desc')) {
        order = 'asc';
    }
    $('.has_sort').removeClass('asc').removeClass('desc');
    $(this).addClass(order);

    order = order === 'desc' ? 'asc' : 'desc';
    sort = sort_value + '|' + order;

    myfunction(per_page, page, search, search_key);

});
    $('#up').on('click', function() {
        if (page != 0) {
            page += 1;
            myfunction(per_page, page, search, search_key);
        }
    });
    $('#down').on('click', function() {
        if (page != 0) {
            page -= 1;
            myfunction(per_page, page, search, search_key);
        }
    });
    $('#per_page').on('change', function() {
        per_page = this.value;
        page = 1;
        $(this).blur();
        myfunction(per_page, page, search, search_key);
    });
    $('#selcted_radio').append('Name');
    $("input[name=search]").attr("placeholder", "Search Name");
    $('input[name=flexRadioDefault1]').on("click", function() {
        $('#selcted_radio').empty();
        search_key = this.value;
        var name = '';
        if (this.value == 'all') {
            name = 'ALL';
            $("input[name=search]").attr("placeholder", "Search All");
        } else if (this.value == 'name') {
            name = 'Name';
            $("input[name=search]").attr("placeholder", "Search Name");
        }
        $('#selcted_radio').append(name);
        myfunction(per_page, page, search, search_key);
    });
    $('input[name=search]').keyup(function() {
        search = this.value;
        if (this.value == '') {
            $('.filter-with').removeClass('search_shadow');
        } else {
            $('.filter-with').addClass('search_shadow');
        }
        page = 1;
        myfunction(per_page, page, search, search_key);
    });

    $('#clear-all').click(function() {
        $('#search').empty();
        $('#selcted_radio').empty();
        $('input[name=flexRadioDefault1]').prop('checked', false);
        $('#name').prop('checked', true);
        $('#selcted_radio').append('Name');
        $('#search').val('');
        $('.filter-with').removeClass('search_shadow');
        $("input[name=search]").attr("placeholder", "Search Name");
        per_page=10;
        page=1; 
        search='';
        search_key='name';
      myfunction(per_page, page, search, search_key);
     });

    myfunction(per_page, page, search, search_key);


    $('#conform_cancel').on('click', function() {
        $('#conform').modal('hide');
        $('#dynamic').modal('hide');
        myfunction(per_page, page, search, search_key);
    });


    function myfunction(per_page, page, search, search_key) {
        var api_url = url + '?page=' + page + '&per_page=' + per_page + '&search_key=' + search_key + '&search=' + search +'&sort='+sort;
        $('#from').empty();
        $('#to').empty();
        $('#total').empty();
        $('#up').removeAttr('disabled', 'disabled');
        $('#down').removeAttr('disabled', 'disabled');
        //var $table = $('#table');
        var $table = $('#table_body');

        $table.html('');
        $.ajax({
            url: api_url,
            type: "GET",
            success: function(response) {
                $('#total').empty();
                $('#table').empty();
                $('#from').append(response.data.from);
                $('#to').append(Math.ceil(response.data.total / per_page));
                $('#total').append('(' + response.data.total + ' fairs)');
                if (response.data.total == response.data.to) {
                    $('#up').attr('disabled', 'disabled');
                }
                if (response.data.from == 1) {
                    $('#down').attr('disabled', 'disabled');
                }
                var sr_no = response.data.from - 1;
                response.data.data.forEach(function(row) {
                    sr_no += 1;
                    if (row.status == 1) {
                        var status = 'active';
                        var verify_status = 'verified';
                    } else {
                        var status = 'Inactive';
                        var verify_status = 'unverified';
                    }
                    $table.append(`
                        <tr id='${sr_no}'>
                            <td data-label="id">${sr_no}</td>
                            <td><p>${row.name}</p></td>
                            <td><button class="button-all align ${verify_status}">${status}</button></td>
                            <td class="edit_row">
                            <a onclick="getModal(${row.id})"><img src="${edit}" width="18" /> </a>
                            <a onclick="deleteModel(${row.id})">
                            <img src="${delete_icon}" width="18" /> 
                            </a></td>
                        </tr>
                    `);
                });
            },
            error: function(xhr) {},
            complete: function(response) {
                $('#pageLoader').fadeOut();
            }
        });
    }
 
    function get(id = '') {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var formMethod = "addEdit";
        $.ajax({
            url: model,
            type: 'POST',
            data: {
                id: id,

            },
            success: function(res) {
                $('#dynamic').modal('show');
                $('#dynamic').html(res);
            }
        })
    }
    $('#conform_delete').click(function() {
        var id = $("#id").val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: delete_url,
            type: "DELETE",
            data: {
                id: id,
            },
            success: function(response) {
                $('#discardModal').modal('hide');
                toastr.success(response.message);
                myfunction(per_page, page, search, search_key);
            },
            error: function(response) {
                $('#discardModal').modal('hide');
                $.each(response.responseJSON.errors, function(field_name, error) {
                    $('#' + field_name + '-error').text(error[0]);
                });
            }
        });
    });
</script>
@endpush