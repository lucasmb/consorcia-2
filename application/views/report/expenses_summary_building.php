<!DOCTYPE HTML>
<html>
    <head>
    <title>Informe - Expensas Calle <?= $building->street?> - <?=$building->number?> </title>
        <link rel="stylesheet" type="text/css" href="<?= url_css('/reports/expenses_summary_building.css') ?>" />
    </head>

<body>
<? 
if ($properties):    
    $total_extraordinary  = 0;
    $total_building = 0;
?>
<div class="page">
    <div>
        <div class="title" style="background: url(assets/img/home/header_report.jpg) no-repeat;">
            <div class="title_center">
                Consorcio de Propietarios Calle: <?= $building->street ?> Número <?= $building->number ?><br>
                Informe mensual correspondiente al mes de : <?= $building->month_name_actual_period(); ?> / <?= date('Y'); ?>
            </div>
        </div>
        <? $colspan = 8;?>
        <div class="content">
            <table cellpadding="0" cellspacing="0" class="table_properties">
                <thead>
                    <tr class="table_header">
                        <th>Propietario</th>
                        <th>UF</th>
                        <th>Piso</th>
                        <th>Dto</th>
                        <th>COEF</th>
                        <th>Exp Atrasada</th>
                        <th>Interes Exp Atrasada</th>
                        <th>Exp comun Actual</th>
                    <? 
                        if($building->has_reserve_fund): 
                            $colspan = $colspan + 3;
                    ?>
                        <th>F. Res atras</th>
                        <th>Interes Fond Res</th>
                        <th>Fond Res Act</th>
                    <? endif; ?>
                    <? 
                        if($building->has_special_expense_last_month()): 
                            $colspan = $colspan + 1;
                    ?>
                        <th>Gastos Esp. **</th>
                    <? endif; ?>
                    <? 
                        if($extraordinary_periods != null): 
                            $colspan = $colspan + 3;
                    ?>
                        <th>Extra ord. Atras</th>
                        <th>Int Extra ord.</th>
                        <th>Extra ord.  Act</th>
                    <? endif;?>
                        <th>Total Al 15</th>
                    </tr>
                </thead>
                <tbody>
<?
    foreach($properties as $property):
?>       
                    <tr class="table_row" >
                        <td id="owner" ><?= $property->owner->lastname." ".substr($property->owner->name,0,1)?></td>
                        <td><?= $property->functional_unity?></td>
                        <td><?= $property->floor ?></td>
                        <td><?= $property->appartment ?></td>
                        <td><?= $property->coefficient ?></td>
                        <td><?= number_format(abs($property->balance),2) ?></td>
                        <td><?= number_format(abs($property->due_interest_expense()),2) ?></td>
                        <td><?= number_format($property->actual_common_expense(),2) ?></td>
                    <? if($building->has_reserve_fund): ?>
                        <td><?= number_format(abs($property->due_fund()),2) ?></td>
                        <td><?= number_format(abs($property->due_interest_fund()),2) ?></td>
                        <td><?= number_format(abs($property->actual_reserve_fund()),2) ?></td>
                    <? endif; ?>
                    <? if($building->has_special_expense_last_month()): ?>
                        <td><?= number_format(abs($property->building->get_special_expense_for_property_last_month($property)),2) ?></td>
                    <? endif; ?>
                    <? 
                        $sum_ext  = 0;
                        if($extraordinary_periods != null): 
                            $sum_due = 0 ; $sum_pay = 0; 
                            foreach($extraordinary_periods as $period):
                                $sum_due += $property->value_of_extraordinary_due($period);
                                $sum_pay += $property->value_of_extraordinary_fee($period);
                                $sum_ext += $property->value_to_pay_extraordinary($period);
                            endforeach;
                            $total_extraordinary += $sum_ext;
                    ?>
                        
                        <td><?= number_format(abs($property->balance_extraordinary),2); ?></td>
                        <td><?= number_format(abs($sum_due),2); ?></td>
                        <td><?= number_format(abs($sum_pay),2); ?></td>
                    <?  endif ;?>
                    
                        <!--<td><?//= number_format(round($property->total_to_pay(), 0 ,PHP_ROUND_HALF_DOWN),2) ?></td>-->
                    <? $total_building += round($property->total_to_pay() + $sum_ext, 0 ,PHP_ROUND_HALF_DOWN); ?>   
                        <td><?= number_format(round($property->total_to_pay() + $sum_ext, 0 ,PHP_ROUND_HALF_DOWN),2) ?></td>
                    </tr>
<?
    endforeach;
?>
                    <tr class="table_row">
                        <td colspan="<?= $colspan ?>">Total a Recaudar:</td>                        
                        <td><?= number_format($total_building,2); ?></td>
                        
                    </tr>
                </tbody>
            </table>


        </div>
    </div>
    
    <? if($building->has_special_expense_last_month()): ?>
        <div class="special_pays">
            ** Los gastos especiales son referidos a: <br />
            <ul>
                <? foreach ($building->get_special_expenses_last_month() as $expense): ?>
                    <li><?= $expense->expense_tag->name ?></li>
                <? endforeach; ?>
            </ul>
            
        </div>        
    <? endif; ?>
    
    <? if ($pay_days): ?>
    <div class="footer_days">
        <div class="header_footer_days">
                <div class="pay_day_title">Días de Cobro <?= $building->month_name_next_period(); ?> <?= date('Y'); ?></div>

                <table cellpadding="2" >
                <? 
                    foreach ($pay_days as $day):
                ?>                
                    
                <tr>
                    <td><?= $day->date->format("d") . ' - ' . week_day_name($day->date->format("Y-m-d")) ?></td>
                    <td><?= 'Horario: ' . $day->hour_start . ':' . $day->minuts_start; ?><? if( $day->hour_end != "" ): echo ' - ' .  $day->hour_end . ':' . $day->minuts_end; endif; ?></td>
                </tr>                
                
                <?
                    endforeach;
                ?>
                </table>

        </div>
        
    </div>
    <div class="footer_due_days">PASADO LOS DIAS DE COBRO TIENE PLAZO HASTA EL DIA 20 DEL CORRIENTE PARA REALIZAR EL PAGO DE EXPENSAS EN LA ADMINISTRACION SITUADA EN CALLE 43 Nº480 5TO D LOS DIAS LUNES, MIERCOLES Y VIERNES EN EL HORARIO DE 11HS A 12HS</div>
    <? endif; ?>
</div>
<?    
endif;
?>