<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::latest()->paginate(5);

        return new ProductResource(true , 'List Data Proudct' , $products);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'product_name' => 'required',
            'product_description' => 'required',
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $image = $request->file('product_image');
        $image->storeAs('public/products', $image->hashName());

        $product = Product::create([
            'product_image' => $image->hashName(),
            'product_name' => $request->product_name,
            'product_price' => $request->product_price,
            'product_description' => $request->product_description,
            'product_category' => $request->product_category
        ]);

        return new ProductResource(true, 'Product Data Successfull be created', $product);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::find($id);

        return new ProductResource(true , 'Detail Data Product', $product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'product_name' => 'required',
            'product_price' => 'required',
            'product_category' => 'required',
            'product_description' => 'required',
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $product = Product::find($id);

        if($request->hasFile('image')){
            $image = $request->file('product_image');
            $image->storeAs('public/products', $image->hashName());

            Storage::delete('public/products'.basename($product->image));

            $product->update([
                'product_image' => $image->hashName(),
                'product_name' =>  $request->product_name,
                'product_price' => $request->product_price,
                'product_description' => $request->product_description,
                'product_category' => $request->product_category
            ]);

        } else {
            $product->update([
                'product_name' => $request->product_name , 
                'product_price' => $request->product_price,
                'product_category' => $request->product_category,
                'product_description' => $request->product_description
            ]);

            return new ProductResource(true , 'Product succesfull update', $product);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
