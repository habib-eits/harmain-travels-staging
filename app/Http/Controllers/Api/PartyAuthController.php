<?php

namespace App\Http\Controllers\Api;

use App\Models\Party;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

class PartyAuthController extends Controller
{
    public function login(Request $request)
    {
        if(env('API_ENABLE') == 0)
        {
            throw ValidationException::withMessages([
                'email' => ['Please Contact Admin to Enable Api'],
            ]);
        
        }
       
      
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);


        $party = Party::where('Email', $request->email)
        ->where('Password', $request->password)
        ->first();

        if(!$party){
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $party->createToken('party-api-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'party' => [
                'id'   => $party->PartyID,
                'name' => $party->PartyName,
            ]
        ]);
    }
    public function logout(Request $request)
    {
        $request->user('party-api')->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out']);
    }
}
