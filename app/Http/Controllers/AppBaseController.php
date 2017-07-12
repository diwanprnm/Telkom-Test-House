<?php

namespace App\Http\Controllers;

use InfyOm\Generator\Utils\ResponseUtil;
use Response; 

class AppBaseController extends Controller
{
    public function sendResponse($result, $message, $errorCode = 0)
    {
		$defaultReturn = ResponseUtil::makeResponse($message, $result);
		$return = new \StdClass();
		$return->status = $defaultReturn["success"];
		$return->error_code = $errorCode;
		$return->message = $defaultReturn["message"];
		$return->data = $defaultReturn["data"];
		return Response::json($return);
    }

    public function sendError($error, $code = 200)
    {
		$defaultReturn = ResponseUtil::makeError($error);
		$return = new \StdClass();
		$return->status = $defaultReturn["success"];
		$return->error_code = 0;
		$return->message = $defaultReturn["message"];
		$return->data = NULL;
		return Response::json($return, $code);
    }
}
