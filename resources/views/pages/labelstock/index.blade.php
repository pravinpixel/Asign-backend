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
          <img src="{{ asset('icons/label.svg') }}" width="20" height="20" />
          <a href="">Stock</a>
        </li>
        <li>
  <!-- <span id="toggle_sidebar">
  <img src="{{ asset('icons/arrange-square.svg') }}" width="20" />
</span> -->
</li> 
</ul>
</div>
<div class="section-title">
  <h4>Stock Overview</h4>
</div>
</section>
<div class="pages purchase-order-summary">
  <section class="table-content" >        
    <table class="asign-table purchase-order-table">
      <thead>
        <tr>
          <th scope="col" width="8%"></th>
          <th scope="col" width="30%">Stock Type</th>
          <th scope="col">Total Balance</th>
        </tr>
      </thead>
    </table>
    <table class="asign-table purchase-order-table" id="accordion_tbl">
      <tbody>
        @foreach($datas as $product)
        <tr class="accordion_head">
          <td width="8%">
            <span class="arrow_indicator down">&nbsp;</span>
          </td>
          <td width="30%">{{ $product->name }}</td>
          <td>
            @php
            $total = 0;
            @endphp
            @foreach($product->stock as $stock)
            @php
            $total += $stock->transit + $stock->agent + $stock->balance;
            @endphp
            @endforeach
            {{ $total }}
          </td>
        </tr>
        <tr class="accordion_body">
          <td colspan="3" class="p-0">
            <table class="asign-table purchase-order-table">
              <thead>
                <tr>
                  <th width="8%"></th>
                  <th width="30%">Location</th>
                  <th>Transit</th>
                  <th>Agent</th>
                  <th>Stock</th>
                </tr>
              </thead>
              <tbody>
                @if( isset($product) && count($product->stock)>0)
                @php
                $totalTransit = 0;
                $totalAgent = 0;
                $totalStock = 0;
            @endphp
                @foreach($product->stock as $stock)
                <tr onclick="window.location='{{ url('label-stock/view',$product->id)}}/@if(isset($stock->location['id'])){{$stock->location['id']}}@endif'">
                  <td></td>
                  <td>
                    {{ $stock->location['location'] ?? '-' }}
                  </td>
                  <td>{{ $stock->transit }}</td>
                  <td>{{ $stock->agent }}</td>
                  <td>{{ $stock->balance }}</td>
                </tr>
                @php
                $totalTransit += $stock->transit;
                $totalAgent += $stock->agent;
                $totalStock += $stock->balance;
            @endphp
                @endforeach
                <tr>
                  <td></td>
                  <td>Total</td>
                  <td>{{ $totalTransit }}</td>
                  <td>{{ $totalAgent }}</td>
                  <td>{{ $totalStock }}</td>
              </tr>
                @endif
              </tbody>
            </table>
          </td>            
        </tr>
        @endforeach
      </tbody>
    </table>
  </section>
</div>
@endsection
@push('scripts')
<script type="text/javascript">
$(document).ready(function () {
    let $accordion_tbl = $("#accordion_tbl");
    $accordion_tbl.find(".accordion_body").hide();

    $accordion_tbl.find(".accordion_head").click(function(){                
        let $accordion_head = $(this);
        let $accordion_body = $accordion_head.next(".accordion_body");
        let $arrow_indicator = $accordion_head.find("span.arrow_indicator");
        $accordion_tbl.find(".accordion_head").removeClass("focus");
        $accordion_head.addClass("focus");
        $arrow_indicator.toggleClass("down up");
        $accordion_body.slideToggle("fast");
        $accordion_tbl.find(".accordion_body").not($accordion_body).slideUp("fast");
        $accordion_tbl.find(".accordion_head").not($accordion_head).removeClass("focus");
        $accordion_tbl.find(".accordion_head").not($accordion_head).find("span.arrow_indicator").removeClass("up").addClass("down");
    });
});

</script>
@endpush