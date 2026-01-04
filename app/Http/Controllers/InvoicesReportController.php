<?php

namespace App\Http\Controllers;

use App\Models\invoices;
use Illuminate\Http\Request;

class InvoicesReportController extends Controller
{
    public function index()
    {
        return view('reports.invoices_reports');
    }

    public function search_invoices(Request $request)
    {
        $radio = $request->radio;
        if($radio == 1) 
        {
            if($request->type && $request->start_at == '' && $request->end_at == '') {
                $invoices = invoices::select('*')->where('status', $request->type)->get();
                $type = $request->type;
                return view('reports.invoices_reports', compact('type'))->withDetails($invoices);
            }   
            else
            {
                $start_at = date('Y-m-d', strtotime($request->start_at));
                $end_at = date('Y-m-d', strtotime($request->end_at));
                $type = $request->type;
                $invoices = invoices::whereBetween('invoice_date', [$start_at, $end_at])->where('status', $type)->get();
                return view('reports.invoices_reports', compact('type', 'start_at', 'end_at'))->withDetails($invoices);
            }
        } 
        else 
        {
            // Search by invoice number
            $invoice_number = $request->invoice_number;
            $invoices = invoices::where('invoice_number', $invoice_number)->get();
            return view('reports.invoices_reports', compact('invoice_number'))->withDetails($invoices);
        }
    }
}
