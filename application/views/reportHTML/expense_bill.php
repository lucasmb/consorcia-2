<!DOCTYPE HTML>
<html>
    <head>
        <title>Recibo-Expensas Calle: <?= $building->street ?> Número <?= $building->number ?></title>
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
    if ($properties):
        $i=0;
        foreach($properties as $property):
            if ($i % 2 == 0):
    
?>
<div class="one_page">
    <div class="left_column">
        <div class="original_left">

            <div class="title" style="background: url(../assets/img/home/header_report.jpg) no-repeat;">
                <div class="title_center">
                    Consorcio de Propietarios <? if($property->building->cuit): ?> CUIT: <?= $property->building->cuit ?> <? endif; ?><br>
                    Calle: <?= $property->building->street ?> Número <?= $property->building->number ?>
                </div>
                <div class="title_right">
                    <div class="title_bill_id"><?= substr ($property->current_hash_ordinary_bill(), 0, 20); ?></div> <br />
                    <div class="title_bill_type">Original</div> <br />
                    Fecha: ... / <?= date("m",strtotime($building->date_next_period())); ?> / <?= date("Y",strtotime($building->date_next_period())); ?><br />
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
                            conceptos de expensas correspondientes al mes: <?= $property->building->month_name_actual_period(); ?> <?= date("Y",strtotime($building->date_next_period())); ?>. 
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
                                <!--<td>Expensa extraordinaria atrasada</td> -->
                                <td class="import"><?= number_format(abs($property->balance_reserve),2) ?></td>
                            </tr>
                            <tr class="table_row">
                                <td>Interes fondo de reserva atrasado</td>
                                <!--<td>Interes expensa extraordinaria atrasada</td>-->
                                <td class="import"><?= number_format(abs($property->due_interest_fund()),2) ?></td>
                            </tr>
                            <? endif; ?>
                            <tr class="table_row">
                                <td>Fondo de reserva actual</td>
                                <!--<td>Expensa extraordinaria actual</td>-->
                                <td class="import"><?= number_format($property->actual_reserve_fund(),2) ?></td>
                            </tr>
                            <? endif; ?>
                            <? if($property->building->has_special_expense_last_month()): ?>
                            <? foreach ($property->building->get_special_expenses_last_month() as $special_expense): ?>
                            <tr class="table_row">
                                <td><?= $special_expense->expense_tag->name ?></td>
                                <td class="import"><?= number_format($property->get_special_expense_to_pay($special_expense),2) ?></td>
                            </tr>
                            <? endforeach; ?>
                            <? endif; ?>
                            <!--<tr class="table_row">
                                <td>Total fondo de reserva al 10</td>
                                <td class="import"><?//= number_format( abs($property->actual_reserve_fund()) + abs($property->due_interest_fund()) + abs($property->due_fund()),2) ?></td>
                            </tr>-->
                            <tr class="table_row">
                                <td><hr></td>
                                <td class="import"><hr></td>
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
            <div class="sign">Admistración</div>
            
        </div>
        <div class="duplicate_left">
            <div class="title" style="background: url(../assets/img/home/header_report.jpg) no-repeat;">
                <div class="title_center">
                    Consorcio de Propietarios <? if($property->building->cuit): ?> CUIT: <?= $property->building->cuit ?> <? endif; ?><br>
                    Calle: <?= $property->building->street ?> Número <?= $property->building->number ?>
                </div>
                <div class="title_right">
                    <div class="title_bill_id"><?= substr ($property->current_hash_ordinary_bill(), 0, 20); ?></div> <br />
                    <div class="title_bill_type">Duplicado</div> <br />
                    Fecha: ... / <?= date("m",strtotime($building->date_next_period())); ?> / <?= date("Y",strtotime($building->date_next_period())); ?><br />
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
                            conceptos de expensas correspondientes al mes: <?= $property->building->month_name_actual_period(); ?> <?= date("Y",strtotime($building->date_next_period())); ?>. 
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
                                <!--<td>Expensa extraordinaria atrasada</td> -->
                                <td class="import"><?= number_format(abs($property->balance_reserve),2) ?></td>
                            </tr>
                            <tr class="table_row">
                                <td>Interes fondo de reserva atrasado</td>
                                <!--<td>Interes expensa extraordinaria atrasada</td>-->
                                <td class="import"><?= number_format(abs($property->due_interest_fund()),2) ?></td>
                            </tr>
                            <? endif; ?>
                            <tr class="table_row">
                                <td>Fondo de reserva actual</td>
                                <!--<td>Expensa extraordinaria actual</td>-->
                                <td class="import"><?= number_format($property->actual_reserve_fund(),2) ?></td>
                            </tr>
                            <? endif; ?>
                            <? if($property->building->has_special_expense_last_month()): ?>
                            <? foreach ($property->building->get_special_expenses_last_month() as $special_expense): ?>
                            <tr class="table_row">
                                <td><?= $special_expense->expense_tag->name ?></td>
                                <td class="import"><?= number_format($property->get_special_expense_to_pay($special_expense),2) ?></td>
                            </tr>
                            <? endforeach; ?>
                            <? endif; ?>
                            <!--<tr class="table_row">
                                <td>Total fondo de reserva al 10</td>
                                <td class="import"><?//= number_format( abs($property->actual_reserve_fund()) + abs($property->due_interest_fund()) + abs($property->due_fund()),2) ?></td>
                            </tr>-->
                            <tr class="table_row">
                                <td><hr></td>
                                <td class="import"><hr></td>
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
            <div class="sign">Admistración</div>
            
        </div>        
    </div>
