<?php
require_once __DIR__ . '/../Services/CartService.php';


/**
 * @OA\Get(
 *     path="/cart/{user_ID}",
 *     tags={"cart"},
 *     summary="Get cart by user ID",
 *     description="Retrieve the cart for a specific user by their user ID.",
 *     @OA\Parameter(name="user_ID", in="path", required=true, description="ID of the user", @OA\Schema(type="integer", example=1)),
 *     @OA\Response(response=200, description="Cart returned successfully."),
 *     @OA\Response(response=404, description="User or cart not found."),
 *     @OA\Response(response=500, description="Internal server error.")
 * )
 */
Flight::route("GET /cart/@user_ID", function ($user_ID) {

    $service = new CartService();

    Flight::json($service->getCartByUserID($user_ID));
});


