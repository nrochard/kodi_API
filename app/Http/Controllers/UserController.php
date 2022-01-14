<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\UserEditRequest;


class UserController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'first_name' => 'required',
            'last_name' => 'required',
            'password' => 'required',
        ]);

        $exists = User::where('email', $request->email)->exists();

        if ($exists) {
            return response()->json(["message" => "Tu as déjà un compte. Merci de te connecter."], 409);
        }

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
        ]);


        $token = $user->createToken("kodiweb")->plainTextToken;

        return response()->json([
            "token" => $token,
            "first_name" => $user->first_name,
            "last_name" => $user->last_name,
            "email" => $user->email,
            "created_at" => $user->created_at
        ], 200);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                "error" => "Les identifiants ne sont pas corrects"
            ], 401);
        }

        $user->tokens()->where('tokenable_id',  $user->id)->delete();

        $token = $user->createToken("kodiweb")->plainTextToken;

        $user = [
            "token" => $token,
            "first_name" => $user->first_name,
            "last_name" => $user->last_name,
            "email" => $user->email,
            "created_at" => $user->created_at
        ]; 

        return response()->json([
            'user' => $user,
            "message" => "Tu es maintenant connecté"
        ], 200);

    }

    public function me(Request $request)
    {
        return response()->json([
            "first_name" => $request->user()->first_name,
            "last_name" => $request->user()->last_name,
            "email" => $request->user()->email,
            "created_at" => $request->user()->created_at,
            "updated_at" => $request->user()->updated_at,
        ], 200);
    }

    public function update($id, UserEditRequest $request)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Not found'], 404);
        }

        if ($user->id != $request->user()->id) {
            return response()->json(["message" => 'Forbidden'], 403);
        }

        $user->email = $request->get('email');
        $user->first_name = $request->get('first_name');
        $user->last_name = $request->get('last_name');
        $user->biography = $request->get('biography');

        if ($request->has('img')) {
            $result = $request->img->storeOnCloudinary();
            cloudinary()->destroy($user->img_id);
            $user->img_id = $result->getPublicId();
            $user->img_url = $result->getSecurePath();
        }

        $user->fill($request->all());
        $user->save();

        if (strlen($request->get('password', '')) > 0) {
            $user->password = Hash::make($request->get('password'));
        }

        $user->save();

        return response()->json([
            'user' => $user,
            "message" => "Ton profil a été mis à jour"
        ], 200);
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(null, 204);
    }
}
