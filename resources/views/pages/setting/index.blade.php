@extends('layouts.index')
@section('title', 'Settings')
@section('style')

@parent
@endsection
@section('content')
<div class="pages customers-list">
    <section class="main-header">
        <div class="section-breadcrumb">
            <ul>

                <li>
                    <a href="{{url('settings')}}"> <img src="{{ asset('icons/profile.svg') }}" alt="settings img" />Settings</a>
                </li>
                <li>
                </li>
            </ul>
        </div>
        <div class="section-title">
            <h4>Settings<span id="total"></span></h4>
        </div>
        <!-- #....Header -->
        <!-- #...search -->

        <!-- #...end search -->
    </section>
    <div id="successMessage" style="display: none;" class="alert alert-success"></div>
    <!-- #...table -->
    <section class="table-content">
        <div class="accordion" id="accordionExample">
            <!-- start -->
            @foreach($settings as $setting)
            <!-- 1st form -->
            @if($setting->slug == 'pricing_artworks')
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">

                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        {{ $setting->name ?? '' }}
                    </button>

                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <form action="" method="POST" id="settingsForm">
                            @php
                            $decodedValue = json_decode($setting->value ?? '', true);
                            @endphp
                            <input type="hidden" id="id" name="id" value="{{ $setting->id ?? '' }}">
                            <input type="hidden" id="slug" name="slug" value="{{ $setting->slug ?? '' }}">
                            @csrf
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">Margin Price:</label>
                                        <input style="width:100%;" type="text" class="form-control" placeholder="Margin Price" name="margin" value="{{ $decodedValue['margin'] ?? '' }}">
                                        <span id="field_margin" class="field-error"></span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">Markup Price Platform Charge:</label>
                                        <input style="width:100%;" type="text" class="form-control" placeholder="Markup Price" name="markup" value="{{ $decodedValue['markup'] ?? '' }}">
                                        <span id="field_markup" class="field-error"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">Service Charge:</label>
                                        <input style="width:100%;" type="text" class="form-control" placeholder="Service Charge" name="service" value="{{ $decodedValue['service'] ?? '' }}">
                                        <span id="field_service" class="field-error"></span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">Shipping Charge:</label>
                                        <input style="width:100%;" type="text" class="form-control" placeholder="Shipping Charge" name="shipping" value="{{ $decodedValue['shipping'] ?? '' }}">
                                        <span id="field_shipping" class="field-error"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Packing Price:</label>
                                <input style="width:49%;" type="text" class="form-control" placeholder="Packing Price" name="packing" value="{{ $decodedValue['packing'] ?? '' }}">
                                <span id="field_packing" class="field-error"></span>
                            </div>
                            <center>
                                <div class="footerbtnDiv" style="margin-top:2%;">
                                    <button type="submit" id="submitButton" onclick="disableButton()" class="btn apply-btn">
                                        @if (!empty($decodedValue) )
                                        Update
                                        @else
                                        Save
                                        @endif
                                    </button>
                                </div>
                            </center>

                        </form>

                    </div>
                </div>
            </div>
            @endif
            <!-- 2nd form -->
            @if($setting->slug == 'label')
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">

                        {{ $setting->name ?? '' }}

                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <form action="" method="POST" id="labelForm">

                            @php
                            $value = json_decode($setting->value ?? '', true);
                            @endphp
                            <input type="hidden" id="id" name="id" value="{{ $setting->id ?? '' }}">
                            <input type="hidden" id="slug" name="slug" value="{{ $setting->slug ?? '' }}">

                            @csrf

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">Label Cost:</label>
                                        <input style="width:100%;" type="text" class="form-control" placeholder="Label Cost" name="labelcost" value="{{ $value['labelcost'] ?? '' }}">
                                        <span id="field_labelcost" class="field-error"></span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">NO Of Min Label:</label>
                                        <input style="width:100%;" type="text" class="form-control" placeholder="NO Of Min Label" name="minlabel" value="{{ $value['minlabel'] ?? '' }}">
                                        <span id="field_minlabel" class="field-error"></span>
                                    </div>
                                </div>
                            </div>

                            <center>
                                <div class="footerbtnDiv" style="margin-top:2%;">
                                    <button type="submit" id="labelsubmit" onclick="disableButton()" class="btn apply-btn">
                                        @if (!empty($value) )
                                        Update
                                        @else
                                        Save
                                        @endif
                                    </button>
                                </div>
                            </center>

                        </form>
                    </div>
                </div>
            </div>
            @endif
            <!-- 3rd form -->
            @if($setting->slug == 'market_place')
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        {{ $setting->name ?? '' }}
                    </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <form action="" method="POST" id="marketForm">

                            @php
                            $data = json_decode($setting->value ?? '', true);
                            @endphp
                            <input type="hidden" id="id" name="id" value="{{ $setting->id ?? '' }}">
                            <input type="hidden" id="slug" name="slug" value="{{ $setting->slug ?? '' }}">

                            @csrf

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">Payment Limitation Per Transaction:</label>
                                        <input style="width:100%;" type="text" class="form-control" placeholder="Payment Limitation" name="payment" value="{{ $data['payment'] ?? '' }}">
                                        <span id="field_payment" class="field-error"></span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">Cart Expiry:</label>
                                        <input style="width:100%;" type="text" class="form-control" placeholder="Cart Expiry" name="expiry" value="{{ $data['expiry'] ?? '' }}">
                                        <span id="field_expiry" class="field-error"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">Amount Repayment Days:</label>
                                        <input style="width:100%;" type="text" class="form-control" placeholder="Amount Repayment Days" name="repayment" value="{{ $data['repayment'] ?? '' }}">
                                        <span id="field_repayment" class="field-error"></span>
                                    </div>
                                </div>
                            </div>

                            <center>
                                <div class="footerbtnDiv" style="margin-top:2%;">
                                    <button type="submit" id="marketsubmit" onclick="disableButton()" class="btn apply-btn">
                                        @if (!empty($data) )
                                        Update
                                        @else
                                        Save
                                        @endif
                                    </button>
                                </div>
                            </center>

                        </form>
                    </div>
                </div>
            </div>
            @endif
             <!-- 4th form -->
             @if($setting->slug == 'payment_details')
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingFour">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                        {{ $setting->name ?? '' }}
                    </button>
                </h2>
                <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <form action="" method="POST" id="paymentForm">

                            @php
                            $data = json_decode($setting->value ?? '', true);
                            @endphp
                            <input type="hidden" id="id" name="id" value="{{ $setting->id ?? '' }}">
                            <input type="hidden" id="slug" name="slug" value="{{ $setting->slug ?? '' }}">

                            @csrf

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">Account Name:</label>
                                        <input style="width:100%;" type="text" class="form-control" placeholder="Account Name" name="account_name" value="{{ $data['account_name'] ?? '' }}">
                                        <span id="field_account_name" class="field-error"></span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">Account NO:</label>
                                        <input style="width:100%;" type="text" class="form-control" placeholder="Account NO" name="account_nO" value="{{ $data['account_nO'] ?? '' }}">
                                        <span id="field_account_nO" class="field-error"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                     <div class="form-group">
                                        <label class="form-label">IFSC:</label>
                                        <input style="width:100%;" type="text" class="form-control" placeholder="IFSC" name="ifsc" value="{{ $data['ifsc'] ?? '' }}">
                                        <span id="field_ifsc" class="field-error"></span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">IBAN:</label>
                                        <input style="width:100%;" type="text" class="form-control" placeholder="IBAN" name="iban" value="{{ $data['iban'] ?? '' }}">
                                        <span id="field_iban" class="field-error"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                     <div class="form-group">
                                        <label class="form-label">Swift:</label>
                                        <input style="width:100%;" type="text" class="form-control" placeholder="Swift" name="swift" value="{{ $data['swift'] ?? '' }}">
                                        <span id="field_swift" class="field-error"></span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">Branch:</label>
                                        <input style="width:100%;" type="text" class="form-control" placeholder="Branch" name="branch" value="{{ $data['branch'] ?? '' }}">
                                        <span id="field_branch" class="field-error"></span>
                                    </div>
                                </div>
                            </div>

                            <center>
                                <div class="footerbtnDiv" style="margin-top:2%;">
                                    <button type="submit" id="paymentsubmit" onclick="disableButton()" class="btn apply-btn">
                                        @if (!empty($data) )
                                        Update
                                        @else
                                        Save
                                        @endif
                                    </button>
                                </div>
                            </center>

                        </form>
                    </div>
                </div>
            </div>
            @endif
            <!-- end -->
            @endforeach
        </div>
    </section>
