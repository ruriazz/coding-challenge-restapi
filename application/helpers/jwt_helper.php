<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Firebase\JWT\JWT;

if(!function_exists('to_user_model')) {
    function to_user_model(array $userModel) {
        $ci =& get_instance();
        $ci->load->model('User_model');
        $userModel = new User_model($userModel);
        return $userModel;
    }
}

if(!function_exists('decode_jwt')) {
    function decode_jwt(String $token) {
        return JsonWebToken::decode($token);
    }
}

if(!function_exists('encode_jwt')) {
    function encode_jwt(String $uid) {
        return JsonWebToken::encode($uid);
    }
}

if(!class_exists('JsonWebToken')) {
    class JsonWebToken {

        private $priv_key;
        private $pub_key;

        function __construct() {
            $this->priv_key = openssl_pkey_get_private(
                file_get_contents(APPPATH . '/config/keys/rsa.pem'),
                'cT2Az0bCxT42d0KRiSJnObHLNPuj'
            );
            $this->pub_key = openssl_pkey_get_details($this->priv_key)['key'];
        }

        public static function encode(String $uid) {
            $jwt = new JsonWebToken();
            $payload = new TokenPayload($uid);
            
            return JWT::encode($payload, $jwt->priv_key, 'RS256');
        }

        public static function decode(String $token) {
            $jwt = new JsonWebToken();

            try {
                return JWT::decode($token, $jwt->pub_key, array('RS256'));
            } catch (Exception $e) {}

            return false;
        }

    }

    class TokenPayload {
        public String $iss;
        public String $aud;
        public String $uid;
        public int $iat;

        function __construct(String $uid) {
            $this->iss = "https://aziz.warkopwarawiri.id";
            $this->aud = "https://api.aziz.warkopwarawiri.id";
            $this->uid = $uid;
            $this->iat = time();
        }
    }
}