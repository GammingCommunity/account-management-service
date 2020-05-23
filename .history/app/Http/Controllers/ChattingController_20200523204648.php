<?php

namespace App\Http\Controllers;

use App\Account;
use App\Helpers\JsonWebToken;
use App\AccountInfoEmail;
use App\AccountSetting;
use App\Chatting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChattingController extends Controller
{
    public function index(){
		$chattings = Chatting::all();
		return view('chatting', compact('chattings'));
	}

    public function chat(Request $req){
		if($req->content){
			Chatting::create(['content' => $req->content]);
		}
	}
}
