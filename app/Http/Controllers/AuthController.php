<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'age' => 'required|integer',
            'paye' => 'required|string|max:255',
            'sexe' => 'required|string|max:10',
            'email' => 'required|email|unique:users,email',
            'tel' => 'required|string|max:20',
            'password' => 'required|string|min:6',
            'photo' => 'nullable|image|max:2048',
        ]);

        $user = new User();
        $user->nom = $request->nom;
        $user->prenom = $request->prenom;
        $user->age = $request->age;
        $user->paye = $request->paye;
        $user->sexe = $request->sexe;
        $user->email = $request->email;
        $user->tel = $request->tel;
        $user->password = Hash::make($request->password);

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->storeAs('public/photos', $filename);
            $user->photo = $filename;
        }

        $user->save();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['message' => 'User registered successfully',
         'user' => $user,
         'token' => $token
         ]);
    }
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Email or password invalid'], 401);
        }

        // Generate token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token
        ]);
    }

    public function checkEmail(Request $request)
    {
        // 1. Dir validation 3la l-format dyal l-email
        $request->validate([
            'email' => 'required|email',
        ]);
        // 2. Check wach l-email exists f la base de données
        $exists = User::where('email', $request->email)->exists();

        // 3. Reje3 l-jawab l-React (JSON)
        return response()->json([
            'exists' => $exists,
            'message' => $exists ? 'Email déjà utilisé' : 'Email disponible'
        ]);
    }
    public function personalitytest(Request $request)
{
    // 1. جيب المستخدم اللي صيفط الطوكن
    $user = $request->user();

    // 2. تأكد واش كاين (Safety check)
    if (!$user) {
        return response()->json(['message' => 'User not found'], 401);
    }

    // 3. تحديث اللون
    $user->color = $request->color;
    
    // 4. حفظ التغييرات
    if ($user->save()) {
        return response()->json([
            'status' => 'success',
            'message' => 'تم حفظ النتيجة بنجاح',
            'user' => $user,
            // صيفط الطوكن القديم باش React ما يوقعش ليه Logout
            'token' => $request->bearerToken() 
        ], 200);
    }

    return response()->json(['message' => 'Error saving data'], 500);
}

 

}
