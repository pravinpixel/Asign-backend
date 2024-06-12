$(document).ready(function() {
    var sort = '';
    $('#paginations').removeClass('pagination');
    function myfunction(per_page, page, search, search_key) {
        var api_url = url + '?page=' + page + '&per_page=' + per_page + '&search_key=' + search_key + '&search=' + search +'&sort='+sort;
        $('#from').empty();
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
                $('#from').empty();
                $('#to').empty();
                $('#from').append(response.data.current_page);
                $('#to').append(Math.ceil(response.data.total / per_page));
                $('#total').append('(' + response.data.total + ' roles)');
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
                    $table.append(`
                    <tr  class="unverified-hover" id='${row.id}'>
                    <td><p>${row.name}</p></td>
                    <td>${row.user_count}</td>
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
    var per_page = 10;
    var page = 1;
    var search = '';
    var search_key = 'name';
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
     $('#conform_save,#save_role').click(function () {
        let form_data = $("#dynamic-form").serialize();

        $.ajax({
            url: save_model,
            type: "POST",
            data: form_data,
            success: function (response) {
                window.location = index;
                toastr.success(response.message);
            },
            error: function (response) {
                $('#conform').modal('hide');
                $.each(response.responseJSON.errors, function (field_name, error) {
                    $('#' + field_name + '-error').text(error[0]);
                });
                $('#dynamic-submit').prop('disabled', true);
                $('#dynamic-discard').prop('disabled', true);
                $('#save_role').prop('disabled', true);
                // toastr.error(response.responseJSON.message);
            }
        });
    });
     $('#exit_save').click(function () {
       window.location.reload(true);
        });
    $('#conform_cancel,#conform_exit,#exit_role').on('click', function(){
        window.location =index;
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
