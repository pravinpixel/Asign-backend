@extends('layouts.index')
@section('title', 'Label Request Create')
@section('style')
    @parent
    <link rel="stylesheet" href="{{ asset('css/label.css') }}">
@endsection
@section('content')
    <div class="pages purchase-order-create">
        <form class="formFieldInput" id="label-form">
            <section class="m-header">
                <div class="hstack gap-3">
                    <a href="{{ url('/label-request') }}">
                        <img src="{{ asset('icons/arrow-left-alt.svg') }}" width="24" height="24" class="cP"/>
                    </a>
                    <h4>{{$request_id}}</h4>
                </div>
                <button type="submit" class="btn apply-btn" disabled>
                    Create Request
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
                                            {{$label?->id ? 'disabled' : ''}}
                                            data-placeholder="Select Source">
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
                <div class="row">
                    <div class="col-12 txt-right">
                        <span class="d-inline-block" data-bs-toggle="tooltip" data-placement="bottom"
                              title="{{ $disabled ? 'Return pending labels to request fresh labels' : '' }}"
                        >
                         <button type="button" class="btn cancel-btn add-product addicon-btn" {{$disabled ? 'disabled' : ''}}>
                             <i class='bx bx-plus pe-2'></i> Add Label
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
                    <th scope="col" width="25%">QUANTITY</th>
                    <th scope="col" width="25%">ACTION</th>
                </tr>
                </thead>
                <tbody id="label-table">
                @include('pages.label.components.product-table')
                </tbody>
            </table>
        </section>

        @include('components.product')
    </div>
@endsection
@push('scripts')
    <script type="text/javascript">

        let disabled = @json($disabled);
        let id = $('input[name="id"]').val();
        let items = @json($label->products ?? []);
        let products = @json($products);
        let addProductBtn = $('.add-product');
        let table = $('#label-table');
        let labelFormBtn = $('#label-form').find('button[type="submit"]');

        let link = baseUrl + '/label-request';

        $('#label-form').on('submit', function (e) {
            e.preventDefault();

            let url = link + "/save";
            let method = 'POST';
            if (id) {
                url = link + "/" + id;
                method = 'PUT';
            }

            let data = {
                request_id: $('input[name="request_id"]').val(),
                agent_id: $('#agent_id').val(),
                items: items
            };
            $.ajax({
                url: url,
                method: method,
                data: data,
                success: function (response) {
                    //window.location.href = link + '/' + response.data;
                    window.location.href = link;
                },
                error: function (xhr) {
                    var error = xhr.responseJSON;
                    if (error.error === 'invalid-request-no') {
                        $('.conform_header').text('Alert');
                        $('.conform_content').text(error.message['msg']);
                        $('input[name="request_id"]').val(error.message['request_id']);
                        $('#conform').modal('show');
                    }
                    else if (error.error === 'invalid-status') {
                        toastr.error(error.message['msg']);
                    }
                    else {
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


        $('#product_form').on('submit', function (e) {
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
                addProductBtn.prop('disabled', !$('#agent_id').val());
                addProductBtn.closest('span').attr('data-bs-original-title', '');
            }
        }

        function disabledSubmitButton() {
            labelFormBtn.prop('disabled', items.length === 0);
            labelFormBtn.html(id ? 'Update' : 'Create Request');
        }

        function addSerialNo() {
            let tr = table.find('tr');
            tr.each(function (index, item) {
                $(this).find('td').eq(0).text(index + 1);
            });
        }

        disabledButton('product_form');

        if (!disabled)
            disabledAddButton();
    </script>
<script>
    $('#export-excel').on('click', function() {
    
        let idValue = $('input[name="id"]').val();
        let url = `/label-request/export-view?id=${encodeURIComponent(idValue)}`;
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
                a.download = 'label-requests.xlsx';
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
