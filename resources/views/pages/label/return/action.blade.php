@extends('layouts.index')
@section('title', 'Label Return Create')
@section('style')
    @parent
    <link rel="stylesheet" href="{{ asset('css/label.css') }}">
@endsection
@section('content')
    <div class="pages purchase-order-create">

        <div id="headerForm">
            <form class="formFieldInput" id="label-form">
                <input type="hidden" name="id" value="{{$label->id ?? ''}}" required>
                <input type="hidden" name="request_id" value="{{$request_id}}" required>
                <section class="m-header">
                    <div class="hstack gap-3">
                        <a href="{{ url('/label-return') }}">
                            <img src="{{ asset('icons/arrow-left-alt.svg') }}" width="24" height="24" class="cP"/>
                        </a>
                        <h4>{{$request_id ?? 'Return Label'}}</h4>
                    </div>
                    <button type="submit" class="btn apply-btn" disabled>
                        Return Label
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
                                    <label for="fresh_id" class="form-label">Request No.</label>
                                    @if(isset($label->request_id))
                                        <input type="text" class="form-control form-control-lg" readonly
                                               placeholder="{{$label->request_id}}">
                                    @else
                                        <div class="w100Select">
                                            <select class="select2Box" id="fresh_id" required
                                                    data-placeholder="Select Request No.">
                                                <option value=""></option>
                                                @foreach($request_nos as $request_no)
                                                    <option
                                                        value="{{ $request_no['id'] }}">{{ $request_no['request_id'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                </div>
                            </div>
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
                        <th scope="col" width="35%">BALANCE</th>
                        <th scope="col" width="35%">RETURNED</th>
                        <th scope="col" width="15%">ACTION</th>
                    </tr>
                    </thead>
                    <tbody id="label-table">
                    @include('pages.label.components.return-table')
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

        let link = baseUrl + '/label-return';
        let id = $('input[name="id"]').val();
        let items = @json($label->products ?? []);
        let oldItems = @json($label->products ?? []);
        let products = @json($products);
        let addProductBtn = $('.add-product');
        let table = $('#label-table');
        let labelFormBtn = $('#label-form').find('button[type="submit"]');
        let details = @json($label->returnedProductDetails ?? []);
        let oldReturned = @json($label->returnedProductDetails ?? []);


        $('#label-form').on('submit', function (e) {
            e.preventDefault();

            if (details.length === 0) {
                toastr.error('Please scan the label');
                return;
            }
            if (id === '') {
                toastr.error('Please select the request no');
                return;
            }

            $.ajax({
                url: link + "/" + id,
                method: 'PUT',
                data: {
                    request_id: $('input[name="request_id"]').val(),
                    agent_id: $('#agent_id').val(),
                    details: details
                },
                success: function (response) {
                    window.location.href = link + '/' + response.data + '/summary';
                },
                error: function (xhr) {
                    showErrorMessage(xhr);
                }
            });
        });


        $(".select2Box").each(function () {
            let placeholder = $(this).attr('data-placeholder');
            $(this).select2({
                placeholder: placeholder,
                minimumResultsForSearch: Infinity,
            });
        });


        $(document).on('change', 'select[name="agent_id"]', function (e) {
            e.preventDefault();
            $('select[id="fresh_id"]').val('');
            getReturned();
        });
        $(document).on('change', 'select[id="fresh_id"]', function (e) {
            e.preventDefault();
            $('select[name="agent_id"]').val('');
            getReturned();
        });


        function getReturned() {
            let data = {
                agent_id: $('#agent_id').val(),
                id: $('#fresh_id').val()
            };

            $('#label-table').html('');
            $('input[name="request_id"]').val('');
            id = '';
            items = [];
            oldItems = [];
            details = [];
            oldReturned = [];

            $.ajax({
                url: link + '/details',
                method: 'get',
                data: data,
                success: function (response) {
                    $('#label-table').html(response.table);
                    $('select[name="agent_id"]').val(response.agent_id).trigger('change.select2');
                    $('select[id="fresh_id"]').val(response.id).trigger('change.select2');
                    $('input[name="request_id"]').val(response.request_id);
                    id = response.id;
                    items = response.products;
                    oldItems = response.products;
                    details = response.return_products.slice();
                    oldReturned = response.return_products.slice();
                    loadOldReturn();
                },
                error: function (xhr) {
                    showErrorMessage(xhr);
                    $('select[name="agent_id"]').val('').trigger('change.select2');
                    $('select[id="fresh_id"]').val('').trigger('change.select2');

                }
            });

        }


        function disabledAddButton() {
            if (table.find('tr').length === products.length) {
                addProductBtn.prop('disabled', true);
            } else {
                addProductBtn.prop('disabled', false);
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
            items[item].returned_qty = 0;

            details = details.filter(function (detail) {
                return detail.product_id != product_id;
            });

            loadOldReturn(product_id);

            let oldProductReturned = oldReturned.filter(function (d) {
                return d.product_id == product_id;
            });

            oldProductReturned.forEach(function (det) {
                items[item].returned_qty += 1;
                details.push(det);
                $('#scanTbody').append(
                    '<tr>' +
                    '<td>' + det.code + '</td>' +
                    '<td>Envelope</td>' +
                    '<td>1</td>' +
                    '</tr>'
                );
            });

            table.find('tr[data-id="' + product_id + '"]').find('td').eq(3).text(oldProductReturned.length);
            $('#scanReturnedQty').html(oldProductReturned.length);
            $('input[name="scan_item"]').val('').focus();

        });

        $(document).on('click', '.backHeader', function () {
            $('#headerForm').show();
            $('#scanForm').hide();
        });

        $(document).on('keydown', 'input[name="scan_item"]', function (e) {
            let code = $.trim($(this).val());
            let product_id = $('#scanProductId').val();

            if (e.keyCode === 13 && code !== '') {
                if (checkDuplicateLabel(product_id, code)) return;
                $.ajax({
                    url: link + '/verify',
                    method: 'get',
                    data: {
                        label_id: id,
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
                        $('input[name="scan_item"]').val('').focus();
                    }
                });

            }
        });

        $(document).on('click', '.scan-product', function (e) {
            $('#headerForm').hide();
            $('#scanForm').show();
            $('#scanTbody').html('');

            let tr = $(this).closest('tr');
            let id = tr.attr('data-id');

            let item = items.find(function (item) {
                return item.product_id == id;
            });

            let productName = tr.find('td').eq(1).text();

            $('#scanProductId').val(item.product_id);
            $('#scanProduct').html(productName);
            $('#scanBalanceQty').html(item.balance_qty);
            $('#scanReturnedQty').html(item.returned_qty ?? 0);
            $('input[name="scan_item"]').focus();

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
            let returnedQty = items[item].returned_qty ?? 0;

            if (returnedQty > items[item].balance_qty) {
                toastr.error('Cannot issue more than Requested quantity');
                return;
            }

            if (returnedQty + 1 <= items[item].balance_qty) {
                items[item].returned_qty = returnedQty + 1;

                let tr = table.find('tr[data-id="' + product_id + '"]');
                tr.find('td').eq(3).text(items[item].returned_qty);
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
                $('#scanReturnedQty').html(items[item].returned_qty ?? 0);
                tr.find('td button').prop('disabled', false);
                if (items[item].returned_qty == items[item].balance_qty) {
                    $('#headerForm').show();
                    $('#scanForm').hide();
                    tr.find('td button').prop('disabled', true);
                }

                labelFormBtn.prop('disabled', false);
                // disabledReturnButton();

            } else {
                toastr.error('Cannot return more than Balance quantity');
            }
        }

        function scanTbodyHtml(empty = false) {

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

        function loadOldReturn(product_id = false) {

            if (oldReturned.length > 0) {
                if (product_id === false) {
                    items.forEach(function (item) {
                        let returned = oldReturned.filter(function (detail) {
                            return detail.product_id == item.product_id;
                        });
                        item.balance_qty += returned.length;
                    });
                }
            }
        }

        loadOldReturn();
    </script>
     <script>
            $('#export-excel').on('click', function() {
        
        let idValue = $('input[name="id"]').val();
        let url = `/label-return/export-return?id=${encodeURIComponent(idValue)}`;
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
                a.download = 'label-return.xlsx';
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
