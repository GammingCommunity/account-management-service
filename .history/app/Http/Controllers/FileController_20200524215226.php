<?php

namespace App\Http\Controllers;

use App\Account;
use App\Helpers\JsonWebToken;
use App\AccountInfoEmail;
use App\AccountSetting;
use App\Chatting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileController extends Controller
{
	private const DIR = 'files/';

	public function index()
	{
		$fileDir = self::DIR;
		$fileNames = $this->readAllFileName();
		return view('file', compact('fileNames', 'fileDir'));
	}

	public function upload(Request $req)
	{
		if ($req->hasFile('uploadingFile')){
			$fileDir = self::DIR;
			$file = $req->uploadingFile;
			// Storage::disk('public')->put($req->uploadingFile, $req->uploadingFile);
			$file->move($fileDir, $file->getClientOriginalName());
			
			$fileNames = $this->readAllFileName();
			return view('file', compact('fileNames', 'fileDir'));
		}
	}

	protected function readAllFileName(): array
	{
		$files = [];

		foreach (glob(self::DIR . '*.*') as $file) {
			array_push($files, substr($file, strlen(self::DIR)));
		}

		return $files;
	}
}
