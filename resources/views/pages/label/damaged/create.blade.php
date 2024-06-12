@php
    
@endphp
@extends('layouts.index')
@section('title', 'Label Damaged Create')
@section('style')
@parent
<style type="text/css">
    .purchase-order-create .m-header{
        padding: 24px 32px;
        display: flex;
        justify-content: flex-start;
        align-items: center;
        border-bottom: 1px solid rgba(29, 29, 29, 0.20);
    }
    .purchase-order-create .m-header .btn{
        margin-left: auto;
    }
    .purchase-order-create .form-content{
        padding: 24px 32px 0px;
    }
    .purchase-order-create  .table-content{
        padding-top: 20px;
    }

    .input-addon{
        position: relative;
    }
    .input-addon > .form-control {
        position: relative;
        z-index: 9;
    }
   .dropdown-toggle:empty::after {
      margin-left: 10px;
      display: none;
    }
</style>
@endsection
@section('content')
<div class="pages purchase-order-create">
    <section class="m-header">
        <div class="hstack gap-3">
            <a href="{{url('/label-damaged')}}">
                <img src="{{ asset('icons/arrow-left-alt.svg') }}" width="24" height="24" class="cP" />
            </a>
            <h4>Damaged Label</h4>
        </div>
        <button type="button" class="btn apply-btn" id="create_btn" disabled="true">
            Save
        </button>
        <div class="dropbar-bar" style="margin-left: 15px">
            <!-- <i class='bx bx-dots-vertical-rounded fs-4 dropdown-toggle' data-bs-toggle="dropdown" aria-expanded="false"></i> -->
            {{-- <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Export</a></li>
            </ul> --}}
        </div>
    </section>
    <section class="form-content">
        <input type="hidden" id="exist_location" name="exist_location" value="{{$selected_loc}}">
        <form class="formFieldInput" id="create_form">
            <input type="hidden" id="product_type" name="product_type" value="">
            <div class="row mb-4">
                <div class="col col-md-6">
                    <div class="row">
                        <div class="col col-md-10">
                            <label for="location_id" class="form-label">Location</label>
                            <div class="w100Select">
                                <select class="select2Box locationbox" data-placeholder="Select Location" id="location_id" name="location_id">
                                    <option></option>
                                    @foreach($locations as $loc => $location)
                                        <option 
                                            value="{{$location['id']}}"
                                            {{ ($location['id'] === $selected_loc) ? 'selected' : '' }} 
                                            >
                                            {{$location['location']}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col col-md-6">
                    <div class="row">
                        <div class="col col-md-10">
                            <label for="reference_id" class="form-label" style="color: #B5B5B5">Reference Number</label>
                            <input style="color: #B5B5B5" type="text" class="form-control form-control-lg" id="reference_id" name="reference_id" value="{{$reference_id}}" placeholder="DL00002" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 txt-right">
                   <button type="button" class="btn cancel-btn" disabled id="trigger_popup_form">
                       <span><i class='bx bx-plus'></i></span>
                        Add Labels
                    </button>           
                </div>
            </div>
        </form>
    </section>
    <section class="table-content" id="product_table">
        @include('pages.label.damaged.tables.product_table', ["products"=>$products])
    </section>
</div>
@include('pages.label.damaged.modals.label_popup')
@endsection
@push('scripts')
<script type="text/javascript" src="{{ asset('js/jquery.validate.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/label/label_damaged_create.js') }}"></script>
<script type="text/javascript">
    let config = new Object();
    config.links = new Object;
    config.data = new Object;
    config.links.save = "{{ route('label-damaged.save') }}";
    config.links.labels = "{{ route('label-damaged.labels') }}";
    config.links.productList = "{{ url('label-damaged/get-products') }}";
    config.links.updateDamagedLabels = "{{ route('label-damaged.update-damaged-labels') }}";
    config.links.list = "{{ route('label-damaged.list') }}";
    config.links.clear = "{{ url('label-damaged/clear-products') }}";
    config.data.damageID = "{{ $reference_id }}";

    new CreateDamaged(config);
</script>
@endpush
