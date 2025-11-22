<?php

namespace App\Http\Controllers;

use App\Models\invoice_attachments;
use Illuminate\Http\Request;

class InvoiceAttachmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $request->validate([
            'file_name' => 'mimes:pdf,png,jpg,jpeg',
        ],[
            'file_name.mimes' => 'يجب ان تكون الصيغة pdf,png,jpg,jpeg',
        ]);

        $file = $request->file('file_name');
        $file_name = $file->getClientOriginalName();
        $file->move(public_path('attachments/' . $request->invoice_number), $file_name);

        $invoice_attachment = new invoice_attachments();
        $invoice_attachment->invoice_number  = $request->invoice_number;
        $invoice_attachment->invoice_id = $request->invoice_id;
        $invoice_attachment->Created_by = auth()->user()->name;
        $invoice_attachment->file_name = $file_name;
        $invoice_attachment->save();
        session()->flash('success', 'File uploaded successfully');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\invoice_attachments  $invoice_attachments
     * @return \Illuminate\Http\Response
     */
    public function show(invoice_attachments $invoice_attachments)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\invoice_attachments  $invoice_attachments
     * @return \Illuminate\Http\Response
     */
    public function edit(invoice_attachments $invoice_attachments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\invoice_attachments  $invoice_attachments
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, invoice_attachments $invoice_attachments)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\invoice_attachments  $invoice_attachments
     * @return \Illuminate\Http\Response
     */
    public function destroy(invoice_attachments $invoice_attachments)
    {
        //
    }
}
