$(document).ready(function () {

    $('#clear-all-protect').hide();
    $('#filter_panel select, input[name="search"]').on('change keyup', function () {
        toggleClearAllButtonVisibility();
    });

    function toggleClearAllButtonVisibility() {
        let filtersCount = $('#filter_panel select').filter(function () {
            return $(this).val().length > 0;
        }).length;
        let searchValue = $('input[name="search"]').val();
        if (filtersCount > 0 || searchValue.trim() !== '') {
            $('#clear-all-protect').show();
        } else {
            $('#clear-all-protect').hide();
        }
    }


    let filter_span = $('#filter_count');
    let total_span = $('#total_count');
    let search_span = $('#search-text');
    let search_input = $('input[name="search"]');
    let search_column = '';
    let page = 1;
    let per_page = 10;
    let sort = '';

    let _debounceSearch = _.debounce(search, 1000);

    $(document).on('click', '.protect .has_sort', function (e) {
        e.preventDefault();
        let sort_value = $(this).data('value');
        let order = 'desc';
        if ($(this).hasClass('desc')) {
            order = 'asc';
        }
        $('.protect .has_sort').removeClass('asc').removeClass('desc');
        $(this).addClass(order);

        order = order === 'desc' ? 'asc' : 'desc';
        sort = sort_value + '|' + order;
        _debounceSearch();
    });

    $(document).on('click', '#protect-dropdown input[name="flexRadioDefault"]', function (e) {
        console.log('protect')

        let text = 'All';
        if ($(this).is(":checked")) {
            text = $(this).closest('div').find('label').html();
        }
        text = text.trim();
        search_span.html(text);
        search_input.attr('placeholder', 'Search ' + text);
        search_column = $(this).val();
        _debounceSearch();
    });


    $(document).on('change', '.custom_select_1', function (e) {
        e.preventDefault();
        let filter_count = 0;
        $('#filter_panel select').each(function () {
            let v = Object.values($(this).val()).length;
            if (v > 0) {
                filter_count++;
                $(this).closest("div").addClass('filter_color');
            } else {
                $(this).closest("div").removeClass('filter_color');
            }
        });
        if (filter_count > 0) {
            filter_span.addClass('span-conn');
            filter_span.closest('div').addClass('filter-bar2');
            $('#toggle_search').addClass('filter_color');
        } else {
            filter_count = '';
            filter_span.removeClass('span-conn');
            filter_span.closest('div').removeClass('filter-bar2');
            $('#toggle_search').removeClass('filter_color');
        }
        filter_span.html(filter_count);
        _debounceSearch();
    });

    $(document).on('click', '#clear-all-protect', function (e) {
        $("#filter_panel").removeClass('open');
        $("#filter_panel").addClass('close');
        e.preventDefault();
        page = 1;
        per_page = 10;
        sort = '';
        search_input.val('');
        $('.protect .has_sort').removeClass('asc').removeClass('desc');
        $('#filter_panel select').each(function () {
            $(this).val('').trigger('change');
            var sumoSelect = $(this)[0].sumo;
            if (sumoSelect) {
                sumoSelect.reload();
            }
        });
        _debounceSearch();
    });

    $(document).on('change', '#per-page', function (e) {
        e.preventDefault();
        page = 1;
        per_page = $(this).val();
        search();
    });

    $(document).on('click', '.arrow-btn', function (e) {
        e.preventDefault();
        let value = $(this).data('value');
        if (value === 'dec') {
            if (page > 1)
                page--;
        } else {
            page++;
        }

        search();
    });

    search();

    function search() {
        $('.tbody-detail').html("<tr><td colspan='6' class='text-center'>Loading .....</td></tr>");
        let city = $('select[name="city"]').val();
        let status = $('select[name="status"]').val();
        let object = $('select[name="object"]').val();
        let data = {
            'search': search_input.val(),
            'search_column': search_column,
            'page': page,
            'per_page': per_page,
            'sort': sort,
            'city': city,
            'status': status,
            'object': object
        };
        $.ajax({
            url: baseUrl + "/customer/protect-request/" + id,
            type: "GET",
            data: data,
            success: function (response) {
                $('.tbody-detail').html(response.table);
                $('#pagination-div').html(response.pagination);
                $('#total_count').html(response.total).addClass('span-conn');

            },
            error: function (xhr) {
                console.log(xhr)

                showErrorMessage(xhr);
            },
            complete: function (response) {
            }
        });

    }

    $(document).on('keyup', search_input, _debounceSearch);

});
