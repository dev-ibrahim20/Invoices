<?php

namespace App\Http\Controllers;

use App\Models\invoices;
use App\Models\sections;
use Illuminate\Http\Request;

class CustomersReportController extends Controller
{
    public function index()
    {
        $sections = sections::all();
        return view('reports.customers_report', compact('sections'));
    }

    public function search_customers(Request $request)
    {
        // Handle the search logic here
        $sectionId = $request->Section;
        $productId = $request->product;
        $startDate = date($request->start_at);
        $endDate = date($request->end_at);
        
        if($sectionId && $productId && $request->start_at == '' && $request->end_at == '')
        {
            $invoices = invoices::select('*')
                ->where('section_id', $sectionId)
                ->where('product', $productId)
                ->get();
            $sections = sections::all();
            // Return the results (you'll need to implement the view logic)
            return view('reports.customers_report', compact('sections'))->withDetails($invoices);
        }
        else
        {
            $invoices = invoices::whereBetween('created_at', [$startDate, $endDate])
                ->where('section_id', $sectionId)
                ->where('product', $productId)
                ->get();

            $sections = sections::all();
            return view('reports.customers_report', compact('sections'))->withDetails($invoices);
        }
    }
}