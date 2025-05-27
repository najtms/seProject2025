<?php
require_once __DIR__ . '/../services/PaymentService.php';

$paymentService = new PaymentService();

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * @OA\Post(
 *     path="/payments",
 *     summary="Process a payment for an order",
 *     tags={"Payments"},
 *     security={{"bearerAuth": {}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"OrderID", "UserID", "Amount", "PaymentMethod"},
 *             @OA\Property(property="OrderID", type="integer", example=101),
 *             @OA\Property(property="UserID", type="integer", example=5),
 *             @OA\Property(property="Amount", type="number", format="float", example=49.99),
 *             @OA\Property(property="PaymentMethod", type="string", example="Credit Card")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Payment processed successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="PaymentID", type="integer", example=1234)
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input or processing error"
 *     )
 * )
 */
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
Flight::route('POST /payments', function () use ($paymentService) {
    $data = Flight::request()->data->getData();
    Flight::auth_middleware()->authorizeRoles([1, 2]);
    try {
        $paymentId = $paymentService->processPayment($data['OrderID'], $data['UserID'], $data['Amount'], $data['PaymentMethod']);
        Flight::json(['PaymentID' => $paymentId], 201);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 400);
    }
});