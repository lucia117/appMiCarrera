<?php
header('Content-Type: text/html; charset=UTF-8');

require_once 'vendor/autoload.php';

use \Firebase\JWT\JWT;

class JWToken {

	public static $SECRET_KEY = '$djY()HMzx?#_29Qsd';
	public static $ALGORITHM = 'HS512';

	public static function crearToken($usuario) {

		try {
			$tokenId = base64_encode(openssl_random_pseudo_bytes(32));
			$issuedAt = time();
			$notBefore = $issuedAt + 10;	//Adding 10 seconds
			$expire = $notBefore + 7200; // Adding 60 seconds
			$serverName = 'http://www.nexoserver.com.ar';
			/*
			 * Create the token as an array
			 */
			$data = [
					'iat' => $issuedAt, // Issued at: time when the token was generated
					'jti' => $tokenId, // Json Token Id: an unique identifier for the token
					'iss' => $serverName, // Issuer
					'nbf' => $notBefore, // Not before
					'exp' => $expire, // Expire
					'data' => [// Data related to the logged user you can set your required data
							'usuario' => $usuario['usuario'] // id from the users table
					]
			];
			$secretKey = base64_decode(static::$SECRET_KEY);
			/// Here we will transform this array into JWT:
			$jwt = JWT::encode(
							$data, //Data to be encoded in the JWT
							$secretKey, // The signing key
							static::$ALGORITHM
			);
			return $jwt;
		} catch (Exception $ex) {
			throw new Exception($ex->getMessage());
		}
	}

	public static function getRequestHeaders() {
		$headers = array();
		foreach ($_SERVER as $key => $value) {
			//if (substr($key, 0, 5) <> 'HTTP_') {
			//  continue;
			//}
			$header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
			$headers[$header] = $value;
		}
		return $headers;
	}

	public static function obtenerToken() {
		// $headers = static::getRequestHeaders();
		$headers = apache_request_headers();

		//foreach (getallheaders() as $name => $value) {
		foreach ($headers as $name => $value) {
			switch ($name) {
				case 'authorization':
					$authorization = $value;
					break;
				case 'Authorization':
					$authorization = $value;
					break;
				default:
					break;
			}
		}

		$token = substr($authorization, 7);

		try {
			$secretKey = base64_decode(static::$SECRET_KEY);
			$DecodedDataArray = JWT::decode($token, $secretKey, array(static::$ALGORITHM));
			return $DecodedDataArray;
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}
}
