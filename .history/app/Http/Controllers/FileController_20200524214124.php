<?php

namespace App\Http\Controllers;

use App\Account;
use App\Helpers\JsonWebToken;
use App\AccountInfoEmail;
use App\AccountSetting;
use App\Chatting;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile as HttpUploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileController extends Controller
{
	private const DIR = 'files/';

	public function index()
	{
		$fileDir = self::DIR;
		$fileNames = $this->readAllFileName();
		return view('file', compact('fileNames', 'fileDir'));
	}

	public function upload(HttpUploadedFile $file)
	{
		// if ($req->uploadingFile){
		// 	Storage::disk('public')->put($req->uploadingFile, $req->uploadingFile);
			
		// 	$fileDir = self::DIR;
		// 	$fileNames = $this->readAllFileName();
		// 	return view('file', compact('fileNames', 'fileDir'));
		// }
		return print_r($file, true);
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