<?
            else:
?>
    <div class="right_column">
        <div class="original_right">
            <div class="title" style="background: url(../assets/img/home/header_report.jpg) no-repeat;">
                <div class="title_center">
                    Consorcio de Propietarios <? if($property->building->cuit): ?> CUIT: <?= $property->building->cuit ?> <? endif; ?><br>
                    Calle: <?= $property->building->street ?> Número <?= $property->building->number ?>
                </div>
                <div class="title_right">
                    <div class="title_bill_id"><?= substr ($property->current_hash_ordinary_bill(), 0, 20); ?></div> <br />
                    <div class="title_bill_type">Original</div> <br />
                    Fecha: ... / <?= date("m",strtotime($building->date_next_period())); ?> / <?= date("Y",strtotime($building->date_next_period())); ?><br />
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
                            conceptos de expensas correspondientes al mes: <?= $property->building->month_name_actual_period(); ?> <?= date("Y",strtotime($building->date_next_period())); ?>. 
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
                                <!--<td>Expensa extraordinaria atrasada</td> -->
                                <td class="import"><?= number_format(abs($property->balance_reserve),2) ?></td>
                            </tr>
                            <tr class="table_row">
                                <td>Interes fondo de reserva atrasado</td>
                                <!--<td>Interes expensa extraordinaria atrasada</td>-->
                                <td class="import"><?= number_format(abs($property->due_interest_fund()),2) ?></td>
                            </tr>
                            <? endif; ?>
                            <tr class="table_row">
                                <td>Fondo de reserva actual</td>
                                <!--<td>Expensa extraordinaria actual</td>-->
                                <td class="import"><?= number_format($property->actual_reserve_fund(),2) ?></td>
                            </tr>
                            <? endif; ?>
                            <? if($property->building->has_special_expense_last_month()): ?>
                            <? foreach ($property->building->get_special_expenses_last_month() as $special_expense): ?>
                            <tr class="table_row">
                                <td><?= $special_expense->expense_tag->name ?></td>
                                <td class="import"><?= number_format($property->get_special_expense_to_pay($special_expense),2) ?></td>
                            </tr>
                            <? endforeach; ?>
                            <? endif; ?>
                            <!--<tr class="table_row">
                                <td>Total fondo de reserva al 10</td>
                                <td class="import"><?//= number_format( abs($property->actual_reserve_fund()) + abs($property->due_interest_fund()) + abs($property->due_fund()),2) ?></td>
                            </tr>-->
                            <tr class="table_row">
                                <td><hr></td>
                                <td class="import"><hr></td>
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
            <div class="sign">Admistración</div>
            
        </div>
        <div class="duplicate_right">
            <div class="title" style="background: url(../assets/img/home/header_report.jpg) no-repeat;">
                <div class="title_center">
                    Consorcio de Propietarios <? if($property->building->cuit): ?> CUIT: <?= $property->building->cuit ?> <? endif; ?><br>
                    Calle: <?= $property->building->street ?> Número <?= $property->building->number ?>
                </div>
                <div class="title_right">
                    <div class="title_bill_id"><?= substr ($property->current_hash_ordinary_bill(), 0, 20); ?></div> <br />
                    <div class="title_bill_type">Duplicado</div> <br />
                    Fecha: ... / <?= date("m",strtotime($building->date_next_period())); ?> / <?= date("Y",strtotime($building->date_next_period())); ?><br />
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
                            conceptos de expensas correspondientes al mes: <?= $property->building->month_name_actual_period(); ?> <?= date("Y",strtotime($building->date_next_period())); ?>. 
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
                                <!--<td>Expensa extraordinaria atrasada</td> -->
                                <td class="import"><?= number_format(abs($property->balance_reserve),2) ?></td>
                            </tr>
                            <tr class="table_row">
                                <td>Interes fondo de reserva atrasado</td>
                                <!--<td>Interes expensa extraordinaria atrasada</td>-->
                                <td class="import"><?= number_format(abs($property->due_interest_fund()),2) ?></td>
                            </tr>
                            <? endif; ?>
                            <tr class="table_row">
                                <td>Fondo de reserva actual</td>
                                <!--<td>Expensa extraordinaria actual</td>-->
                                <td class="import"><?= number_format($property->actual_reserve_fund(),2) ?></td>
                            </tr>
                            <? endif; ?>
                            <? if($property->building->has_special_expense_last_month()): ?>
                            <? foreach ($property->building->get_special_expenses_last_month() as $special_expense): ?>
                            <tr class="table_row">
                                <td><?= $special_expense->expense_tag->name ?></td>
                                <td class="import"><?= number_format($property->get_special_expense_to_pay($special_expense),2) ?></td>
                            </tr>
                            <? endforeach; ?>
                            <? endif; ?>
                            <!--<tr class="table_row">
                                <td>Total fondo de reserva al 10</td>
                                <td class="import"><?//= number_format( abs($property->actual_reserve_fund()) + abs($property->due_interest_fund()) + abs($property->due_fund()),2) ?></td>
                            </tr>-->
                            <tr class="table_row">
                                <td><hr></td>
                                <td class="import"><hr></td>
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
            <div class="sign">Admistración</div>
            
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