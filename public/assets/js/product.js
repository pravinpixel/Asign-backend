$(document).ready(function() {
    $('#paginations').removeClass('pagination');
    function myfunction(per_page, page, search, search_key) {
        var agent_id = $("#agent_id").val();
        var product_id = $("#product_id").val();
        var location_id = $("#location_id").val();
        var api_url = url + '?agent_id=' + agent_id + '&product_id=' + product_id + '&location_id=' + location_id + '&page=' + page + '&per_page=' + per_page + '&search_key=' + search_key + '&search=' + search;
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
                $('#total').append('(' + response.data.total + ' prices)');
                if (response.data.total == response.data.to) {
                    $('#up').attr('disabled', 'disabled');
                }
                if (response.data.from == 1) {
                    $('#down').attr('disabled', 'disabled');
                }
                var sr_no =response.data.from-1;
                console.log(response.data.data);
                response.data.data.forEach(function(row) {
                    $table.append(`
                    <tr class="row-class" data-id="${row.label.id}" data-product-id="${row.product_id}">
                        <td><p>${row.label.request_id}</p></td>
                        <td><p>${new Date(row.label.request_date).toLocaleDateString('en-US',{ month: 'short', day: 'numeric', year: 'numeric' })}</p></td>
                        <td><p>${row.qty}</p></td>
                        <td><p>${row.consumed_qty}</p></td>
                        <td><p>${row.returned_qty}</p></td>
                        <td><p>${row.balance_qty}</p></td>
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
    myfunction(per_page, page, search, search_key);
});