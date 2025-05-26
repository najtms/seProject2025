<?php
require_once __DIR__ . '/../services/WishlistService.php';

$wishlistService = new WishlistService();

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * @OA\Post(
 *     path="/wishlist",
 *     summary="Add a product to the wishlist",
 *     tags={"Wishlist"},
 *     security={{"bearerAuth": {}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"UserID", "ProductID"},
 *             @OA\Property(property="UserID", type="integer", example=5),
 *             @OA\Property(property="ProductID", type="integer", example=101)
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Product added to wishlist",
 *         @OA\JsonContent(
 *             @OA\Property(property="WishlistID", type="integer", example=12)
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input or processing error"
 *     )
 * )
 */
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
Flight::route('POST /wishlist', function () use ($wishlistService) {
    Flight::auth_middleware()->authorizeRoles([1, 2]);
    $data = Flight::request()->data->getData();
    try {
        $wishlistId = $wishlistService->addToWishlist($data['UserID'], $data['ProductID']);
        Flight::json(['WishlistID' => $wishlistId], 201);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});