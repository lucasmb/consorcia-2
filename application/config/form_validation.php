<?php
$config = array(                 
    
'add_building' => array(
array('field' => 'zone','label' => 'Provincia','rules' => 'required'),
array('field' => 'city','label' => 'Ciudad','rules' => 'required'),
array('field' => 'name','label' => 'Nombre','rules' => 'required|trim|strip_tags'),
array('field' => 'street','label' => 'Calle','rules' => 'required|trim|strip_tags'),
array('field' => 'number','label' => 'Numero de calle','rules' => 'required|trim|strip_tags'),
array('field' => 'total_coefficient','label' => 'Suma del coeficiente','rules' => 'required|numeric'),
array('field' => 'balance','label' => 'Saldo inicial','rules' => 'required|numeric|trim|strip_tags'),
array('field' => 'balance_extraordinary','label' => 'Saldo inicial Extraodinarias','rules' => 'required|numeric|trim|strip_tags'),
array('field' => 'earning_monthly','label' => 'Expensas Mensuales','rules' => 'required|trim|strip_tags'),
array('field' => 'type_expense_period','label' => 'Tipo cobro expensas','rules' => 'required|is_natural_no_zero'),
array('field' => 'payment_type','label' => 'Forma cobro expensas','rules' => 'required'),
array('field' => 'tax_percentage','label' => 'Porcentaje interes al proximo vencimiento','rules' => 'required|trim|strip_tags'),
array('field' => 'tax_late_percentage','label' => 'Porcentaje interes a expensas atrasadas','rules' => 'required|trim|strip_tags'),
array('field' => 'has_reserve_fund','label' => 'Fondo de reserva','rules' => 'trim|strip_tags'),
array('field' => 'reserve_fund','label' => 'Fondo de reserva inicial','rules' => 'trim|strip_tags'),
array('field' => 'reserve_percentage','label' => 'Porcentaje interes fondo reserva','rules' => 'trim|strip_tags'),
array('field' => 'earn_static_fund','label' => 'Monto de fondo a recaudar fijo'),
array('field' => 'earn_static_fund_value','label' => 'Monto a recaudar del fondo de reserva','rules' => 'trim|strip_tags')
    ),

'save_property_new_owner' => array(
array('field' => 'floor','label' => 'Piso de Propiedad','rules' => 'required|trim|strip_tags'),
array('field' => 'appartment','label' => 'Departamento de Propiedad','rules' => 'trim|strip_tags'),
array('field' => 'coefficient','label' => 'Coeficiente','rules' => 'required|trim|strip_tags'),
array('field' => 'functional_unity','label' => 'Unidad Funcional','rules' => 'required|trim|strip_tags'),
array('field' => 'meters','label' => 'Metros','rules' => 'numeric|trim|strip_tags'),
array('field' => 'static_reserve_value','label' => 'Valor Fondo Reserva','rules' => 'numeric|trim|strip_tags'),
array('field' => 'balance', 'label' => 'Balance inicial', 'rules' => 'numeric|trim|strip_tags'),
array('field' => 'balance_reserve','label' => 'Saldo Fondo Reserva','rules' => 'numeric|trim|strip_tags'),
array('field' => 'balance_extraordinary','label' => 'Saldo Extraordinary','rules' => 'numeric|trim|strip_tags'),
array('field' => 'lastname','label' => 'Apellido','rules' => 'required|trim|strip_tags')/*,
array('field' => 'name','label' => 'Nombre','rules' => 'required'),
array('field' => 'street','label' => 'Calle','rules' => 'required'),
array('field' => 'number_street','label' => 'Numero de calle','rules' => 'required'),
array('field' => 'phone','label' => 'Telefono','rules' => 'required'),
array('field' => 'alternative_phone','label' => 'Tel. Alternativo'),   
array('field' => 'type_document','label' => 'Tipo Doc.','rules' => 'required'),   
array('field' => 'document','label' => 'N. Documento','rules' => 'required'),   
array('field' => 'cuit','label' => 'cuit'),   
array('field' => 'floor_owner','label' => 'Piso'),
array('field' => 'appartment_owner','label' => 'Departamento')*/

    ),    

'save_property_with_owner' => array(
array('field' => 'floor','label' => 'Piso de Propiedad','rules' => 'required|trim|strip_tags'),
array('field' => 'appartment','label' => 'Departamento de Propiedad','rules' => 'required|trim|strip_tags'),
array('field' => 'coefficient','label' => 'Coeficiente','rules' => 'required|trim|strip_tags'),
array('field' => 'functional_unity','label' => 'Unidad Funcional','rules' => 'required|trim|strip_tags'),
array('field' => 'meters','label' => 'Metros','rules' => 'numeric|trim|strip_tags'),
array('field' => 'static_reserve_value','label' => 'Valor Fondo Reserva','rules' => 'numeric|trim|strip_tags'),
array('field' => 'balance','label' => 'Balance inicial','rules' => 'numeric|trim|strip_tags'),
array('field' => 'balance_reserve','label' => 'Saldo Fondo Reserva','rules' => 'numeric|trim|strip_tags'),
array('field' => 'balance_extraordinary','label' => 'Saldo Extraordinary','rules' => 'numeric|trim|strip_tags'),
array('field' => 'owner','label' => 'Propietario','rules' => 'required|trim|strip_tags')
    ),
    

'add_additional_income' => array(
array('field' => 'income_tags','label' => 'Ingreso','rules' => 'required|trim|strip_tags'),
array('field' => 'date','label' => 'Fecha','rules' => 'required|trim|strip_tags'),
array('field' => 'value','label' => 'Valor','rules' => 'required|numeric|trim|strip_tags')
    ),
    
'add_older_additional_income' => array(
array('field' => 'income_older_tags','label' => 'Ingreso','rules' => 'required|trim|strip_tags'),
array('field' => 'date_older','label' => 'Fecha','rules' => 'required|trim|strip_tags'),
array('field' => 'value_older','label' => 'Valor','rules' => 'required|numeric|trim|strip_tags')
    ),
    

'add_expense' => array(
array('field' => 'expense_tags','label' => 'Descripción','rules' => 'required|trim|strip_tags'),
array('field' => 'value','label' => 'Valor','rules' => 'required|trim|strip_tags'),
array('field' => 'type_expense','label' => 'Tipo de egreso','rules' => 'required|trim|strip_tags'),
array('field' => 'building','label' => 'Edificio','rules' => 'required|is_natural_no_zero|trim|strip_tags'),
array('field' => 'date','label' => 'Fecha|trim|strip_tags'),    
array('field' => 'priority','label' => 'Prioridad','rules' => 'is_natural|trim|strip_tags')
    ),

    
'add_older_expense' => array(
array('field' => 'expense_older_tags','label' => 'Descripción','rules' => 'required|trim|strip_tags'),
array('field' => 'value_older','label' => 'Valor','rules' => 'required|trim|strip_tags'),
array('field' => 'type_older_expense','label' => 'Tipo de egreso','rules' => 'required|trim|strip_tags'),
array('field' => 'expense_older_building_id','label' => 'Edificio','rules' => 'required|is_natural_no_zero|trim|strip_tags'),
array('field' => 'date_older','label' => 'Fecha|trim|strip_tags'),    
array('field' => 'expense_older_priority','label' => 'Prioridad','rules' => 'is_natural|trim|strip_tags')
    ),

'add_special_expense' => array(
array('field' => 'expense_tags_special','label' => 'Descripción','rules' => 'required|trim|strip_tags'),
array('field' => 'value','label' => 'Valor','rules' => 'required|trim|strip_tags'),
array('field' => 'type_expense','label' => 'Tipo de egreso','rules' => 'required|trim|strip_tags'),
array('field' => 'type_special_expense','label' => 'Tipo de pago','rules' => 'required|trim|strip_tags'),
array('field' => 'building_special','label' => 'Edificio','rules' => 'required|is_natural_no_zero|trim|strip_tags'),
array('field' => 'date','label' => 'Fecha|trim|strip_tags'),    
array('field' => 'priority','label' => 'Prioridad','rules' => 'is_natural|trim|strip_tags')
    ),

    
'add_older_special_expense' => array(
array('field' => 'special_expense_older_tags','label' => 'Descripción','rules' => 'required|trim|strip_tags'),
array('field' => 'special_value_older','label' => 'Valor','rules' => 'required|trim|strip_tags'),
array('field' => 'special_type_older_expense','label' => 'Tipo de egreso','rules' => 'required|trim|strip_tags'),
array('field' => 'special_type_older_special_expense','label' => 'Tipo de pago','rules' => 'required|trim|strip_tags'),
array('field' => 'special_expense_older_building_id','label' => 'Edificio','rules' => 'required|is_natural_no_zero|trim|strip_tags'),
array('field' => 'special_date_older','label' => 'Fecha|trim|strip_tags'),    
array('field' => 'special_expense_older_priority','label' => 'Prioridad','rules' => 'is_natural|trim|strip_tags')
    ),

'add_extraordinary_expense' => array(
array('field' => 'building_extra','label' => 'Edificio','rules' => 'required|is_natural_no_zero'),
array('field' => 'extraordinary_period','label' => 'Expensa Extraordinaria','rules' => 'required|trim|strip_tags'),
array('field' => 'value_extraordinary','label' => 'Valor','rules' => 'required|trim|strip_tags'),
array('field' => 'expense_tags_extraordinary','label' => 'Tipo de egreso','rules' => 'required|trim|strip_tags'),
array('field' => 'date_extraordinary','label' => 'Fecha|trim|strip_tags'),    
array('field' => 'priority_extraordinary','label' => 'Prioridad','rules' => 'is_natural|trim|strip_tags')
    ),

    
'add_extraordinary_period' => array(
array('field' => 'name','label' => 'Descripción','rules' => 'required|trim|strip_tags'),
array('field' => 'value','label' => 'Valor Total','rules' => 'required|numeric|trim|strip_tags'),
array('field' => 'tax_percentage','label' => 'Interés','rules' => 'required|numeric|trim|strip_tags'),
array('field' => 'pay_form','label' => 'pay_form','rules' => 'required|is_natural|trim|strip_tags'),
array('field' => 'fees','label' => 'Cuotas','rules' => 'required|is_natural_no_zero|trim|strip_tags')
    ),
    
'add_estimative_expense' => array(
array('field' => 'expense_tags_estimative','label' => 'Descripción','rules' => 'required|trim|strip_tags'),
array('field' => 'value','label' => 'Valor','rules' => 'required|trim|strip_tags'),
array('field' => 'type_expense','label' => 'Tipo de egreso','rules' => 'required|trim|strip_tags'),
array('field' => 'building_estimative','label' => 'Edificio','rules' => 'required|is_natural_no_zero|trim|strip_tags'),
array('field' => 'date','label' => 'Fecha|trim|strip_tags'),    
array('field' => 'priority','label' => 'Prioridad','rules' => 'is_natural|trim|strip_tags')
    ),

'add_older_estimative_expense' => array(
array('field' => 'estimative_expense_older_expense_tag_id','label' => 'Descripción','rules' => 'required|trim|strip_tags'),
array('field' => 'estimative_value_older','label' => 'Valor','rules' => 'required|trim|strip_tags'),
array('field' => 'estimative_type_older_expense','label' => 'Tipo de egreso','rules' => 'required|trim|strip_tags'),
array('field' => 'estimative_expense_older_building_id','label' => 'Edificio','rules' => 'required|is_natural_no_zero|trim|strip_tags'),
array('field' => 'estimative_date_older','label' => 'Fecha', 'rules' => 'required|trim|strip_tags'),    
array('field' => 'estimative_expense_older_priority','label' => 'Prioridad','rules' => 'is_natural|trim|strip_tags')
    ),

'add_pay_day' => array(
    
array('field' => 'building_id','label' => 'Edificio','rules' => 'required|trim|strip_tags'),
array('field' => 'date','label' => 'Fecha','rules' => 'required|trim|strip_tags'),
array('field' => 'hour_start','label' => 'Hora Inicio','rules' => 'required|numeric|trim|strip_tags'),
array('field' => 'minuts_start','label' => 'Minutos Inicio','rules' => 'required|numeric|trim|strip_tags'),
array('field' => 'hour_end','label' => 'Hora Fin','rules' => 'numeric|trim|strip_tags'),
array('field' => 'minuts_end','label' => 'Minutos Fin','rules' => 'numeric|trim|strip_tags')

    )
    
    
);
