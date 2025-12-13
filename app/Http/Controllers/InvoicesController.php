<?php

namespace App\Http\Controllers;

use App\Models\invoice_attachments;
use App\Models\invoices;
use App\Models\invoices_details;
use App\Models\products;
use App\Models\sections;
use App\Models\User;
use Auth;
use File;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class InvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = invoices::all();
        return view('invoices.invoices', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = products::all();
        $sections = sections::all();
        return view('invoices.add_invoices', compact('sections', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Insert invoice
        invoices::create([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'discount' => $request->Discount,
            'value_vat' => $request->Value_VAT,
            'rate_vat' => $request->Rate_VAT,
            'total' => $request->Total,
            'status' => 'غير مدفوعة',
            'value_Status' => 2,
            'note' => $request->note,
            'user' => (auth()->user()->name),
        ]);

        // Insert invoice details
        $invoice_id = invoices::latest()->first()->id;
        invoices_details::create([
            'id_Invoice' => $invoice_id,
            'invoice_number' => $request->invoice_number,
            'product' => $request->product,
            'section' => $request->Section,
            'status' => 'غير مدفوعة',
            'value_status' => 2,
            'note' => $request->note,
            'user' => (Auth::user()->name),
        ]);


        if ($request->hasFile('pic')) {

            $invoice_id = Invoices::latest()->first()->id;
            $image = $request->file('pic');
            $file_name = $image->getClientOriginalName();
            $invoice_number = $request->invoice_number;

            $attachments = new invoice_attachments();
            $attachments->file_name = $file_name;
            $attachments->invoice_number = $invoice_number;
            $attachments->Created_by = Auth::user()->name;
            $attachments->invoice_id = $invoice_id;
            $attachments->save();

            // move pic
            $imageName = $request->pic->getClientOriginalName();
            $request->pic->move(public_path('Attachments/' . $invoice_number), $imageName);
        }

        session()->flash('Add', 'تم اضافة الفاتورة بنجاح');
        return redirect()->route('invoices.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $invoices = invoices::where('id', $id)->first();
        return view('invoices.status_show', compact('invoices'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $invoices = invoices::where('id', $id)->first();
        $sections = sections::all();
        return view('invoices.edit_invoices', compact('invoices', 'sections'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $invoices = invoices::findOrFail($request->invoice_id);
        $invoices->update($request->all());
        session()->flash('edit', 'تم تعديل الفاتورة بنجاح');
        return redirect()->route('invoices.index');
    }


    public function statusUpdate(Request $request, $id)
    {
        $invoices = invoices::findOrFail($id);
        if($request->status == 'مدفوعة'){
            $invoices->update(['status' => $request->status, 'value_Status' => 1, 'payment_date' => $request->payment_date]);
            invoices_details::create([
            'id_Invoice' => $request->id,
            'invoice_number' => $request->invoice_number,
            'product' => $request->product,
            'section' => $request->Section,
            'status' => $request->status,
            'value_status' => 1,
            'payment_date' => $request->Payment_Date,
            'note' => $request->note,
            'user' => (Auth::user()->name),
            ]);
        }
        elseif($request->status == 'مدفوعة جزئيا')
        {
            $invoices->update(['status' => $request->status, 'value_Status' => 3, 'payment_date' => $request->payment_date]);
            invoices_details::create([
            'id_Invoice' => $request->id,
            'invoice_number' => $request->invoice_number,
            'product' => $request->product,
            'section' => $request->Section,
            'status' => $request->status,
            'value_status' => 3,
            'payment_date' => $request->Payment_Date,
            'note' => $request->note,
            'user' => (Auth::user()->name),
            ]);
        }
        session()->flash('edit', 'تم تعديل الفاتورة بنجاح');
        return redirect()->route('invoices.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->invoice_id;
        $invoices = invoices::where('id', $id)->first();
        $details = invoice_attachments::where('invoice_id', $id)->first();
        if(!empty($details->invoice_number)){
        File::deleteDirectory(public_path('Attachments/' . $details->invoice_number));
        }
        $invoices->forceDelete();
        session()->flash('delete_invoice', 'تم حذف الفاتورة بنجاح');
        return redirect()->route('invoices.index');
    }

    public function archive(Request $request)
    {
        $id = $request->invoice_id;
        $invoices = invoices::where('id', $id)->first();
        $invoices->delete();
        session()->flash('archive', 'تم ارشفة الفاتورة بنجاح');
        return redirect()->route('invoices.index');
    }


    public function getproducts($id)
    {
        $products = products::where('section_id', $id)->pluck('product_name', 'id');
        return json_encode($products);
    }

    public function PaidInvoices()
    {
        $invoices = invoices::where('status', 'مدفوعة')->get();
        return view('invoices.invoices_paid', compact('invoices'));
    }

    public function UnpaidInvoices()
    {
        $invoices = invoices::where('status', 'غير مدفوعة')->get();
        return view('invoices.invoices_unpaid', compact('invoices'));
    }
    public function PartialInvoices()
    {
        $invoices = invoices::where('status', 'مدفوعة جزئيا')->get();
        return view('invoices.invoices_partial', compact('invoices'));
    }
}
