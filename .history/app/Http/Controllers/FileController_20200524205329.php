<?php

namespace App\Http\Controllers;

use App\Account;
use App\Helpers\JsonWebToken;
use App\AccountInfoEmail;
use App\AccountSetting;
use App\Chatting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FileController extends Controller
{
    public function index(){
		$chattings = $this->getAllChattings();
		return view('chatting', compact('chattings'));
	}
}
