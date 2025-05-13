<?php

use OpenApi\Attributes as OA;

/**
 * @OA\Info(
 *     title="Software Engineering Project",
 *     description="GameShop",
 *     version="1.0",
 *     @OA\Contact(
 *         email="muhamad.assaad@stu.ibu.edu.ba",
 *         name="Muhamad Assaad & Vildan Kadric",
 *     )
 * ),
 * @OA\Server(
 *     url="http://localhost:8888/SoftwareEng-Muhamad_Assaad-Vildan_Kadric/backend/",
 *     description="API server"
 * ),
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
