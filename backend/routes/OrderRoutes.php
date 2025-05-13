<?php
Flight::group('/orders', function () {
    Flight::set('order_service', new OrderService());

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * @OA\Get(
     *     path="/orders",
     *     summary="Get all orders",
     *     tags={"Orders"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of all orders"
     *     )
     * )
     */
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    Flight::route('GET /', function () {
        Flight::auth_middleware()->authorizeRoles([1, 2]);
        Flight::json(Flight::orderService()->getAllOrders());
    });

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * @OA\Get(
     *     path="/orders/{id}/orders",
     *     summary="Get all orders by user ID",
     *     tags={"Orders"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="User ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Orders for a specific user"
     *     )
     * )
     */
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    Flight::route('GET /@id/orders', function ($id) {
        Flight::auth_middleware()->authorizeRoles([1, 2]);
        Flight::json(Flight::orderService()->getOrdersByUserId($id));
    });

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * @OA\Post(
     *     path="/orders",
     *     summary="Create a new order",
     *     tags={"Orders"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"UserID", "Items"},
     *             @OA\Property(property="UserID", type="integer", example=1),
     *             @OA\Property(
     *                 property="Items",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="ProductID", type="integer", example=10),
     *                     @OA\Property(property="Quantity", type="integer", example=2)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Order successfully created"
     *     )
     * )
     */
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    Flight::route('POST /', function () {
        Flight::auth_middleware()->authorizeRoles([1, 2]);
        $orderData = Flight::request()->data->getData();
        $newOrder = Flight::orderService()->createOrder($orderData);
        Flight::json($newOrder, 201);
    });

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * @OA\Get(
     *     path="/orders/{id}",
     *     summary="Get order with items by order ID",
     *     tags={"Orders"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Order ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order details including items"
     *     )
     * )
     */
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    Flight::route('GET /@id', function ($id) {
        Flight::auth_middleware()->authorizeRoles([1, 2]);
        Flight::json(Flight::orderService()->getOrderWithItems($id));
    });

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * @OA\Put(
     *     path="/orders/{id}/status",
     *     summary="Update the status of an order",
     *     tags={"Orders"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Order ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"Status"},
     *             @OA\Property(property="Status", type="string", example="Shipped")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order status updated"
     *     )
     * )
     */
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    Flight::route('PUT /@id/status', function ($id) {
        Flight::auth_middleware()->authorizeRoles([1, 2]);
        $data = Flight::request()->data->getData();
        Flight::json(Flight::orderService()->updateOrderStatus($id, $data['Status']));
    });
});
