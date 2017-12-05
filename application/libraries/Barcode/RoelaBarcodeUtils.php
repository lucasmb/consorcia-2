<?php

class RoelaBarcodeUtils {

	static function create_codebar_font($string_code) {

		$code_numbers = str_split($string_code, 2);

		$returning_codebar_font = 'Ë';
		foreach ($code_numbers as $key => $number) {
			if ($number > 93) {
				$returning_codebar_font = $returning_codebar_font . chr(intval($number)+103);
			} else {
				$returning_codebar_font = $returning_codebar_font . chr(intval($number)+33);
			}
			
		}
		$returning_codebar_font = $returning_codebar_font . 'Ì';
		$returning_codebar_font = RoelaBarcodeUtils::replace_special_characters($returning_codebar_font);
		
		return $returning_codebar_font;
	}
	
	static function replace_special_characters_ascii($code) {

		$normalizeChars = array(
		    'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj','Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A',
		    'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
		    'Ï'=>'I', 'Ñ'=>'N', 'Ń'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U',
		    'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a',
		    'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i',
		    'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ń'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u',
		    'ú'=>'u', 'û'=>'u', 'ü'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'ƒ'=>'f',
		    'ă'=>'a', 'î'=>'i', 'â'=>'a', 'ș'=>'s', 'ț'=>'t', 'Ă'=>'A', 'Î'=>'I', 'Â'=>'A', 'Ș'=>'S', 'Ț'=>'T', "." => " " , 
		);

		return strtr($code, $normalizeChars);

	}

	static function replace_special_characters($code) {
		$code = str_replace(chr(60) , "&#60;" , $code);
		$code = str_replace(chr(61) , "&#61;" , $code);
		$code = str_replace(chr(62) , "&#62;" , $code);
		$code = str_replace(chr(63) , "&#63;" , $code);

		$code = str_replace(chr(197) , "&#197;" , $code);
		$code = str_replace(chr(198) , "&#198;" , $code);
		$code = str_replace(chr(199) , "&#199;" , $code);
		$code = str_replace(chr(200) , "&#200;" , $code);
		$code = str_replace(chr(201) , "&#201;" , $code);
		$code = str_replace(chr(202) , "&#202;" , $code);

		$code = str_replace(chr(209) , "&#209;" , $code);

		return $code;
	}

    static function checksum_int25($code) {
		
		$code_numbers = str_split($code, 1);
		$first_digit_checksum_numbers =  "135793579357935793579357935793579357935793579357935793";
		$second_digit_checksum_numbers = "1357935793579357935793579357935793579357935793579357935";

		$code = $code . RoelaBarcodeUtils::get_checksum_digit($code, $first_digit_checksum_numbers);
		$code = $code . RoelaBarcodeUtils::get_checksum_digit($code, $second_digit_checksum_numbers);

		return $code;
		
	}

	static function get_checksum_digit($code_string, $checksum_string) {

		$code_string_numbers = str_split($code_string, 1);
		$digit_checksum_string_numbers = str_split($checksum_string, 1);

		$total_digit = 0;
		for ($i = 0; $i <= (count($code_string_numbers) - 1); $i++) {
			$code_number = intval($code_string_numbers[$i]);
			$digit_checksum_number = intval($digit_checksum_string_numbers[$i]);

			$total_digit += $code_number * $digit_checksum_number;
		}	

		$checksum_digit = floor($total_digit / 2) % 10;
		return strval($checksum_digit);

	}

}
