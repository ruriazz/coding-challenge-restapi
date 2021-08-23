<?php defined('BASEPATH') OR exit('No direct script access allowed');

if(!class_exists('Generate')) {
    class Generate {
        public static function unique_id(String $type = null) {
            if($type == null)
                $type = IDType::Default;

            $today = date("Ymd");
            $id = uniqid("$type$today", false);
            return $id;
        }

    }

    class IDType {
        const Default = '00';
        const User = '11';
        const Payment = '20';
    }

}