@extends('layouts.index')
@section('title', 'Stock Check')
@section('style')
    @parent
    <link rel="stylesheet" href="{{ asset('css/label.css') }}">

    <style>
        .colorRed {
            color: red
        }
    </style>

@endsection
@section('content')
    <div class="pages purchase-order-create">

        <div id="headerForm">
            <form class="formFieldInput" id="check-form">
                <input type="hidden" name="id" value="{{$check->id ?? ''}}" required>
                <input type="hidden" name="status" value="{{$check->status ?? ''}}">
                <input type="hidden" name="request_id" value="{{$request_id}}" required>
                <input type="hidden" name="click_type" value="" required>
                <section class="m-header">
                    <div class="hstack gap-3">
                        <a href="{{ url('/stock-check') }}">
                            <img src="{{ asset('icons/arrow-left-alt.svg') }}" width="24" height="24" class="cP"/>
                        </a>
                        <h4>{{$request_id ?? 'Stock Check'}}</h4>
                    </div>
                    <div style="flex: 1; text-align: right" class="submitButton">

                        <input type="submit" class="no-display">
                        <button type="submit" data-value="complete" class="btn apply-btn" disabled id="completedStock">
                            Complete Stock Check
                        </button>

                        <button type="button" data-value="override" class="btn cancel-btn no-display mx-2" id="override">
                            Override
                        </button>

                        <button type="button" data-value="adjust" class="btn apply-btn no-display" id="adjust" disabled>
                            Adjust Stock
                        </button>

                        <button type="button" data-value="stop-enquiry" class="btn apply-btn no-display"
                                id="stopEnquiry" disabled>
                            Stop Enquiry
                        </button>

                        <button type="button" data-value="start-enquiry" class="btn apply-btn no-display"
                                id="startEnquiry">
                            Start Enquiry
                        </button>
                    </div>
                    <div class="dropdown-bar">
                        <i class='bx bx-dots-vertical-rounded fs-4 dropdown-toggle' data-bs-toggle="dropdown" aria-expanded="false"></i>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" id="export-excel" href="#">Export</a></li>
                        </ul>
                    </div>

                </section>
                <section class="form-content">
                    <div class="row mb-4">

                        <div class="col col-md-6">
                            <div class="row">
                                <div class="col col-md-10">
                                    <label for="fresh_id" class="form-label">Check type</label>
                                    <div class="w100Select">
                                        <select class="select2Box" id="type" name="type" required
                                                {{$check?->type ? 'disabled' : ''}}
                                                data-placeholder="Select Check type">
                                            <option value=""></option>
                                            <option value="location"
                                                {{ isset($check->type) && $check->type == 'location' ? 'selected' : '' }}
                                            >Location
                                            </option>
                                            <option value="agent"
                                                {{ isset($check->type) && $check->type == 'agent' ? 'selected' : '' }}
                                            >Agent
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col col-md-6">
                            <div class="row">
                                <div class="col col-md-10">
                                    <label for="agent_id" class="form-label">Stock Location</label>
                                    <div class="w100Select">
                                        <select class="select2Box" id="location_id" name="location_id" required
                                                {{$check?->location_id ? 'disabled' : ''}}
                                                data-placeholder="Select Location">
                                            <option></option>
                                            @foreach($locations as $location)
                                                <option value="{{ $location['id'] }}"
                                                    {{ isset($check->location_id) && $location['id'] == $check->location_id ? 'selected' : '' }}
                                                >{{ $location['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            class="col col-md-6 mt-4 {{$check?->type == 'location' ||  !isset($check->type) ? ' no-display' : ''}}"
                            id="agentDiv">
                            <div class="row">
                                <div class="col col-md-10">
                                    <label for="agent_id" class="form-label">Agent Name</label>
                                    <div class="w100Select">
                                        <select class="select2Box" id="agent_id" name="agent_id"
                                                {{$check?->agent_id ? 'disabled' : ''}}
                                                data-placeholder="Select Agent Name">
                                            <option></option>
                                            @foreach($agents as $agent)
                                                <option value="{{ $agent['id'] }}"
                                                    {{ isset($check->agent_id) && $agent['id'] == $check->agent_id ? 'selected' : '' }}
                                                >{{ $agent['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row {{$disabled ? 'no-display' : ''}}">
                        <div class="col-12 txt-right">
                        <span class="d-inline-block" data-bs-toggle="tooltip" data-placement="bottom" title="">
                         <button type="button" class="btn cancel-btn add-product addicon-btn" disabled>
                                 <i class='bx bx-plus pe-2'></i>  Add Products
                        </button>
                        </span>
                        </div>
                    </div>

                </section>
            </form>
            <section class="table-content">
                <table class="asign-table purchase-order-table">
                    <thead>
                    <tr>
                        <th scope="col" width="15%">SR NO</th>
                        <th scope="col" width="20%">LABEl TYPE</th>
                        <th scope="col" width="20%">EXPECTED STOCK</th>
                        <th scope="col" width="20%">ACTUAL STOCK</th>
                        <th scope="col" width="25%">ACTION</th>
                    </tr>
                    </thead>
                    <tbody id="label-table">
                    @include('pages.stock-check.check-table')
                    </tbody>
                </table>
            </section>
        </div>

        <div id="scanForm">
            @include('pages.label.components.scan-form')
        </div>

        @include('components.product')
        @include('pages.label.components.confirm-modal')

    </div>
@endsection
@push('scripts')
    <script type="text/javascript">

        let link = baseUrl + '/stock-check';
        let id = $('input[name="id"]').val();
        let items = @json($check->products ?? []);
        let products = @json($products);
        let addProductBtn = $('.add-product');
        let table = $('#label-table');
        let checkFormBtn = $('#check-form').find('button[type="submit"]');
        let details = @json($check->productDetails ?? []);
        let clickType = $('input[name="click_type"]');

        $('#check-form').on('submit', function (e) {
            e.preventDefault();
            let data = {
                request_id: $('input[name="request_id"]').val(),
                type: $('#type').val(),
                location_id: $('#location_id').val(),
                agent_id: $('#agent_id').val(),
            };

            if (id) {
                var url = link + "/" + id;
                var method = 'PUT';
                data.details = details;
                data.items = items;
            } else {
                var url = link + "/save";
                var method = 'POST';
                data.details = details;
                data.items = items;
            }
            data.click_type = $('input[name="click_type"]').val();

            $.ajax({
                url: url,
                method: method,
                data: data,
                success: function (response) {

                    let redirect = link + '/' + response.data.id;
                    if(response.data.status === 'complete'){
                        redirect = link;
                    }
                    window.location.href = redirect;
                },
                error: function (xhr) {
                    var error = xhr.responseJSON;
                    if (error.error === 'invalid-request-no') {
                        $('.conform_header').text('Alert');
                        $('.conform_content').text(error.message['msg']);
                        $('input[name="request_id"]').val(error.message['request_id']);
                        $('#conform').modal('show');
                    } else {
                        showErrorMessage(xhr);
                    }
                }
            });
        });

        $(document).on('click', '#conform_save', function () {
            $('input[type="submit"]').trigger('click');
        });


        $(document).on('click', '#stopEnquiry', function (e) {
            e.preventDefault();
            clickType.val('enquiry-stop');
            $('input[type="submit"]').trigger('click');
        });

        $(document).on('click', '#startEnquiry', function () {
            clickType.val('enquiry-start');
            $('input[type="submit"]').trigger('click');
        });

        $(document).on('click', '#overrideStock', function (e) {
            e.preventDefault();
            $('#adjust-modal').modal('hide');
            clickType.val($(this).data('value'));
            $('input[type="submit"]').trigger('click');
        });

        $(document).on('click', '#override', function (e) {
            e.preventDefault();
            $('#overrideStock').attr('data-value', 'override');
            $('.adjustHead').text('Override Stock');
            $('#adjust-modal').modal('show');
        });

        $(document).on('click', '#adjust', function (e) {
            e.preventDefault();
            $('#overrideStock').attr('data-value', 'enquiry-adjust');
            $('.adjustHead').text('Adjust Stock');
            $('#adjust-modal').modal('show');
        });

        $(".select2Box").each(function () {
            let placeholder = $(this).attr('data-placeholder');
            $(this).select2({
                placeholder: placeholder,
                minimumResultsForSearch: Infinity,
            });
        });


        $('#product_form').on('submit', function (e) {
            e.preventDefault();
            if (!items.length) table.html('');

            let product_id = $('#product_id').val();
            let product_name = $('#product_id option:selected').text();

            $.ajax({
                url: link + '/stocks',
                method: 'get',
                data: {
                    type: $('#type').val(),
                    location_id: $('#location_id').val(),
                    agent_id: $('#agent_id').val(),
                    product_id: product_id,
                },
                success: function (response) {
                    let data = response.data;
                    let tr = table.find('tr[data-id="' + product_id + '"]');
                    if (!tr.length) {
                        items.push({
                            product_id: product_id,
                            product_name: product_name,
                            qty: data.stock,
                        });
                        let tr = '<tr data-id="' + product_id + '">' +
                            '<td>1</td>' +
                            '<td>' + product_name + '</td>' +
                            '<td>' + data.stock + '</td>' +
                            '<td>' + 0 + '</td>' +
                            '<td>' +
                            '<a href="#" class="btn btn-outline-dark scan-product mx-2">Scan</a>' +
                            '<a href="#" class="btn btn-outline-dark remove-product">Remove</a>' +
                            '</td>' +
                            '</tr>';
                        table.append(tr);
                    }
                    $('#product_popup').modal('hide');
                    addSerialNo();
                    disabledAddButton();

                },
                error: function (xhr) {
                    showErrorMessage(xhr);
                }
            });

        });

        $(document).on('change', '#type', function (e) {

            let type = $(this).val();
            if (type === 'location') {
                $('#agentDiv').addClass('no-display');
            } else {
                $('#agentDiv').removeClass('no-display');
                $('#agent_id').val('').trigger('change');
            }
            removeOldData();
            disabledAddButton();
        });


        $(document).on('change', '#agent_id', function(e){
            e.preventDefault();
            removeOldData(false);
            disabledAddButton();
        });

        $(document).on('change', '#location_id', function (e) {
            e.preventDefault();
            let location_id = $(this).val();

            let agentSelect = $('#agent_id');
            agentSelect.html('');
            agentSelect.append('<option></option>');

            removeOldData();
            disabledAddButton();

            if (location_id) {
                $.ajax({
                    url: baseUrl + '/location-agents/' + location_id,
                    method: 'get',
                    success: function (response) {
                        response.data.forEach(function (agent) {
                            let option = '<option value="' + agent.id + '">' + agent.name + '</option>';
                            agentSelect.append(option);
                        });
                        agentSelect.val('').trigger('change');
                    },
                    error: function (xhr) {
                        showErrorMessage(xhr);
                    }
                });
            }
        });


        $(document).on('click', '.add-product', function (e) {
            e.preventDefault();
            let modal = $('#product_popup');
            let form = $('#product_form');
            $('#qtyDiv').hide();
            form.find('input[name="qty"]').removeAttr('required');
            let productSelect = form.find('select[name="product_id"]');
            productSelect.html('');
            form.trigger('reset');
            products.forEach(function (product) {
                let disabled = items.find(function (item) {
                    return item.product_id == product.id;
                });
                let option = '<option value="' + product.id + '"  ' + (disabled ? 'disabled' : '') + '>' + product.name + '</option>';
                productSelect.append(option);
            });
            productSelect.val('').trigger('change');
            modal.find('button[type="submit"]').prop('disabled', true);
            modal.modal('show');
        });

        $(document).on('click', '.remove-product', function (e) {
            e.preventDefault();
            let tr = $(this).closest('tr');
            let row_id = tr.attr('data-id');
            items = items.filter(function (item) {
                return item.product_id != row_id;
            });

            details = details.filter(function (detail) {
                return detail.product_id != row_id;
            });

            tr.remove();
            addSerialNo();
            disabledAddButton();
        });

        function disabledAddButton() {

            let type = $('#type').val();
            let location_id = $('#location_id').val();
            let agent_id = $('#agent_id').val();

            if (type && location_id) {
                if (type === 'location' || (type === 'agent' && agent_id)) {
                    addProductBtn.closest('span').attr('data-bs-original-title', '');
                    addProductBtn.prop('disabled', false);
                    if (table.find('tr').length === products.length) {
                        addProductBtn.prop('disabled', true);
                        addProductBtn.closest('span').attr('data-bs-original-title', 'No other label types to add');
                    }
                } else {
                    addProductBtn.prop('disabled', true);
                    addProductBtn.closest('span').attr('data-bs-original-title', 'Please select location or agent');
                }

                let enableCompleted = true;

                if (items.length === 0) {
                    enableCompleted = false;
                } else {
                    enableCompleted = true;
                    items.forEach(function (item) {
                        if (item.on_hand != item.qty) {
                            enableCompleted = false;
                        }
                    });
                }
                $('#completedStock').prop('disabled', !enableCompleted);

                let status = $('input[name="status"]').val();

                if (enableCompleted || details.length === 0) {
                    $('#completedStock').removeClass('no-display');
                    $('#override').addClass('no-display');
                    $('#adjust').addClass('no-display');
                    $('#startEnquiry').addClass('no-display');
                    $('#stopEnquiry').addClass('no-display');

                    if (status === 'complete') {
                        $('#completedStock').addClass('no-display');
                    }

                } else {
                    $('#completedStock').addClass('no-display');
                    $('#override').removeClass('no-display');
                    $('#adjust').addClass('no-display');
                    $('#startEnquiry').removeClass('no-display');

                    if (status === 'enquiry-start') {
                        $('#override').removeClass('no-display');
                        $('#stopEnquiry').removeClass('no-display').attr('disabled', false);
                        $('#startEnquiry').addClass('no-display');
                    } else if (status === 'enquiry-stop') {
                        $('#startEnquiry').addClass('no-display');
                        $('#adjust').removeClass('no-display').attr('disabled', false);
                        $('#override').removeClass('no-display');
                    } else if (status === 'override') {
                        $('#startEnquiry').addClass('no-display');
                        $('#override').addClass('no-display');
                    } else if (status === 'complete') {
                        $('#completedStock').addClass('no-display');
                    } else if (status === 'enquiry-adjust') {
                        $('#startEnquiry').addClass('no-display');
                        $('#override').addClass('no-display');
                    }

                }

            }
        }

        function addSerialNo() {
            let tr = table.find('tr');
            tr.each(function (index, item) {
                $(this).find('td').eq(0).text(index + 1);
            });
        }

        disabledButton('product_form');
        disabledAddButton();

        //      Scan start

        $(document).on('click', '#resetBtn', function () {
            $('#confirm-modal').modal('show');
        });

        $(document).on('click', '#removeLabel', function () {
            $('#confirm-modal').modal('hide');
            $('#scanTbody').html('');
            let product_id = $('#scanProductId').val();
            let item = items.findIndex(function (item) {
                return item.product_id == product_id;
            });
            items[item].on_hand = 0;
            details = details.filter(function (detail) {
                return detail.product_id != product_id;
            });

            table.find('tr[data-id="' + product_id + '"]').find('td').eq(3).text(0);
            $('#scanActualQty').html(0);
            $('input[name="scan_item"]').val('').focus();
            disabledAddButton();
        });


        $(document).on('click', '.backHeader', function () {
            $('#headerForm').show();
            $('#scanForm').hide();
        });

        $(document).on('keydown', 'input[name="scan_item"]', function (e) {

            let code = $.trim($(this).val());
            let product_id = $('#scanProductId').val();
            let type = $('#type').val();
            let agent_id = $('#agent_id').val();
            let location_id = $('#location_id').val();

            if (e.keyCode === 13 && code !== '') {
                if (checkDuplicateLabel(product_id, code)) return;
                $.ajax({
                    url: link + '/verify',
                    method: 'get',
                    data: {
                        type: type,
                        location_id: location_id,
                        agent_id: agent_id,
                        product_id: product_id,
                        code: code
                    },
                    success: function (response) {
                        checkAppendScanValue(product_id, code);
                        disabledAddButton();
                    },
                    error: function (xhr) {
                        var error = xhr.responseJSON;
                        if (error.error) {
                            toastr.error(error.error);
                        } else {
                            showErrorMessage(xhr);
                        }
                    }
                });

            }
        });

        $(document).on('click', '.scan-product', function (e) {
            $('#headerForm').hide();
            $('#scanForm').show();

            let tr = $(this).closest('tr');
            let id = tr.attr('data-id');

            let item = items.find(function (item) {
                return item.product_id == id;
            });

            let productName = tr.find('td').eq(1).text();

            $('#scanProductId').val(item.product_id);
            $('#scanProduct').html(productName);
            $('#scanExpectedQty').html(item.qty);
            $('#scanActualQty').html(item.on_hand ?? 0);
            $('input[name="scan_item"]').focus().val('');
            $('#scanTbody').html('');
            details.forEach(function (detail) {
                if (detail.product_id == item.product_id) {
                    $('#scanTbody').append(
                        '<tr>' +
                        '<td>' + detail.code + '</td>' +
                        '<td>Envelope</td>' +
                        '<td>1</td>' +
                        '</tr>'
                    );
                }
            });
        });


        function checkAppendScanValue(product_id, code) {

            let item = items.findIndex(function (item) {
                return item.product_id == product_id;
            });
            if (item === -1) {
                toastr.error('Invalid Product');
                return;
            }

            $('input[name="scan_item"]').val('').focus();
            let onHand = items[item].on_hand ?? 0;

            if (onHand > items[item].qty) {
                toastr.error('Cannot add more than Expected stock');
                return;
            }

            if (onHand + 1 <= items[item].qty) {
                items[item].on_hand = onHand + 1;
                let tr = table.find('tr[data-id="' + product_id + '"]');
                let actualTd = tr.find('td').eq(3);

                actualTd.removeClass('colorRed');
                if (items[item].on_hand < items[item].qty)
                    actualTd.addClass('colorRed');

                actualTd.text(items[item].on_hand);

                details.push({
                    code: code,
                    product_id: product_id
                });
                $('#scanTbody').append(
                    '<tr>' +
                    '<td>' + code + '</td>' +
                    '<td>Envelope</td>' +
                    '<td>1</td>' +
                    '</tr>'
                );
                $('#scanActualQty').html(items[item].on_hand ?? 0);
                tr.find('td a.scan-product').prop('disabled', false);
                if (items[item].on_hand == items[item].qty) {
                    $('#headerForm').show();
                    $('#scanForm').hide();
                    tr.find('td a.scan-product').prop('disabled', true);
                }
                disabledAddButton();

            } else {
                toastr.error('Cannot add more than Expected stock');
            }
        }

        function checkDuplicateLabel(product_id, code) {
            let item = details.findIndex(function (item) {
                return item.code == code;
            });
            if (item !== -1) {
                toastr.error('You have already scanned this label');
                return true;
            }
            return false;
        }

        function removeOldData(removeAgent = true) {
            items = [];
            details = [];
            table.html('');
            if (removeAgent)
                $('#agent').val('').trigger('change');
        }


    </script>

<script>
            $('#export-excel').on('click', function() {
        
        let idValue = $('input[name="id"]').val();
        let url = `/stock-check/export-show?id=${encodeURIComponent(idValue)}`;
        $.ajax({
            url: baseUrl + url, 
            method: 'GET',
            xhrFields: {
                responseType: 'blob'
            },
            success: function(blob) {
                let url = window.URL.createObjectURL(blob);
                let a = document.createElement('a');
                a.href = url;
                a.download = 'stock-check.xlsx';
                document.body.appendChild(a);
                a.click();
                a.remove();
                window.URL.revokeObjectURL(url);
            },
            error: function(xhr) {
                console.error('Export failed:', xhr);
            }
        });
    });
    </script>
@endpush
