$(document).ready(function() {
    // Remove pagination class from paginations element
    $('#paginations').removeClass('pagination');

    // Function to fetch data from the server
    function fetchData(per_page, page, search, search_key, field_sort) {
        var id = $("#id").val();
        var product_id = $("#product_id").val();
        var api_url = url + '?id=' + id + '&product_id=' + product_id + '&page=' + page + '&per_page=' + per_page + '&search_key=' + search_key + '&search=' + search + '&field_sort=' + field_sort;

        // Clear table and other elements
        $('#from').empty();
        $('#to').empty();
        $('#total').empty();
        $('#up').removeAttr('disabled');
        $('#down').removeAttr('disabled');
        var $table = $('#table');
        $table.html('');

        // Make AJAX request to fetch data
        $.ajax({
            url: api_url,
            type: "GET",
            success: function(response) {
                console.log(response);
                // Update pagination information
                $('#total').text('(' + response.data.total + ' locations)');
                $('#from').text(response.data.current_page);
                $('#to').text(Math.ceil(response.data.total / per_page));

                if (response.data.total == response.data.to) {
                    $('#up').attr('disabled', 'disabled');
                }
                if (response.data.from == 1) {
                    $('#down').attr('disabled', 'disabled');
                }
                var sr_no = (response.data.current_page - 1) * per_page;
                response.data.data.forEach(function(row) {
                    sr_no++;
                    var issued="-";
                    var consumed="-";
                    if(row.issued){
                        issued=new Date(row.issued).toLocaleDateString('en-US',{ month: 'short', day: 'numeric', year: 'numeric' });
                    }
                    if(row.consumed){
                        consumed=new Date(row.consumed).toLocaleDateString('en-US',{ month: 'short', day: 'numeric', year: 'numeric' });
                    }
                    $table.append(`
                    <tr>
                    <td><p>${row.code}</p></td>
                        <td><p>-</p></td>
                        <td><p> ${issued}</p></td>
                        <td><p> ${consumed}</p></td>
                        <td>
                            ${getStatusIndicator(row.status)}
                        </td>
                    </tr>
                `);
                
                function getStatusIndicator(status) {
                    switch (status) {
                        case 'issued':
                            return '<button class="button-all align statusYellow">Issued</button>';
                        case 'consumed':
                            return '<button class="button-all align statusGreen">Consumed</button>';
                        case 'damaged':
                            return '<button class="button-all align statusRed">Damaged</button>';
                        case 'adjust':
                            return '<button class="button-all align statusSkyblue">Adjust</button>';
                        case 'returned':
                            return '<button class="button-all align statusOrange">Returned</button>';
                        default:
                            return '<button class="button-all align statusGreen" style="text-transform: capitalize;">'+ status +'</button>'; 
                    }
                }
                
                    $('[data-toggle="tooltip"]').tooltip();
                });
            },
            error: function(xhr) {},
            complete: function(response) {
                $('#pageLoader').fadeOut();
            }
        });
    }

    // Default values for filters
    var per_page = 10;
    var page = 1;
    var search = '';
    var search_key = 'all';
    var field_sort = '';

    // Event handlers for pagination buttons
    $('#up').on('click', function() {
        if (page != 0) {
            page++;
            fetchData(per_page, page, search, search_key, field_sort);
        }
    });

    $('#down').on('click', function() {
        if (page != 0) {
            page--;
            fetchData(per_page, page, search, search_key, field_sort);
        }
    });

    // Event handler for per_page dropdown
    $('#per_page').on('change', function() {
        per_page = this.value;
        page = 1;
        fetchData(per_page, page, search, search_key, field_sort);
    });

    // Event handler for search input
    $('input[name=search]').keyup(function() {
        search = this.value;
        page = 1;
        fetchData(per_page, page, search, search_key, field_sort);
    });

    // Event handler for filter selection
    $('.custom_select').on('change', function() {
        field_sort = $(this).val();
        fetchData(per_page, page, search, search_key, field_sort);
    });

    // Initial data fetch
    fetchData(per_page, page, search, search_key, field_sort);
});
