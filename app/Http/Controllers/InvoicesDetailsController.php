<?php

namespace App\Http\Controllers;

use App\Models\invoice_attachments;
use App\Models\invoices;
use App\Models\invoices_details;
use Illuminate\Http\Request;

class InvoicesDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $invoices = invoices::where('id', $id)->first();
        $details = invoices_Details::where('id_Invoice', $id)->get();
        $attachments = invoice_attachments::where('invoice_id', $id)->get();

        return view('invoices.invoicesDetails', compact('invoices', 'details', 'attachments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\invoices_details  $invoices_details
     * @return \Illuminate\Http\Response
     */
    public function show(invoices_details $invoices_details)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\invoices_details  $invoices_details
     * @return \Illuminate\Http\Response
     */
    public function edit(invoices_details $invoices_details)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\invoices_details  $invoices_details
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, invoices_details $invoices_details)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\invoices_details  $invoices_details
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $invoice = invoice_attachments::findOrFail($request->id_file);
        $invoice->delete();
        unlink(public_path('attachments/' . $request->invoice_number . '/' . $request->file_name));
        session()->flash('success', 'File deleted successfully');
        return redirect()->back();
    }

    public function View_file($invoice_number, $file_name)
    {
        $file_path = public_path('attachments/' . $invoice_number . '/' . $file_name);
        if (file_exists($file_path)) {
            return response()->file($file_path);
        } else {
            abort(404);
        }
    }

    public function download($invoice_number, $file_name)
    {
        $file_path = public_path('attachments/' . $invoice_number . '/' . $file_name);
        if (file_exists($file_path)) {
            return response()->download($file_path);
        } else {
            abort(404);
        }
    }

}
