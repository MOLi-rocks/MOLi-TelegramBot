<?php

namespace MOLiBot\Http\Controllers;

use Illuminate\Http\Request;

use MOLiBot\Http\Requests;
use MOLiBot\Http\Controllers\Controller;

class MOLiBotController extends Controller
{
    /**
     * 回應對 GET / 的請求
     */
    public function getIndex()
    {
        return redirect('https://moli.rocks');
    }
}
