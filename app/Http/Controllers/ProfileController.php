<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function attachProduct(Request $request) 
    {
        $input = $request->validate(['product_id' => 'required|exists:products,id']);
        $request->user()->products()->syncWithoutDetaching($input['product_id']);
        return response()->json(['message' => 'Product attached to your account.']);
    }

    public function detachProduct(Request $request) {
        $input = $request->validate(['product_id' => 'required|exists:products,id']);
        $request->user()->products()->detach($input['product_id']);
        return response()->json(['message' => 'Product detached from your account.']);
    }

    public function products(Request $request) {
        return response()->json(['products' => $request->user()->products()->paginate()]);
    }
}
