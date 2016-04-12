<?php

namespace MOLiBot\Http\Controllers;

use Illuminate\Http\Request;

use MOLiBot\Http\Requests;
use MOLiBot\Http\Controllers\Controller;

use Telegram;
use Validator;

class TelegramController extends Controller
{

    protected $telegram;

    public function __construct( Telegram $telegram )
    {
        $this->telegram = $telegram;
    }

    public function postSendMessage(Request $request)
    {
        $rules = [
            'chatid' => 'required',
            'message' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails())
        {
            $errors = $validator->errors()->all();
            return response()->json(["status" => "fail", "errors" => $errors], 400);
        }

        Telegram::sendMessage([
            'chat_id' => $request['chatid'],
            'text' => $request['message']
        ]);

        return response()->json(["status" => "success", "message" => $request['message']], 200);
    }
}
