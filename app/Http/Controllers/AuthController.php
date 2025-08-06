<?php

namespace App\Http\Controllers;

use App\Models\OauthCode;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str; // don't forget this import!

class AuthController extends Controller
{
    public function login()
    {
        $githubUser = Socialite::driver('github')
        ->scopes(['read:user', 'user:email'])
        ->stateless()
        ->user();

        $user = User::firstOrCreate(
            ['github_id' => $githubUser->id],
            [
                'name' => $githubUser->name ?? $githubUser->nickname,
                'email' => $githubUser->email,
                'github_username' => $githubUser->nickname,
                'avatar' => $githubUser->avatar,
                'password' => bcrypt(Str::random(24)),
            ]
        );

        $tempCode = Str::random(40);

        OauthCode::create([
            'code'=>$tempCode,
            'user_id'=>$user->id,
            'expires_at'=> Carbon::now()->addMinutes(5)
        ]);

    return redirect()->to("http://localhost:3000/auth/success?code={$tempCode}");

    }

    public function exchangeToken(Request $request){

        $request->validate([
            'code'=>'required|string',
        ]);

        $code = OauthCode::where('code',$request->code)

                         ->where('expires_at','>',now())
                         ->first();


        if (!$code) {
        return response()->json(['message' => 'Invalid or expired code'], 401);
    }

        $user = $code->user;

        // Delete code so it can’t be reused
        $code->delete();

        $token = $user->createToken('dev_token')->plainTextToken;

        return response()->json(['token' => $token, 'user' => $user]);

    }

    public function logout(Request $request)
{
    // Revoke the current token that was used to authenticate the request
    $request->user()->currentAccessToken()->delete();

    return response()->json([
        'message' => 'Logged out successfully'
    ]);
}
}

