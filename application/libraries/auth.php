<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use Firebase\JWT\JWT;
require_once(APPPATH.'libraries/jwt/src/JWT.php');

class Auth 
{
	public function getSecretKey(){
		$secretKey = 'c4r.dev';
		return $secretKey;
	}

	public function validToken($data){
        $time = time(); //Fecha y hora actual en segundos
        $key = $this->getSecretKey();
		$payload = [
			'iat' => $time,
			'exp' => $time + (18000),
			'data' => $data
		];
		$token = JWT::encode($payload, $key);//Codificamos el Token
        return $token;
	}

	public function verifyToken($token){       
		$key = $this->getSecretKey();
        try{
        	return JWT::decode($token, $key, array('HS256'));
		}catch(Exception $e){
			return false;
		}
    }
}
