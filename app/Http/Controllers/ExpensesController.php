<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

class ExpensesController extends Controller
{
    function get_expenses()
    {
        $fetch_expenses = DB::table( 'expenses' )->get();

        if( isset($fetch_expenses) )
        {
            return response()->json([
            'status'=>'success',
            'data'=>$fetch_expenses
            ]);
        }
    }

    function get_single_expense( $id )
    {
        $fetch_expense = DB::table( 'expenses' )->where('expense_id', $id)->first();

        if( isset($fetch_expense) )
        {
            return response()->json([
                'status'=>'success',
                'data'=>$fetch_expense
            ]);
        }
    }

    function add_expenses(Request $request)
    {
        $request->validate([
            'name'=>'required', 
            'amount'=>'required',
            'time_period'=>'required'
        ]);

        $data = array();
        $data['name'] = $request->name;
        $data['amount'] = $request->amount;
        $data['time_period'] = $request->time_period;
        $data['created_at'] = Carbon::now();
        $data['updated_at'] = Carbon::now();

        $insert_expenses = DB::table( 'expenses' )->insert( $data );

        if( isset($insert_expenses) )
        {
            return response()->json([
            'status'=>'success',
            'data'=>'Added Successfully'
            ]);
        }
    }

    // function edit_expense(Request $request, $id)
    // {
    //     $request->validate([
    //         'name'=>'required', 
    //         'amount'=>'required',
    //         'time_period'=>'required'
    //     ]);

    //     $data = array();
    //     $data['name'] = $request->name;
    //     $data['amount'] = $request->amount;
    //     $data['date'] = $request->date;
    //     $data['updated_at'] = Carbon::now();

    //     $edit_expense = DB::table( 'expenses' )->where('expense_id', $id)->update( $data );

    //     if( isset($edit_expense) )
    //     {
    //         return response()->json([
    //         'status'=>'success',
    //         'data'=>'Edited Successfully'
    //         ]);
    //     }
    // }

    function delete_expense($id)
    {
        $delete_expense = DB::table( 'expenses' )->where('expense_id', $id)->delete();

        if( isset($delete_expense) )
        {
            return response()->json([
            'status'=>'success',
            'data'=>'Deleted Successfully'
            ]);
        }
    }
}
