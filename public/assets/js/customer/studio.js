$(document).ready(function () {

   $('#clear-all-studio').hide();
    $('#filter_panel_studio select, input[name="search_studio"]').on('change keyup', function () {
        toggleClearAllStudioButtonVisibility();
    });

    function toggleClearAllStudioButtonVisibility() {
        let filtersCount = $('#filter_panel_studio select').filter(function () {
            return $(this).val().length > 0;
        }).length;
        let searchValue = $('input[name="search_studio"]').val();
        if (filtersCount > 0 || searchValue.trim() !== '') {
            $('#clear-all-studio').show();
        } else {
            $('#clear-all-studio').hide();
        }
    }

    let filter_span_studio = $('#filter_studio_count');
    let search_span_studio = $('#search-studio-text');
    let search_studio = $('input[name="search_studio"]');
    let search_studio_column = '';
    let page_studio = 1;
    let per_page_studio = 10;
    let sort_studio = '';

    let _debounceStudioSearch = _.debounce(searchStudio, 1000);

    $(document).on('click', '.studio .has_sort', function (e) {
        e.preventDefault();
        let sort_value = $(this).data('value');
        let order = 'desc';
        if ($(this).hasClass('desc')) {
            order = 'asc';
        }
        $('.studio .has_sort').removeClass('asc').removeClass('desc');
        $(this).addClass(order);

        order = order === 'desc' ? 'asc' : 'desc';
        sort_studio = sort_value + '|' + order;
        _debounceStudioSearch();
    });

    $(document).on('click', '#studio-dropdown input[name="flexRadioDefault"]', function (e) {
        let text = 'All';
        if ($(this).is(":checked")) {
            text = $(this).closest('div').find('label').html();
        }
        text = text.trim();
        search_span_studio.html(text);
        search_studio.attr('placeholder', 'Search ' + text);
        search_studio_column = $(this).val();
        _debounceStudioSearch();
    });


    $(document).on('change', '#filter_panel_studio .custom_select', function (e) {
        e.preventDefault();
        let filter_count = 0;
        $('#filter_panel_studio select').each(function () {
            let v = Object.values($(this).val()).length;
            if (v > 0) {
                filter_count++;
                $(this).closest("div").addClass('filter_color');
            } else {
                $(this).closest("div").removeClass('filter_color');
            }
        });
        if (filter_count > 0) {
            filter_span_studio.addClass('span-conn');
            filter_span_studio.closest('div').addClass('filter-bar2');
            $('#toggle_search_studio').addClass('filter_color');
        } else {
            filter_count = '';
            filter_span_studio.removeClass('span-conn');
            filter_span_studio.closest('div').removeClass('filter-bar2');
            $('#toggle_search_studio').removeClass('filter_color');
        }
        filter_span_studio.html(filter_count);
        _debounceStudioSearch();
    });

    $(document).on('click', '#clear-all-studio', function (e) {
        $("#filter_panel_studio").removeClass('open');
        $("#filter_panel_studio").addClass('close');
        e.preventDefault();
        page_studio = 1;
        per_page_studio = 10;
        sort_studio = '';
        search_studio.val('');
        $('.studio .has_sort').removeClass('asc').removeClass('desc');
        $('#filter_panel_studio select').each(function () {
            $(this).val('').trigger('change');
            var sumoSelect = $(this)[0].sumo;
            if (sumoSelect) {
                sumoSelect.reload();
            }
        });
        _debounceStudioSearch();
    });

    $(document).on('change', '#per-page-studio', function (e) {
        e.preventDefault();
        page_studio = 1;
        per_page_studio = $(this).val();
        searchStudio();
    });

    $(document).on('click', '.arrow-btn-studio', function (e) {
        e.preventDefault();
        let value = $(this).data('value');
        if (value === 'dec') {
            if (page_studio > 1)
                page_studio--;
        } else {
            page_studio++;
        }

        searchStudio();
    });
    searchStudio();


    function searchStudio() {
        $('.studio-detail').html("<tr><td colspan='8' class='text-center'>Loading .....</td></tr>");
        let type = $('select[name="object_type"]').val();
        let data = {
            'search': search_studio.val(),
            'search_column': search_studio_column,
            'page': page_studio,
            'per_page': per_page_studio,
            'sort': sort_studio,
            'type': type
        };
        $.ajax({
            url: baseUrl + "/customer/my-studio/" + id,
            type: "GET",
            data: data,
            success: function (response) {
                $('.studio-detail').html(response.table);
                $('#studio-pagination-div').html(response.pagination);
                $('#total_count1').html(response.total).addClass('span-conn');
            },
            error: function (xhr) {
                showErrorMessage(xhr);
            },
            complete: function (response) {
            }
        });

    }

    $(document).on('keyup', search_studio, _debounceStudioSearch);

    $(document).on('click', '#toggle_search_studio', function () {
        $('#filter_panel_studio').toggleClass("close open");
    });

});
