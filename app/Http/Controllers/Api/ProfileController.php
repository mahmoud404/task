<?php

namespace App\Http\Controllers\Api;

use App\Helpers\UploadImage;
use App\Http\Requests\Api\OrderRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Resources\AccountResource;
use App\Http\Resources\NotificationResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\UtilizeResource;
use App\Order;
use App\Product;
use App\Utilize;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    use UploadImage;

    /**
     *
     * @SWG\Get(
     *      tags={"account"},
     *      path="/account/me",
     *      summary="Get the current logged in user",
     *      security={
     *          {"jwt": {}}
     *      },
     *      @SWG\Response(response=200, description="object"),
     * )
     */
    public function me()
    {
        return AccountResource::make(auth()->user());
    }

    /**
     *
     * @SWG\post(
     *      tags={"account"},
     *      path="/account/update",
     *      summary="update My Account",
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
     *         name="photo",
     *         in="formData",
     *         type="file",
     *      ),
     *      @SWG\Response(response=200, description="token"),
     *      @SWG\Response(response=400, description="Unauthorized"),
     * )
     * @param RegisterRequest $request
     * @return AccountResource
     */
    public function update(RegisterRequest $request)
    {
        $user = auth()->user();
        $user->update($request->except('photo'));
        if ($request->photo) {
            $this->upload($request->photo,$user,null,true);
        }
        return AccountResource::make(auth()->user());
    }


    /**
     *
     * @SWG\post(
     *      tags={"account"},
     *      path="/account/update-password",
     *      summary="update My Password",
     *      security={
     *          {"jwt": {}}
     *      },
     *      @SWG\Parameter(
     *         name="current_password",
     *         in="formData",
     *         required=true,
     *         type="string",
     *         format="string",
     *      ),@SWG\Parameter(
     *         name="password",
     *         in="formData",
     *         required=true,
     *         type="string",
     *         format="string",
     *      ),
     *      @SWG\Response(response=200, description="User Model"),
     *      @SWG\Response(response=400, description="Unauthorized"),
     * )
     * @param Request $request
     * @return AccountResource|\Illuminate\Http\JsonResponse
     */
    public function updatePassword(Request $request)
    {
        $user = auth()->user();
        if (Hash::check($request->current_password,$user->password))
        {
            if ($request->current_password === $request->password)
            {
                return $this->responseJson('error', 'Current And New Password is Same',400);
            }
            $user->update(['password'=>bcrypt($request->password)]);

            return AccountResource::make($user);
        }
        return $this->responseJson('error', 'Current Password Wrong',400);
    }

    /**
     *
     * @SWG\post(
     *      tags={"account"},
     *      path="/account/update-device-token",
     *      summary="update My device token",
     *      security={
     *          {"jwt": {}}
     *      },
     *      @SWG\Parameter(
     *         name="device_token",
     *         in="formData",
     *         required=true,
     *         type="string",
     *         format="string",
     *      ),
     *      @SWG\Response(response=200, description="User Model"),
     *      @SWG\Response(response=400, description="Unauthorized"),
     * )
     * @param Request $request
     * @return AccountResource|\Illuminate\Http\JsonResponse
     */
    public function updateDeviceToken(Request $request)
    {
        $user = auth()->user();
        $user->update(['device_token'=>$request->device_token]);
        return AccountResource::make($user);
    }


    /**
     *
     * @SWG\Get(
     *      tags={"notifications"},
     *      path="/notifications",
     *      summary="Get all tips notifications paginate 5 per page",
     *      security={
     *          {"jwt": {}}
     *      },
     *     @SWG\Parameter(
     *         name="Accept-Language",
     *         in="header",
     *         description="Langouge Code",
     *         required=true,
     *         type="string",
     *         format="string",
     *         default="en",
     *      ),
     *      @SWG\Response(response=200, description="objects"),
     * )
     */
    public function tipsNotifications()
    {
        $tips = auth()->user()->notifications()->latest()->paginate(5);
        return NotificationResource::collection($tips);
    }

    /**
     *
     * @SWG\Get(
     *      tags={"notifications"},
     *      path="/notifications/{id}",
     *      summary="read notification",
     *      security={
     *          {"jwt": {}}
     *      },
     *     @SWG\Parameter(
     *         name="Accept-Language",
     *         in="header",
     *         description="Langouge Code",
     *         required=true,
     *         type="string",
     *         format="string",
     *         default="en",
     *      ),
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         type="string",
     *         format="string",
     *      ),
     *      @SWG\Response(response=200, description="objects"),
     * )
     */
    public function readNotification($id)
    {
         auth()->user()->notifications()->find($id)->markAsRead();
        return response()->json('read successfully',200);
    }

    /**
     *
     * @SWG\Get(
     *      tags={"notifications"},
     *      path="/read-all-notifications",
     *      summary="read-all-notifications",
     *      security={
     *          {"jwt": {}}
     *      },
     *     @SWG\Parameter(
     *         name="Accept-Language",
     *         in="header",
     *         description="Langouge Code",
     *         required=true,
     *         type="string",
     *         format="string",
     *         default="en",
     *      ),
     *      @SWG\Response(response=200, description="object"),
     * )
     */
    public function readAllNotifications()
    {
        $tips = auth()->user()->unreadNotifications->markAsRead();
        return response()->json('read successfully',200);
    }
}
