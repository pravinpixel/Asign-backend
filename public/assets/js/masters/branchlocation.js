$(document).ready(function() {
    var sort = '';
    $('#paginations').removeClass('pagination');
    function myfunction(per_page, page, search, search_key) {
        var api_url = url + '?page=' + page + '&per_page=' + per_page + '&search_key=' + search_key + '&search=' + search +'&sort='+sort;
        $('#from').empty();
        $('#to').empty();
        $('#total').empty();
        $('#up').removeAttr('disabled', 'disabled');
        $('#down').removeAttr('disabled', 'disabled');
        var $table = $('#table');
        $table.html('');
        $.ajax({
            url: api_url,
            type: "GET",
            success: function(response) {
                $('#total').empty();
                $('#table').empty();
                $('#from').append(response.data.current_page);
                $('#to').append(Math.ceil(response.data.total / per_page));
                $('#total').append('(' + response.data.total + ' asign locations)');
                if (response.data.total == response.data.to) {
                    $('#up').attr('disabled', 'disabled');
                }
                if (response.data.from == 1) {
                    $('#down').attr('disabled', 'disabled');
                }
                var sr_no =response.data.from-1;
                response.data.data.forEach(function(row) {
                    sr_no += 1;
                    if (row.status == 1) {
                        var status = 'active';
                        var verify_status = 'verified';
                    } else {
                        var status = 'Inactive';
                        var verify_status = 'unverified';
                    }
                    if($('#master_edit').val()==0){
                        var disabled_edit='disabled data-toggle="tooltip" data-placement="bottom" title="You don’t have Edit permission"';
                         }
                        if($('#master_delete').val()==0){
                        var disabled_delete='disabled data-toggle="tooltip" data-placement="bottom" title="You don’t have Delete permission"';
                        }
                    $table.append(`
                    <tr id='${sr_no}'>
                        <td data-label="id">${sr_no}</td>
                        <td><p>${row.location}</p></td>
                        <td><button class="button-all align ${verify_status}">${status}</button></td>
                        <td class="edit_row">
                        <p ${disabled_edit}>
                        <a onclick="getModal(${row.id})" ${disabled_edit}><img src="${edit}" width="18" /> </a></p>
                        <p ${disabled_delete}><a onclick="deleteModel(${row.id})" ${disabled_delete}>
                        <img src="${delete_icon}" width="18" /> 
                         </a></p>
                        </td>
                    </tr>
                `);
                $('[data-toggle="tooltip"]').tooltip();
                });
            },
            error: function(xhr) {},
            complete: function(response) {
                $('#pageLoader').fadeOut();
            }
        });
    }
    var per_page = 10;
    var page = 1;
    var search = '';
    var search_key = 'location';
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
    $('#selcted_radio').append('Location');
    $("input[name=search]").attr("placeholder", "Search Location");
    $('input[name=flexRadioDefault1]').on("click", function() {
        $('#selcted_radio').empty();
        search_key = this.value;
        var name = '';
        if (this.value == 'all') {
            name = 'All';
            $("input[name=search]").attr("placeholder", "Search All");
        } else if (this.value == 'location') {
            name = 'Location';
            $("input[name=search]").attr("placeholder", "Search Location");
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
        $('#location').prop('checked', true);
        $('#selcted_radio').append('Location');
        $('#search').val('');
        $('.filter-with').removeClass('search_shadow');
        $("input[name=search]").attr("placeholder", "Search Location");
        per_page=10;
        page=1; 
        search='';
        search_key='name';
      myfunction(per_page, page, search, search_key);
     });

    myfunction(per_page, page, search, search_key);
    
    $('#conform_save').click(function () {
        let form_data = $("#dynamic-form").serialize();

        $.ajax({
            url: save_model,
            type: "POST",
            data: form_data,
            success: function (response) {
                toastr.success(response.message);
                $("#dynamic-submit").attr("disabled", false);
                $('#dynamic-form')[0].reset();
                $('#dynamic').modal('hide');
                $('#conform').modal('hide');
                myfunction(per_page, page, search, search_key);
            },
            error: function (response) {
                $('#conform').modal('hide');
                $("#dynamic-submit").attr("disabled", false);
                $.each(response.responseJSON.errors, function (field_name, error) {
                    $('#' + field_name + '-error').text(error[0]);
                });
                // toastr.error(response.responseJSON.message);
            }
        });
    });
   
    
    $('#conform_delete').click(function () {
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
            success: function (response) {
                $('#discardModal').modal('hide');
                toastr.success(response.message);
                myfunction(per_page, page, search, search_key);
            },
            error: function (response) {
                $('#discardModal').modal('hide');
                $.each(response.responseJSON.errors, function (field_name, error) {
                    $('#' + field_name + '-error').text(error[0]);
                });
            }
        });
    });
    
    $('#conform_cancel').on('click', function(){
        $('#conform').modal('hide');
        $('#dynamic').modal('hide');
        myfunction(per_page, page, search, search_key);

   });
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
});