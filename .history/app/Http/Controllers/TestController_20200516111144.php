<?php

namespace App\Http\Controllers;

use App\Account;
use App\Helpers\JsonWebToken;
use App\AccountInfoEmail;
use App\AccountSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TestController extends Controller
{
    public function index(){
		$x = '0005470230';
		echo trim($x, '0');
			
	}

	protected function setAccountEmails(){
		$accounts = Account::all();

		foreach ($accounts as $acc) {
			$acc->email = $this->getRandomEmail();
			$acc->save();
		}
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

	protected function getRandomEmail(){
		$list = [
			'ca-tech@dps.centrin.net.id',
			'trinanda_lestyowati@telkomsel.co.id',
			'asst_dos@astonrasuna.com',
			'amartabali@dps.centrin.net.id',
			'achatv@cbn.net.id',
			'bali@tuguhotels.com',
			'baliminimalist@yahoo.com',
			'bliss@thebale.com',
			'adhidharma@denpasar.wasantara.net.id',
			'centralreservation@ramayanahotel.com',
			'apribadi@balimandira.com',
			'cdagenhart@ifc.org',
			'dana_supriyanto@interconti.com',
			'dos@novotelbali.com',
			'daniel@hotelpadma.com',
			'daniel@balibless.com',
			'djoko_p@jayakartahotelsresorts.com',
			'expdepot@indosat.net.id',
			'feby.adamsyah@idn.xerox.com',
			'christian_rizal@interconti.com',
			'singgih93@mailcity.com',
			'idonk_gebhoy@yahoo.com',
			'info@houseofbali.com',
			'kyohana@toureast.net',
			'sales@nusaduahotel.com',
			'jayakarta@mataram.wasantara.net.id',
			'mapindo@indo.net.id',
			'sm@ramayanahotel.com',
			'anekabeach@dps.centrin.net.id',
			'yogya@jayakartahotelsresorts.com',
			'garudawisatajaya@indo.net.id',
			'ketut@kbatur.com',
			'bondps@bonansatours.com',
			'witamgr@dps.centrin.net.id',
			'dtedja@indosat.net.id',
			'info@stpbali.ac.id',
			'baliprestigeho@dps.centrin.net.id',
			'pamilu@mas-travel.com',
			'amandabl@indosat.net.id',
			'marketing@csdwholiday.com',
			'luha89@yahoo.com',
			'indahsuluh2002@yahoo.com.sg',
			'imz1991@yahoo.com',
			'gus_war81@yahoo.com',
			'kf034@indosat.net.id',
			'800produkwil@posindonesia.co.id',
			'kontak.synergi@yahoo.com',
			'oekaoeka@yahoo.com',
			'fitrianti@hotmail.com',
			'meylina310@yahoo.com',
			'h4ntoro@yahoo.com',
			'novi_enbe@yahoo.com',
			'dila_dewata@yahoo.co.id',
			'tiena_asfary@yahoo.co.id',
			'da_lawoffice@yahoo.com',
			'rini@ncsecurities.biz',
			'sudarnoto_hakim@yahoo.com',
			'wastioke@yahoo.com',
			'leebahri@yahoo.com.',
			'lia_kiara97@yahoo.com',
			'rido@weddingku.com',
			'b_astuti@telkomsel.co.id',
			'garudawisata@indo.net.id',
			'grfurniture@yahoo.com',
			'gosyen2000@hotmail.com',
			'hvhfood@indosat.net.id',
		];

		return $list[rand(0, count($list))];
	}
}
