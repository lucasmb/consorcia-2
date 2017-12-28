<?php

function vd($value, $options = array()) {
    if(ENVIRONMENT == 'production')
        return FALSE;
    $default = array('message' => '');
    $options = array_merge($default, $options);

    var_dump($value);
    die($options['message']);
}

function ed($value, $options = array()) {
    $default = array('message' => '');
    $options = array_merge($default, $options);

    echo($value);
    die($options['message']);
}


//----- andy -----


function url_css($css){
    return base_url().BASE_CSS.$css;
}

function url_js($js){
    return base_url().BASE_JS.$js;
}

function url_img($img){
    return base_url().BASE_IMG.$img;
}

function url_view($uri){
    return base_url().$uri;
}

function get_month_dates_from_date($date)
{
    
    $month = intval($date->format('n'));
    $year = intval($date->format('Y'));
    $num = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    
    $date_format = 'Y-m-d';

    $dates = array();
    for ($i = 0; $i < $num; $i++)
    {
        $date = DateTime::createFromFormat($date_format, $year . "-" . $month . "-" . ($i + 1));
        $dates[] = $date;
    }

    return $dates;

}

function get_select_payment_type($set_value = null){
    
    $payment_types = array();
    $payment_types[] = create_object(BUILDING_PAYMENT_TYPE_MANUAL, "Cobro manual");
    $payment_types[] = create_object(BUILDING_PAYMENT_TYPE_BANK_BARCODE, "Cobro bancos, Pago Facil y Rapipago");
    $payment_types[] = create_object(BUILDING_PAYMENT_TYPE_BANK_DIGITAL, "Cobro Pago Mis Cuentas y Link Pagos");
    $payment_types[] = create_object(BUILDING_PAYMENT_TYPE_BANK, "Cobro bancos, Pago Facil, Rapipago, Pago Mis Cuentas y Link pagos");
    return html_options($payment_types, $set_value);
}

function create_object($id, $name) {
    $obj = new stdClass;
    $obj->id = $id;
    $obj->name = $name;
    return $obj;
}

function get_select_type_expense_period($set_value = null){

    $result = TypeExpensePeriod::all();    
    return html_options($result,$set_value);
}

function get_select_building($set_value = null){
    $options = array('is_active' => 1);
    $result = Building::all($options);
    return html_options($result,$set_value);
}

function get_select_building_bank_payment($set_value = null){
    $result = Building::find('all', array('conditions' => 'payment_type <> '.BUILDING_PAYMENT_TYPE_MANUAL.' AND is_active = 1'));
    return html_options($result,$set_value);
}

function get_select_zones($set_value = null){
    $result = Zone::all();
    return html_options($result,$set_value);
}

function get_select_cities($zone_id ,$set_value = null){
    $result = City::all(array('conditions' => "zone_id = '$zone_id'"));
    return html_options($result,$set_value);
}

function get_select_extraordinary_periods($building_id, $set_value = null, $state = null){
    $conditions = array( 'conditions' => "building_id = '$building_id' ");
    if ($state != null) {
        $conditions['conditions'] = $conditions['conditions'] . " AND state = '$state' ";
    }
    $result = ExtraordinaryPeriod::all($conditions);    
    return html_options($result , $set_value);
}

function get_select_type_document($set_value = null){
    $result = TypeDocument::all();
    return html_options($result,$set_value);
}

function get_type_aditional_income($set_value = null){
    $result = TypeAditionalIncome::all();
    return html_options($result,$set_value);
}

function get_type_expense($set_value = null){
    $result = TypeExpense::all();
    return html_options($result,$set_value);
}

function get_type_special_expense($set_value = null){
    $result = TypeSpecialExpense::all();
    return html_options($result,$set_value);
}

function get_select_owners($set_value = null){
    $result = Person::all(array('conditions' => "type = 1"));
    return html_options_owners($result,$set_value);
}

function get_select_property_auxiliary($building_id,$set_value = null){
    $result = Property::all(array('conditions' => "building_id = $building_id"));
    return html_options_properties($result,$set_value);
}

function get_select_building_periods($building_id){
    $result = BuildingTransaction::all(array('conditions' => "building_id = $building_id"));
    return html_building_periods($result,$set_value = null);
}

function get_select_balance_income_tags($building_id,$set_value = null){
    $result = IncomeBalanceTag::all(array('conditions' => "building_id = $building_id"));
    return html_options($result,$set_value);
}

function get_select_balance_expense_tags($building_id,$set_value = null){
    $result = ExpenseBalanceTag::all(array('conditions' => "building_id = $building_id"));
    return html_options($result,$set_value);
}

function get_multiselect_income_tags($building_id,$balance_tag_id){
    $result = IncomeTag::all(array('conditions' => array("income_tag_id = $balance_tag_id OR income_tag_id IS NULL"), 'order' => 'name asc'));
    return html_options_income_tags($result,$balance_tag_id);
}

