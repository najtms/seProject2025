<?php
Flight::group('/product', function () {
    Flight::set('product_service', new ProductService());


    Flight::route('GET /all', function () {
        Flight::json([
           Flight::productService()->getAll(),
        ]);
    });
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * @OA\Get(
     *     path="/product",
     *     summary="Retrieve products with optional filters",
     *     tags={"Products"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="category",
     *         in="query",
     *         description="Filter by category ID",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="onsale",
     *         in="query",
     *         description="Filter for on-sale products",
     *         required=false,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of products"
     *     )
     * )
     */
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    Flight::route('GET /', function () {
        Flight::auth_middleware()->authorizeRoles([1, 2]);
        $categoryId = Flight::request()->query['category'] ?? null;
        $onSale = isset(Flight::request()->query['onsale']);

        if ($categoryId) {
            Flight::json(Flight::productService()->getProductsByCategory($categoryId));
        } elseif ($onSale) {
            Flight::json(Flight::productService()->getProductsOnSale());
        } else {
            Flight::json(Flight::productService()->getAllProducts());
        }
    });

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * @OA\Get(
     *     path="/product/{id}",
     *     summary="Get product by ID",
     *     tags={"Products"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Product ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product data"
     *     )
     * )
     */
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    Flight::route('GET /@id', function ($id) {
       
        Flight::json(Flight::productService()->getById($id));
    });

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * @OA\Post(
     *     path="/product",
     *     summary="Add a new product",
     *     tags={"Products"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"Name", "Description", "Price", "CategoryID", "Stock"},
     *             @OA\Property(property="Name", type="string", example="Product Name"),
     *             @OA\Property(property="Description", type="string", example="Detailed description"),
     *             @OA\Property(property="Price", type="number", format="float", example=29.99),
     *             @OA\Property(property="CategoryID", type="integer", example=3),
     *             @OA\Property(property="Stock", type="integer", example=50)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Product created successfully"
     *     )
     * )
     */
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    Flight::route('POST /addproduct', function () {
        
        $data = Flight::request()->data->getData();
        Flight::json(Flight::productService()->addProduct($data), 201); 
    });

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * @OA\Put(
     *     path="/product/{id}",
     *     summary="Update product by ID",
     *     tags={"Products"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Product ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="Name", type="string", example="Updated Product"),
     *             @OA\Property(property="Description", type="string", example="Updated description"),
     *             @OA\Property(property="Price", type="number", format="float", example=39.99),
     *             @OA\Property(property="CategoryID", type="integer", example=2),
     *             @OA\Property(property="Stock", type="integer", example=75)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product updated successfully"
     *     )
     * )
     */
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    Flight::route('PUT /@id', function ($id) {
  
        $data = Flight::request()->data->getData();
        Flight::json(Flight::productService()->update($id, $data));
    });

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * @OA\Patch(
     *     path="/product/{id}/stock",
     *     summary="Update stock for a product",
     *     tags={"Products"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Product ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"quantity"},
     *             @OA\Property(property="quantity", type="integer", example=25)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Stock updated successfully"
     *     )
     * )
     */
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    Flight::route('PATCH /@id/stock', function ($id) {
        Flight::auth_middleware()->authorizeRoles([1, 2]);
        $data = Flight::request()->data->getData();
        Flight::json(Flight::productService()->updateStock($id, $data['quantity']));
    });

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * @OA\Delete(
     *     path="/product/{id}",
     *     summary="Delete a product by ID",
     *     tags={"Products"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Product ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product deleted"
     *     )
     * )
     */
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  Flight::route('DELETE /@id', function ($id) {


    Flight::json(Flight::productService()->deleteProduct($id), 200);
});

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * @OA\Get(
     *     path="/product/search",
     *     summary="Search products by query string",
     *     tags={"Products"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         required=true,
     *         description="Search query",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of matching products"
     *     )
     * )
     */
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    Flight::route('GET /search', function () {
        Flight::auth_middleware()->authorizeRoles([1, 2]);
        $query = Flight::request()->query['q'];
        Flight::json(Flight::productService()->searchProducts($query));
    });
});