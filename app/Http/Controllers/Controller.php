<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * @SWG\Swagger(
 * 		basePath="/api",
 * 		@SWG\Info(
 * 			title="Test Task",
 * 			version="1.0.0",
 *           @SWG\Contact(
 *             email="mahmoudnada5050@gmail.com"
 *         ),
 * 		),
 * 		@SWG\SecurityScheme(
 * 			securityDefinition="jwt",
 * 			description="Value: Bearer \<token\><br><br>",
 * 			type="apiKey",
 * 			name="Authorization",
 * 			in="header",
 * 		),
 * )
 */

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function responseJson($type,$message,$status)
    {
        $result = ['status' => $type ,'data' => is_array($message) ? $message : array($message)];
        throw new HttpResponseException(response()->json($result , $status));
    }
}
