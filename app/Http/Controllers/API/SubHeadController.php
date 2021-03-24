<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubHead;

class SubHeadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return SubHead::with('head')->latest()->paginate(10);
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
            'head_id' => 'required',
            'name' => 'required'
        ]);

        SubHead::create([
            'head_id' => $request->head_id,
            'name' => $request->name,
            'description' => $request->description
        ]);

        return SubHead::with('head')->latest()->paginate(10);
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
            'name' => 'required'
        ]);

        $sub_head = SubHead::find($id);

        $sub_head->update([
            'head_id' => $request->head_id,
            'name' => $request->name,
            'description' => $request->description
        ]);

        return SubHead::with('head')->latest()->paginate(10);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        SubHead::find($id)->delete();
        return SubHead::with('head')->latest()->paginate(10);
    }
}
