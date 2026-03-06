<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
   public function save(Request $request){
    $data=$request->validate(([
        'product_name' => 'required|string',
        'quantity' => 'required|integer',
        'price' => 'required|numeric',
    ]));
    //   save in database
      $product = Product::create($data);
    //   update data
      $allProducts=Product::all();
Storage::disk('public')->put( 'products.json', json_encode($allProducts, JSON_PRETTY_PRINT));
        return response()->json([
        'success' => true,
        'product' => $product,
        'total_value' => $product->quantity * $product->price,
        'formatted_date' => $product->created_at->format('Y-m-d H:i:s')
    ]);
   }
}
