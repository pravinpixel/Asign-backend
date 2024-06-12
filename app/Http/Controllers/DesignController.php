<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DesignController extends Controller
{
    // Stock Overview
    public function stockOverviewList(Request $request)
    {

        return view('designs.stock_overview_list');
    }
    public function stockViewByID(Request $request)
    {

        return view('designs.stock_view');
    }
    public function stockViewUserList(Request $request)
    {

        return view('designs.stock_userlist_view');
    }
    public function stockUserRequest(Request $request)
    {

        return view('designs.stock_user_request');
    }

    // User Management
    public function userManagementList(Request $request)
    {

        return view('designs.user_management_list');
    }
    public function userManagementCreate(Request $request)
    {

        return view('designs.user_management_create');
    }

    // Role Mamangement
    public function roleManagementList(Request $request)
    {

        return view('designs.role_management_list');
    }
    public function roleManagementCreate(Request $request)
    {

        return view('designs.role_management_create');
    }

    // Purchase Orders
    public function purchaseOrderList(Request $request)
    {

        return view('designs.purchase_order_list');
    }
    public function purchaseOrderCreate(Request $request)
    {

        return view('designs.purchase_order_create');
    }
    public function purchaseOrderSummary(Request $request, $order_id)
    {

        return view('designs.purchase_order_summary');
    }

    // Stock Transfer Orders
    public function stockTransferOrderList(Request $request)
    {

        return view('designs.stock_transfer_order_list');
    }
    public function stockTransferOrderCreate(Request $request)
    {

        return view('designs.stock_transfer_order_create');
    }
    public function stockTransferOrderSummary(Request $request, $order_id)
    {

        return view('designs.stock_transfer_order_summary');
    }

    // GRN
    public function grnList(Request $request)
    {

        return view('designs.grn_list');
    }
    public function grnCreate(Request $request)
    {

        return view('designs.grn_create');
    }
    public function grnScan(Request $request, $grn_id)
    {

        return view('designs.grn_scan');
    }
    public function grnSummary(Request $request, $grn_id)
    {

        return view('designs.grn_summary');
    }

    // Label Request
    public function labelRequestList(Request $request)
    {

        return view('designs.label_request_list');
    }
    public function labelRequestCreate(Request $request)
    {

        return view('designs.label_request_new');
    }
    // Label Issue
    public function labelIssueList(Request $request)
    {

        return view('designs.label_issue_list');
    }
    public function labelIssueCreate(Request $request)
    {

        return view('designs.label_issue_new');
    }

    // Label Return
    public function labelReturnList(Request $request)
    {

        return view('designs.label_return_list');
    }
    public function labelReturnCreate(Request $request)
    {

        return view('designs.label_return_new');
    }
    public function labelReturnScan(Request $request, $return_id)
    {

        return view('designs.label_return_scan');
    }
    public function labelReturnSummary(Request $request, $return_id)
    {

        return view('designs.label_return_summary');
    }

    // Damaged Labels
    public function labelDamagedList(Request $request)
    {

        return view('designs.label_damaged_list');
    }
    public function labelDamagedCreate(Request $request)
    {

        return view('designs.label_damaged_create');
    }
    public function labelDamagedSummary(Request $request)
    {

        return view('designs.label_damaged_summary');
    }

    // Stock Check
    public function stockCheckList(Request $request)
    {

        return view('designs.stock_check_list');
    }
    public function stockCheckCreate(Request $request)
    {

        return view('designs.stock_check_create');
    }
    public function stockCheckSummary(Request $request)
    {

        return view('designs.stock_check_summary');
    }
    public function stockCheckScan(Request $request)
    {

        return view('designs.stock_check_scan');
    }
}
