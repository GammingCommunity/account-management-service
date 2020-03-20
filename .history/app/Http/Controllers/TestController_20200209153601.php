<?php

namespace App\Http\Controllers;

use App\Account;
use App\Helpers\JsonWebToken;
use App\AccountInfoEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TestController extends Controller
{
    public function index(){
		// $result = 'hello world';

		// $acc = Account::find(1);
		// $acc->email;
		// $acc->phone;
		// $acc->setting;
		// $acc->position;
		// $acc->birthYear;
		// $result = $acc;
		// // $email = AccountInfoEmail::find(1);
		// // $email->privacyType;
		// // $result = $email;

		// // $result = dechex(100000000000000000000000000000000);

		// $result = $this->genarate();

		// $result = JsonWebToken::decode('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpZCI6MTk5OCwidXNlcm5hbWUiOiJob2FuZyBjb24gaGVvIn0.Z05QSUJ8eHHw1DNWlFpOjvSUs32DglZnwHZ8I-ok2abUUEIk1E6kb-Zuj5ADdSoG2Qa2wTTyLvGdkrYoTMTaCw');
		if(isset($awdawdawd)){
			$result = 'true';
			
		}else{
			$result = 'false';

		}

		dd($result);
	}

	
	private function genarate(): string{
		$result = '';

		$writer = [1, 8, 9, 10];
		$cs = [142, 145, 147];
		$oc = [23, 24, 25];
		$qc = [60, 61, 62, 63, 65, 40, 41, 42, 43, 116, 117, 64, 126];

		for ($i=1; $i < 321; $i++) {
			$account_id = $writer[rand(0, count($writer) - 1)];
			$result .= "insert into detail_confirmations (order_id, account_id) values('{$i}', '$account_id');\n";
			
			$account_id = $cs[rand(0, count($cs) - 1)];
			$result .= "insert into detail_confirmations (order_id, account_id) values('{$i}', '$account_id');\n";

			$account_id = $oc[rand(0, count($oc) - 1)];
			$result .= "insert into detail_confirmations (order_id, account_id) values('{$i}', '$account_id');\n";

			$account_id = $qc[rand(0, count($qc) - 1)];
			$result .= "insert into detail_confirmations (order_id, account_id) values('{$i}', '$account_id');\n";
		}

		return $result;
	}

	public function post(Request $req){
		return $req->base64String;
		$data = base64_decode($req->base64String, true);
		$myfile = fopen("D:/file.png", "w");
		fwrite($myfile, $data);
		fclose($myfile);
	}

	public function file(){
		return response()->download('../resources/files/TOPI-SUNSET-IMG_1866.jpg');
	}
}
