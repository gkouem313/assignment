<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class UserController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * register a new user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        // log function name
        $functionName = __CLASS__ . '::' . __FUNCTION__;
        \Log::debug("$functionName()", $request->all());

        // add validation
        $validator = validator($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|max:255|min:8',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error_code' => 1,
                'validator_errors' => $validator->errors()
            ], 400);
        }
        $validated = $validator->safe();

        // create the user
        $user = User::create($validated->toArray());

        // return response
        return response()->json([
            'error_code' => 0,
            'user' => $user
        ], 200);
    }

    /**
     * log in a user and generate token
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        // log function name
        $functionName = __CLASS__ . '::' . __FUNCTION__;
        \Log::debug("$functionName()", $request->all());

        // add validation
        $validator = validator($request->all(), [
            'name' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error_code' => 1,
                'validator_errors' => $validator->errors()
            ], 400);
        }
        $validated = $validator->safe();

        // get authenticated user and create token for him
        $authUser = auth()->user();
        $token = $authUser->createToken($validated->name);

        return response()->json([
            'error_code' => 0,
            'token' => $token
        ], 200);
    }
}
