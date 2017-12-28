<!DOCTYPE HTML>
<html>
    <head>
    <title>Factura Expensas</title>
        <link rel="stylesheet" type="text/css" href="<?= '/assets/css/reports/expense_bill.css' ?>" />
    </head>

<body>
<? 
    if ($properties):
        $i=0;
        foreach($properties as $property):
            if ($i % 2 == 0):
    
?>
    <div class="page">
    <div class="left_column">
        <div class="original_left">
            <div class="title" style="background: url(/assets/img/home/header_report.jpg) no-repeat;">
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
                            <? if($property->balance < 0): ?>
                            <tr class="table_row">
                                <td>Expensa comun atrasada</td>
                                <td class="import"><?= number_format(abs($property->balance),2) ?></td>
                            </tr>
                            <tr class="table_row">
                                <td>Interes Expensa comun</td>
                                <td class="import"><?= number_format(abs($property->due_interest_expense()),2) ?></td>
                            </tr>
                            <? endif; ?>
                            <tr class="table_row">
                                <td>Expensa comun actual</td>
                                <td class="import"><?= number_format($property->actual_common_expense(),2) ?></td>
                            </tr>
                            <!--<tr class="table_row">
                                <td>Total Expensa comun actual al 10</td>
                                <td class="import"><?//= number_format($property->actual_common_expense() + abs($property->due_interest_expense()) + abs($property->balance),2) ?></td>
                            </tr>-->
                            <? if($property->building->has_reserve_fund): ?>
                            <? if($property->balance_reserve < 0): ?>
                            <tr class="table_row">
                                <td>Fondo de reserva atrasado</td>                                
                                <td class="import"><?= number_format(abs($property->balance_reserve),2) ?></td>
                            </tr>
                            <tr class="table_row">
                                <td>Interes fondo de reserva atrasado</td>
                                <td class="import"><?= number_format(abs($property->due_interest_fund()),2) ?></td>
                            </tr>
                            <? endif; ?>
                            <tr class="table_row">
                                <td>Fondo de reserva actual</td>                                
                                <td class="import"><?= number_format($property->actual_reserve_fund(),2) ?></td>
                            </tr>
                            <? endif; ?>
                            <!--<tr class="table_row">
                                <td>Total fondo de reserva al 10</td>
                                <td class="import"><?//= number_format( abs($property->actual_reserve_fund()) + abs($property->due_interest_fund()) + abs($property->due_fund()),2) ?></td>
                            </tr>-->
                            <tr class="table_row">
                                <td>----------------------------------------------------</td>
                                <td class="import">--------------</td>
                            </tr>                            
                           <!-- <tr class="table_row">
                                <td>Total a abonar al 10</td>
                                <td class="import"><?//= number_format(round($property->total_to_pay(), 0 ,PHP_ROUND_HALF_DOWN),2) ?></td>
                            </tr>                            
                            <tr class="table_row">
                                <td>Total a abonar al 15</td>
                                <td class="import"><?//= number_format(round($property->total_to_pay_due(), 0 ,PHP_ROUND_HALF_DOWN),2)  ?></td>
                            </tr>-->
                            <tr class="table_row">
                                <td>Total a abonar al 15</td>
                                <td class="import"><?= number_format(round($property->total_to_pay(), 0 ,PHP_ROUND_HALF_DOWN),2);  ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
            </div>
            <div class="sign">
                    ....................................<br>
                    Admistración
            </div>
            <div class="footer">
                    Personas habilitadas para efectuar el cobro: <span>José Omar Hernandez (DNI: 13.667.908)</span> <!--<span>y Mauro Craco(DNI: 32.485.828)</span>-->
            </div>
        </div>
        <div class="duplicate_left">
            <div class="title" style="background: url(/assets/img/home/header_report.jpg) no-repeat;">
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
                            <? if($property->balance < 0): ?>
                            <tr class="table_row">
                                <td>Expensa comun atrasada</td>
                                <td class="import"><?= number_format(abs($property->balance),2) ?></td>
                            </tr>
                            <tr class="table_row">
                                <td>Interes Expensa comun</td>
                                <td class="import"><?= number_format(abs($property->due_interest_expense()),2) ?></td>
                            </tr>
                            <? endif; ?>
                            <tr class="table_row">
                                <td>Expensa comun actual</td>
                                <td class="import"><?= number_format($property->actual_common_expense(),2) ?></td>
                            </tr>
                            <!--<tr class="table_row">
                                <td>Total Expensa comun actual al 10</td>
                                <td class="import"><?//= number_format($property->actual_common_expense() + abs($property->due_interest_expense()) + abs($property->balance),2) ?></td>
                            </tr>-->
                            <? if($property->building->has_reserve_fund): ?>
                            <? if($property->balance_reserve < 0): ?>
                            <tr class="table_row">
                                <td>Fondo de reserva atrasado</td>                                
                                <td class="import"><?= number_format(abs($property->balance_reserve),2) ?></td>
                            </tr>
                            <tr class="table_row">
                                <td>Interes fondo de reserva atrasado</td>
                                <td class="import"><?= number_format(abs($property->due_interest_fund()),2) ?></td>
                            </tr>
                            <? endif; ?>
                            <tr class="table_row">
                                <td>Fondo de reserva actual</td>                                
                                <td class="import"><?= number_format($property->actual_reserve_fund(),2) ?></td>
                            </tr>
                            <? endif; ?>
                            <!--<tr class="table_row">
                                <td>Total fondo de reserva al 10</td>
                                <td class="import"><?//= number_format( abs($property->actual_reserve_fund()) + abs($property->due_interest_fund()) + abs($property->due_fund()),2) ?></td>
                            </tr>-->
                            <tr class="table_row">
                                <td>----------------------------------------------------</td>
                                <td class="import">--------------</td>
                            </tr>                            
                            <!-- <tr class="table_row">
                                <td>Total a abonar al 10</td>
                                <td class="import"><?//= number_format(round($property->total_to_pay(), 0 ,PHP_ROUND_HALF_DOWN),2) ?></td>
                            </tr>                            
                            <tr class="table_row">
                                <td>Total a abonar al 15</td>
                                <td class="import"><?//= number_format(round($property->total_to_pay_due(), 0 ,PHP_ROUND_HALF_DOWN),2)  ?></td>
                            </tr>-->
                            <tr class="table_row">
                                <td>Total a abonar al 15</td>
                                <td class="import"><?= number_format(round($property->total_to_pay(), 0 ,PHP_ROUND_HALF_DOWN),2);  ?></td>
                            </tr>                          
                        </tbody>
                    </table>
                </div>
                
            </div>
            <div class="sign">
                    ....................................<br>
                    Admistración
            </div>
            <div class="footer">
                    Personas habilitadas para efectuar el cobro: <span>José Omar Hernandez (DNI: 13.667.908)</span> <!--<span>y Mauro Craco(DNI: 32.485.828)</span>-->
            </div>
        </div>        
    </div>
