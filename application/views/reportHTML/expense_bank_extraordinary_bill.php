<!DOCTYPE HTML>
<html>
    <head>
        <title>Recibo-Expensas Extraordinaria Calle: <?= $building->street ?> Número <?= $building->number ?></title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="<?= '/assets/css/reportsHTML/expense_bank_bill_single.css' ?>" />
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
    foreach ($extraordinary_periods as $period):
    
        $properties = $period->get_actives_properties();

        if ($properties):
            foreach($properties as $property):
                $last_transaction = $property->get_last_extraordinary_transaction($period);
?>
<div class="book">
    <div class="page">
        <div class="subpage">
            <!- Seccion de header del recibo ->
            <div class="header">

                <div class="header_left">
                    Consorcio de Propietarios <br>
                    Calle: <?= $property->building->street ?> Número <?= $property->building->number ?>
                    <? if($property->building->cuit): ?> 
                        - CUIT: <?= $property->building->cuit ?>
                    <? endif; ?><br/>
                    REG. SEG. SOCIAL - IVA SUJETO NO ALCANZADO
                    
                </div>

            </div>

            <div class="uf_data">

                <div class="detail_title">
                    Descripción de la Unidad Funcional
                </div>

                <div class="mid_field">
                    <span>Nombre:</span> <?= ucfirst(strtolower($property->owner->lastname))." ".substr($property->owner->name,0,1).'.'?>
                </div>

                <div class="mid_field">
                    <span>Coeficiente:</span> <?= $property->coefficient ?><br/>
                </div>

                
                <div class="mid_field">
                    <span>Piso:</span> <?= $property->floor ?>
                </div>

                <? if ($property->appartment): ?>
                <div class="mid_field">
                    <span>Depto:</span> <?= $property->appartment ?>
                </div>
                <? endif; ?>
                
                <div class="mid_field">
                    <span>Número de Unidad Funcional:</span> <?= $property->functional_unity?>
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


                    <? if(($last_transaction != null) && ($last_transaction->type_pay != "complete_pay")): ?>
                        
                        <div class="detail_table_row">
                            <div class="row_description">Expensa Extraordinaria atrasada</div>
                            <div class="row_price"> $ <?= number_format(abs($last_transaction->property->balance_extraordinary),2) ?></div>
                        </div>

                        <? if($period->tax_percentage > 0 ): ?>

                            <div class="detail_table_row">
                                <div class="row_description">Interes Expensa Extraordinaria atrasada</div>
                                <div class="row_price"> $ <?= number_format(abs($property->value_of_extraordinary_due($period)),2) ?></div>
                            </div>
                            
                        <? endif; ?>

                    <? endif; ?>

                    <? if ($period->get_number_of_fees($period->building->actual_period) <= $period->fees): ?>

                        <div class="detail_table_row">
                            <div class="row_description">E. Extraordinaria actual - <?= $period->name ?> - <?= $period->get_number_of_fees($period->building->actual_period) ?> / <?= $period->fees ?></div>
                            <div class="row_price"> $ <?= number_format($property->value_of_extraordinary_fee($period),2) ?></div>
                        </div>

                    <? endif; ?>

                    <div class="detail_table_row">
                        <div class="row_description"><span>Total a abonar </span></div>
                        <div class="row_price"><span> $ <?= number_format($property->value_to_pay_extraordinary($period),2); ?> </span></div>
                    </div>

                </div>

            </div>

            <!- Seccion de Pago ->
            <div class="payment_section">

                <!- Seccion de Codigo de barras ->

            <? if ($property->is_current_extraordinary_close()): ?>

                <div class="bank_section">
                    Para abonar a través de Link Pagos y Pago Mis Cuentas: <br />
                    Código de Pago Electrónico: <?= $property->get_bank_digital_extraordinary_identifier() ?> / Búsqueda: Banco Roela SIRO (rubro: Consorcios).
                </div>

            <? endif; ?>

                <!- Seccion de Codigo de barras ->
                <div class="codebar_section">
                    <span> <?= $property->get_current_extraordinary_payment_font_code() ?> </span> <br />
                    <p>Abonar en: Rapipago, Pago Fácil y Provincia Pagos. </p>
                    <p>Entidad Recaudadora: BANCO ROELA a través de <img src="../assets/img/report/siroBillImage.jpg" width="50" height="15">.</p>
                </div>

            </div>

        </div>       
    </div>
</div>
<? 
            endforeach;
        endif; 
    endforeach;
?>
</body>
</html>