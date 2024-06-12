<div class="pages purchase-order-summary">
    <section class="m-header">
        <main class="hstack gap-3">
            <a href="#" class="backHeader">
                <img src="{{ asset('icons/arrow-left-alt.svg') }}" width="24" height="24" class="cP"/>
            </a>
            <h4>{{$request_id}}</h4>
        </main>
        <div>
            <button type="button" class="btn cancel-btn" id="resetBtn">
                Reset
            </button>
        </div>
    </section>
    <section class="section-inner">
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="headerBorderBox">
                    <div class="vstack gap-2">
                        <div class="headerBorderBox-head">Product Name</div>
                        <input type="hidden" id="scanProductId">
                        <div class="headerBorderBox-sub" id="scanProduct"></div>
                    </div>
                </div>
            </div>

            @if(isset($is_returned) && $is_returned)
                <div class="col-md-4">
                    <div class="headerBorderBox">
                        <div class="vstack gap-2">
                            <div class="headerBorderBox-head">Balance</div>
                            <div class="headerBorderBox-sub" id="scanBalanceQty">0</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="headerBorderBox">
                        <div class="vstack gap-2">
                            <div class="headerBorderBox-head">Returned</div>
                            <div class="headerBorderBox-sub" id="scanReturnedQty">0</div>
                        </div>
                    </div>
                </div>

            @elseif(isset($is_adjust) && $is_adjust)
                <div class="col-md-4">
                    <div class="headerBorderBox">
                        <div class="vstack gap-2">
                            <div class="headerBorderBox-head">Expected Stock</div>
                            <div class="headerBorderBox-sub" id="scanExpectedQty">0</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="headerBorderBox">
                        <div class="vstack gap-2">
                            <div class="headerBorderBox-head">Actual Stock</div>
                            <div class="headerBorderBox-sub" id="scanActualQty">0</div>
                        </div>
                    </div>
                </div>

            @else
                <div class="col-md-4">
                    <div class="headerBorderBox">
                        <div class="vstack gap-2">
                            <div class="headerBorderBox-head">Request Quantity</div>
                            <div class="headerBorderBox-sub" id="scanRequestQty">0</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="headerBorderBox">
                        <div class="vstack gap-2">
                            <div class="headerBorderBox-head">Issued Quantity</div>
                            <div class="headerBorderBox-sub" id="scanIssuedQty">0</div>
                        </div>
                    </div>
                </div>

            @endif


        </div>
        <div class="row">
            <div class="col col-md-4">
                <label for="request_id" class="form-label">Bar Code / Qr Code</label>
                <input type="text" class="form-control form-control-lg" name="scan_item">
            </div>
        </div>
    </section>

    <section class="table-content">
        <table class="asign-table purchase-order-table">
            <thead>
            <tr>
                <th scope="col" width="30%">PRODUCT ID</th>
                <th scope="col" width="35%">CATEGORY</th>
                <th scope="col" width="35%">PRODUCT QUANTITY</th>
            </tr>
            </thead>
            <tbody id="scanTbody">
            {{--            <tr>--}}
            {{--                <td colspan="3" class="txt-center empty-msg">--}}
            {{--                    Start Scanning Inventory QR Codes--}}
            {{--                </td>--}}
            {{--            </tr>--}}

            </tbody>
        </table>
    </section>

</div>
