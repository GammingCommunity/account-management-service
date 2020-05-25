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
	private const DIR = 'file/';

	public function index()
	{
		$fileDir = self::DIR;
		$fileNames = $this->readAllFileName();
		return view('file', compact('fileNames', 'fileDir'));
	}

	public function upload(Request $req)
	{
		if ($req->uploadingFile){
			return print_r($req->uploadingFile, true);
		}
		
		// $fileDir = self::DIR;
		// $fileNames = $this->readAllFileName();
		// return view('file', compact('fileNames', 'fileDir'));
	}

	protected function readAllFileName(): array
	{
		$files = [];

		foreach (glob(self::DIR . '*.*') as $file) {
			array_push($files, $file);
		}

		return $files;
	}
}
