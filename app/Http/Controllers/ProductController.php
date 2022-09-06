<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Product::search($request->buscar);
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
        $request->validate(['name' => 'required|max:65', 'description' => 'required|max:255', 'front_url' => 'image|max:1000', 'price' => 'required']);
        $data = $request->all();
        if($photo = $request->file('front_url')){

            $obj = Cloudinary::upload($photo->getRealPath(), ['folder' => 'products']);
            $url = $obj->getSecurePath();
            $id_image = $obj->getPublicId();

            $data['front_url'] = $url;
            $data['id_image'] = $id_image;
        }
        return Product::create($data); 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Product::findOrFail($id);
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
        $request->validate(['name' => 'required|max:65', 'description' => 'required|max:255', 'front_url' => 'image|max:1000', 'price' => 'required']);
        $data = $request->all();
        if($photo = $request->file('front_url')){

            if($product->front_url != ''){
                $id_image = $product->id_image;
                Cloudinary::destroy($id_image);
            }

            $obj = Cloudinary::upload($photo->getRealPath(), ['folder' => 'products']);
            $id_image = $obj->getPublicId();
            $url = $obj->getSecurePath();

            $data['front_url'] = $url;
            $data['id_image'] = $id_image;
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
            $id_image = $product->id_image;
            Cloudinary::destroy($id_image);
        } 
        return $product->delete();
    }
}
