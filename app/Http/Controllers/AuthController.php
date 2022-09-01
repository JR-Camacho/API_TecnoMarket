<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use \stdClass;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = $request->validate([
            'name' => 'required | string | max:255',
            'email' => 'required | string | email | max:255 | unique:users',
            'password' => 'required | string | min:8'
        ]);

        if (!$validator) {
            return response()->json(['message' => 'No se completo la validacion']);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['data' => $user, 'access_token' => $token, 'token_type' => 'Bearer']);
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();
        $token = $user->CreateToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => "Hi $user->name",
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    public function logout()
    {
        /** @var \App\Models\User $user **/
        auth()->user()->tokens()->delete();

        return [
            'message' => 'logged out'
        ];
    }

    // public function user()
    // {
    //     return auth()->user();
    // }
   
    // public function update_user(Request $request, $id)
    // {
    //     $user = User::findOrFail($id);
    //     $request->validate(['name' => 'required|max:30', 'surnames' => 'max:40', 'password' => 'required|min:8', 'description' => 'max:255']);
    //     $data = $request->all();
    //     if($photo = $request->file('profile_url')){
    //         $photo_name = time() . '-' . $photo->getClientOriginalName();
    //         $photo->move('images/users', $photo_name);
    //         $data['profile_url'] = $photo_name;
    //     }
    //     $data['name'] = $request->name;
    //     $data['password'] = Hash::make($request->password);
    //     return $user->update($data);
    // }

    // public function delete_user($id)
    // {
    //     $user = User::findOrFail($id)->delete();
    //     if($user->profile_url != ''){
    //         unlink('images/users/'.$user->profile_url);
    //         Product::where('user_id', $id)->delete();
    //         return $user->delete();
    //     } else{
    //         Product::where('user_id', $id)->delete();
    //         return $user->delete();
    //     }
    // }
}
