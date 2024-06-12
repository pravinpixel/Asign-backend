@extends('layouts.index')
@section('title', 'Stock Transfer Orders')
@section('style')
    @parent
    <link rel="stylesheet" href="{{ asset('css/label.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/jquery-ui.css')}}"/>
@endsection
@section('content')
    <div class="pages purchase-order-create">
        <form class="formFieldInput" id="transfer-form">
            <input type="hidden" name="id" value="{{$transfer->id ?? ''}}">
            <input type="hidden" name="transfer_no" value="{{$transfer_no}}">
            <section class="m-header">
                <div class="hstack gap-3">
                    <a href="{{ url('/label-transfer') }}">
                        <img src="{{ asset('icons/arrow-left-alt.svg') }}" width="24" height="24" class="cP"/>
                    </a>
                    <h4>{{$transfer_no}}</h4>
                </div>
                <button type="submit" class="btn apply-btn" disabled>
                    Create STO
                </button>
                <div class="dropdown-bar">
                    <i class='bx bx-dots-vertical-rounded fs-4 dropdown-toggle' data-bs-toggle="dropdown"
                       aria-expanded="false"></i>
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
                                <label for="source_id" class="form-label">Stock Source</label>
                                <div class="w100Select">
                                    <select class="select2Box" id="source_id" name="source_id" required
                                            {{$disabled ? 'disabled' : ''}}
                                            data-placeholder="Select Stock Source">
                                        <option></option>
                                        @foreach($branches as $branch)
                                            <option value="{{ $branch['id'] }}"
                                                {{isset($transfer->destination_id) && $branch['id'] == $transfer->destination_id ? 'disabled' : ''}}
                                                {{ isset($transfer->source_id) && $branch['id'] == $transfer->source_id ? 'selected' : '' }}
                                            >{{ $branch['name'] }}</option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col col-md-6">
                        <div class="row">
                            <div class="col col-md-10">
                                <label for="destination_id" class="form-label">Stock Destination</label>
                                <div class="w100Select">
                                    <select class="select2Box" id="destination_id" name="destination_id" required
                                            {{$disabled ? 'disabled' : ''}}
                                            data-placeholder="Select Stock Destination">
                                        <option></option>
                                        @foreach($branches as $branch)
                                            <option value="{{ $branch['id'] }}"
                                                {{isset($transfer->source_id) && $branch['id'] == $transfer->source_id ? 'disabled' : ''}}
                                                {{ isset($transfer->destination_id) && $branch['id'] == $transfer->destination_id ? 'selected' : '' }}
                                            >{{ $branch['name'] }}</option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col col-md-6">
                        <div class="row">
                            <div class="col col-md-10">
                                <label for="reason_id" class="form-label">Transfer Reason</label>
                                <div class="w100Select">
                                    <select class="select2Box" id="reason_id" name="reason_id" required
                                            {{$disabled ? 'disabled' : ''}}
                                            data-placeholder="Select Transfer Reason">
                                        <option></option>
                                        @foreach($reasons as $reason)
                                            <option value="{{ $reason['id'] }}"
                                                {{ isset($transfer->source_id) && $reason['id'] == $transfer->reason_id ? 'selected' : '' }}
                                            >{{ $reason['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col col-md-6">
                        <div class="row">
                            <div class="col col-md-10 input-addon addon-right">
                                <label for="date" class="form-label">Creation Date</label>
                                <input {{$disabled ? 'disabled' : ''}} type="text" class="datepicker form-control form-control-lg" id="date"
                                       value="{{\App\Helpers\UtilsHelper::displayDate($transfer?->date, 'l, d M, Y')}}"
                                       placeholder="Select Creation Date" name="date">
                                <span class="error" id="created_date_error"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4" id="other_reason_input" style="display: none">
                    <div class="col col-md-6">
                        <div class="row">
                            <div class="col col-md-10">
                                <label for="reason_others" class="form-label">Other Reason</label>
                                <input type="text" {{$disabled ? 'disabled' : ''}} class="form-control form-control-lg" id="reason_others"
                                       name="reason_others" placeholder="Type Other Reason"
                                       value="{{$transfer?->reason_others}}">
                                <span class="error" id="other_transfer_reason_error"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 txt-right">
                        <span class="d-inline-block" data-bs-toggle="tooltip" data-placement="bottom">
                                <button type="button" class="btn cancel-btn add-product addicon-btn">
                             <i class='bx bx-plus pe-2'></i> Add Products
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
                    <th scope="col" width="35%">PRODUCT NAME</th>
                    <th scope="col" width="25%">ORDER QUANTITY</th>
                    <th scope="col" width="25%">ACTION</th>
                </tr>
                </thead>
                <tbody id="transfer-table">
                @include('pages.label.components.transfer-table')
                </tbody>
            </table>
        </section>

        @include('components.product-stock')
    </div>
@endsection
@push('scripts')
    <script src="{{asset('js/jquery-ui.min.js')}}"></script>
    <script type="text/javascript">

        let disabled = @json($disabled);
        let id = $('input[name="id"]').val();
        let items = @json($transfer->details ?? []);
        let products = @json($products);
        let addProductBtn = $('.add-product');
        let table = $('#transfer-table');
        let transferFormBtn = $('#transfer-form').find('button[type="submit"]');

        let link = baseUrl + '/label-transfer';

        $('#transfer-form').on('submit', function (e) {
            e.preventDefault();

            let url = link + "/save";
            let method = 'POST';
            if (id) {
                url = link + "/" + id;
                method = 'PUT';
            }

            var data = {};
            var dataArray = $('#transfer-form').serializeArray();

            $(dataArray).each(function (i, field) {
                if (field.name === "date") {
                    data[field.name] = changeDateFormat(field.value);
                } else {
                    data[field.name] = field.value;
                }
            });
            data['items'] = items;

            $.ajax({
                url: url,
                method: method,
                data: data,
                success: function (response) {
                    window.location.href = link;
                },
                error: function (xhr) {
                    var error = xhr.responseJSON;
                    if (error.error === 'invalid-transfer-no') {
                        $('.conform_header').text('Alert');
                        $('.conform_content').text(error.message['msg']);
                        $('input[name="transfer_no"]').val(error.message['transfer_no']);
                        $('#conform').modal('show');
                    } else if (error.error === 'invalid-status') {
                        toastr.error(error.message);
                    } else {
                        showErrorMessage(xhr);
                    }
                }
            });
        });

        $(document).on('click', '#conform_save', function () {
            transferFormBtn.trigger('click');
        });


        $(".select2Box").each(function () {
            let placeholder = $(this).attr('data-placeholder');
            $(this).select2({
                placeholder: placeholder,
                minimumResultsForSearch: Infinity,
            });
        });
        $(".datepicker").datepicker({
            dateFormat: "DD, d M, yy",
            minDate: 0
        });

        $(document).on('change', '#reason_id', function () {
            let reason = $(this).find('option:selected').text();
            if (reason === 'Others' || reason === 'others' || reason === 'Other' || reason === 'other') {
                $('#other_reason_input').show();

            } else {
                $('#other_reason_input').hide();
            }
        });

        $('#reason_id').trigger('change');

        $(document).on('change', '#source_id, #destination_id', function () {
            let source_id = $('#source_id').val();
            let destination_id = $('#destination_id').val();
            $('#source_id option').prop('disabled', false);
            $('#destination_id option').prop('disabled', false);
            $('#source_id option[value="' + destination_id + '"]').prop('disabled', true);
            $('#destination_id option[value="' + source_id + '"]').prop('disabled', true);
            table.html(' <tr><td colspan="4" class="txt-center empty-msg">Add Labels</td></tr>');
            disabledAddButton();
        });


        $(document).on('change', 'select[name="agent_id"]', function () {
            disabledAddButton();
            table.html('');
            items = [];
            let data = {
                agent_id: $(this).val(),
                id: id
            };
            $.ajax({
                url: link + '/exist',
                method: 'get',
                data: data,
                success: function (response) {
                    console.log(response);
                },
                error: function (xhr) {
                    var error = xhr.responseJSON;
                    if (error.error === 'exist') {
                        window.location.href = link + '/' + error.message['id'];
                    } else {
                        showErrorMessage(xhr);
                    }
                }
            });
        });


        $('#product_stock_form').on('submit', function (e) {
            e.preventDefault();
            if (!items.length) table.html('');
            $('#qty_error').text('');
            let qty = $('#qty').val();
            if (qty === '0') {
                $('#qty_error').text('Please enter valid quantity');
                return false;
            }

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
                    '<td>' +
                    '<a href="#" class="btn btn-outline-dark edit-product  mx-2">Edit</a>' +
                    '<a href="#" class="btn btn-outline-dark remove-product">Remove</a>' +
                    '</td>' +
                    '</tr>';
                table.append(tr);
            }

            $('#product_popup').modal('hide');
            $('#checkAvailable').show();
            addSerialNo();
            disabledAddButton();
            disabledSubmitButton();
        });

        $(document).on('click', '.remove-product', function (e) {
            e.preventDefault();
            let tr = $(this).closest('tr');
            let row_id = tr.attr('data-id');
            items = items.filter(function (item) {
                return item.product_id != row_id;
            });
            tr.remove();
            addSerialNo();
            disabledAddButton();
            disabledSubmitButton();
        });


        $(document).on('click', '.add-product, .edit-product', function (e) {
            e.preventDefault();
            let modal = $('#product_popup');
            let form = $('#product_stock_form');
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
            modal.find('.ok-btn').prop('disabled', true);
            modal.modal('show');
        });


        function disabledAddButton() {
            let message = '';
            let addProductBtnDisabled = true;
            if (table.find('tr').length === products.length) {
                addProductBtnDisabled = true;
                message = 'No other label types to add';
            } else {
                if ($('#source_id').val() && $('#destination_id').val()) {
                    addProductBtnDisabled = false;
                }
            }
            addProductBtn.prop('disabled', addProductBtnDisabled);
            addProductBtn.closest('span').attr('data-bs-original-title', message);
        }

        function disabledSubmitButton() {

            transferFormBtn.prop('disabled', items.length === 0);
        }

        function addSerialNo() {
            let tr = table.find('tr');
            tr.each(function (index, item) {
                $(this).find('td').eq(0).text(index + 1);
            });
        }

        $(document).on('click', '#checkAvailable', function(){

            // check stock available

            $('#checkAvailable').hide();
            $('#okAvailable').show();
        });


        disabledButton('product_stock_form');
        disabledAddButton();

    </script>
    <script>
        $('#export-excel').on('click', function () {

            let idValue = $('input[name="id"]').val();
            let url = `/label-transfer/export-view?id=${encodeURIComponent(idValue)}`;
            $.ajax({
                url: baseUrl + url,
                method: 'GET',
                xhrFields: {
                    responseType: 'blob'
                },
                success: function (blob) {
                    let url = window.URL.createObjectURL(blob);
                    let a = document.createElement('a');
                    a.href = url;
                    a.download = 'label-transfer.xlsx';
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                    window.URL.revokeObjectURL(url);
                },
                error: function (xhr) {
                    console.error('Export failed:', xhr);
                }
            });
        });
    </script>
@endpush
