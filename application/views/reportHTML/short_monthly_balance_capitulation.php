<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>Balance de Consorcio Calle <?= $building->street ?> Número <?= $building->number ?>
               Desde <?= $periods[0]->period_date->format("Y-m") ?> - 
               Hasta <?= $periods[count($periods) - 1]->period_date->format("Y-m") ?>
        </title>
    
        <link rel="stylesheet" type="text/css" href="<?= url_css('/reportsHTML/short_monthly_balance_capitulation.css') ?>" />
        <script type="text/javascript" src="<?= url_js('/home/jquery.js')?>"></script>
        <script type="text/javascript" src="<?= url_js('/reportsHTML/font_resizer.js')?>"></script>
        
    </head>
    <style type="text/css" media="print">
        @page { size: landscape; }
    </style>

<body>
    <div class="no-print">
        <button id="increaseFont"> + </button>
        <button id="decreaseFont"> - </button>
        <button id="resetFont"> Reset </button>
        <button id="print"> Print</button>
    </div>
    <div class="content">
        <table class="table_properties">
            <!-- Periods -->
            <thead>
                    <th>EXPENSAS ORDINARIAS</th>
                <? foreach ($periods as $period): ?>
                    <th><?= $period->period_date->format("Y-m") ?></th>
                <? endforeach; ?>
                    <th>TOTALES</th>
                    <th>%</th>
            </thead>
            
            
            <tbody>
                
                <!-- Saldo inicial -->
                <tr>
                    <td>SALDO INICIAL</td>
                <? foreach ($periods as $period): ?>
                    <td class="to_right"><?= number_format($period->last_balance,2) ?></td>
                <? endforeach; ?>
                </tr>
                
                <!-- Ingresos ordinary -->
                <tr>
                    <td>INGRESOS ORDINARIOS Y ATRASADOS</td>
                <? 
                    $sum_incomes_ordinary = 0;
                    foreach ($periods as $period): 
                ?>
                    <td class="to_right">
                        <?  $sum_incomes_ordinary = $sum_incomes_ordinary + $building->get_total_ordinary_incomes_for_period($period->period_date->format("Y-m-d")); ?>
                        <?= number_format($building->get_total_ordinary_incomes_for_period($period->period_date->format("Y-m-d")),2) ?>
                    </td>
                <? endforeach; ?>
                    <td class="to_right"><?= number_format($sum_incomes_ordinary,2) ?></td>
                <!-- porcentual ordinary income -->
                <? 
                    $sum_total_income = 0;
                    foreach ($periods as $period): 
                        $sum_total_income = $sum_total_income + $building->get_total_ordinary_incomes_for_period($period->period_date->format("Y-m-d")) +
                        $building->get_total_aditional_incomes_value_for_period($period->period_date->format("Y-m-d"));
                    endforeach;
                ?>
                <td class="to_right"><?= number_format($sum_incomes_ordinary * 100 / $sum_total_income,2) ?></td>
                        
                
                    
                </tr>
                
                <!-- Aditional incomes -->
                <? if($income_balance_tags || $other_incomes): ?>
                    <!-- Header aditional incomes -->
                    <tr>
                        <td class="subheader" colspan="<?= count($periods) + 3?>">INGRESOS ADICIONALES</td>
                    </tr>
                    
                    <!-- Each aditional incomes -->
                    
                    <? 
                    if($income_balance_tags):
                        foreach ($income_balance_tags as $income_tag): 
                    ?>
                    <tr>
                        <td><?= $income_tag->name ?></td>
                        <? 
                            $sum_aditional_income = 0;
                            foreach ($periods as $period): 
                                //$sum_aditional_income = $sum_aditional_income + $building->get_aditional_income_value_for_tag_and_period($income_tag->income_tag_id,$period->period_date->format("Y-m-d"));
                                $sum_aditional_income = $sum_aditional_income + $building->get_aditional_income_value_for_balance_tag_and_period($income_tag->id,$period->period_date->format("Y-m-d"));
                        ?>                        
                        <td class="to_right"><?= number_format($building->get_aditional_income_value_for_balance_tag_and_period($income_tag->id,$period->period_date->format("Y-m-d")),2) ?></td>
                        <?  endforeach; ?>
                        <td class="to_right"><?= number_format($sum_aditional_income,2) ?></td>
                        <? 
                            $sum_total_income = 0;
                            foreach ($periods as $period): 
                                $sum_total_income = $sum_total_income + $building->get_total_ordinary_incomes_for_period($period->period_date->format("Y-m-d")) +
                                $building->get_total_aditional_incomes_value_for_period($period->period_date->format("Y-m-d"));
                            endforeach;
                        ?>
                        <td class="to_right"><?= number_format($sum_aditional_income * 100 / $sum_total_income,2) ?></td>
                    </tr>
                    <? 
                        endforeach; 
                    endif;
                    ?>
                    
                    <!-- Other Aditional incomes -->
                    <?
                    if ($other_incomes):
                        foreach ($other_incomes as $income_tag): 
                    ?>
                    <tr>
                        <td><?= $income_tag->name ?></td>
                        <? 
                            $sum_other_income = 0;
                            foreach ($periods as $period): 
                                $sum_other_income = $sum_other_income + $building->get_aditional_income_value_for_tag_and_period($income_tag->income_tag_id,$period->period_date->format("Y-m-d"));
                        ?>                        
                        <td class="to_right"><?= number_format($building->get_aditional_income_value_for_tag_and_period($income_tag->income_tag_id,$period->period_date->format("Y-m-d")),2) ?></td>
                        <?  endforeach; ?>
                        <td class="to_right"><?= number_format($sum_other_income,2) ?></td>
                    </tr>
                    <? 
                        endforeach;
                    endif;
                    ?>
                    
                    <!-- Total aditional incomes -->
                    <tr class="subfooter">
                        <td>INGRESOS ADICIONALES TOTALES</td>
                        <? 
                            $sum_total_aditional_income = 0;
                            foreach ($periods as $period): 
                                $sum_total_aditional_income = $sum_total_aditional_income + $building->get_total_aditional_incomes_value_for_period($period->period_date->format("Y-m-d"))
                        ?>
                        <td class="to_right"><?= number_format($building->get_total_aditional_incomes_value_for_period($period->period_date->format("Y-m-d")),2) ?></td>
                        <? endforeach; ?>
                        <td class="to_right"><?= number_format($sum_total_aditional_income,2) ?></td>
                        <? 
                            $sum_total_income = 0;
                            foreach ($periods as $period): 
                                $sum_total_income = $sum_total_income + $building->get_total_ordinary_incomes_for_period($period->period_date->format("Y-m-d")) +
                                $building->get_total_aditional_incomes_value_for_period($period->period_date->format("Y-m-d"));
                            endforeach;
                        ?>
                        <td class="to_right"><?= number_format($sum_total_aditional_income * 100 / $sum_total_income,2) ?></td>
                    </tr>
                <? endif; ?>
                
                <!-- Total incomes -->
                <tr class="subfooter">
                    <td>TOTAL DE INGRESOS DEL MES</td>
                    <? 
                        $sum_total_income = 0;
                        foreach ($periods as $period): 
                            $sum_total_income = $sum_total_income + $building->get_total_ordinary_incomes_for_period($period->period_date->format("Y-m-d")) +
                            $building->get_total_aditional_incomes_value_for_period($period->period_date->format("Y-m-d"))
                    ?>
                    <td class="to_right"><?= number_format($building->get_total_ordinary_incomes_for_period($period->period_date->format("Y-m-d")) +
                            $building->get_total_aditional_incomes_value_for_period($period->period_date->format("Y-m-d")),2) ?></td>
                    <? endforeach; ?>
                    <td class="to_right"><?= number_format($sum_total_income,2) ?></td>
                    <td class="to_right">100</td>
                </tr>
                    
                <!-- Laboral expenses -->
                <? if($expense_laboral_balance_tags || $expense_laboral_others_tags): ?>
                
                    <!-- Header laboral expenses -->
                    <tr>
                        <td class="subheader" colspan="<?= count($periods) + 3?>">EGRESOS LABORALES</td>
                    </tr>
                    
                    <!-- Each laboral expense -->
                    <? 
                    if ($expense_laboral_balance_tags):
                        foreach ($expense_laboral_balance_tags as $expense_balance_tag): 
                    ?>
                    <tr>
                        <td><?= $expense_balance_tag->name ?></td>
                        <? 
                            $sum_expense_laboral = 0;
                            foreach ($periods as $period): 
                                $sum_expense_laboral = $sum_expense_laboral + $building->get_expense_value_for_balance_tag_and_period($expense_balance_tag->id,$period->period_date->format("Y-m-d"));
                        ?>
                        <td class="to_right"><?= number_format($building->get_expense_value_for_balance_tag_and_period($expense_balance_tag->id,$period->period_date->format("Y-m-d")),2) ?></td>
                        <? endforeach; ?>
                        <td class="to_right"><?= number_format($sum_expense_laboral,2) ?></td>
                        <!-- porcentual expense -->
                        <? 
                            $sum_total_expense = 0;
                            foreach ($periods as $period):
                                $sum_total_expense = $sum_total_expense + $building->get_total_expenses_laboral_value_for_period($period->period_date->format("Y-m-d")) + 
                                $building->get_total_expenses_non_laboral_value_for_period($period->period_date->format("Y-m-d"));
                            endforeach;
                        ?>
                        <td class="to_right"><?= number_format($sum_expense_laboral * 100 / $sum_total_expense,2) ?></td>
                    </tr>
                    <? 
                        endforeach; 
                    endif;
                    ?>
                    
                    <!-- Other laboral expense -->
                    <? 
                    if ($expense_laboral_others_tags):
                        foreach ($expense_laboral_others_tags as $expense_tag): 
                    ?>
                    <tr>
                        <td><?= $expense_tag->name ?> - <?= $expense_tag->expense_tag_id ?></td>
                        <? 
                            $sum_expense_laboral = 0;
                            foreach ($periods as $period): 
                                $sum_expense_laboral = $sum_expense_laboral + $building->get_expense_value_for_other_tag_and_period($expense_tag->expense_tag_id,$period->period_date->format("Y-m-d"));
                        ?>
                        <td class="to_right"><?= number_format($building->get_expense_value_for_other_tag_and_period($expense_tag->expense_tag_id,$period->period_date->format("Y-m-d")),2) ?></td>
                        <? endforeach; ?>
                        <td class="to_right"><?= number_format($sum_expense_laboral,2) ?></td>
                        <!-- porcentual expense -->
                        <? 
                            $sum_total_expense = 0;
                            foreach ($periods as $period):
                                $sum_total_expense = $sum_total_expense + $building->get_total_expenses_laboral_value_for_period($period->period_date->format("Y-m-d")) + 
                                $building->get_total_expenses_non_laboral_value_for_period($period->period_date->format("Y-m-d"));
                            endforeach;
                        ?>
                        <td class="to_right"><?= number_format($sum_expense_laboral * 100 / $sum_total_expense,2) ?></td>
                    </tr>
                    <? 
                        endforeach; 
                    endif;
                    ?>
                    
                    <!-- Total laboral expenses  -->
                    <tr class="subfooter">
                        <td>EGRESOS LABORALES TOTALES</td>
                        <? 
                            $sum_total_laboral_expense = 0;
                            foreach ($periods as $period): 
                                $sum_total_laboral_expense  = $sum_total_laboral_expense  + $building->get_total_expenses_laboral_value_for_period($period->period_date->format("Y-m-d"));
                        ?>
                        <td class="to_right"><?= number_format($building->get_total_expenses_laboral_value_for_period($period->period_date->format("Y-m-d")),2) ?></td>
                        <? endforeach; ?>
                        <td class="to_right"><?= number_format($sum_total_laboral_expense,2)  ?></td>
                        <!-- porcentual expense -->
                        <? 
                            $sum_total_expense = 0;
                            foreach ($periods as $period):
                                $sum_total_expense = $sum_total_expense + $building->get_total_expenses_laboral_value_for_period($period->period_date->format("Y-m-d")) + 
                                $building->get_total_expenses_non_laboral_value_for_period($period->period_date->format("Y-m-d"));
                            endforeach;
                        ?>
                        <td class="to_right"><?= number_format($sum_total_laboral_expense * 100 / $sum_total_expense,2) ?></td>
                    </tr>
                <? endif; ?>
                    
                <!-- Non Laboral expenses -->
                <? if($expense_non_laboral_balance_tags || $expense_non_laboral_others_tags): ?>
                    
                    <!-- Header non laboral expenses -->
                    <tr>
                        <td class="subheader" colspan="<?= count($periods) + 3?>">DEMAS EGRESOS</td>
                    </tr>
                    
                    <? 
                    if($expense_non_laboral_balance_tags):
                        foreach ($expense_non_laboral_balance_tags as $expense_balance_tag):
                    ?>
                    <tr>
                        <td><?= $expense_balance_tag->name ?></td>
                        <? 
                            $sum_expense_non_laboral = 0;
                            foreach ($periods as $period):
                                $sum_expense_non_laboral = $sum_expense_non_laboral + $building->get_expense_value_for_balance_tag_and_period($expense_balance_tag->id,$period->period_date->format("Y-m-d"));
                        ?>
                        <td class="to_right"><?= number_format($building->get_expense_value_for_balance_tag_and_period($expense_balance_tag->id,$period->period_date->format("Y-m-d")),2) ?></td>
                        <? endforeach; ?>
                        <td class="to_right"><?= number_format($sum_expense_non_laboral,2) ?></td>
                        <!-- porcentual expense -->
                        <? 
                            $sum_total_expense = 0;
                            foreach ($periods as $period):
                                $sum_total_expense = $sum_total_expense + $building->get_total_expenses_laboral_value_for_period($period->period_date->format("Y-m-d")) + 
                                $building->get_total_expenses_non_laboral_value_for_period($period->period_date->format("Y-m-d"));
                            endforeach;
                        ?>
                        <td class="to_right"><?= number_format($sum_expense_non_laboral * 100 / $sum_total_expense,2) ?></td>
                    </tr>
                    <? 
                        endforeach;
                    endif;
                    ?>
                    
                    <!-- Other non laboral expense -->
                    <? 
                    if ($expense_non_laboral_others_tags):
                        foreach ($expense_non_laboral_others_tags as $expense_tag): 
                    ?>
                    <tr>
                        <td><?= $expense_tag->name ?>   <?= $expense_tag->expense_tag_id ?></td>
                        <? 
                            $sum_expense_laboral = 0;
                            foreach ($periods as $period): 
                                $sum_expense_laboral = $sum_expense_laboral + $building->get_expense_value_for_other_tag_and_period($expense_tag->expense_tag_id,$period->period_date->format("Y-m-d"));
                        ?>
                        <td class="to_right"><?= number_format($building->get_expense_value_for_other_tag_and_period($expense_tag->expense_tag_id,$period->period_date->format("Y-m-d")),2) ?></td>
                        <? endforeach; ?>
                        <td class="to_right"><?= number_format($sum_expense_laboral,2) ?></td>
                        <!-- porcentual expense -->
                        <? 
                            $sum_total_expense = 0;
                            foreach ($periods as $period):
                                $sum_total_expense = $sum_total_expense + $building->get_total_expenses_laboral_value_for_period($period->period_date->format("Y-m-d")) + 
                                $building->get_total_expenses_non_laboral_value_for_period($period->period_date->format("Y-m-d"));
                            endforeach;
                        ?>
                        <td class="to_right"><?= number_format($sum_expense_laboral * 100 / $sum_total_expense,2) ?></td>
                    </tr>
                    <? 
                        endforeach; 
                    endif;
                    ?>
                    
                    <!-- Total non laboral expenses  -->
                    <tr class="subfooter">
                        <td>DEMAS EGRESOS TOTALES</td>
                        <? 
                            $sum_total_expense_non_laboral = 0;
                            foreach ($periods as $period):
                                $sum_total_expense_non_laboral = $sum_total_expense_non_laboral + $building->get_total_expenses_non_laboral_value_for_period($period->period_date->format("Y-m-d"));
                        ?>
                        <td class="to_right"><?= number_format($building->get_total_expenses_non_laboral_value_for_period($period->period_date->format("Y-m-d")),2) ?></td>
                        <? endforeach; ?>
                        <td class="to_right"><?= $sum_total_expense_non_laboral ?></td>
                        <!-- porcentual expense -->
                        <? 
                            $sum_total_expense = 0;
                            foreach ($periods as $period):
                                $sum_total_expense = $sum_total_expense + $building->get_total_expenses_laboral_value_for_period($period->period_date->format("Y-m-d")) + 
                                $building->get_total_expenses_non_laboral_value_for_period($period->period_date->format("Y-m-d"));
                            endforeach;
                        ?>
                        <td class="to_right"><?= number_format($sum_total_expense_non_laboral * 100 / $sum_total_expense,2) ?></td>
                    </tr>
                <? endif; ?>
                    
                    <!-- Total expenses  -->
                    <tr class="subfooter">
                        <td>TOTAL DE EGRESOS DEL MES</td>
                        <? 
                            $sum_total_expense = 0;
                            foreach ($periods as $period):
                                $sum_total_expense = $sum_total_expense + $building->get_total_expenses_laboral_value_for_period($period->period_date->format("Y-m-d")) + 
                            $building->get_total_expenses_non_laboral_value_for_period($period->period_date->format("Y-m-d"));
                        ?>
                        <td class="to_right"><?= number_format($building->get_total_expenses_laboral_value_for_period($period->period_date->format("Y-m-d")) + 
                            $building->get_total_expenses_non_laboral_value_for_period($period->period_date->format("Y-m-d")),2) ?></td>
                        <? endforeach; ?>
                        <td class="to_right"><?= $sum_total_expense ?></td>
                        <td class="to_right">100</td>
                    </tr>
                    
                <!-- Saldo Final -->
                <tr class="subfooter">
                    <td>SALDO FINAL</td>
                <? foreach ($periods as $period): ?>
                    <td class="to_right">
                        <?= number_format($period->last_balance + 
                            $building->get_total_ordinary_incomes_for_period($period->period_date->format("Y-m-d")) +
                            $building->get_total_aditional_incomes_value_for_period($period->period_date->format("Y-m-d")) - 
                            $building->get_total_expenses_laboral_value_for_period($period->period_date->format("Y-m-d")) - 
                            $building->get_total_expenses_non_laboral_value_for_period($period->period_date->format("Y-m-d")),2); ?>
                    </td>
                <? endforeach; ?>
                </tr>
            </tbody>
                
        </table>
        
        
    </div>
</body>
</html>