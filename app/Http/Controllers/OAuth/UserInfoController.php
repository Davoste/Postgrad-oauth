<?php

namespace App\Http\Controllers\OAuth;

use App\Http\Controllers\Controller;
use App\Models\OAuth\OAuthAccessToken;
use Illuminate\Http\Request;

/**
 * UserInfo endpoint — Moodle's OAuth2 "standard" issuer type calls this with
 * the access token to fetch the fields it maps to firstname/lastname/email
 * (Site admin > OAuth 2 > your issuer > User field mappings).
 */
class UserInfoController extends Controller
{
    public function show(Request $request)
    {
        $bearer = $request->bearerToken();
        if (!$bearer) {
            return response()->json(['error' => 'invalid_token'], 401);
        }

        $token = OAuthAccessToken::findValidByPlainToken($bearer);
        if (!$token) {
            return response()->json(['error' => 'invalid_token'], 401);
        }

        $user = $token->user;

        return response()->json([
            'sub'           => (string) $user->id,
            'id'            => $user->id,
            'email'         => $user->email,
            'email_verified'=> $user->hasVerifiedEmail(),
            'name'          => $user->name,
            'given_name'    => $user->first_name ?: \Illuminate\Support\Str::before($user->name, ' '),
            'family_name'   => $user->surname ?: \Illuminate\Support\Str::afterLast($user->name, ' '),
            // Moodle's idnumber-based matching (see ProvisionMoodleUser) —
            // the admission number, when the user has been admitted.
            'idnumber'      => optional($user->student)->reg_no,
        ]);
    }
}
