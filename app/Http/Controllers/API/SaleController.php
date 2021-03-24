<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Transaction;
use App\Models\Stock;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Sale::with(['product', 'from_head', 'to_head'])->latest()->paginate(20);
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
            'date' => 'required',
            'product_id' => 'required',
            'quantity' => 'required',
            'rate' => 'required',
            'amount' => 'required',
            'from_head_id' => 'required',
            'to_head_id' => 'required'
        ]);

        $sale = new Sale();
        $sale->date = date('Y-m-d', strtotime($request->date));
        $sale->product_id = $request->product_id;
        $sale->quantity = $request->quantity;
        $sale->rate = $request->rate;
        $sale->amount = $request->amount;
        $sale->from_head_id = $request->from_head_id;
        $sale->from_subhead_id = $request->from_subhead_id;
        $sale->to_head_id = $request->to_head_id;
        $sale->to_subhead_id = $request->to_subhead_id;
        $sale->description = $request->description;

        $sale->save();


        Transaction::create([
            'date' => $sale->date,
            'sale_id' => $sale->id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'rate' => $request->rate,
            'amount' => $request->amount,
            'from_head_id' => $request->from_head_id,
            'from_subhead_id' => $request->from_subhead_id,
            'to_head_id' => $request->to_head_id,
            'to_subhead_id' => $request->to_subhead_id,
        ]);

        Stock::create([
            'date' => $sale->date,
            'sale_id' => $sale->id,
            'product_id' => $sale->product_id,
            'quantity' => $sale->quantity,
            'stock_type' => 'sale',
        ]);

        return Sale::with(['product', 'from_head', 'to_head'])->latest()->paginate(20);
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
            'date' => 'required',
            'product_id' => 'required',
            'quantity' => 'required',
            'rate' => 'required',
            'amount' => 'required',
            'from_head_id' => 'required',
            'to_head_id' => 'required'
        ]);

        
        $sale = Sale::find($id);
        $sale->date = date('Y-m-d', strtotime($request->date));
        $sale->product_id = $request->product_id;
        $sale->quantity = $request->quantity;
        $sale->rate = $request->rate;
        $sale->amount = $request->amount;
        $sale->from_head_id = $request->from_head_id;
        $sale->from_subhead_id = $request->from_subhead_id;
        $sale->to_head_id = $request->to_head_id;
        $sale->to_subhead_id = $request->to_subhead_id;
        $sale->description = $request->description;

        $sale->save();

        $transaction = Transaction::where('sale_id', $id)->first();

        $transaction->update([
            'date' => $sale->date,
            'product_id' => $sale->product_id,
            'quantity' => $sale->quantity,
            'rate' => $sale->rate,
            'amount' => $sale->amount,
            'from_head_id' => $sale->from_head_id,
            'from_subhead_id' => $sale->from_subhead_id,
            'to_head_id' => $sale->to_head_id,
            'to_subhead_id' => $sale->to_subhead_id,
        ]);

        $stock = Stock::where('sale_id', $id)->first();

        $stock->update([
            'date' => $sale->date,
            'product_id' => $sale->product_id,
            'quantity' => $sale->quantity,
            'stock_type' => 'sale',
        ]);

        return Sale::with(['product', 'from_head', 'to_head'])->latest()->paginate(20);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $transaction = Transaction::where('sale_id', $id)->first();
        $transaction->delete();

        $stock = Stock::where('sale_id', $id)->first();
        $stock->delete();

        Sale::find($id)->delete();

        return Sale::with(['product', 'from_head', 'to_head'])->latest()->paginate(20);
    }
}
