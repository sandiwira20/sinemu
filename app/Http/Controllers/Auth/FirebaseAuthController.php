<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Kreait\Firebase\Factory;
use App\Models\User;
use Illuminate\Support\Str;

class FirebaseAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        try {
            $auth = (new Factory)
                ->withServiceAccount(storage_path('app/firebase/firebase.json'))
                ->createAuth();

            // Allow small clock skew between client/server to avoid "issued in the future".
            $verifiedToken = $auth->verifyIdToken($request->token, false, 120);
            $uid = $verifiedToken->claims()->get('sub');

            $firebaseUser = $auth->getUser($uid);

            // EMAIL ADMIN
            $adminEmail = 'sinemuadmn@gmail.com';

            // TENTUKAN ROLE
            $roleId = $firebaseUser->email === $adminEmail ? 1 : 2;

            // Semua email diperbolehkan untuk login Google.

            // SIMPAN / UPDATE USER
            $user = User::updateOrCreate(
                ['email' => $firebaseUser->email],
                [
                    'name' => $firebaseUser->displayName ?? 'User',
                    'password' => bcrypt(Str::random(16)),
                    'role_id' => $roleId,
                ]
            );

            Auth::login($user);

            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            \Log::error($e->getMessage());
            return response()->json(['success' => false], 401);
        }
    }
}
