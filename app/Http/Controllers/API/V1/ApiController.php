<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiController extends Controller
{
        /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($message,$data = null)
    {
    	$response = [
            'message' => $message,
        ];
        if ($data) $response['data'] = $data;

        return response()->json($response, 200);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($message, $errorMessages = [], $code = 404)
    {
    	$response = [
            'message' => $message,
        ];

        if(!empty($errorMessages)){
            $response['errors'] = $errorMessages;
        }

        return response()->json($response, $code);
    }


    public function sendResponseWithPaginatedData($paginated, $otherData = []) {
        $paginated = $paginated->toArray();
        $paginated = [
            "current_page" => $paginated["current_page"],
            "data" => $paginated["data"],
            "from" => $paginated["from"],
            "last_page" => $paginated["last_page"],
            "per_page" => $paginated["per_page"],
            "to" => $paginated["to"],
            "total" => $paginated["total"],
        ];

        return $this->sendResponse(__("Fetched Successfully"), $paginated);
    }
}
