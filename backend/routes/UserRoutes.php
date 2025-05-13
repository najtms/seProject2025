<?php

Flight::group('/users', function () {

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @OA\Get(
     *     path="/users",
     *     summary="Retrieve all users",
     *     tags={"Users"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of users"
     *     )
     * )
     */

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    Flight::route('GET /', function () {

        Flight::auth_middleware()->authorizeRoles([1, 2]);
        Flight::json(Flight::userService()->getAll());
    });

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @OA\Get(
     *     path="/users/{id}",
     *     summary="Get user by ID",
     *     tags={"Users"},
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
     *         description="User data"
     *     )
     * )
     */

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    Flight::route('GET /@id', function ($id) {
        Flight::auth_middleware()->authorizeRoles([1, 2]);
        Flight::json(Flight::userService()->getById($id));
    });

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @OA\Post(
     *     path="/users/register",
     *     summary="Register a new user",
     *     tags={"Users"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"Email","Password"},
     *             @OA\Property(property="Email", type="string", example="john@test.com"),
     *             @OA\Property(property="Password", type="string", example="123456")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User successfully registered"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error occurred during registration"
     *     )
     * )
     */

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    Flight::route('POST /register', function () {
        Flight::auth_middleware()->authorizeRoles([1, 2]);
        try {
            $data = Flight::request()->data->getData();
            $result = Flight::userService()->registerUser($data);
            Flight::json($result, 201);
        } catch (Exception $e) {
            Flight::json(['error' => $e->getMessage()], 500);
        }
    });

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @OA\Post(
     *     path="/users/login",
     *     summary="Login user",
     *     tags={"Users"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"Email", "Password"},
     *             @OA\Property(property="Email", type="string", example="john@test.com"),
     *             @OA\Property(property="Password", type="string", example="123456")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="JWT token"
     *     )
     * )
     */

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    Flight::route('POST /login', function () {
        Flight::auth_middleware()->authorizeRoles([1, 2]);
        $data = Flight::request()->data->getData();
        Flight::json(Flight::userService()->loginUser($data['Email'], $data['Password']));
    });

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @OA\Put(
     *     path="/users/{id}",
     *     summary="Update a user",
     *     tags={"Users"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="User ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="Email", type="string", example="newemail@test.com"),
     *             @OA\Property(property="Password", type="string", example="newpassword123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User updated"
     *     )
     * )
     */

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    Flight::route('PUT /@id', function ($id) {
        Flight::auth_middleware()->authorizeRoles([1, 2]);
        $data = Flight::request()->data->getData();
        Flight::json(Flight::userService()->updateUser($id, $data));
    });

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @OA\Delete(
     *     path="/users/{id}",
     *     summary="Delete a user",
     *     tags={"Users"},
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
     *         description="User deleted"
     *     )
     * )
     */

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    Flight::route('DELETE /@id', function ($id) {
        Flight::auth_middleware()->authorizeRoles([1, 2]);
        Flight::userService()->deleteUser($id);
        Flight::json(["message" => "User $id deleted"]);
    });
});