</div>
<!-- #...end table -->
<!-- #..paginate -->

@include('layouts.paginate')
@include('pages.masters.deletemodel')
@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        initializeFormValidation('#settingsForm', '#submitButton');
        initializeFormValidation('#labelForm', '#labelsubmit');
        initializeFormValidation('#marketForm', '#marketsubmit');
        initializeFormValidation('#paymentForm', '#paymentsubmit');
    });

    function isEmpty(value) {
        return value === null || value === undefined || value === '';
    }

    function initializeFormValidation(formId, submitButtonId) {
        var $form = $(formId);
        var $submitButton = $(submitButtonId);

        $submitButton.prop('disabled', true);

        $form.on('change keyup paste', function(e) {
            let allValues = $(this).serializeArray();
            let formType = $form.find('input[name="id"]').val();
            if (formType) {
                $submitButton.prop('disabled', true);
            }
            var isAnyValueEmpty = allValues.some(function(obj) {
                for (var key in obj) {
                    if (obj["name"] !== "id") {
                        if (obj.hasOwnProperty(key) && isEmpty(obj[key])) {
                            return true;
                        }
                    }
                }
                return false;
            });
            $submitButton.prop('disabled', isAnyValueEmpty);
        });
    }

    $(document).ready(function() {
        function submitForm(formId, url, successMessageSelector) {
            $(document).on('submit', formId, function(event) {
                event.preventDefault();
                var formData = $(this).serialize();

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        console.log(response);
                        $('.field-error').text('');

                        if (response.success && response.message) {
                           
                            $(successMessageSelector).text(response.message).show();
                            $(formId).find('button[type="submit"]').text('Update');
                            setTimeout(function() {
                                $(successMessageSelector).fadeOut('slow');
                            }, 3000);
                           
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        var errors = JSON.parse(xhr.responseText);
                        $.each(errors.errors, function(key, value) {
                            $(formId).find("#field_" + key).html('<span class="field-error">' + value + '</span>');
                        });
                    }
                });
            });
        }


        submitForm('#settingsForm', '{{ route('pricing.save') }}', '#successMessage');
        submitForm('#labelForm', '{{ route('label.save') }}', '#successMessage');
        submitForm('#marketForm', '{{ route('market.save') }}', '#successMessage');
        submitForm('#paymentForm', '{{ route('payment.save') }}', '#successMessage');
    });
</script>