<?php

namespace App\Http\Traits;

trait ApiResponseTrait {
    
    public function responseData($data, $code = 0, $message = 'success', $status = 200)
    {
        return response()->json([
            'code' => $code,
            'message' => $message,
            'data' => $data
        ], $status, [], JSON_UNESCAPED_UNICODE);
    }
    
    public function responseSuccess($message = 'success')
    {
        return $this->responseMessage(0, $message);
    }
    
    public function responseFailed($message = 'failed')
    {
        return $this->responseMessage(2, $message, 500);
    }
    
    public function responseError($message = 'error')
    {
        return $this->responseMessage(1, $message, 500);
    }
    
    public function responseMessage($code, $message, $status = 200)
    {
        return response()->json([
            'code' => $code,
            'message' => $message,
        ], $status);
    }
    
    public function responseNotFound($message = 'not found')
    {
        return response()->json([
            'code' => 404,
            'message' => $message,
        ], 404);
    }
    
    public function unauthorized($message = 'Unauthorized') {
        return response()->json(['code' => 401, 'message' => $message] , 422);
    }
    
    public function responseValidateError($message = '数据验证失败') {
        return response()->json(['code' => 422, 'message' => $message] , 422);
    }

}
