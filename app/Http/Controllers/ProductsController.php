<?php

namespace App\Http\Controllers;

use App\Models\products;
use App\Models\sections;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = products::all();
        $sections = sections::all();
        return view('products.products', compact('products', 'sections'));
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
        $validated = $request->validate([
            'product_name' => 'required|string|max:999|min:1',
            'description' => 'nullable|string',
            'section_id' => 'required|exists:sections,id',
        ], [
            'product_name.required' => 'Please enter the product name.',
            'section_id.required' => 'Please select a section.',
            'section_id.exists' => 'The selected section is invalid.',
        ]);

        if ($validated) {
            products::create([
                'product_name' => $request->product_name,
                'description' => $request->description,
                'section_id' => $request->section_id,
            ]);
            session()->flash('Add', 'Product added successfully.');
            return redirect('/products');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\products  $products
     * @return \Illuminate\Http\Response
     */
    public function show(products $products)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\products  $products
     * @return \Illuminate\Http\Response
     */
    public function edit(products $products)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\products  $products
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, products $products, $id)
    {
        $id = $request->pro_id;
        $this->validate($request, [
            'Product_name' => 'required|string|max:999|min:1',
            'section_id' => 'exists:sections,id',
            'description' => 'nullable|string',
        ], [
            'product_name.required' => 'Please enter the product name.',
            'section_id.exists' => 'The selected section is invalid.',
        ]);
        $s_id = sections::where('section_name', $request->section_name)->first()->id;
        $products = products::find($id);
        $products->update([
            'product_name' => $request->Product_name,
            'section_id' => $s_id,
            'description' => $request->description,
        ]);
        session()->flash('edit', 'Product updated successfully.');
        return redirect('/products');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\products  $products
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->pro_id;
        // dd($id);
        products::find($id)->delete();
        session()->flash('delete', 'Product deleted successfully.');
        return redirect('/products');
    }
}
