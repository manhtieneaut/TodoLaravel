<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/login",
     *     tags={"auth"},
     *     summary="Login",
     *     description="This method get login.",
     *     operationId="login",
     *     @OA\Parameter(
     *         name="tags",
     *         in="query",
     *         description="Tags to filter by",
     *         required=true,
     *         explode=true,
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(
     *                 type="string",
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid status value"
     *     ),
     * )
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    
        $credentials = $request->only('email', 'password');
    
        if (!Auth::guard('api')->attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }
    
        $user = Auth::guard('api')->user();
    
        $token = $user->createToken('api_token');
    
        return response()->json([
            'token' => $token->plainTextToken,
            'user' => $user,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/logout",
     *     tags={"auth"},
     *     summary="Logout",
     *     description="This method get logout.",
     *     operationId="logout",
     *     @OA\Parameter(
     *         name="tags",
     *         in="query",
     *         description="Tags to filter by",
     *         required=true,
     *         explode=true,
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(
     *                 type="string",
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid status value"
     *     ),
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['success' => 'logout']);
    }
}
