@extends('layouts.index')
@section('title', 'Goods Received Notes')
@section('style')
@parent
<style type="text/css">
    .purchase-orders .addon-search{
        height: 100%;
        padding: 10px 8px 0px 16px;
    }
</style>
@endsection
@section('content')
<div class="pages purchase-orders">
    <section class="main-header filterHeaderCtr">
        <div class="section-breadcrumb">
            <ul>
                <li>
                    <img src="{{ asset('icons/box-1.svg') }}" width="20" height="20" /><a href="#">Good Received Note</a>
                </li>
                <li>
                    <span id="toggle_sidebar">
                        <img src="{{ asset('icons/arrange-square.svg') }}" width="20" />
                    </span>
                </li> 
            </ul>
        </div>
        <div class="section-title">
          <h4>Goods Received Notes</h4>
        </div>        
    </section>
    @include('components.tables.grn_filter')
    <section class="table-content">        
        @include('components.tables.grn_table')
    </section>
</div>
@endsection
@push('scripts')
<script type="text/javascript">
$(document).ready(function() {

});
</script>
@endpush
