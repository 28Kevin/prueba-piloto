<?php

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="API de Autenticación",
 *     description="API para manejo de autenticación de usuarios",
 *     @OA\Contact(
 *         email="support@example.com"
 *     )
 * )
 */

/**
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */

namespace App\OpenApi;

class AuthController
{
    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Iniciar sesión",
     *     description="Autentica a un usuario y retorna un token de acceso",
     *     operationId="login",
     *     tags={"Autenticación"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="usuario@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login exitoso",
     *         @OA\JsonContent(
     *             @OA\Property(property="links", type="object"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="type", type="string", example="tokens"),
     *                 @OA\Property(property="id", type="string"),
     *                 @OA\Property(
     *                     property="attributes",
     *                     type="object",
     *                     @OA\Property(property="access_token", type="string"),
     *                     @OA\Property(property="expires_at", type="string", format="date-time"),
     *                     @OA\Property(property="message", type="string", example="Bienvenido")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Credenciales inválidas",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="errors",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="status", type="string", example="401"),
     *                     @OA\Property(property="title", type="string", example="Unauthorized"),
     *                     @OA\Property(property="detail", type="string", example="Usuario o contraseña incorrectos"),
     *                     @OA\Property(property="attempts", type="integer", example=1),
     *                     @OA\Property(property="remaining_attempts", type="integer", example=2)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=429,
     *         description="Demasiados intentos",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="errors",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="status", type="string", example="429"),
     *                     @OA\Property(property="title", type="string", example="Too Many Attempts"),
     *                     @OA\Property(property="detail", type="string", example="Demasiados intentos de inicio de sesión. Por favor, intente nuevamente en 30 segundos.")
     *                 )
     *             )
     *         )
     *     )
     * )
     */

    /**
     * @OA\Get(
     *     path="/api/get-user",
     *     summary="Obtener información del usuario",
     *     description="Retorna la información del usuario autenticado",
     *     operationId="getUser",
     *     tags={"Autenticación"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Información del usuario",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="email", type="string", format="email")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="errors",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="status", type="string", example="401"),
     *                     @OA\Property(property="title", type="string", example="Unauthorized"),
     *                     @OA\Property(property="detail", type="string", example="No estás autenticado o tu token ha expirado.")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
} 