<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

class taxController extends Controller
{
    function get_tax()
    {
        $fetch_tax = DB::table( 'tax' )->get();

        if( isset($fetch_tax) )
        {
            return response()->json([
            'status'=>'success',
            'data'=>$fetch_tax
            ]);
        }
    }

    function get_single_tax( $id )
    {
        $fetch_tax = DB::table( 'tax' )->where('tax_id', $id)->first();

        if( isset($fetch_tax) )
        {
            return response()->json([
                'status'=>'success',
                'data'=>$fetch_tax
            ]);
        }
    }

    function add_tax(Request $request)
    {
        $request->validate([
            'state'=>'required',
            'cgst'=>'required',
            'sgst'=>'required',
        ]);

        $data = array();
        $data['state'] = $request->state;
        $data['cgst'] = $request->cgst;
        $data['sgst'] = $request->sgst;
        $data['total'] = (int)$request->cgst + (int)$request->sgst;
        $data['created_at'] = Carbon::now();
        $data['updated_at'] = Carbon::now();

        $insert_tax = DB::table( 'tax' )->insert($data);

        if( isset( $insert_tax ) )
        {
            return response()->json([
                'status'=>'success',
                'data'=>'Added Successfully'
            ]);
        }
    }

    function edit_tax(Request $request, $id)
    {
        $request->validate([
            'state'=>'required',
            'cgst'=>'required',
            'sgst'=>'required',
        ]);

     

        $data = array();
        $data['state'] = $request->state;
        $data['cgst'] = $request->cgst;
        $data['sgst'] = $request->sgst;
        $data['total'] = (int)$request->cgst + (int)$request->sgst;
        $data['created_at'] = Carbon::now();
        $data['updated_at'] = Carbon::now();

        $edit_tax = DB::table( 'tax' )->where('tax_id', $id)->update( $data );

        if( isset($edit_tax) )
        {
            return response()->json([
            'status'=>'success',
            'data'=>'Edited Successfully'
            ]);
        }
    }

    function delete_tax($id)
    {
        $delete_tax = DB::table( 'tax' )->where('tax_id', $id)->delete();

        if( isset($delete_tax) )
        {
            return response()->json([
            'status'=>'success',
            'data'=>'Deleted Successfully'
            ]);
        }
    }
}
