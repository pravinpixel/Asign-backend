<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use App\Models\PurchaseOrder;
use App\Models\GrnProductDetail;
use App\Models\GrnRelation;
use App\Models\PurchaseOrderProduct;
use App\Models\StockTransferOrderProduct;
use App\Models\StockTransferOrder;
use App\Models\Grn;
use Session;

class UnSavedGrns
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (Session::has('UN_SAVED_PO_GRN_ID')) {
            $grn_id = Session::get('UN_SAVED_PO_GRN_ID');
            $grn_query = Grn::where(["id" => $grn_id, "grn_no" => null])->first();
            if ($grn_query) {
                $grn_products = GrnProductDetail::where(['grn_id' => $grn_id])->get();
                if ($grn_products) {
                    $products = [];
                    foreach ($grn_products as $gp) {
                        if (!isset($products[$gp->product_id])) {
                            $products[$gp->product_id] = 0;
                        }
                        $products[$gp->product_id] += 1;
                        $gp->delete();
                    }
                    foreach ($products as $k => $v) {
                        $po_product = PurchaseOrderProduct::where(['purchase_order_id' => $grn_query->purchase_order_id, 'product_id' => $k])->first();
                        if ($po_product) {
                            $po_product->grn_quantity = $po_product->grn_quantity - $v;
                            $po_product->save();
                        }

                    }
                }
                $grn_query->delete();
            }
            Session::forget('UN_SAVED_PO_GRN_ID');
        }

        if (Session::has('UN_SAVED_STO_GRN_ID')) {
            $grn_id = Session::get('UN_SAVED_STO_GRN_ID');

            $grn_query = Grn::where(["id" => $grn_id, "grn_no" => null])->first();
            if ($grn_query) {

                $sto = StockTransferOrder::find($grn_query->purchase_order_id);


                $grn_products = GrnProductDetail::where(['grn_id' => $grn_id])->get();
                if ($grn_products) {
                    $products = [];
                    foreach ($grn_products as $gp) {
                        if (!isset($products[$gp->product_id])) {
                            $products[$gp->product_id] = 0;
                        }
                        $products[$gp->product_id] += 1;

                        // tranfered label with location
                        if ($sto) {
                            GrnProductDetail::where('scanned_product_id', $gp->scanned_product_id)
                                ->where('location_id', $sto->stock_source_id)
                                ->where('is_pack_scanned', 1)
                                ->where('status', 'transfered')
                                ->update(['status' => 'transit']);
                        }

                        $gp->delete();
                    }
                    foreach ($products as $k => $v) {
                        $sto_product = StockTransferOrderProduct::where(['stock_transfer_order_id' => $grn_query->purchase_order_id, 'product_id' => $k])->first();
                        if ($sto_product) {
                            $sto_product->grn_quantity = $sto_product->grn_quantity - $v;
                            $sto_product->save();
                        }
                    }
                }
                $grn_query->delete();

            }

            Session::forget('UN_SAVED_STO_GRN_ID');
        }


        return $next($request);
    }
}
