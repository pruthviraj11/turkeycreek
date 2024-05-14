<?php

namespace App\Http\Middleware;

use App\Models\Registration;
use Closure;
use Illuminate\Http\Request;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;

class JwtMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('Authorization');
        $token = str_replace('Bearer ', '', $token);
        // dd($token);

        if (!$token) {
            return response()->json(['error' => 'Unauthorized. Token not provided.'], 401);
        }

        $config = Configuration::forSymmetricSigner(new Sha256(), InMemory::plainText('7A95895F6CE7FC2672AA68AA814C4FED'));
        try {
            $parsedToken = $config->parser()->parse($token);
            $userId = $parsedToken->claims()->get('jti');

            $user = Registration::find($userId);
            if (!$user) {
                return response()->json(['error' => 'Unauthorized. User not found.'], 401);
            }

            $datas = $request->merge(['auth_user_id' => $user->id]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unauthorized. Invalid token.'], 401);
        }

        return $next($request);
    }
}