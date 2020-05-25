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
		$fileNames = $this->readAllFileName();
		// return view('file', compact('files'));
	}

	protected function readAllFileName(): array
	{
		$files = [];

		foreach (glob($this->DIR . '*.*') as $file) {
			array_push($files, $file);
		}

		return $files;
	}
}
