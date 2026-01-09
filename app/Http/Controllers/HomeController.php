<?php

namespace App\Http\Controllers;

use App\Models\invoices;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {


        $paidInvoices = invoices::where('value_status', 1)->count();
        $partiallyPaidInvoices = invoices::where('value_status', 3)->count();
        $unpaidInvoices = invoices::where('value_status', 2)->count();
        $totalInvoices = invoices::count();
        
        $chartjs2 = app()->chartjs
        ->name('pieChartTest')
        ->type('pie')
        ->size(['width' => 400, 'height' => 200])
        ->labels(['الفواتير غير المدفوعة', 'الفواتير المدفوعة جزئيا', 'الفواتير المدفوعة'])
        ->datasets([
            [
                'backgroundColor' => ['#FF6384', '#36A2EB', 'green'],
                'hoverBackgroundColor' => ['#FF6384', '#36A2EB', 'green'],
                'data' => [($unpaidInvoices / $totalInvoices) * 100, ($partiallyPaidInvoices / $totalInvoices) * 100, ($paidInvoices / $totalInvoices) * 100]
            ]
        ])
        ->options([]);

        $chartjs = app()->chartjs
                ->name('barChartTest')
                ->type('bar')
                ->size(['width' => 400, 'height' => 200])
                ->labels(['2024', '2025', '2026'])
                ->datasets([
                    [
                        "label" => "الفواتير المدفوعة جزئيا",
                        'backgroundColor' => ['yellow', 'yellow', 'yellow'],
                        'data' => [invoices::whereYear('invoice_Date', '2024')->where('value_status', 3)->sum('total'), invoices::whereYear('invoice_Date', '2025')->where('value_status', 3)->sum('total'), invoices::whereYear('invoice_Date', '2026')->where('value_status', 3)->sum('total')]
                    ],
                    [
                        "label" => "الفواتير المدفوعة",
                        'backgroundColor' => ['green', 'green', 'green'],
                        'data' => [invoices::whereYear('invoice_Date', '2024')->where('value_status', 1)->sum('total'), invoices::whereYear('invoice_Date', '2025')->where('value_status', 1)->sum('total'), invoices::whereYear('invoice_Date', '2026')->where('value_status', 1)->sum('total')]
                    ],
                    [
                        "label" => "الفواتير غير المدفوعة",
                        'backgroundColor' => ['red', 'red', 'red'],
                        'data' => [invoices::whereYear('invoice_Date', '2024')->where('value_status', 2)->sum('total'), invoices::whereYear('invoice_Date', '2025')->where('value_status', 2)->sum('total'), invoices::whereYear('invoice_Date', '2026')->where('value_status', 2)->sum('total')]
                    ]
                ])
                ->options([]);

        return view('dashboard', compact('chartjs', 'chartjs2'));
    }
}
