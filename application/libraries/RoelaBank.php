<?php
include_once APPPATH.'libraries/Barcode/RoelaBarcodeUtils.php';
include_once APPPATH.'libraries/Barcode/RoelaFileUtils.php';

#-------------------- CODEBAR NUMBER EXAMPLE --------------------#
#                                                                #
# 0447 1 12345678 1304150149080160151015180161480 1234567890 8 8 #
#                                                                #
#----------------------------------------------------------------#
# 0447 - Fixed otorgado por SIRO
# 1 - TYPE OF BILL PAYMENT
# 12345678 - Identifier of functional unit (UF)
# 130415 0149080 16 0151015 18 0161480 - Dates and Values to Pay (for open cases uses all 0)
# 1234567890 - Number of account 
# 8 - First checksum number
# 8 - Second checksum number

#------------------- DIGITAL ROW CODE EXAMPLE -------------------#
# 5 0 12345678 1234567890 XXXXXXXXXXXXXXX 2 1014 0 20141115 00000001000 20141120 00000001200 20141130 00000001400 0000000000000000000 0 12345678 1234567890 |SIRO TEST                             | |SIRO TEST    | |                                                          | 00000000000000000000000000000 #
#                                                                #
#----------------------------------------------------------------#
# 5 - Fixed otorgado por SIRO (Pago Mis Cuentas)
# 0 - Filler
# 12345678 - Identifier of functional unit (UF)
# 1234567890 - Number of account
# XXXXXXXXXXXXXXX - Bill number 
# 2 - TYPE OF BILL PAYMENT
# 1014 - Month and Year MMYY 
# 0 - Fixed otorgado por SIRO (Pago Mis Cuentas)
# 20141115 - First Payment Date 
# 00000001000 - First Payment ammount
# 20141120 - Second Payment Date 
# 00000001200 - Second Payment ammount
# 20141130 - Third Payment Date 
# 00000001400 - Third Payment ammount
# 0000000000000000000 - Fixed all 0 otorgado por SIRO (Pago Mis Cuentas)
# 0 - Repeated Filler 
# 12345678 - Repeated Field
# 1234567890 - Repeated Field
# |SIRO TEST                             | - Ticket Message (40 chars)
# |SIRO TEST    | - Screen Message - repeat first 15 chars of Ticket Message
# |                                                          | - Space filler 60 chars
# 00000000000000000000000000000 - All 0 filler 29 chars


#------------------------ Archivo de pagos ----------------------#
# 20141110 20141113 20141107 0061504 09501714 0 04440095017141411070060655070061504000000000515000745627 00000000000000000000 RP  0000000000000000000000000000000000000000
#                                                                #
#----------------------------------------------------------------#
# 20141110 - Fecha en la que el cliente efectuo el pago.
# 20141113 - Fecha de acreditación del pago en cuenta corriente de Banco Roela.
# 20141107 - Surge del Primer Vencimiento del código de barra.
# 0061504 - Importe pagado: Ej: Si el monto fue de $650,04, se informa 0061504
# 09501714 - Surge del Identificador de Usuario del codigo de barra. En Link Pagos y Pago Mis Cuentas, surge de tomar las posiciones 2o a 9o del Identificador de Usuario
# 0 - Surge del Identificador de Conceptodel codigo de barra.
# 04440095017141411070060655070061504000000000515000745627 - Es el codigo de barra completo que dio origen al pago que se esta rindiendo.
# 00000000000000000000 - Solo para las recaudaciones a traves de Link Pagos y Pago mis Cuentas
# RP  - PF(Pago Facil); RP (Rapipago); PP (provincia Pagos); CJ (cajeros); LK(Link Pagos); PC(Pago Mis Cuentas).
# 0000000000000000000000000000000000000000 - Completar con espacios en blanco.
#

// ROELA CODEBAR CONSTANTS //
define("RB_SIRO_CODE", "0447");

