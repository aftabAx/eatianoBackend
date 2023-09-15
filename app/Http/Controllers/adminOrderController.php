<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class adminOrderController extends Controller
{
    public function all_order(){
        if(auth()->user()->role == 'admin'){

        }
    }

    


}
