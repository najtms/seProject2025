<?php

use OpenApi\Attributes as OA;

/**
 * @OA\Info(
 *     title="Software Engineering Project",
 *     description="GameShop",
 *     version="1.0",
 *     @OA\Contact(
 *         email="vildan.onedrive@gmail.com",
 *         name= "Vildan Kadric",
 *     )
 * ),
 * @OA\Server(
 *     url="http://localhost/vildankWebProject/backend/",
 *     description="API server"
 * ),
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */