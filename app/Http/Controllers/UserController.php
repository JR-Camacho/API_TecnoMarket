<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use \stdClass;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function user()
    {
        return auth()->user();
    }
   
    public function update_user(Request $request)
    {
        $user = User::findOrFail($request->id);
        $request->validate(['name' => 'required|max:30', 'surnames' => 'max:40', 'password' => 'required|min:8', 'description' => 'max:255', 'profile_url' => 'image|max:2000']);
        $data = $request->all();
        if($photo = $request->file('profile_url')){
           
            if($user->profile_url != ''){
                $id_image = $user->id_image;
                Cloudinary::destroy($id_image);
            }

            $obj = Cloudinary::upload($photo->getRealPath(), ['folder' => 'users']);
            $id_image = $obj->getPublicId();
            $url = $obj->getSecurePath();

            $data['profile_url'] = $url;
            $data['id_image'] = $id_image;
        }
        $data['password'] = Hash::make($request->password);

        return $user->update($data);
    }

    public function delete_user($id)
    {
        $user = User::findOrFail($id);
        if($user->profile_url != ''){
            $id_image = $user->id_image;
            Cloudinary::destroy($id_image);
        } 
        Product::where('user_id', $id)->delete();
        return $user->delete();
    }

    public function show($id){
        return User::findOrFail($id);
    }
}
