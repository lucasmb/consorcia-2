<?php

class RoelaFileUtils {

	static function create_validation_text_file($properties) {

		$data = "";
		
		foreach ($properties as $property) {

			$data .= $property->get_current_payment_code();
			$data .= "\r\n";

		}

		return $data;

	}
	
	static function create_pago_mis_cuentas_text_file($properties) {

		$data = RoelaFileUtils::create_pago_mis_cuentas_header();
		$data .= RoelaFileUtils::create_pago_mis_cuentas_detail($properties);
		$data .= RoelaFileUtils::create_pago_mis_cuentas_footer($properties);

		return $data;

	}

    static private function create_pago_mis_cuentas_header() {
		
		$now_time = strtotime("now");
		$string_date = date("Ymd", $now_time);
    	$data = "04000000".$string_date.str_repeat("0", 264);
    	$data .= "\r\n";

    	return $data;

	}

	static private function create_pago_mis_cuentas_detail($properties) {

		$data = "";
		foreach ($properties as $property) {

			$data .= $property->get_current_payment_digital_file_row();
			$data .= "\r\n";

			if ($property->has_extraordinary_period_active()) {
				$data .= $property->get_current_extraordinary_payment_digital_file_row();
				$data .= "\r\n";				
			}

		}

		return $data;
		
	}

	static private function get_total_ammount($properties) {

		$sum = 0;
		
		foreach ($properties as $property) {
			$to_pay = round($property->total_to_pay(),2);

			if ($property->has_extraordinary_period_active()) {
			 	$to_pay = $to_pay + round($property->total_to_pay_all_extraordinaries(),2);
			}

			$sum = $sum + $to_pay;
		}

		return $sum;

	}

	static private function get_number_of_rows($properties) {

		$count = 0;
		
		foreach ($properties as $property) {

			$count++;
			if ($property->has_extraordinary_period_active()) {
			 	$count++;
			}

		}

		return $count;

	}
	
	static private function create_pago_mis_cuentas_footer($properties) {

		$now_time = strtotime("now");
		$string_date = date("Ymd", $now_time);
		$number_of_detail_rows = sprintf('%07d', RoelaFileUtils::get_number_of_rows($properties));
		$sum = sprintf('%011d', number_format(RoelaFileUtils::get_total_ammount($properties), 2, '', ''));

		$data = "94000000". $string_date . $number_of_detail_rows . "0000000" . $sum . str_repeat("0", 239);
		
		return $data;

	}

}
