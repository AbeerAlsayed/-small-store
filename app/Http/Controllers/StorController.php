<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StorController extends Controller
{
    public function test(Request $request){
        $file=$request->file('photo');
        return response()->json($file);
    }
}
