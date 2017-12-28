<!DOCTYPE HTML>
<html>
    <head>
        <title>Recibo-Expensas Calle: <?= $building->street ?> Número <?= $building->number ?></title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="<?= '/assets/css/reportsHTML/expense_bank_bill.css' ?>" />
        <script type="text/javascript" src="<?= '/assets/js/home/jquery.js'?>"></script>
        
    </head>
    
    

<body onclick="myFunction();">
    <script>
        function myFunction()
        {
            window.print();
        }
    </script>
<? 
    if ($properties):
        $i=0;
        foreach($properties as $property):
            if ($i % 2 == 0):
    
?>
<div class="expense_page">
    <div class="left_column">
        <div class="original_left">

            <!- Seccion de header del recibo ->
            <div class="header">

                <div class="header_left">
                    Recibo de propiedad horizontal ley 13.512 <br>
                    Consorcio de Propietarios <? if($property->building->cuit): ?> CUIT: <?= $property->building->cuit ?> <? endif; ?><br>
                    Calle: <?= $property->building->street ?> Número <?= $property->building->number ?>
                </div>

                <div class="header_right">
                    Nombre: <?= ucfirst(strtolower($property->owner->lastname))." ".substr($property->owner->name,0,1).'.'?><br>
                    Piso: <?= $property->floor ?> <? if ($property->appartment): ?> - Depto: <?= $property->appartment ?><? endif; ?><br> 
                    Coeficiente: <?= $property->coefficient ?><br> 
                    Unidad Funcional: <?= $property->functional_unity?><br>
                </div>

            </div>

            <!- Seccion de detalle de pago ->
            <div class="detail">

                <div class="detail_title">
                    Descripción del cobro de la expensa 
                </div>

                <div>
                    Por cuenta y orden del consorcio de propietarios Calle <?= $property->building->street ?> Número <?= $property->building->number ?> para aplicar los 
                            conceptos de expensas correspondientes al mes: <?= $property->building->month_name_actual_period(); ?> <?= $property->building->actual_period->format("Y"); ?>
                </div>

                <div class="detail_table">

                    <div class="detail_table_row">
                        <div class="row_description">Descripcion</div>
                        <div class="row_price">Precio</div>
                    </div>

                    <? if($property->balance < 0): ?>
                        <div class="detail_table_row">
                            <div class="row_description">Expensa comun atrasada</div>
                            <div class="row_price"> $ <?= number_format(abs($property->balance),2) ?></div>
                        </div>

                        <div class="detail_table_row">
                            <div class="row_description">Interes Expensa comun</div>
                            <div class="row_price"> $ <?= number_format(abs($property->due_interest_expense()),2) ?></div>
                        </div>
                    <? endif; ?>

                    <div class="detail_table_row">
                        <div class="row_description">Expensa comun actual</div>
                        <div class="row_price"> $ <?= number_format($property->actual_common_expense(),2) ?></div>
                    </div>

                    <? if($property->building->has_reserve_fund): ?>
                        
                        <? if($property->balance_reserve < 0): ?>
                            <div class="detail_table_row">
                                <div class="row_description">Fondo de reserva atrasado</div>
                                <!--<div class="row_description">Expensa extraordinaria atrasada</div> -->
                                <div class="row_price"> $ <?= number_format(abs($property->balance_reserve),2) ?></div>
                            </div>

                            <div class="detail_table_row">
                                <div class="row_description">Interes fondo de reserva atrasado</div>
                                <!--<div class="row_description">Interes expensa extraordinaria atrasada</div> -->
                                <div class="row_price"> $ <?= number_format(abs($property->due_interest_fund()),2) ?></div>
                            </div>
                        <? endif; ?>
                        
                        <div class="detail_table_row">
                            <div class="row_description">Fondo de reserva actual</div>
                            <div class="row_price"> $ <?= number_format($property->actual_reserve_fund(),2) ?></div>
                        </div>

                    <? endif; ?>

                    <? if($property->building->has_special_expense_last_month()): ?>

                        <? foreach ($property->building->get_special_expenses_last_month() as $special_expense): ?>
                            <div class="detail_table_row">
                                <div class="row_description">$special_expense->expense_tag->name</div>
                                <div class="row_price"> $ <?= number_format($property->get_special_expense_to_pay($special_expense),2) ?></div>
                            </div>
                        <? endforeach; ?>

                    <? endif; ?>
                          
                    <div class="detail_table_row">
                        <div class="row_description">Total a abonar </div>
                        <div class="row_price"> $ <?= number_format($property->total_to_pay(),2); ?></div>
                    </div>

                </div>
                
            </div>

            <!- Seccion de Pago ->
            <div class="payment_section">

                <!- Seccion de Codigo de barras ->

            <? if ($property->is_current_ordinary_close()): ?>

                <div class="bank_section">
                    Para abonar a través de Link Pagos y Pago Mis Cuentas: <br />
                    Código de Pago Electrónico: <?= $property->get_bank_digital_identifier() ?> / Búsqueda: Banco Roela SIRO (rubro: Consorcios).
                </div>

            <? endif; ?>

                <!- Seccion de Codigo de barras ->
                <div class="codebar_section">
                    <span> <?= $property->get_current_payment_font_code() ?> </span> <br />
                    <p>Abonar en: Rapipago, Pago Fácil y Provincia Pagos. </p>
                    <p>Entidad Recaudadora: BANCO ROELA a través de <img src="../assets/img/report/siroBillImage.jpg" width="30" height="15">.</p>
                </div>

            </div>
            
        </div>       
    </div>