function get_multiselect_expense_tags($building_id,$balance_tag_id){
    $result = ExpenseTag::all(array('conditions' => array("building_id = $building_id AND (expense_tag_id = $balance_tag_id OR expense_tag_id IS NULL)"), 'order' => 'name asc'));
    return html_options_expense_tags($result,$balance_tag_id);
}

function html_options($result,$set_value = null){

    if ($set_value == null){
        $options = "<option value='' selected='selected' >--Seleccionar--</option>";
        foreach ($result as $field) 
            $options .= "<option value='".$field->id."'>".$field->name."</option>";
    }
    else{
        $options = "<option value=''>--Seleccionar--</option>";
        foreach ($result as $field) {
            if ($set_value == $field->id)
                $options .= "<option value='".$field->id."' selected='selected' >".$field->name."</option>";
            else
                $options .= "<option value='".$field->id."'>".$field->name."</option>";
        }
    }
            
  
    return $options;
}

function html_tag_options($result,$set_value = null){

    $options = "<option value=''>--Seleccionar--</option>";
    foreach ($result as $field) {
        if ($field->bulding_id != null)
            $options .= "<option value='".$field->id."' selected='selected' >".$field->name."</option>";
        else
            $options .= "<option value='".$field->id."'>".$field->name."</option>";
    }
    
    return $options;
}

function html_options_income_tags($result,$balance_tag_id){

    $options = "";
    foreach ($result as $field) {
        if ($field->income_tag_id == $balance_tag_id)
            $options .= "<option value='".$field->id."' selected='selected' >".$field->name."</option>";
        else
            $options .= "<option value='".$field->id."'>".$field->name."</option>";
    }
    
    return $options;
}

function html_options_expense_tags($result,$balance_tag_id){

    $options = "";
    foreach ($result as $field) {
        if ($field->expense_tag_id == $balance_tag_id)
            $options .= "<option value='".$field->id."' selected='selected' >".$field->name."</option>";
        else
            $options .= "<option value='".$field->id."'>".$field->name."</option>";
    }
    
    return $options;
}


function html_options_owners($result,$set_value = null){

    if ($set_value == null){
        $options = "<option value='' selected='selected' >--Seleccionar--</option>";
        foreach ($result as $field) 
            $options .= "<option value='".$field->id."'>".$field->name.' '.$field->lastname."</option>";
    }
    else{
        $options = "<option value=''>--Seleccionar--</option>";
        foreach ($result as $field) {
            if ($set_value == $field->id)
                $options .= "<option value='".$field->id."' selected='selected' >".$field->name.' '.$field->lastname."</option>";
            else
                $options .= "<option value='".$field->id."'>".$field->name.' '.$field->lastname."</option>";
        }
    }
            
  
    return $options;
}

function html_building_periods($result,$set_value = null){

    if ($set_value == null){
        $options = "<option value='' selected='selected' >--Seleccionar--</option>";
        foreach ($result as $field) {
            $options .= "<option value='".$field->period_date->format("Y-m-d")."'>".$field->period_date->format("Y-m-d")."</option>";
        }
    }
    else{
        $options = "<option value='' selected='selected' >--Seleccionar--</option>";
        foreach ($result as $field) {
            $options .= "<option value='".$field->period_date->format("Y-m-d")."'>".$field->period_date->format("Y-m-d")."</option>";
        }
    }
         
    return $options;
}

function html_options_properties($result,$set_value = null){

    if ($set_value == null){
        $options = "<option value='' selected='selected' >--Seleccionar--</option>";
        foreach ($result as $field) 
            $options .= "<option value='".$field->id."'>Piso: ".$field->floor.' - Depto: '.$field->appartment."</option>";
    }
    else{
        $options = "<option value=''>--Seleccionar--</option>";
        foreach ($result as $field) {
            if ($set_value == $field->id)
                $options .= "<option value='".$field->id."' selected='selected' >Piso: ".$field->floor.' - Depto: '.$field->appartment."</option>";
            else
                $options .= "<option value='".$field->id."'>Piso: ".$field->floor.' - Depto: '.$field->appartment."</option>";
        }
    }
            
  
    return $options;
}

function week_day_name($date){
    $dias_sem = array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");
    $arraydate = explode ("-", $date);
    $dateunix= mktime(0,0,0,$arraydate[1],$arraydate[2],$arraydate[0]);
    $dia=date("w", $dateunix); 
    return $dias_sem[$dia];
}

function escape($value) {
    $return = '';
    for($i = 0; $i < strlen($value); ++$i) {
        $char = $value[$i];
        $ord = ord($char);
        if($char !== "'" && $char !== "\"" && $char !== '\\' && $ord >= 32 && $ord <= 126)
            $return .= $char;
        else
            $return .= '\\x' . dechex($ord);
    }
    return $return;
}


