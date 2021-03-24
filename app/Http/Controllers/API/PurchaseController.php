<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Transaction;
use App\Models\Stock;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Purchase::with(['product', 'from_head', 'to_head'])->latest()->paginate(50);
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

        $purchase = new Purchase();
        $purchase->date = date('Y-m-d', strtotime($request->date));
        $purchase->product_id = $request->product_id;
        $purchase->quantity = $request->quantity;
        $purchase->rate = $request->rate;
        $purchase->amount = $request->amount;
        $purchase->from_head_id = $request->from_head_id;
        $purchase->from_subhead_id = $request->from_subhead_id;
        $purchase->to_head_id = $request->to_head_id;
        $purchase->to_subhead_id = $request->to_subhead_id;
        $purchase->description = $request->description;

        $purchase->save();


        Transaction::create([
            'date' => $purchase->date,
            'purchase_id' => $purchase->id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'rate' => $purchase->rate,
            'amount' => $request->amount,
            'from_head_id' => $request->from_head_id,
            'from_subhead_id' => $request->from_subhead_id,
            'to_head_id' => $request->to_head_id,
            'to_subhead_id' => $request->to_subhead_id,
        ]);

        Stock::create([
            'date' => $purchase->date,
            'purchase_id' => $purchase->id,
            'product_id' => $purchase->product_id,
            'quantity' => $purchase->quantity,
            'stock_type' => 'purchase',
        ]);

        return Purchase::with(['product', 'from_head', 'to_head'])->latest()->paginate(50);
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

        
        $purchase = Purchase::find($id);
        $purchase->date = date('Y-m-d', strtotime($request->date));
        $purchase->product_id = $request->product_id;
        $purchase->quantity = $request->quantity;
        $purchase->rate = $request->rate;
        $purchase->amount = $request->amount;
        $purchase->from_head_id = $request->from_head_id;
        $purchase->from_subhead_id = $request->from_subhead_id;
        $purchase->to_head_id = $request->to_head_id;
        $purchase->to_subhead_id = $request->to_subhead_id;
        $purchase->description = $request->description;

        $purchase->save();

        $transaction = Transaction::where('purchase_id', $id)->first();

        $transaction->update([
            'date' => $purchase->date,
            'product_id' => $purchase->product_id,
            'quantity' => $purchase->quantity,
            'rate' => $purchase->rate,
            'amount' => $purchase->amount,
            'from_head_id' => $purchase->from_head_id,
            'from_subhead_id' => $purchase->from_subhead_id,
            'to_head_id' => $purchase->to_head_id,
            'to_subhead_id' => $purchase->to_subhead_id,
        ]);

        $stock = Stock::where('purchase_id', $id)->first();

        $stock->update([
            'date' => $purchase->date,
            'product_id' => $purchase->product_id,
            'quantity' => $purchase->quantity,
            'stock_type' => 'purchase',
        ]);

        return Purchase::with(['product', 'from_head', 'to_head'])->latest()->paginate(50);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $transaction = Transaction::where('purchase_id', $id)->first();
        $transaction->delete();

        $stock = Stock::where('purchase_id', $id)->first();
        $stock->delete();

        Purchase::find($id)->delete();

        return Purchase::with(['product', 'from_head', 'to_head'])->latest()->paginate(50);
    }
}
