<!DOCTYPE HTML>
<html>
    <head>
    <title>Rendición de Cuenta Calle <?= $building->street ?> Número <?= $building->number ?></title>
    <link rel="stylesheet" type="text/css" href="<?= url_css('/reports/monthly_capitulation_building.css') ?>" />
    </head>

<body>
    <div class="page">
        <div class="title" style="background: url(assets/img/home/header_report_rendicion.jpg) no-repeat;">
            
            <div class="title_center">
                Consorcio de Propietarios Calle: <?= $building->street ?> Número <?= $building->number ?><br>
                CUIT: <?= $building->cuit ?> REG. SEG. SOCIAL - IVA SUJETO NO ALCANZADO<br>
                Informe mensual correspondiente al mes de : <?= $building->month_name_last_month(); ?> / <?= date('Y'); ?><br>
                
            </div>
            
            <div class="title_left">
                Administracion de Consorcios Jose Hernandez<br>
                Telefono: 0221-422-9410<br>
                Celular:  0221-15-485-1448
            </div>
            
        </div>
        <? $last_building_transaction = $building->get_last_month_building_transaction(); ?>
        <div class="content">            
            <table cellpadding="0"  cellspacing="0" class="table_properties">
            
                
                    <tr>
                        <th>EXPENSAS ORDINARIAS</th>
                        <th class="right-text">Periodo</th>
                        <th>Ingresos</th>
                        <th>Egresos</th>
                        <th>Saldo</th>
                    </tr>
                
                <tbody>
                    <tr>
                        <td>SALDO INICIAL AL</td>
                        <td><?= $building->initial_day_last_period(); ?></td>
                        <td colspan="2"></td>
                        <td><?= number_format($last_building_transaction->last_reserve_fund + $last_building_transaction->last_balance,2)?></td>                        
                    </tr>
                </tbody>
            <!-- Ingresos -->
                
                    <tr>
                        <th colspan="5">INGRESOS</th>
                    </tr>
                
                <tbody>
                    <tr>
                        <td>Ingresos Expensas Ordinarias y Atras</td>
                        <td><?= $last_building_transaction->period_date->format("Y-m") ?></td>
                        <td><?= number_format($building->total_ordinary_gain_last_month(),2); ?></td>
                        <td colspan="2"></td>
                    </tr>
                    <? if($building->has_reserve_fund): ?>
                    <tr>
                        <td>Ingresos Fondo de Reserva y Atras</td>
                        <td><?= $last_building_transaction->period_date->format("Y-m") ?></td>
                        <td><?= number_format($building->total_fund_gain_last_month(),2); ?></td>
                        <td colspan="2"></td>
                    </tr>
                    <? endif; ?>
                <? 
                if ($aditional_incomes != null): 
                    foreach ($aditional_incomes as $ai):
                ?>
                    <tr>
                        <td><?= $ai->income_tag->name; ?></td>
                        <td><?= $ai->period_date->format("Y-M"); ?></td>
                        <td><?= number_format($ai->value,2); ?></td>
                        <td colspan="2"></td>
                    </tr>
                <? 
                    endforeach;
                endif;
                ?>
                    <tr>
                        <td colspan="5" />
                    </tr>
                    <tr id="tr-no-border-top">
                        <td >TOTAL DE INGRESOS DEL MES</td>
                        <td ></td>
                        <td ><?= number_format($building->get_total_incomes_of_last_month(),2); ?></td>
                        <td colspan="2"></td>
                    </tr>
                </tbody>
        
                <!-- Egresos -->
                
                    <tr>
                        <th colspan="5">EGRESOS</th>
                    </tr>
                
                <?
                if($expenses != null):
                    $type = 0;
                    foreach ($expenses as $e):
                        if($type != $e->type_expense_id):
                            if ($type != 0):
                ?>
                                </tbody>
                <?
                            endif;
                ?>
                
                    <tr>
                        <th id="sub_expense" class="sub_expense" colspan="5"><?= $e->type_expense->name?> (<?= number_format($building->get_percentage_from_type_expense_of_last_month($e->type_expense_id),2);?>%)</th>
                    </tr>
                
                <tbody>
                <?      
                            $type = $e->type_expense_id;
                        endif;
                ?>
                
                    <tr>
                        <td><?= $e->expense_tag->name ?></td>
                        <td><?= $e->date ?></td>
                        <td></td>
                        <td><?= number_format($e->value, 2) ?></td>
                        <td></td>
                    </tr>
                <?
                    endforeach;
                ?>
                    </tbody>
                <?
                endif;
                ?>
                
                <tbody>
                    <tr>
                        <td colspan="5" />
                    </tr>
                    <tr id="tr-no-border-top">
                        <td >TOTAL DE EGRESOS DEL MES</td>
                        <td colspan="2"></td>                        
                        <td><?= number_format($building->get_total_expense_of_last_month(), 2); ?></td>
                        <td ></td>
                    </tr>
                </tbody> 


                <!-- Egresos Especiales -->
                <? if($special_expenses != null): ?>
                    <tbody>
                        <tr>
                            <th colspan="5">EGRESOS ESPECIALES</th>
                        </tr>
                <? foreach ($special_expenses as $special_expense): ?>
                        <tr>
                            <td><?= $special_expense->expense_tag->name ?></td>
                            <td><?= $special_expense->date ?></td>
                            <td></td>
                            <td><?= number_format($special_expense->value, 2) ?></td>
                            <td></td>
                        </tr>
                <? endforeach; ?>
                    </tbody>
                <? endif; ?>

                <tbody id="tr-header">
                    <tr>
                        <td>SALDO FINAL AL</td>
                        <td><?= $building->last_day_last_period(); ?></td>
                        <td colspan="2"></td>
                        <td><?= number_format($last_building_transaction->last_reserve_fund + $last_building_transaction->last_balance + $building->get_total_incomes_of_last_month() - $building->get_total_expense_of_last_month(),2)?></td>                        
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Expensas Extraordinarias -->
        <? 
            if($extraordinary_periods):
                $total_extraordinary = 0;
                $total_extraordinary_fees = 0;
        ?>
        <div class="content">
            
            <table cellpadding="0"  cellspacing="0" class="table_properties">            
                
                    <tr>
                        <th>EXPENSAS EXTRAORDINARIAS</th>                        
                        <th>PERIODO</th>
                        <th>CUOTA</th>
                        <th>INGRESOS</th>
                        <th>EGRESOS</th>
                        <th>SALDO</th>
                    </tr>
                
                <tbody>
                    <tr>
                        <td>SALDO INICIAL AL</td>
                        <td><?= $building->initial_day_last_period(); ?></td>
                        <td colspan="3"></td>
                        <td><?= number_format($last_building_transaction->last_balance_extraordinary , 2)?></td>                        
                    </tr>
                </tbody>
                
                    <tr>
                        <th colspan="6">INGRESOS</th>
                    </tr>
                
                <tbody>
                <? 
                    
                    foreach ($extraordinary_periods as $period):                                       
                        $total_extraordinary = $total_extraordinary + round($building->total_gain_extra_last_month_for($period),2,PHP_ROUND_HALF_DOWN);                        
                ?>
                    <tr>
                        <td>Expensas Extraordinarias referidas a <?= $period->name ?></td>
                        <td></td>
                        <td><?= ($period->get_number_of_fees($period->building->actual_period) - 1) ?> / <?= $period->fees ?></td>
                        <td><?= number_format(round($building->total_gain_extra_last_month_for($period), 0 ,PHP_ROUND_HALF_DOWN),2) ?></td>
                        <td colspan="2"></td>
                    </tr>
                <?
                    endforeach;
                ?>
                </tbody>
                
                    <tr>
                        <th colspan="6">EGRESOS</th>
                    </tr>
                
                <tbody>
                <? 
                    $i = 0;
                    foreach ($extraordinary_periods as $period):                        
                        
                        $extra_expenses = $building->get_extra_expenses_last_month($period);
                        $total_extraordinary_fees = $total_extraordinary_fees + $building->total_expense_extra_last_month_for($period);
                        if (count($extra_expenses) > 0):
                            foreach ($extra_expenses as $extra_expense):
                            
                ?>
                    
                    <tr>
                        <td><?= $extra_expense->expense_tag->name ?></td>
                        <td></td>
                        <td><?= ($period->get_number_of_fees($period->building->actual_period)) - 1 ?> / <?= $period->fees ?></td>
                        <td></td>
                        <td><?= number_format($extra_expense->value,2) ?></td>
                        <td></td>
                    </tr>
                <?
                            endforeach;
                ?>
                    <tr>
                        <td >TOTAL DE EGRESOS EXTRAORDINARIOS DEL MES</td>
                        <td colspan="3"></td>                        
                        <td><?= number_format($total_extraordinary_fees, 2); ?></td>
                        <td ></td>
                    </tr>
                
                <?
                        else:
                            $i++;
                            if ($i == count(count($extra_expenses)))
                ?>
                    <tr>
                        <td>Sin Gastos</td>
                        <td colspan="5"></td>                        
                    </tr>
                <?            
                        endif;
                    endforeach;
                ?>
                </tbody>                
                <tbody id="tr-header">
                    <tr>
                        <td colspan="2">SALDO FINAL AL</td>
                        <td><?= $building->last_day_last_period(); ?></td>
                        <td colspan="2"></td>
                        <td><?= number_format($last_building_transaction->last_balance_extraordinary + $total_extraordinary - $total_extraordinary_fees,2)?></td>                        
                    </tr>
                </tbody>
            </table>
        </div>
        <?
            endif;
        ?>
        
        <div class="footer" >
            <div class="footer_firm">
                La documentación respaldatoria de la presente rendición de cuentas se encuentra a su disposición, contactese previamente.-
                
                <div class="footer_firm_sign">
                    Administrador: JOSE HERNANDEZ
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>