// ROELA DIGITAL ROW CONSTANTS //
define("RB_SIRO_FILE_DETAIL_INITIAL_CODE", "5");
define("RB_SIRO_FILE_DETAIL_NO_BILL_NUMBER", "XXXXXXXXXXXXXXX");

// TYPE OF BILL PAYMENT // 
define("RB_ORDINARY_EXPENSE", "0");
define("RB_EXTRAORDINARY_EXPENSE", "1");

define("RB_ORDINARY_EXPENSE_OPEN", "8");
define("RB_EXTRAORDINARY_EXPENSE_OPEN", "9");

// ROELA CONSTANTS FILLERS //
define("RB_FILLER_ZEROS_1", "0");
define("RB_FILLER_ZEROS_18", "000000000000000000");
define("RB_FILLER_ZEROS_19", "0000000000000000000");
define("RB_FILLER_ZEROS_31", "0000000000000000000000000000000");
define("RB_FILLER_ZEROS_29", "00000000000000000000000000000");
define("RB_FILLER_WHITESPACE", " ");

// ROELA EXCEL CONSTANTS //
define("RB_PHPEXCEL_DATE_COLUMN", "B");
define("RB_PHPEXCEL_ACCOUNT_COLUMN", "C");
define("RB_PHPEXCEL_CONCEPT_ID_COLUMN", "D");
define("RB_PHPEXCEL_CONCEPT_DESCRIPTION_COLUMN", "E");
define("RB_PHPEXCEL_AMMOUNT_COLUMN", "F");

// ROELA FUNCTIONS

// DATE FUNCTIONS
function strtodate($string , $format = "Ymd") {

	switch ($format) {
		case 'Ymd':
			return date('Y-m-d', strtotime(substr($string, 0, 4)."-".substr($string, 4, 2)."-".substr($string, 6, 2)));
			break;

		case 'dmY':
			return date('Y-m-d', strtotime(substr($string, 4, 4)."-".substr($string, 2, 2)."-".substr($string, 0, 2)));
			break;
		
		default:
			return date('Y-m-d');
			break;
	}

}

function strtotimestamp($string , $format = "Ymd") {

	switch ($format) {
		case 'Ymd':
			return date('Y-m-d H:i:s', strtotime(substr($string, 0, 4)."-".substr($string, 4, 2)."-".substr($string, 6, 2)));
			break;

		case 'dmY':
			return date('Y-m-d H:i:s', strtotime(substr($string, 4, 4)."-".substr($string, 2, 2)."-".substr($string, 0, 2)));
			break;
		
		default:
			return date('Y-m-d H:i:s');
			break;
	}

}

function date_to_monthname($date) {

	$month_number = (int)$date->format('n') - 1;
	$month_names = ["ENE", "FEB", "MAR", "ABR", "MAY", "JUN", "JUL", "AGO", "SEP", "OCT", "NOV", "DIC"];

	return $month_names[$month_number];

}

// PAYMENT TYPE FUNCTIONS
function is_extraordinary_operation($operation_type) {
	switch ($operation_type) {
		case RB_ORDINARY_EXPENSE:
		case RB_ORDINARY_EXPENSE_OPEN:
			return false;
			break;
		case RB_EXTRAORDINARY_EXPENSE:
		case RB_EXTRAORDINARY_EXPENSE_OPEN:
			return true;
			break;
		default:
			break;
	}
}

function payment_type($bill_payment_type) {

	switch ($bill_payment_type) {
		case RB_ORDINARY_EXPENSE:
			return "Exp. Ordinaria";
			break;
		case RB_EXTRAORDINARY_EXPENSE:
			return "Exp. Extraordinaria";
			break;
		case RB_ORDINARY_EXPENSE_OPEN:
			return "Exp. Ordinaria abierta";
			break;
		case RB_EXTRAORDINARY_EXPENSE_OPEN:
			return "Exp. Extraordinaria abierta";
			break;
		default:
			return "Expensa indeterminada";
			break;
	}

}
