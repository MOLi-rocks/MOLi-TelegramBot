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

    public function getUpdates()
    {
        $updates = Telegram::getUpdates();
        dd($updates);
    }
    
    public function getSendMessage()
    {
        return view('send-message');
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
            //return redirect()->back()
            //    ->with('status', 'danger')
            //    ->with('message', 'Message is required');
            $errors = $validator->errors()->all();
            return response()->json(["status" => "fail", "errors" => $errors], 400);
        }

        //Telegram::sendMessage('58915887', $request['message']);
        Telegram::sendMessage([
            'chat_id' => $request['chatid'],
            'text' => $request['message']
        ]);

        //return redirect()->back()
        //    ->with('status', 'success')
        //    ->with('message', 'Message sent');
        return response()->json(["status" => "success", "message" => $request['message']], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