<?
            else:
?>
    <div class="right_column">
        <div class="original_right">
            <div class="title" style="background: url(/assets/img/home/header_report.jpg) no-repeat;">
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
                            <? if($property->balance < 0): ?>
                            <tr class="table_row">
                                <td>Expensa comun atrasada</td>
                                <td class="import"><?= number_format(abs($property->balance),2) ?></td>
                            </tr>
                            <tr class="table_row">
                                <td>Interes Expensa comun</td>
                                <td class="import"><?= number_format(abs($property->due_interest_expense()),2) ?></td>
                            </tr>
                            <? endif; ?>
                            <tr class="table_row">
                                <td>Expensa comun actual</td>
                                <td class="import"><?= number_format($property->actual_common_expense(),2) ?></td>
                            </tr>
                            <!--<tr class="table_row">
                                <td>Total Expensa comun actual al 10</td>
                                <td class="import"><?//= number_format($property->actual_common_expense() + abs($property->due_interest_expense()) + abs($property->balance),2) ?></td>
                            </tr>-->
                            <? if($property->building->has_reserve_fund): ?>
                            <? if($property->balance_reserve < 0): ?>
                            <tr class="table_row">
                                <td>Fondo de reserva atrasado</td>                                
                                <td class="import"><?= number_format(abs($property->balance_reserve),2) ?></td>
                            </tr>
                            <tr class="table_row">
                                <td>Interes fondo de reserva atrasado</td>
                                <td class="import"><?= number_format(abs($property->due_interest_fund()),2) ?></td>
                            </tr>
                            <? endif; ?>
                            <tr class="table_row">
                                <td>Fondo de reserva actual</td>                                
                                <td class="import"><?= number_format($property->actual_reserve_fund(),2) ?></td>
                            </tr>
                            <? endif; ?>
                            <!--<tr class="table_row">
                                <td>Total fondo de reserva al 10</td>
                                <td class="import"><?//= number_format( abs($property->actual_reserve_fund()) + abs($property->due_interest_fund()) + abs($property->due_fund()),2) ?></td>
                            </tr>-->
                            <tr class="table_row">
                                <td>----------------------------------------------------</td>
                                <td class="import">--------------</td>
                            </tr>                            
                            <!-- <tr class="table_row">
                                <td>Total a abonar al 10</td>
                                <td class="import"><?//= number_format(round($property->total_to_pay(), 0 ,PHP_ROUND_HALF_DOWN),2) ?></td>
                            </tr>                            
                            <tr class="table_row">
                                <td>Total a abonar al 15</td>
                                <td class="import"><?//= number_format(round($property->total_to_pay_due(), 0 ,PHP_ROUND_HALF_DOWN),2)  ?></td>
                            </tr>-->
                            <tr class="table_row">
                                <td>Total a abonar al 15</td>
                                <td class="import"><?= number_format(round($property->total_to_pay(), 0 ,PHP_ROUND_HALF_DOWN),2);  ?></td>
                            </tr>                            
                        </tbody>
                    </table>
                </div>
                
            </div>
            <div class="sign">
                    ....................................<br>
                    Admistración
            </div>
            <div class="footer">
                    Personas habilitadas para efectuar el cobro: <span>José Omar Hernandez (DNI: 13.667.908)</span> <!--<span>y Mauro Craco(DNI: 32.485.828)</span>-->
            </div>
        </div>
        <div class="duplicate_right">
            <div class="title" style="background: url(/assets/img/home/header_report.jpg) no-repeat;">
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
                            <? if($property->balance < 0): ?>
                            <tr class="table_row">
                                <td>Expensa comun atrasada</td>
                                <td class="import"><?= number_format(abs($property->balance),2) ?></td>
                            </tr>
                            <tr class="table_row">
                                <td>Interes Expensa comun</td>
                                <td class="import"><?= number_format(abs($property->due_interest_expense()),2) ?></td>
                            </tr>
                            <? endif; ?>
                            <tr class="table_row">
                                <td>Expensa comun actual</td>
                                <td class="import"><?= number_format($property->actual_common_expense(),2) ?></td>
                            </tr>
                            <!--<tr class="table_row">
                                <td>Total Expensa comun actual al 10</td>
                                <td class="import"><?//= number_format($property->actual_common_expense() + abs($property->due_interest_expense()) + abs($property->balance),2) ?></td>
                            </tr>-->
                            <? if($property->building->has_reserve_fund): ?>
                            <? if($property->balance_reserve < 0): ?>
                            <tr class="table_row">
                                <td>Fondo de reserva atrasado</td>                                
                                <td class="import"><?= number_format(abs($property->balance_reserve),2) ?></td>
                            </tr>
                            <tr class="table_row">
                                <td>Interes fondo de reserva atrasado</td>
                                <td class="import"><?= number_format(abs($property->due_interest_fund()),2) ?></td>
                            </tr>
                            <? endif; ?>
                            <tr class="table_row">
                                <td>Fondo de reserva actual</td>                                
                                <td class="import"><?= number_format($property->actual_reserve_fund(),2) ?></td>
                            </tr>
                            <? endif; ?>
                            <!--<tr class="table_row">
                                <td>Total fondo de reserva al 10</td>
                                <td class="import"><?//= number_format( abs($property->actual_reserve_fund()) + abs($property->due_interest_fund()) + abs($property->due_fund()),2) ?></td>
                            </tr>-->
                            <tr class="table_row">
                                <td>----------------------------------------------------</td>
                                <td class="import">--------------</td>
                            </tr>                            
                            <!-- <tr class="table_row">
                                <td>Total a abonar al 10</td>
                                <td class="import"><?//= number_format(round($property->total_to_pay(), 0 ,PHP_ROUND_HALF_DOWN),2) ?></td>
                            </tr>                            
                            <tr class="table_row">
                                <td>Total a abonar al 15</td>
                                <td class="import"><?//= number_format(round($property->total_to_pay_due(), 0 ,PHP_ROUND_HALF_DOWN),2)  ?></td>
                            </tr>-->
                            <tr class="table_row">
                                <td>Total a abonar al 15</td>
                                <td class="import"><?= number_format(round($property->total_to_pay(), 0 ,PHP_ROUND_HALF_DOWN),2);  ?></td>
                            </tr>                           
                        </tbody>
                    </table>
                </div>                
            </div>
            <div class="sign">
                    ....................................<br>
                    Admistración
            </div>
            <div class="footer">
                Personas habilitadas para efectuar el cobro: <span>José Omar Hernandez (DNI: 13.667.908)</span> <!--<span>y Mauro Craco(DNI: 32.485.828)</span>-->
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