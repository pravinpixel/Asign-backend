@extends('layouts.index')
@section('title', 'Stock Overview List')
@section('style')
@parent
<style type="text/css">
    .purchase-order-summary .m-header{
        padding: 24px 32px;
        display: flex;
        justify-content: flex-start;
        align-items: center;
        border-bottom: 1px solid rgba(29, 29, 29, 0.20); 
    }
    .purchase-order-summary .m-header > div{
        margin-left: auto;
    }
    .purchase-order-summary .form-content{
        padding: 24px 32px 0px;
    }
    .accordion_head span.arrow_indicator{
        position: relative;
    }
    .accordion_head span.arrow_indicator.down::before{
        position: absolute;
        content: url(https://uat-api.asign.art/admin/public/assets/icons/down.png);
    }
    .accordion_head span.arrow_indicator.up::before{
        position: absolute;
        content: url(https://uat-api.asign.art/admin/public/assets/icons/up.png);
    }

    .accordion_body{
        display:none;
    }
    .accordion_body > td{
        padding: 0px;
    }

    .accordion_body .purchase-order-table tbody tr td{
        background: #E8E8E8;
    }

</style>
@endsection

@section('content')
<section class="section main-header filterHeaderCtr">
	<div class="section-breadcrumb">
		<ul>
			<li>
				<img src="{{ asset('icons/box-1.svg') }}" width="20" height="20" />
                <a href="">Stock</a>
			</li>
			<li>
				<span id="toggle_sidebar">
					<img src="{{ asset('icons/arrange-square.svg') }}" width="20" />
				</span>
			</li> 
		</ul>
	</div>
	<div class="section-title">
		<h4>Stock Overview</h4>
	</div>
</section>
<div class="pages purchase-order-summary">
    <section class="table-content">        
        @include('components.tables.stock_list_table')
    </section>
</div>
@endsection
@push('scripts')
<script type="text/javascript">
$(document).ready(function () {
    let $accordion_tbl = $("#accordion_tbl");
    $accordion_tbl.find("tr").eq('.accordion_body').hide();

    $accordion_tbl.find(".accordion_head").click(function(){                
        $accordion_tbl.find("tr.accordion_head").removeClass("focus");        
        $accordion_tbl.find("tr.accordion_body").hide(); 
        if($(this).hasClass("un_focus")){
            $(this).find("span.arrow_indicator").removeClass("down").addClass("up"); 
            $(this).removeClass("un_focus").addClass("focus");
            $(this).next("tr.accordion_body").fadeIn("fast");
        }
        else{
            $(this).find("span.arrow_indicator").removeClass("up").addClass("down"); 
            $(this).removeClass("focus").addClass("un_focus");
            $(this).next("tr.accordion_body").fadeOut("fast");
        }
    });
});
</script>
@endpush
