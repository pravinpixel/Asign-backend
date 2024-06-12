@extends('layouts.index')
@section('title', 'Label Issue Create')
@section('style')
    @parent
    <link rel="stylesheet" href="{{ asset('css/label.css') }}">

@endsection
@section('content')
    <div class="pages purchase-order-create">

        <div id="headerForm">
            <form class="formFieldInput" id="label-form">
                <section class="m-header">
                    <div class="hstack gap-3">
                        <a href="{{ url('/label-issues') }}">
                            <img src="{{ asset('icons/arrow-left-alt.svg') }}" width="24" height="24" class="cP"/>
                        </a>
                        <h4>{{$request_id}}</h4>
                    </div>
                    <button type="submit" class="btn apply-btn" disabled>
                        Issue Labels
                    </button>
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
                                    <label for="agent_id" class="form-label">Agent Name</label>
                                    <div class="w100Select">
                                        <select class="select2Box" id="agent_id" name="agent_id" required
                                                {{$label?->agent_id ? 'disabled' : ''}}
                                                data-placeholder="Select Agent">
                                            <option></option>
                                            @foreach($agents as $agent)
                                                <option value="{{ $agent['id'] }}"
                                                    {{ isset($label->agent_id) && $agent['id'] == $label->agent_id ? 'selected' : '' }}
                                                >{{ $agent['name'] }}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col col-md-6">
                            <div class="row">
                                <div class="col col-md-10 disabled-control">
                                    <label for="request_id" class="form-label">Request No.</label>
                                    <input type="hidden" name="id" value="{{$label->id ?? ''}}" required>
                                    <input type="hidden" name="request_id" value="{{$request_id}}" required>
                                    <input type="text" class="form-control form-control-lg" readonly
                                           placeholder="{{$request_id}}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row no-display" id="addLabelBtn">
                        <div class="col-12 txt-right">
                        <span class="d-inline-block" data-bs-toggle="tooltip" data-placement="bottom"
                              title="{{ $disabled ? 'Return pending labels to request fresh labels' : '' }}">
                         <button type="button" class="btn cancel-btn add-product" {{$disabled ? 'disabled' : ''}}>
                            <img alt="" src="{{ asset('icons/add.png') }}" class="pe-2"> Add Label
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
                        <th scope="col" width="35%">LABEl TYPE</th>
                        <th scope="col" width="35%">REQUEST QUANTITY</th>
                        <th scope="col" width="35%">ISSUED QUANTITY</th>
                        <th scope="col" width="15%">ACTION</th>
                    </tr>
                    </thead>
                    <tbody id="label-table">
                    @include('pages.label.components.issue-table')
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

        let link = baseUrl + '/label-issues';
        let id = $('input[name="id"]').val();
        let items = @json($label->products ?? []);
        let oldItems = @json($label->products ?? []);
        let products = @json($products);
        let addProductBtn = $('.add-product');
        let table = $('#label-table');
        let labelFormBtn = $('#label-form').find('button[type="submit"]');
        let details = @json($label->productDetails ?? []);
        let oldIssued = @json($label->productDetails ?? []);
        let requestTotalQty = items.reduce((total, item) => total + item.qty, 0);

        console.log(items);
        $('#label-form').on('submit', function (e) {
            e.preventDefault();
            let data = {
                request_id: $('input[name="request_id"]').val(),
                agent_id: $('#agent_id').val()
            };

            if (id) {
                var url = link + "/" + id;
                var method = 'PUT';
                data.details = details;
            } else {
                var url = link + "/save";
                var method = 'POST';
                data.items = items;
            }

            $.ajax({
                url: url,
                method: method,
                data: data,
                success: function (response) {
                    let redirect = link + '/' + response.data;
                    if (id || method === 'POST')
                        redirect += '/summary';
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
            labelFormBtn.trigger('click');
        });

        $(".select2Box").each(function () {
            let placeholder = $(this).attr('data-placeholder');
            $(this).select2({
                placeholder: placeholder,
                minimumResultsForSearch: Infinity,
            });
        });

        $(document).on('change', 'select[name="agent_id"]', function (e) {
            let agent_id = $(this).val();
            let agent_name = $(this).find('option:selected').text();
            table.html('');
            items = [];
            let data = {
                agent_id: agent_id,
                id: id
            };
            $.ajax({
                url: link + '/exist',
                method: 'get',
                data: data,
                success: function (response) {
                    loadPreviousRequests(e);
                },
                error: function (xhr) {
                    var error = xhr.responseJSON;
                    if (error.error === 'exist') {
                        $('#exist-model').modal('show');
                        $('#agentName').html(agent_name);
                    } else {
                        showErrorMessage(xhr);
                    }
                }
            });
        });


        $(document).on('click', '#create-request', loadPreviousRequests);


        $('#product_form').on('submit', function (e) {
            e.preventDefault();
            if (!items.length) table.html('');
            $('#qty_error').text('');
            let qty = $('#qty').val();
            let issued_qty = $('input[name="issued_qty"]').val() ?? 0;
            if (qty === '0') {
                $('#qty_error').text('Please enter valid quantity');
                return false;
            }

            if (parseInt(issued_qty) > parseInt(qty)) {
                $('#qty_error').text('Please enter greater than or equal to issued quantity');
                return false;
            }

            labelFormBtn.prop('disabled', false);
            if (id)
                labelFormBtn.html('Update');
            else
                labelFormBtn.html('Send Request');

            let product_id = $('#product_id').val();
            let product_name = $('#product_id option:selected').text();

            let tr = table.find('tr[data-id="' + product_id + '"]');
            if (tr.length > 0) {
                let td = tr.find('td');
                td.eq(2).text(qty);

                items = items.map(function (item) {
                    if (item.product_id == product_id) {
                        item.qty = qty;
                    }
                    return item;
                });

            } else {
                items.push({
                    product_id: product_id,
                    product_name: product_name,
                    qty: qty
                });
                let tr = '<tr data-id="' + product_id + '">' +
                    '<td>1</td>' +
                    '<td>' + product_name + '</td>' +
                    '<td>' + qty + '</td>' +
                    '<td>' + 0 + '</td>' +
                    '<td>' +
                    '<a href="#" class="btn btn-outline-dark edit-product">Edit</a>' +
                    '</td>' +
                    '</tr>';
                table.append(tr);
            }

            $('#product_popup').modal('hide');
            addSerialNo();
            disabledAddButton();
        });

        $(document).on('click', '.add-product, .edit-product', function (e) {
            e.preventDefault();
            let modal = $('#product_popup');
            let form = $('#product_form');
            let productSelect = form.find('select[name="product_id"]');
            let selectedItem = '';
            productSelect.html('');

            if ($(this).hasClass('edit-product')) {
                let tr = $(this).closest('tr');
                let row_id = tr.attr('data-id');

                let item = items.find(function (item) {
                    return item.product_id == row_id;
                });
                selectedItem = item.product_id;
                form.find('input[name="id"]').val(row_id);
                form.find('input[name="qty"]').val(item.qty);
                form.find('input[name="issued_qty"]').val(item.issued_qty);
            } else {
                form.trigger('reset');
            }

            products.forEach(function (product) {
                let disabled = items.find(function (item) {
                    return item.product_id == product.id;
                });
                if (product.id == selectedItem)
                    disabled = false;
                let option = '<option value="' + product.id + '"  ' + (disabled ? 'disabled' : '') + '>' + product.name + '</option>';
                productSelect.append(option);
            });
            productSelect.val(selectedItem).trigger('change');
            modal.find('button[type="submit"]').prop('disabled', true);
            modal.modal('show');
        });

        function disabledAddButton() {
            if (table.find('tr').length === products.length) {
                addProductBtn.prop('disabled', true);
                addProductBtn.closest('span').attr('data-bs-original-title', 'No other label types to add');
            } else {
                addProductBtn.prop('disabled', false);
                addProductBtn.closest('span').attr('data-bs-original-title', '');
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
            items[item].issued_qty = 0;
            details = details.filter(function (detail) {
                return detail.product_id != product_id;
            });

            loadOldIssue(product_id);
            let oldProductIssued = oldIssued.filter(function (d) {
                return d.product_id == product_id;
            });

            oldProductIssued.forEach(function (det) {
                items[item].issued_qty += 1;
                details.push(det);
                $('#scanTbody').append(
                    '<tr>' +
                    '<td>' + det.code + '</td>' +
                    '<td>Envelope</td>' +
                    '<td>1</td>' +
                    '</tr>'
                );
            });


            table.find('tr[data-id="' + product_id + '"]').find('td').eq(3).text(oldProductIssued.length);
            $('#scanIssuedQty').html(oldProductIssued.length);
            $('input[name="scan_item"]').val('').focus();
            disabledIssueButton();
        });


        $(document).on('click', '.backHeader', function () {
            $('#headerForm').show();
            $('#scanForm').hide();
        });

        $(document).on('keydown', 'input[name="scan_item"]', function (e) {

            let code = $.trim($(this).val());
            let product_id = $('#scanProductId').val();
            let agent_id = $('select[name="agent_id"]').val();

            if (e.keyCode === 13 && code !== '') {
                if (checkDuplicateLabel(product_id, code)) return;
                $.ajax({
                    url: link + '/verify',
                    method: 'get',
                    data: {
                        agent_id: agent_id,
                        product_id: product_id,
                        code: code
                    },
                    success: function (response) {
                        checkAppendScanValue(product_id, code);
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
            $('#scanRequestQty').html(item.qty);
            $('#scanIssuedQty').html(item.issued_qty ?? 0);
            $('input[name="scan_item"]').focus();
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
            let issueQty = items[item].issued_qty ?? 0;

            if (issueQty > items[item].qty) {
                toastr.error('Cannot issue more than Requested quantity');
                return;
            }

            if (issueQty + 1 <= items[item].qty) {
                items[item].issued_qty = issueQty + 1;
                let tr = table.find('tr[data-id="' + product_id + '"]');
                tr.find('td').eq(3).text(items[item].issued_qty);
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
                $('#scanIssuedQty').html(items[item].issued_qty ?? 0);
                tr.find('td button').prop('disabled', false);
                if (items[item].issued_qty == items[item].qty) {
                    $('#headerForm').show();
                    $('#scanForm').hide();
                    tr.find('td button').prop('disabled', true);
                }

                disabledIssueButton();

            } else {
                toastr.error('Cannot issue more than Requested quantity');
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

        function disabledIssueButton() {
            let issueTotalQty = items.reduce((total, item) => total + (item.issued_qty ?? 0), 0);
            labelFormBtn.prop('disabled', !(issueTotalQty === requestTotalQty));
        }


        function loadPreviousRequests(e) {
            e.preventDefault();
            $('#exist-model').modal('hide');
            labelFormBtn.text('Create Request');
            $('#addLabelBtn').removeClass('no-display');

            let agent_id = $('select[name="agent_id"]').val();

            $.ajax({
                url: link + '/' + agent_id + '/load-previous',
                method: 'get',
                success: function (response) {
                    let data = response.data;
                    items = data.products;
                    table.html('');
                    items.forEach(function (item) {
                        let tr = '<tr data-id="' + item.product_id + '">' +
                            '<td>1</td>' +
                            '<td>' + item.product_name + '</td>' +
                            '<td>' + item.qty + '</td>' +
                            '<td>' + (item.issued_qty ?? 0) + '</td>' +
                            '<td>' +
                            '<a href="#" class="btn btn-outline-dark edit-product">Edit</a>' +
                            '</td>' +
                            '</tr>';
                        table.append(tr);
                    });

                    addSerialNo();
                    disabledAddButton();
                },
                error: function (xhr) {
                    var error = xhr.responseJSON;
                    if (error.error !== 'No label found') {
                        showErrorMessage(xhr);
                    }
                }
            });

        }

        function loadOldIssue(product_id = false) {
            if (oldIssued.length > 0) {
                if (product_id === false) {
                    items.forEach(function (item) {
                        let issued = oldIssued.filter(function (detail) {
                            return detail.product_id == item.product_id;
                        });
                        item.issued_qty = issued.length;
                    });
                }
            }
        }

       loadOldIssue();

    </script>
    <script>
            $('#export-excel').on('click', function() {

        let idValue = $('input[name="id"]').val();
        let url = `/label-issues/export-issue?id=${encodeURIComponent(idValue)}`;
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
                a.download = 'label-issues.xlsx';
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
