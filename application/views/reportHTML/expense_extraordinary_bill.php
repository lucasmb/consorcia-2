<!DOCTYPE HTML>
<html>
    <head>
        <title>Recibo-Expensas Extraordinarias Calle: <?= $building->street ?> Número <?= $building->number ?></title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="<?= url_css('/reportsHTML/expense_bill.css') ?>" />
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

foreach ($extraordinary_periods as $period):
?>
<?   
    $properties = $period->get_actives_properties();
    
    if ($properties):
        $i=0;   
        foreach($properties as $property):    
            
            //if ($property->balance_extraordinary < 0):
            //if (if ($period->get_number_of_fees($period->building->actual_period) < $period->fees):)
                $last_transaction = $property->get_last_extraordinary_transaction($period);
                
                if ($i % 2 == 0):
                
    
?>
<div class="one_page">
    <div class="left_column">
        <div class="original_left">
            <div class="title" style="background: url(../assets/img/home/header_report.jpg) no-repeat;">
                <div class="title_center">
                    Recibo de propiedad horizontal ley 13.512 Consorcio de Propietarios CUIT: <?= $property->building->cuit ?><br>
                    Calle: <?= $property->building->street ?> Número <?= $property->building->number ?>
                </div>
                <div class="title_right">
                    <p>Original</p>
                    <p>Fecha: ... / ... / <?= date('Y'); ?></p>
                </div>
            </div>
            <table cellpadding="0" cellspacing="0" class="table_owner">
                    <tbody>
                        <tr class="table_row">
                            <td id="owner">Recibi de: <?= $property->owner->lastname." ".substr($property->owner->name,0,1).'.'?></td>                           
                            <td>Piso: <?= $property->floor ?></td>
                            <td>Depto: <?= $property->appartment ?></td>
                            <td>UF: <?= $property->functional_unity?></td>                            
                        </tr>
                        <tr class="table_row">
                            <td colspan="4">Por cuenta y orden del consorcio de propietarios Calle <?= $property->building->street ?> Número <?= $property->building->number ?> para aplicar los 
                            conceptos de expensas correspondientes al mes: <?= $property->building->month_name_actual_period(); ?> <?= date('Y'); ?>. 
                            Coeficiente <?= $property->coefficient ?></td>
                        </tr>
                    </tbody>
            </table>
            <div class="content">
                <div>
                    <table cellpadding="0" cellspacing="0" class="table_properties">
                        <thead>
                            <tr class="table_header">
                                <th>Descripcion</th>
                                <th>Importe</th>
                            </tr>
                        </thead>
                        <tbody>
                            <? if(($last_transaction != null) && ($last_transaction->type_pay != "complete_pay")): ?>
                            <tr class="table_row">
                                <td>Expensa Extraordinaria atrasada</td>
                                <td class="import"><?= number_format(abs($last_transaction->property->balance_extraordinary),2) ?></td>
                            </tr>                            
                            <? if($period->tax_percentage > 0 ): ?>
                            <tr class="table_row">
                                <td>Interes Expensa Extraordinaria atrasada</td>
                                <td class="import"><?= number_format(abs($property->value_of_extraordinary_due($period)),2) ?></td>
                            </tr>                            
                            <? endif; ?>
                            <? endif; ?>
                            <? if ($period->get_number_of_fees($period->building->actual_period) <= $period->fees): ?>
                            <tr class="table_row">
                                <td>E. Extraordinaria actual - <?= $period->name ?> - <?= $period->get_number_of_fees($period->building->actual_period) ?> / <?= $period->fees ?></td>
                                <td class="import"><?= number_format($property->value_of_extraordinary_fee($period),2) ?></td>
                            </tr>                            
                            <? endif; ?>
                            <tr class="table_row">
                                <td><hr></td>
                                <td class="import"><hr></td>
                            </tr>
                            <tr class="table_row">
                                <td>Total a abonar al 15</td>
                                <td class="import"><?= number_format(round($property->value_to_pay_extraordinary($period), 0 ,PHP_ROUND_HALF_DOWN),2);  ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
            </div>
            <div class="sign">Admistración</div>
            <div class="footer">
                    Personas habilitadas para efectuar el cobro: <span>José Omar Hernandez (DNI: 13.667.908)</span>
            </div>
        </div>
        <div class="duplicate_left">
            <div class="title" style="background: url(../assets/img/home/header_report.jpg) no-repeat;">
                <div class="title_center">
                    Recibo de propiedad horizontal ley 13.512 Consorcio de Propietarios CUIT: <?= $property->building->cuit ?><br>
                    Calle: <?= $property->building->street ?> Número <?= $property->building->number ?>
                </div>
                <div class="title_right">
                    <p>Duplicado</p>
                    <p>Fecha: ... / ... / <?= date('Y'); ?></p>
                </div>
            </div>
            <table cellpadding="0" cellspacing="0" class="table_owner">
                    <tbody>
                        <tr class="table_row">
                            <td id="owner">Recibi de: <?= $property->owner->lastname." ".substr($property->owner->name,0,1).'.'?></td>                           
                            <td>Piso: <?= $property->floor ?></td>
                            <td>Depto: <?= $property->appartment ?></td>
                            <td>UF: <?= $property->functional_unity?></td>                            
                        </tr>
                        <tr class="table_row">
                            <td colspan="4">Por cuenta y orden del consorcio de propietarios Calle <?= $property->building->street ?> Número <?= $property->building->number ?> para aplicar los 
                            conceptos de expensas correspondientes al mes: <?= $property->building->month_name_actual_period(); ?> <?= date('Y'); ?>. 
                            Coeficiente <?= $property->coefficient ?></td>
                        </tr>
                    </tbody>
            </table>
            <div class="content">
                <div>
                    <table cellpadding="0" cellspacing="0" class="table_properties">
                        <thead>
                            <tr class="table_header">
                                <th>Descripcion</th>
                                <th>Importe</th>
                            </tr>
                        </thead>
                        <tbody>
                            <? if(($last_transaction != null) && ($last_transaction->type_pay != "complete_pay")): ?>
                            <tr class="table_row">
                                <td>Expensa Extraordinaria atrasada</td>
                                <td class="import"><?= number_format(abs($last_transaction->property->balance_extraordinary),2) ?></td>
                            </tr>                            
                            <? if($period->tax_percentage > 0 ): ?>
                            <tr class="table_row">
                                <td>Interes Expensa Extraordinaria atrasada</td>
                                <td class="import"><?= number_format(abs($property->value_of_extraordinary_due($period)),2) ?></td>
                            </tr>                            
                            <? endif; ?>
                            <? endif; ?>
                            <? if ($period->get_number_of_fees($period->building->actual_period) <= $period->fees): ?>
                            <tr class="table_row">
                                <td>E. Extraordinaria actual - <?= $period->name ?> - <?= $period->get_number_of_fees($period->building->actual_period) ?> / <?= $period->fees ?></td>
                                <td class="import"><?= number_format($property->value_of_extraordinary_fee($period),2) ?></td>
                            </tr>                            
                            <? endif; ?>                           
                            <tr class="table_row">
                                <td><hr></td>
                                <td class="import"><hr></td>
                            </tr>
                            <tr class="table_row">
                                <td>Total a abonar al 15</td>
                                <td class="import"><?= number_format(round($property->value_to_pay_extraordinary($period), 0 ,PHP_ROUND_HALF_DOWN),2);  ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
            </div>
            <div class="sign">Admistración</div>
            <div class="footer">
                    Personas habilitadas para efectuar el cobro: <span>José Omar Hernandez (DNI: 13.667.908)</span>
            </div>
        </div>
    </div>
