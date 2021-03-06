<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>Rendición de Cuenta Extraordinaria Calle <?= $building->street ?> Número <?= $building->number ?></title>
        <link rel="stylesheet" type="text/css" href="<?= '/assets/css/reportsHTML/monthly_only_extraordinary_capitulation_building.css' ?>" />
        <script type="text/javascript" src="<?= '/assets/js/home/jquery.js'?>"></script>
        <script type="text/javascript" src="<?= '/assets/js/reportsHTML/monthly_capitulation_only_extraordinary.js'?>"></script>
    </head>
    <style type="text/css" media="print">
        @page { size: portrait; }
    </style>

<body>
    <div class="no-print">
        <button id="increaseFont"> + </button>
        <button id="decreaseFont"> - </button>
        <button id="resetFont"> Reset </button>
        <button id="print"> Print</button>
    </div>
    <div class="page">
        <div class="title" style="background: url(../assets/img/home/header_report_rendicion.jpg) repeat-x; width:100%;">
            
            <div class="title_center">
                Consorcio de Propietarios Calle: <?= $building->street ?> Número <?= $building->number ?><br>
                CUIT: <?= $building->cuit ?> REG. SEG. SOCIAL - IVA SUJETO NO ALCANZADO<br>
                Informe mensual correspondiente al mes de : <?= $building->month_name_last_month($period); ?> / <?= date('Y'); ?><br>
                
            </div>
            
            <div class="title_left">
                Administracion de Consorcios Jose Hernandez<br>
                Telefono: 0221-484-6188<br>
                Celular:  0221-15-485-1448
            </div>
            
        </div>
        <? $last_building_transaction = $building->get_last_month_building_transaction(); ?>
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
                        <th>CUOTA / FECHA</th>
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
                        <td class="extraordinary_period_description">Expensas Extraordinarias referidas a <?= $period->name ?></td>
                        <td></td>
                        <td>
                        <? if($period->get_number_of_fees($period->building->actual_period) > $period->fees): ?>
                            Cuotas pendientes
                        <? else: ?>
                            Cuota: <?= $period->get_number_of_fees($period->building->actual_period) ?> / <?= $period->fees ?>
                        <? endif; ?>
                        </td>
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
                       // var_dump($extraordinary_periods);
                        $extra_expenses = $building->get_extra_expenses_last_month($period);
                        $total_extraordinary_fees = $total_extraordinary_fees + $building->total_expense_extra_last_month_for($period);
                        if (count($extra_expenses) > 0):
                            foreach ($extra_expenses as $extra_expense):
                            
                ?>
                    
                    <tr>
                        <td><?= $extra_expense->expense_tag->name ?></td>
                        <td></td>
                        <td><?= $extra_expense->date ?></td>
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