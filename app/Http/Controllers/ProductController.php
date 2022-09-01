<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Product::all();
    }

    public function myproducts($id){
        return User::findOrFail($id)->product;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|max:65', 'description' => 'max:255', 'front_url' => 'image']);
        $data = $request->all();
        if($photo = $request->file('front_url')){
            $photo_name = time() . '_'  . $photo->getClientOriginalName();
            $photo->move('images/products', $photo_name);
            $data['front_url'] = $photo_name;
        }
        return Product::create($data); 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return Product::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $product= Product::findOrFail($request->id);
        $request->validate(['name' => 'required|max:65', 'description' => 'max:255', 'front_url' => 'image']);
        $data = $request->all();
        if($photo = $request->file('front_url')){
            $photo_name = time() . '_'  . $photo->getClientOriginalName();
            $photo->move('images/products', $photo_name);
            $data['front_url'] = $photo_name;

            if($product->front_url != ''){
                unlink('images/products/'.$product->front_url);
            }
        }
        return $product->update($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if($product->front_url != ''){
            unlink('images/products/'.$product->front_url);
            return $product->delete();
        } else{
            return $product->delete();
        }
    }
}
