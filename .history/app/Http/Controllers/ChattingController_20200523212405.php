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
		$chattings = $this->getAllChattings();
		return view('chatting', compact('chattings'));
	}
	
    public function chat(Request $req){
		date_default_timezone_set('Asia/Ho_Chi_Minh');
		
		if($req->content){
			Chatting::create(['content' => $req->content]);
		}

		$chattings = $this->getAllChattings();
		return view('chatting', compact('chattings'));
	}

	protected function getAllChattings(){
		return $chattings = Chatting::all();
	}
}
