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
    public function jsonResponse(int $status, $code = 1, $msg = '', $data = []) : JsonResponse
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
    private function format(int $code, string $msg, array $data) : array
    {
        return [
            'Code' => $code,
            'Data' => $data,
            'Msg'  => $msg,
        ];
    }
}
