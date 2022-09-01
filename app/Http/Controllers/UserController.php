<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
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
        $request->validate(['name' => 'required|max:30', 'surnames' => 'max:40', 'password' => 'required|min:8', 'description' => 'max:255']);
        $data = $request->all();
        if($photo = $request->file('profile_url')){
            $photo_name = time() . '-' . $photo->getClientOriginalName();
            $photo->move('images/users', $photo_name);
            $data['profile_url'] = $photo_name;

            if($user->profile_url != ''){
                unlink('images/users/'.$user->profile_url);
            }
        }
        $data['password'] = Hash::make($request->password);

        return $user->update($data);
    }

    public function delete_user($id)
    {
        $user = User::findOrFail($id)->delete();
        if($user->profile_url != ''){
            unlink('images/users/'.$user->profile_url);
            Product::where('user_id', $id)->delete();
            return $user->delete();
        } else{
            Product::where('user_id', $id)->delete();
            return $user->delete();
        }
    }
}
