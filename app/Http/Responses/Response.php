<?php

namespace MOLiBot\Http\Responses;

use Illuminate\Http\JsonResponse;

class Response
{
    /**
     * Response of JSON
     *
     * @param int $status
     * @param int $code
     * @param string $msg
     * @param array $data
     * @return JsonResponse
     */
    public function jsonResponse($status, $code = 1, $msg = '', $data = [])
    {
        $data = $this->format($code, $msg, $data);
        return response()->json($data, $status);
    }

    /**
     * Format Response Data
     *
     * @param int $code
     * @param string $msg
     * @param array $data
     * @return array
     */
    private function format($code, $msg, $data)
    {
        return [
            'Code' => $code,
            'Data' => $data,
            'Msg'  => $msg,
        ];
    }
}
