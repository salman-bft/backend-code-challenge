<?php

namespace App\Http\Controllers;

use App\Utils\ResponseUtil;
use Response;

/**
 * @SWG\Swagger(
 *   basePath="/api/v1",
 *   @SWG\Info(
 *     title="Laravel Generator APIs",
 *     version="1.0.0",
 *   )
 * )
 * This class should be parent class for other API controllers
 * Class AppBaseController
 */
class AppBaseController extends Controller
{
    public function sendResponse($result, $message)
    {
        return Response::json(ResponseUtil::makeResponse($message, $result));
    }

    public function sendError($error=[], $code = 422)
    {
        $content = array(
            'success' => false,
            'data' => 'something went wrong.',  
            'message' => $error
        );
        return response($content)->setStatusCode($code);
    }

    public function sendSuccess($message)
    {
        return Response::json([
            'success' => true,
            'message' => $message,
        ], 200);
    }

    public function sendSuccessResponse($data,$message)
    {
        return Response::json([
            'success' => true,
            'data'      => $data,
            'message' => $message,
        ], 200);
    }
    public function sendErrorResponse($data,$error=[], $code = 422)
    {
        $content = array(
            'success' => false,
            'data' => $data,
            'message' => $error
        );
        return response($content)->setStatusCode($code);
    }
    public function validatorError($error)
    {
        $error=array_values($error->toArray());
        return $this->sendError($error[0][0]);
    }

    public function response($response){
        return Response::json([
            'success'=>$response
        ]);
    }
}