<?
            else:
?>
    <div class="right_column">
        <div class="original_right">
            <!- Seccion de header del recibo ->
            <div class="header">

                <div class="header_left">
                    Recibo de propiedad horizontal ley 13.512 <br>
                    Consorcio de Propietarios <? if($property->building->cuit): ?> CUIT: <?= $property->building->cuit ?> <? endif; ?><br>
                    Calle: <?= $property->building->street ?> Número <?= $property->building->number ?>
                </div>

                <div class="header_right">
                    Nombre: <?= ucfirst(strtolower($property->owner->lastname))." ".substr($property->owner->name,0,1).'.'?><br>
                    Piso: <?= $property->floor ?> <? if ($property->appartment): ?> - Depto: <?= $property->appartment ?><? endif; ?><br> 
                    Coeficiente: <?= $property->coefficient ?><br> 
                    Unidad Funcional: <?= $property->functional_unity?><br>
                </div>

            </div>

            <!- Seccion de detalle de pago ->
            <div class="detail">
                <div class="detail_title">
                    Descripción del cobro de la expensa 
                </div>

                <div>
                    Por cuenta y orden del consorcio de propietarios Calle <?= $property->building->street ?> Número <?= $property->building->number ?> para aplicar los 
                            conceptos de expensas correspondientes al mes: <?= $property->building->month_name_actual_period(); ?> <?= date('Y'); ?>
                </div>

                <div class="detail_table">

                    <div class="detail_table_row">
                        <div class="row_description">Descripcion</div>
                        <div class="row_price">Precio</div>
                    </div>

                    <? if($property->balance < 0): ?>
                        <div class="detail_table_row">
                            <div class="row_description">Expensa comun atrasada</div>
                            <div class="row_price"> $ <?= number_format(abs($property->balance),2) ?></div>
                        </div>

                        <div class="detail_table_row">
                            <div class="row_description">Interes Expensa comun</div>
                            <div class="row_price"> $ <?= number_format(abs($property->due_interest_expense()),2) ?></div>
                        </div>
                    <? endif; ?>

                    <div class="detail_table_row">
                        <div class="row_description">Expensa comun actual</div>
                        <div class="row_price"> $ <?= number_format($property->actual_common_expense(),2) ?></div>
                    </div>

                    <? if($property->building->has_reserve_fund): ?>
                        
                        <? if($property->balance_reserve < 0): ?>
                            <div class="detail_table_row">
                                <div class="row_description">Fondo de reserva atrasado</div>
                                <!--<div class="row_description">Expensa extraordinaria atrasada</div> -->
                                <div class="row_price"> $ <?= number_format(abs($property->balance_reserve),2) ?></div>
                            </div>

                            <div class="detail_table_row">
                                <div class="row_description">Interes fondo de reserva atrasado</div>
                                <!--<div class="row_description">Interes expensa extraordinaria atrasada</div> -->
                                <div class="row_price"> $ <?= number_format(abs($property->due_interest_fund()),2) ?></div>
                            </div>
                        <? endif; ?>
                        
                        <div class="detail_table_row">
                            <div class="row_description">Fondo de reserva actual</div>
                            <div class="row_price"> $ <?= number_format($property->actual_reserve_fund(),2) ?></div>
                        </div>

                    <? endif; ?>

                    <? if($property->building->has_special_expense_last_month()): ?>

                        <? foreach ($property->building->get_special_expenses_last_month() as $special_expense): ?>
                            <div class="detail_table_row">
                                <div class="row_description">$special_expense->expense_tag->name</div>
                                <div class="row_price"> $ <?= number_format($property->get_special_expense_to_pay($special_expense),2) ?></div>
                            </div>
                        <? endforeach; ?>

                    <? endif; ?>
                          
                    <div class="detail_table_row">
                        <div class="row_description">Total a abonar </div>
                        <div class="row_price"> $ <?= number_format($property->total_to_pay(),2); ?></div>
                    </div>

                </div>
                
            </div>

            <!- Seccion de Pago ->
            <div class="payment_section">

            <? if ($property->is_current_ordinary_close()): ?>

                <div class="bank_section">
                    Para abonar a través de Link Pagos y Pago Mis Cuentas: <br />
                    Código de Pago Electrónico: <?= $property->get_bank_digital_identifier() ?> / Búsqueda: Banco Roela SIRO (rubro: Consorcios).
                </div>

            <? endif; ?>

                <!- Seccion de Codigo de barras ->
                <div class="codebar_section">
                    <span><?= $property->get_current_payment_font_code() ?></span> <br />
                    <p>Abonar en: Rapipago, Pago Fácil y Provincia Pagos. </p>
                    <p>Entidad Recaudadora: BANCO ROELA a través de <img src="../assets/img/report/siroBillImage.jpg" width="30" height="15">.</p>
                </div>

            </div>
            
        </div>
    </div>
</div>
<? 
            endif;
            $i++;
        endforeach;
    endif; 
?>
</body>
</html>