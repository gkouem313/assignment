<?php

namespace App\Http\Controllers\API;

use App\Jobs\SendEmail;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class OfferController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * store offer and notify owners with email
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        // log function name
        $functionName = __CLASS__ . '::' . __FUNCTION__;
        \Log::debug("$functionName()", $request->all());

        // add validation
        $validator = validator($request->all(), [
            'shop_id' => 'required|integer|gt:0',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error_code' => 1,
                'validator_errors' => $validator->errors()
            ], 400);
        }
        $validated = $validator->safe();

        // get authenticated user
        $authUser = auth()->user();

        // get shop
        $shop = Shop::where('user_id', $authUser->id)->find($validated->shop_id);
        if (!$shop) {
            return response()->json([
                'error_code' => 2,
                'message' => 'Shop not found'
            ], 400);
        }

        // create offer
        $offer = $shop->offers()->create($validated->toArray());

        // get all owners except from the authenticated one and send them email
        $owners = User::where('id', '!=', $authUser->id)->get();
        foreach ($owners as $owner) {
            dispatch(new SendEmail($owner, $shop));
        }

        // return response
        return response()->json([
            'error_code' => 0,
            'offer' => $offer
        ], 200);
    }
}
