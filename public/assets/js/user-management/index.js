$(document).ready(function() {
    $('#paginations').removeClass('pagination');
    function myfunction(per_page, page, search, search_key,location,role) {
        $('#filter_count').empty();
          let filter_count = 0;
          
          if (location.length > 0) {
              filter_count = filter_count + 1;
          }
          if (role.length > 0) {
            filter_count = filter_count + 1;
        }
          if (filter_count != 0) {
              $('#filter_count').append(filter_count);
              $('#filter_count').addClass('span-conn');
              $('#toggle_search').addClass('filter_color');
          }
          if (filter_count == '') {
              $('#filter_count').removeClass('span-conn');
              $('#toggle_search').removeClass('filter_color');
          }
        var api_url = url + '?page=' + page + '&per_page=' + per_page + '&search_key=' + search_key + '&search=' + search+'&sort='+sort+'&location='+location+'&role='+role;
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
                $('#total').append('(' + response.data.total + ' users)');
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
                    <td><p class="overflow-tooltip">${row.code}</p></td>
                    <td><p class="overflow-tooltip">${row.name}</p></td>
                    <td><p class="overflow-tooltip">${row.role_name}</p></td>
                    <td><p class="overflow-tooltip">${row.branch_name}</p></td>
                    <td><p class="overflow-tooltip">${row.mobile_number}</p></td>
                    <td><p class="overflow-tooltip">${row.email}</p></td>
                    
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
    var sort = '';
    var location = [];
    var role = [];
    $('#up').on('click', function() {
        if (page != 0) {
            page += 1;
            myfunction(per_page, page, search, search_key,location,role);
        }
    });
    $('#down').on('click', function() {
        if (page != 0) {
            page -= 1;
            myfunction(per_page, page, search, search_key,location,role);
        }
    });
    $('#per_page').on('change', function() {
        per_page = this.value;
        page = 1;
        $(this).blur();
        myfunction(per_page, page, search, search_key,location,role);
    });
    $('#selcted_radio').append('Name');
    $("input[name=search]").attr("placeholder", "Search Name");
    $('input[name=flexRadioDefault1]').on("click", function() {
        $('#selcted_radio').empty();
          search_key = this.value;
          var name = '';
          if (this.value == 'all') {
              name = 'All';
              $("input[name=search]").attr("placeholder", "Search All");
          } else if (this.value == 'email') {
              name = 'Email Id';
              $("input[name=search]").attr("placeholder", "Search Email");
          } else if (this.value == 'mobile_number') {
              name = 'Mobile Number';
              $("input[name=search]").attr("placeholder", "Search Mobile Number");
          } else if (this.value == 'code') {
              name = 'Code';
              $("input[name=search]").attr("placeholder", "Search Code");
          } else if (this.value == 'name') {
              name = 'Name';
              $("input[name=search]").attr("placeholder", "Search Name");
          }
          $('#selcted_radio').append(name);
        myfunction(per_page, page, search, search_key,location,role);
    });
    $('#location').on("change", function() {
        location = getSelectedValues();
        myfunction(per_page, page, search, search_key,location,role);
    });
    $('#role').on("change", function() {
        role = getSelectedRoleValues();
        myfunction(per_page, page, search, search_key,location,role);
    });
    $('input[name=search]').keyup(function() {
        search = this.value;
        if (this.value == '') {
            $('.filter-with').removeClass('search_shadow');
        } else {
            $('.filter-with').addClass('search_shadow');
        }
         page = 1;
        myfunction(per_page, page, search, search_key,location,role);
    });

    $('#clear-all').click(function() {
        $('#search').empty();
        $('#selcted_radio').empty();
        $('input[name=flexRadioDefault1]').prop('checked', false);
        $('#name').prop('checked', true);
        $('#selcted_radio').append('Name');
        $(".options li").removeClass('selected');
        $("#role p span").text('Select Role');
        $(".SumoSelect  p span").addClass('placeholder');
        $("#location p span").text('Select Location');
        $('#search').val('');
        $('.filter-with').removeClass('search_shadow');
        $("input[name=search]").attr("placeholder", "Search Name");
        per_page=10;
        page=1; 
        search='';
        role=[];
        location=[];
        search_key='name';
      myfunction(per_page, page, search, search_key,location,role);
     });
     function getSelectedValues() {
        var selectedValues = [];
        $("#location option:selected").each(function() {
            selectedValues.push($(this).val());
        });
        return selectedValues;
    }
    function getSelectedRoleValues() {
        var selectedRoleValues = [];
        $("#role option:selected").each(function() {
            selectedRoleValues.push($(this).val());
        });
        return selectedRoleValues;
    }
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

    myfunction(per_page, page, search, search_key,location,role);

  });

    myfunction(per_page, page, search, search_key,location,role);
     $('#conform_save').click(function () {
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
                $("#dynamic-submit").attr("disabled", false);
                $.each(response.responseJSON.errors, function (field_name, error) {
                    $('#' + field_name + '-error').text(error[0]);
                });
                // toastr.error(response.responseJSON.message);
            }
        });
    });
     $('#exit_save').click(function () {
       window.location.reload(true);
    });
    $('#conform_exit').on('click', function(){
        window.location =index;
        myfunction(per_page, page, search, search_key,location,role);

   });
     $('#conform_cancel').click(function () {
       $('#conform').modal('hide');
    });
});
