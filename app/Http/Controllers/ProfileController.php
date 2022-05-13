<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function attachProduct(Request $request) 
    {
        $input = $request->validate(['product_id' => 'required|exists:products,id']);
        $request->user()->products()->attach($input['product_id']);
    }

    public function detachProduct(Request $request) {
        $input = $request->validate(['product_id' => 'required|exists:products,id']);
        $request->user()->products()->detach($input['product_id']);
    }
}
