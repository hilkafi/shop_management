<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Stock;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Product::with('category')->latest()->paginate(3);
    }

    public function load_product() {
        return Product::all();
    }

    public function get_purchase_rate($id) {
        return Product::find($id)->purchase_price;
    }

    public function get_sale_rate($id) {
        return Product::find($id)->sale_price;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'category_id' => 'required',
            'name' => 'required'
        ]);

        Product::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'unit' => $request->unit,
            'purchase_price' => $request->purchase_price,
            'sale_price' => $request->sale_price,
            'description' => $request->description
        ]);

        return Product::with('category')->latest()->paginate(3);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'category_id' => 'required',
            'name' => 'required'
        ]);

        $product = Product::find($id);
        
        $product->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'unit' => $request->unit,
            'purchase_price' => $request->purchase_price,
            'sale_price' => $request->sale_price,
            'description' => $request->description
        ]);

        return Product::with('category')->latest()->paginate(3);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function stock() {
        return Product::with('category')->get();
    }

    public function stock_search(Request $request) {
        $query = Product::query();
        if(!empty($request->product_id)){
            $query->where('id', $request->product_id);
        }

        return $query->with('category')->get();
    }


    public function destroy($id)
    {
        $product = Product::find($id);
        $product->delete();
        return Product::with('category')->latest()->paginate(3);
    }
}