<?
            else:                
?>
    <div class="right_column">
        <div class="original_right">
            <div class="title" style="background: url(../assets/img/home/header_report.jpg) no-repeat;">
                <div class="title_center">
                    Recibo de propiedad horizontal ley 13.512 Consorcio de Propietarios CUIT: <?= $property->building->cuit ?><br>
                    Calle: <?= $property->building->street ?> Número <?= $property->building->number ?>
                </div>
                <div class="title_right">
                    <p>Original</p>
                    <p>Fecha: ... / ... / <?= date('Y'); ?></p>
                </div>
            </div>
            <table cellpadding="0" cellspacing="0" class="table_owner">
                    <tbody>
                        <tr class="table_row">
                            <td id="owner">Recibi de: <?= $property->owner->lastname." ".substr($property->owner->name,0,1).'.'?></td>                           
                            <td>Piso: <?= $property->floor ?></td>
                            <td>Depto: <?= $property->appartment ?></td>
                            <td>UF: <?= $property->functional_unity?></td>                            
                        </tr>
                        <tr class="table_row">
                            <td colspan="4">Por cuenta y orden del consorcio de propietarios Calle <?= $property->building->street ?> Número <?= $property->building->number ?> para aplicar los 
                            conceptos de expensas correspondientes al mes: <?= $property->building->month_name_actual_period(); ?> <?= date('Y'); ?>. 
                            Coeficiente <?= $property->coefficient ?></td>
                        </tr>
                    </tbody>
            </table>
            <div class="content">
                <div>
                    <table cellpadding="0" cellspacing="0" class="table_properties">
                        <thead>
                            <tr class="table_header">
                                <th>Descripcion</th>
                                <th>Importe</th>
                            </tr>
                        </thead>
                        <tbody>
                            <? if(($last_transaction != null) && ($last_transaction->type_pay != "complete_pay")): ?>
                            <tr class="table_row">
                                <td>Expensa Extraordinaria atrasada</td>
                                <td class="import"><?= number_format(abs($last_transaction->property->balance_extraordinary),2) ?></td>
                            </tr>                            
                            <? if($period->tax_percentage > 0 ): ?>
                            <tr class="table_row">
                                <td>Interes Expensa Extraordinaria atrasada</td>
                                <td class="import"><?= number_format(abs($property->value_of_extraordinary_due($period)),2) ?></td>
                            </tr>                            
                            <? endif; ?>
                            <? endif; ?>
                            <? if ($period->get_number_of_fees($period->building->actual_period) <= $period->fees): ?>
                            <tr class="table_row">
                                <td>E. Extraordinaria actual - <?= $period->name ?> - <?= $period->get_number_of_fees($period->building->actual_period) ?> / <?= $period->fees ?></td>
                                <td class="import"><?= number_format($property->value_of_extraordinary_fee($period),2) ?></td>
                            </tr>                            
                            <? endif; ?>                           
                            <tr class="table_row">
                                <td><hr></td>
                                <td class="import"><hr></td>
                            </tr>
                            <tr class="table_row">
                                <td>Total a abonar al 15</td>
                                <td class="import"><?= number_format(round($property->value_to_pay_extraordinary($period), 0 ,PHP_ROUND_HALF_DOWN),2);  ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
            </div>
            <div class="sign">Admistración</div>
            <div class="footer">
                    Personas habilitadas para efectuar el cobro: <span>José Omar Hernandez (DNI: 13.667.908)</span>
            </div>
        </div>
        <div class="duplicate_right">
            <div class="title" style="background: url(../assets/img/home/header_report.jpg) no-repeat;">
                <div class="title_center">
                    Recibo de propiedad horizontal ley 13.512 Consorcio de Propietarios CUIT: <?= $property->building->cuit ?><br>
                    Calle: <?= $property->building->street ?> Número <?= $property->building->number ?>
                </div>
                <div class="title_right">
                    <p>Duplicado</p>
                    <p>Fecha: ... / ... / <?= date('Y'); ?></p>
                </div>
            </div>
            <table cellpadding="0" cellspacing="0" class="table_owner">
                    <tbody>
                        <tr class="table_row">
                            <td id="owner">Recibi de: <?= $property->owner->lastname." ".substr($property->owner->name,0,1).'.'?></td>                           
                            <td>Piso: <?= $property->floor ?></td>
                            <td>Depto: <?= $property->appartment ?></td>
                            <td>UF: <?= $property->functional_unity?></td>                            
                        </tr>
                        <tr class="table_row">
                            <td colspan="4">Por cuenta y orden del consorcio de propietarios Calle <?= $property->building->street ?> Número <?= $property->building->number ?> para aplicar los 
                            conceptos de expensas correspondientes al mes: <?= $property->building->month_name_actual_period(); ?> <?= date('Y'); ?>. 
                            Coeficiente <?= $property->coefficient ?></td>
                        </tr>
                    </tbody>
            </table>
            <div class="content">
                <div>
                    <table cellpadding="0" cellspacing="0" class="table_properties">
                        <thead>
                            <tr class="table_header">
                                <th>Descripcion</th>
                                <th>Importe</th>
                            </tr>
                        </thead>
                        <tbody>
                            <? if(($last_transaction != null) && ($last_transaction->type_pay != "complete_pay")): ?>
                            <tr class="table_row">
                                <td>Expensa Extraordinaria atrasada</td>
                                <td class="import"><?= number_format(abs($last_transaction->property->balance_extraordinary),2) ?></td>
                            </tr>                            
                            <? if($period->tax_percentage > 0 ): ?>
                            <tr class="table_row">
                                <td>Interes Expensa Extraordinaria atrasada</td>
                                <td class="import"><?= number_format(abs($property->value_of_extraordinary_due($period)),2) ?></td>
                            </tr>                            
                            <? endif; ?>
                            <? endif; ?>
                            <? if ($period->get_number_of_fees($period->building->actual_period) <= $period->fees): ?>
                            <tr class="table_row">
                                <td>E. Extraordinaria actual - <?= $period->name ?> - <?= $period->get_number_of_fees($period->building->actual_period) ?> / <?= $period->fees ?></td>
                                <td class="import"><?= number_format($property->value_of_extraordinary_fee($period),2) ?></td>
                            </tr>                            
                            <? endif; ?>                            
                            <tr class="table_row">
                                <td><hr></td>
                                <td class="import"><hr></td>
                            </tr>
                            <tr class="table_row">
                                <td>Total a abonar al 15</td>
                                <td class="import"><?= number_format(round($property->value_to_pay_extraordinary($period), 0 ,PHP_ROUND_HALF_DOWN),2);  ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
            </div>
            <div class="sign">Admistración</div>
            <div class="footer">
                    Personas habilitadas para efectuar el cobro: <span>José Omar Hernandez (DNI: 13.667.908)</span>
            </div>
        </div>
    </div>
    </div>
<? 
                endif;
                $i++;
            //endif;
            
        endforeach;
    endif;
?>
<?
endforeach;
?>
</body>
</html>