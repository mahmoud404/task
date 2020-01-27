<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Mail\ResetMail;
use App\Notifications\TipNotification;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register','reset','checkCode']]);
    }

    /**
     *
     * @SWG\Post(
     *      tags={"auth"},
     *      path="/auth/register",
     *      summary="register",
     *      security={
     *          {"jwt": {}}
     *      },
     *      @SWG\Parameter(
     *         name="name",
     *         in="formData",
     *         required=true,
     *         type="string",
     *         format="string",
     *         default="mahmoud",
     *      ),@SWG\Parameter(
     *         name="email",
     *         in="formData",
     *         required=true,
     *         type="string",
     *         format="string",
     *         default="mahmoudnada5050@gmail.com",
     *      ),@SWG\Parameter(
     *         name="device_token",
     *         in="formData",
     *         required=true,
     *         type="string",
     *         format="string",
     *         default="device_token",
     *      ),
     *      @SWG\Parameter(
     *         name="password",
     *         in="formData",
     *         required=true,
     *         type="string",
     *         format="string",
     *         default="123456",
     *      ),
     *      @SWG\Response(response=200, description="token"),
     *      @SWG\Response(response=400, description="Unauthorized"),
     * )
     * @param RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function register(RegisterRequest $request)
    {

        $user = User::create($request->all());

        $token = auth('api')->login($user);
        try
        {
            Notification::send($user,new TipNotification($user));
        }catch (\Exception $exception)
        {
            return $this->responseJson('error', $exception->getMessage(),$exception->getCode());
        }
        return $this->respondWithToken($token);
    }

    /**
     *
     * @SWG\Post(
     *      tags={"auth"},
     *      path="/auth/login",
     *      summary="login",
     *      security={
     *          {"jwt": {}}
     *      },
     *      @SWG\Parameter(
     *         name="email",
     *         in="formData",
     *         required=true,
     *         type="string",
     *         format="string",
     *         default="mahmoudnada5050@gmail.com",
     *      ),
     *      @SWG\Parameter(
     *         name="password",
     *         in="formData",
     *         required=true,
     *         type="string",
     *         format="string",
     *         default="123456",
     *      ),
     *      @SWG\Response(response=200, description="token"),
     *      @SWG\Response(response=400, description="Unauthorized"),
     * )
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (! $token = auth('api')->attempt($credentials)) {
            return $this->responseJson('error', 'Unauthorized',401);
        }

        return $this->respondWithToken($token);
    }

    /**
     *
     * @SWG\Get(
     *      tags={"auth"},
     *      path="/auth/logout",
     *      summary="logout currently logged in user",
     *      security={
     *          {"jwt": {}}
     *      },
     *      @SWG\Response(response=200, description="Successfully logged out"),
     * )
     */
    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     *
     * @SWG\Post(
     *      tags={"auth"},
     *      path="/auth/refresh",
     *      summary="refreshes expired token",
     *      security={
     *          {"jwt": {}}
     *      },
     *      @SWG\Response(response=200, description="token"),
     *      @SWG\Response(response=400, description="Unauthorized"),
     * )
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
