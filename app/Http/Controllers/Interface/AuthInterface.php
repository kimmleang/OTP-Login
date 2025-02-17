<?php

namespace App\Http\Controllers\Interface;

use Illuminate\Http\Request;

interface AuthInterface
{
    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Register a new user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User registered successfully",
     *     ),
     * )
     */
    public function register(Request $request);

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Login a user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OTP sent to your email",
     *     ),
     * )
     */
    public function login(Request $request);

    /**
     * @OA\Post(
     *     path="/api/verify-otp",
     *     summary="Verify OTP",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *             @OA\Property(property="otp", type="integer", example=123456),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid or expired OTP",
     *     ),
     * )
     */
    public function verifyOtp(Request $request);
}