<?php
require_once __DIR__ . '/../services/OrderItemService.php';

Flight::group('/order-items', function () {
    $orderItemService = new OrderItemService();

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @OA\Get(
     *     path="/order-items/order/{orderId}",
     *     summary="Get all items for a specific order",
     *     tags={"OrderItems"},
     *  *  security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         name="orderId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of order items",
     *         @OA\JsonContent(type="array", @OA\Items())
     *     )
     * )
     */

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    Flight::route('GET /order/@orderId', function ($orderId) use ($orderItemService) {
        Flight::auth_middleware()->authorizeRoles([1, 2]);
        Flight::json($orderItemService->getItemsByOrder($orderId));
    });

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @OA\Post(
     *     path="/order-items",
     *     summary="Add an item to an order",
     *     tags={"OrderItems"},
     *  *  security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"OrderID", "ProductID", "Quantity"},
     *             @OA\Property(property="OrderID", type="integer"),
     *             @OA\Property(property="ProductID", type="integer"),
     *             @OA\Property(property="Quantity", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Order item created",
     *         @OA\JsonContent(
     *             @OA\Property(property="OrderItemID", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */


    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    Flight::route('POST /order-items', function () use ($orderItemService) {
        Flight::auth_middleware()->authorizeRoles([1, 2]);
        $data = Flight::request()->data->getData();
        try {
            $itemId = $orderItemService->addItemToOrder($data['OrderID'], $data['ProductID'], $data['Quantity']);
            Flight::json(['OrderItemID' => $itemId], 201);
        } catch (Exception $e) {
            Flight::json(['error' => $e->getMessage()], 400);
        }
    });

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @OA\Put(
     *     path="/order-items/{itemId}",
     *     summary="Update quantity of an item in an order",
     *     tags={"OrderItems"},
     *  security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         name="itemId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"Quantity"},
     *             @OA\Property(property="Quantity", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Quantity updated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request"
     *     )
     * )
     */

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    Flight::route('PUT /@itemId', function ($itemId) use ($orderItemService) {
        Flight::auth_middleware()->authorizeRoles([1, 2]);
        $data = Flight::request()->data->getData();
        try {
            $orderItemService->updateItemQuantity($itemId, $data['Quantity']);
            Flight::json(['message' => 'Quantity updated']);
        } catch (Exception $e) {
            Flight::json(['error' => $e->getMessage()], 400);
        }
    });

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @OA\Get(
     *     path="/order-items/total/{orderId}",
     *     summary="Calculate total cost of an order",
     *     tags={"OrderItems"},
     *  security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         name="orderId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Total order cost",
     *         @OA\JsonContent(
     *             @OA\Property(property="total", type="number", format="float")
     *         )
     *     )
     * )
     */

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    Flight::route('GET /total/@orderId', function ($orderId) use ($orderItemService) {
        Flight::auth_middleware()->authorizeRoles([1, 2]);
        $total = $orderItemService->calculateOrderTotal($orderId);
        Flight::json(['total' => $total]);
    });
});
