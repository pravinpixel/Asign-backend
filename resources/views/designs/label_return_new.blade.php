@extends('layouts.index')
@section('title', 'Label Return Create')
@section('style')
@parent
<link rel="stylesheet" type="text/css" href="{{asset('css/jquery-ui.css')}}" />
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
    
</style>
@endsection
@section('content')
<div class="pages purchase-order-create">
    <section class="m-header">
        <div class="hstack gap-3">
            <a href="{{url('/label-issues')}}">
                <img src="{{ asset('icons/arrow-left-alt.svg') }}" width="24" height="24" class="cP" />
            </a>
            <h4>LR000001</h4>
        </div>
        <button type="button" class="btn apply-btn">
            Issue Labels
        </button>
    </section>
    <section class="form-content">
        <form class="formFieldInput">
            <div class="row mb-4">
                <div class="col col-md-6">
                    <div class="row">
                        <div class="col col-md-10">
                            <label for="agent_name" class="form-label">Agent Name</label> 
                            <div class="w100Select">
                                <select class="select2Box" data-placeholder="Select Agent">
                                    <option></option>
                                    <option value="Manipal">Manipal</option>
                                    <option value="Harsh">Harsh</option>
                                    <option value="Redmond D’Souza">Redmond D’Souza</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col col-md-6">
                    <div class="row">
                        <div class="col col-md-10">
                            <label for="purchase_order_no" class="form-label">Request No.</label>
                            <input type="text" class="form-control form-control-lg" id="purchase_order_no" placeholder="LR000001">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
    <section class="table-content">        
        @include('components.tables.label_return_create_table')
    </section>
</div>
@include('components.popups.add_product_popup')
@endsection
@push('scripts')
<script src="{{asset('js/jquery-ui.min.js')}}"></script>
<script type="text/javascript">
$(document).ready(function() {
    $(".select2Box").each(function(){
        var placeholder = $(this).attr('data-placeholder');
        $(this).select2({
            placeholder: placeholder,
            minimumResultsForSearch: Infinity,
        });
    });
    $(".datepicker").datepicker({
        dateFormat: "DD, d M, yy"
    });
});
</script>
@endpush
