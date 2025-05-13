<?php
require_once __DIR__ . '/../services/CartService.php';

Flight::set('cart_service', new CartService());

Flight::group('/cart', function () {

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * @OA\Get(
     *     path="/cart/{userId}",
     *     summary="Get cart items by user ID",
     *     tags={"Cart"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         required=true,
     *         description="ID of the user",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of cart items"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error retrieving cart items"
     *     )
     * )
     */
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    Flight::route('GET /@userId', function ($userId) {
        Flight::auth_middleware()->authorizeRoles([1, 2]);
        try {
            $cartItems = Flight::get('cart_service')->getCartByUserId($userId);
            Flight::json($cartItems);
        } catch (Exception $e) {
            Flight::json(['error' => $e->getMessage()], 400);
        }
    });

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * @OA\Post(
     *     path="/cart",
     *     summary="Add item to cart",
     *     tags={"Cart"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"userId", "productId"},
     *             @OA\Property(property="userId", type="integer", example=1),
     *             @OA\Property(property="productId", type="integer", example=101),
     *             @OA\Property(property="quantity", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Item added to cart",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="result", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error adding to cart"
     *     )
     * )
     */
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    Flight::route('POST /', function () {
        Flight::auth_middleware()->authorizeRoles([1, 2]);
        $data = Flight::request()->data->getData();
        try {
            $userId = $data['userId'];
            $productId = $data['productId'];
            $quantity = $data['quantity'] ?? 1;
            $result = Flight::get('cart_service')->addToCart($userId, $productId, $quantity);
            Flight::json(['success' => true, 'result' => $result]);
        } catch (Exception $e) {
            Flight::json(['error' => $e->getMessage()], 400);
        }
    });

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * @OA\Delete(
     *     path="/cart/{userId}/{productId}",
     *     summary="Remove a product from user's cart",
     *     tags={"Cart"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         required=true,
     *         description="ID of the user",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="productId",
     *         in="path",
     *         required=true,
     *         description="ID of the product",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product removed from cart",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error removing item"
     *     )
     * )
     */
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    Flight::route('DELETE /@userId/@productId', function ($userId, $productId) {
        Flight::auth_middleware()->authorizeRoles([1, 2]);
        try {
            $success = Flight::get('cart_service')->removeFromCart($userId, $productId);
            Flight::json(['success' => $success]);
        } catch (Exception $e) {
            Flight::json(['error' => $e->getMessage()], 400);
        }
    });

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * @OA\Delete(
     *     path="/cart/{userId}",
     *     summary="Clear all items from user's cart",
     *     tags={"Cart"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         required=true,
     *         description="ID of the user",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cart cleared",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error clearing cart"
     *     )
     * )
     */
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    Flight::route('DELETE /@userId', function ($userId) {
        Flight::auth_middleware()->authorizeRoles([1, 2]);
        try {
            $success = Flight::get('cart_service')->clearCart($userId);
            Flight::json(['success' => $success]);
        } catch (Exception $e) {
            Flight::json(['error' => $e->getMessage()], 400);
        }
    });
});
