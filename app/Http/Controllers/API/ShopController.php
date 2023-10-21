<?php

namespace App\Http\Controllers\API;

use App\Models\Shop;
use App\Models\ShopCategory;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class ShopController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * get the shops of the authenticated user
     * this is not the endpoint that will be used by the guests in order to see every shop
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        // log function name
        $functionName = __CLASS__ . '::' . __FUNCTION__;
        \Log::debug("$functionName()", $request->all());

        $authUser = auth()->user();

        // get owners shops
        $shops = Shop::with('shopCategory')->where('user_id', $authUser->id)->get();

        // return response
        return response()->json([
            'error_code' => 0,
            'shops' => $shops
        ], 200);
    }

    /**
     * get the required required info in order to create a shop
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        // log function name
        $functionName = __CLASS__ . '::' . __FUNCTION__;
        \Log::debug("$functionName()", $request->all());

        // get all categories
        $shopCategories = ShopCategory::all();

        // return response
        return response()->json([
            'error_code' => 0,
            'shop_categories' => $shopCategories
        ], 200);
    }

    /**
     * create a new shop for the authenticated user
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
            'shop_category_id' => 'required|integer|gt:0',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'open_hours' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
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

        // get shop-category
        $shopCategory = ShopCategory::find($validated->shop_category_id);
        if (!$shopCategory) {
            return response()->json([
                'error_code' => 2,
                'message' => 'Shop category not found'
            ], 400);
        }

        // create shop
        $shop = $authUser->shops()->create($validated->toArray());

        // return response
        return response()->json([
            'error_code' => 0,
            'shop' => $shop
        ], 200);
    }

    /**
     * get details of a specific shop for the authenticated user
     * this is not the endpoint that will be used by the guests in order to shop's details
     *
     * @param Request $request
     * @param int $shopId
     * @return JsonResponse
     */
    public function show(Request $request, int $shopId): JsonResponse
    {
        // log function name
        $functionName = __CLASS__ . '::' . __FUNCTION__;
        \Log::debug("$functionName()", $request->all());

        // get authenticated user
        $authUser = auth()->user();

        // get shop with category
        $shop = Shop::with('shopCategory')->where('user_id', $authUser->id)->find($shopId);
        if (!$shop) {
            return response()->json([
                'error_code' => 2,
                'message' => 'Shop not found'
            ], 400);
        }

        // return response
        return response()->json([
            'error_code' => 0,
            'shop' => $shop
        ], 200);
    }

    /**
     * get details of a shop for the authenticated user in order to edit it
     *
     * @param Request $request
     * @param int $shopId
     * @return JsonResponse
     */
    public function edit(Request $request, int $shopId): JsonResponse
    {
        // log function name
        $functionName = __CLASS__ . '::' . __FUNCTION__;
        \Log::debug("$functionName()", $request->all());

        // get authenticated user
        $authUser = auth()->user();

        // get shop with category
        $shop = Shop::with('shopCategory')->where('user_id', $authUser->id)->find($shopId);
        if (!$shop) {
            return response()->json([
                'error_code' => 2,
                'message' => 'Shop not found'
            ], 400);
        }

        // get all shop categories
        $shopCategories = ShopCategory::all();

        // return response
        return response()->json([
            'error_code' => 0,
            'shop' => $shop,
            'shop_categories' => $shopCategories
        ], 200);
    }

    /**
     * update a shop for the authenticated user
     *
     * @param Request $request
     * @param int $shopId
     * @return JsonResponse
     */
    public function update(Request $request, int $shopId): JsonResponse
    {
        // log function name
        $functionName = __CLASS__ . '::' . __FUNCTION__;
        \Log::debug("$functionName()", $request->all());

        // add validation
        $validator = validator($request->all(), [
            'shop_category_id' => 'integer|gt:0',
            'name' => 'string|max:255',
            'description' => 'string',
            'open_hours' => 'string|max:255',
            'city' => 'string|max:255',
            'address' => 'nullable|string|max:255',
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
        $shop = Shop::where('user_id', $authUser->id)->find($shopId);
        if (!$shop) {
            return response()->json([
                'error_code' => 2,
                'message' => 'Shop not found'
            ], 400);
        }

        // get shop categeory if requested
        if (isset($validated['shop_category_id'])) {
            $shopCategory = ShopCategory::find($validated->shop_category_id);
            if (!$shopCategory) {
                return response()->json([
                    'error_code' => 3,
                    'message' => 'Shop category not found'
                ], 400);
            }
        }

        // update shop
        $shop->update($validated->toArray());

        // return response
        return response()->json([
            'error_code' => 0,
            'shop' => $shop
        ], 200);
    }
}
