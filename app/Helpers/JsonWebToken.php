<?php

namespace App\Helpers;

use \Firebase\JWT\JWT;

class JsonWebToken
{
	private const SECRET_KEY = 'Ra7uH5pDFPgOZA3JITmZda0Erd9FpUb94vq9QDnLH1fM2P79em5l2Hio1xv7r93x5xEZmV1ncVW3jFBHpTguHQA8M82A6B3FeE4v8fg2O1oBjqCe4EUbb9142EGlN3Ow0uT6R1PzO3WRDZ770w09A9X0Z87XPNv7coGn84rnnV8dHI36a32D3u98wrz1c174hc90452qNvcFsoleRP6jc8hU2J0a0025tbR8Q0Mxl08LQR0SG3kfU0h492T2p9gk0C88mZ3c17jqK62d5dP0w1tYP8Ip0I0cnjcJSLAUadj3Mpjh85Z2qT6zTv09878CE5zBy5yUehjRx5yhgr65FWh9DV0d0HtjSu86P85TuyMFb9nrci0mwDddTnB1cR9WFUI9TPlb8KrdcwOJP9M037Hsn0ASnHxAhl0HnW38494j09409Dr05vU76rltvSpPK753TdE2ffg5V2h3ke129jEcgXEKIy5e606wzFolUyB975aJ9Tgk7L2xsXeT9e72';

	/**
	 * @param integer $accountId
	 * @return string token
	 */
	public static function encode(int $accountId): string
	{
		return JWT::encode(['id' => $accountId], self::SECRET_KEY, 'HS512');
	}

	/**
	 * @param string $token
	 * @return integer|null id account
	 */
	public static function decode(string $token): ?int
	{
		$result = null;

		try {
			$result = JWT::decode($token, self::SECRET_KEY, ['HS512'])->id;
		} catch (\Exception $ex) {
			sleep(2);
		}

		return $result;
	}
}
