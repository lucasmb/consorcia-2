<!DOCTYPE HTML>
<html>
    <head>
        <title>Recibo-Expensas Calle: <?= $building->street ?> Número <?= $building->number ?></title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="<?= url_css('/reportsHTML/expense_bank_bill_single.css') ?>" />
        <script type="text/javascript" src="<?= url_js('/home/jquery.js')?>"></script>
        
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
        foreach($properties as $property):
?>
<div class="book">
    <div class="page">
        <div class="subpage">
            <!- Seccion de header del recibo ->
            <div class="header">

                <div class="header_left">
                    Recibo de propiedad horizontal ley 13.512 <br>
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
                        <div class="row_description">Descripción</div>
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
                        <div class="row_description"><span>Total a abonar </span></div>
                        <div class="row_price"><span> $ <?= number_format($property->total_to_pay(),2); ?> </span></div>
                    </div>

                    <div class="detail_expiration_date">
                        Este recibo vence: 12 / <?= date("m",strtotime($building->date_next_period())); ?> / <?= $property->building->actual_period->format("Y"); ?>
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
                    <p>Entidad Recaudadora: BANCO ROELA a través de <img src="../assets/img/report/siroBillImage.jpg" width="50" height="15">.</p>
                </div>

            </div>
            
        </div>       
    </div>
</div>
<? 
        endforeach;
    endif; 
?>
</body>
</html>