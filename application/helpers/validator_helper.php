<?php defined('BASEPATH') OR exit('No direct script access allowed');

if(!class_exists('Valid')) {
	class Valid {
		
		public static function email(String $email) {
			if(!is_string($email) || $email == null || strlen($email) < 10) return false;

			return filter_var($email, FILTER_VALIDATE_EMAIL);	
		}

	}
}