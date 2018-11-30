<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>Rendición de Cuenta Calle <?= $building->street ?> Número <?= $building->number ?></title>
    <link rel="stylesheet" type="text/css" href="<?= '/assets/css/reportsHTML/monthly_capitulation_building.css' ?>" />
    <script type="text/javascript" src="<?= '/assets/js/home/jquery.js'?>"></script>
    <script type="text/javascript" src="<?= '/assets/js/reportsHTML/monthly_capitulation_only_ordinary.js'?>"></script>
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
    <div class="title" style="background: url(/assets/img/home/header_report_rendicion.jpg) repeat-x; width:100%;">

        <div class="title_center">
            Consorcio de Propietarios Calle: <?= $building->street ?> Número <?= $building->number ?><br>
            CUIT: <?= $building->cuit ?> REG. SEG. SOCIAL - IVA SUJETO NO ALCANZADO<br>
            Informe mensual correspondiente al mes de : <?= $building->month_name_last_month($period); ?> / <?= date('Y', strtotime($period)); ?><br>

        </div>

        <div class="title_left">
            Administracion de Consorcios Jose Hernandez<br>
            Telefono: 0221-422-9410<br>
            Celular:  0221-15-485-1448
        </div>

    </div>
    <? $last_building_transaction = $building->get_last_month_building_transaction($period); ?>

    <div class="content">
        <table cellpadding="0"  cellspacing="0" class="table_properties">


            <tr>
                <th>EXPENSAS ORDINARIAS</th>
                <th class="right-text">Periodo</th>
                <th class="number_col">Ingresos</th>
                <th class="number_col">Egresos</th>
                <th class="number_col">Saldo</th>
                <th class="number_col">%</th>
            </tr>

            <tbody>
            <tr>
                <td>SALDO INICIAL AL</td>
                <td ><?= $building->initial_day_last_period($period); ?></td>
                <td colspan="2"></td>
                <td class="number_col"><?= number_format($last_building_transaction->last_reserve_fund + $last_building_transaction->last_balance,2)?></td>
                <td></td>
            </tr>
            </tbody>
            <!-- Ingresos -->

            <tr>
                <th colspan="6">INGRESOS</th>
            </tr>

            <tbody>
            <? $percentageI = 0; ?>
            <? $totalI = 0; ?>
            <tr>
                <td>INGRESO EXPENSA DEL MES</td>
                <td><?= $last_building_transaction->period_date->format("Y-m") ?></td>
                <td class="number_col"><?= number_format($building->total_ordinary_gain_without_interest_last_month($period),2); ?></td>
                <td colspan="2"></td>
                <td class="number_col"><?= number_format($building->total_ordinary_gain_without_interest_last_month($period) * 100 / $building->get_total_incomes_of_last_month(),2); ?></td>
                <? $percentageI += $building->total_ordinary_gain_without_interest_last_month($period) * 100 / $building->get_total_incomes_of_last_month($period); ?>
                <? $totalI += $building->total_ordinary_gain_without_interest_last_month($period); ?>
            </tr>
            <tr>
                <td>INGRESO EXPENSAS ATRASADAS</td>
                <td><?= $last_building_transaction->period_date->format("Y-m") ?></td>
                <td class="number_col"><?= number_format($building->total_ordinary_interest_gain_last_month($period),2); ?></td>
                <td colspan="2"></td>
                <td class="number_col"><?= number_format($building->total_ordinary_interest_gain_last_month($period) * 100 / $building->get_total_incomes_of_last_month($period),2); ?></td>
                <? $percentageI += $building->total_ordinary_interest_gain_last_month($period) * 100 / $building->get_total_incomes_of_last_month($period); ?>
                <? $totalI += $building->total_ordinary_interest_gain_last_month($period); ?>
            </tr>
            <? if($building->has_reserve_fund): ?>
                <tr>
                    <td>Ingresos Fondo de Reserva y Atras</td>
                    <td><?= $last_building_transaction->period_date->format("Y-m") ?></td>
                    <td class="number_col"><?= number_format($building->total_fund_gain_last_month($period),2); ?></td>
                    <td colspan="2"></td>
                    <td class="number_col"><?= number_format($building->total_fund_gain_last_month($period) * 100 / $building->get_total_incomes_of_last_month($period),2); ?></td>
                    <? $percentageI += $building->total_fund_gain_last_month($period) * 100 / $building->get_total_incomes_of_last_month($period); ?>
                    <? $totalI += $building->total_fund_gain_last_month($period); ?>
                </tr>
            <? endif; ?>
            <?
            if ($aditional_incomes != null):
                foreach ($aditional_incomes as $ai):
                    ?>
                    <tr>
                        <td><?= $ai->income_tag->name; ?></td>
                        <td><?= $ai->period_date->format("Y-M"); ?></td>
                        <td class="number_col"><?= number_format($ai->value,2); ?></td>
                        <td colspan="2"></td>
                        <td class="number_col"><?= number_format($ai->value * 100 / $building->get_total_incomes_of_last_month(),2); ?></td>
                        <? $percentageI += $ai->value * 100 / $building->get_total_incomes_of_last_month($period) ?>
                        <? $totalI += $ai->value; ?>
                    </tr>
                <?
                endforeach;
            endif;
            ?>
            <tr>
                <td colspan="6" />
            </tr>
            <tr id="tr-no-border-top">
                <td >TOTAL DE INGRESOS DEL MES</td>
                <td ></td>
                <td class="number_col"><?= number_format($building->get_total_incomes_of_last_month($period),2); ?></td>
                <td ></td>
                <td ></td>
                <td class="number_col"><?= $percentageI ?></td>
            </tr>
            </tbody>

            <!-- Egresos -->
            <? if($laboral_expenses != null || $non_laboral_expenses != null): ?>
                <tr>
                    <th colspan="6">EGRESOS</th>
                </tr>

                <!-- laboral expenses -->
                <? if($laboral_expenses != null): ?>

                    <tr>
                        <th id="sub_expense" class="sub_expense" colspan="6">COSTO LABORAL <!--(<?= number_format($building->get_percentage_from_type_expense_of_last_month(1),2);?>%)--></th>
                    </tr>
                    <tbody>
                    <? $percentageL = 0; ?>
                    <? $totalL = 0; ?>
                    <? foreach ($laboral_expenses as $e): ?>
                        <tr>
                            <td><?= $e->expense_tag->name ?></td>
                            <td><?= $e->date ?></td>
                            <td></td>
                            <td class="number_col"><?= number_format($e->value, 2) ?></td>
                            <td></td>
                            <td class="number_col"><?= number_format(round($e->value * 100 / $building->get_total_expense_of_last_month(), 3), 2) ?></td>
                            <? $percentageL += round($e->value * 100 / $building->get_total_expense_of_last_month(), 3) ?>
                            <? $totalL += $e->value; ?>
                        </tr>
                    <? endforeach; ?>
                    <tr>
                        <td colspan="3">TOTAL COSTO LABORAL</td>
                        <td class="number_col"><?= number_format($totalL, 2) ?></td>
                        <td></td>
                        <td class="number_col"><?= number_format($percentageL, 2) ?></td>
                    </tr>
                    </tbody>
                <? endif; ?>

                <!-- non laboral expenses -->
                <? if($non_laboral_expenses != null): ?>

                    <tr>
                        <th id="sub_expense" class="sub_expense" colspan="6">DEMAS GASTOS <!--(<?= number_format($building->get_percentage_from_non_type_expense_of_last_month(1),2);?>%)--></th>
                    </tr>
                    <tbody>
                    <? $totalNL = 0; ?>
                    <? $percentageNL = 0; ?>
                    <? foreach ($non_laboral_expenses as $e): ?>
                        <tr>
                            <td><?= $e->expense_tag->name ?></td>
                            <td><?= $e->date ?></td>
                            <td></td>
                            <td class="number_col"><?= number_format($e->value, 2) ?></td>
                            <td></td>
                            <td class="number_col"><?= number_format(round($e->value * 100 / $building->get_total_expense_of_last_month(), 3), 2) ?></td>
                            <? $percentageNL += round($e->value * 100 / $building->get_total_expense_of_last_month(), 3) ?>
                            <? $totalNL += $e->value; ?>
                        </tr>
                    <? endforeach; ?>
                    <tr>
                        <td colspan="3">TOTAL COSTO DEMAS GASTOS</td>
                        <td class="number_col"><?= number_format($totalNL, 2) ?></td>
                        <td></td>
                        <td class="number_col"><?= number_format($percentageNL, 2) ?></td>
                    </tr>
                    </tbody>
                <? endif; ?>
            <? endif; ?>
            <tbody>
            <tr>
                <td colspan="6" />
            </tr>
            <tr id="sub_expense" class="sub_expense">
                <td >TOTAL DE EGRESOS DEL MES</td>
                <td colspan="2"></td>
                <td class="number_col"><?= number_format($building->get_total_expense_of_last_month($period), 2); ?></td>
                <td></td>
                <td class="number_col">100</td>
            </tr>
            </tbody>
            <tbody id="tr-header">
            <tr>
                <td>SALDO FINAL AL</td>
                <td><?= $building->last_day_last_period($period); ?></td>
                <td colspan="2"></td>
                <?
                /*var_dump($last_building_transaction);
                var_dump($last_building_transaction->last_balance);
                var_dump($building->get_total_incomes_of_last_month($period));
                var_dump($building->get_total_expense_of_last_month($period),2);
                */
                ?>
                <td class="number_col"><?= number_format($last_building_transaction->last_reserve_fund + $last_building_transaction->last_balance + $building->get_total_incomes_of_last_month($period) - $building->get_total_expense_of_last_month($period),2)?></td>
            </tr>
            </tbody>
        </table>

        <? if(($estimative_expenses != null) && (count($estimative_expenses) > 0)): ?>

            <br />
            <table cellpadding="0"  cellspacing="0" class="table_properties">
                <tr>
                    <th colspan="6">GASTOS ESTIMATIVOS DE PAGOS CORRESPONDIENTES AL MES DE <?= strtoupper($building->month_name_from_current_period(-2)); ?> QUE SE PAGARA EN EL MES DE <?= strtoupper($building->month_name_from_current_period(-1)); ?></th>
                </tr>

                <tr>
                    <th>Egreso</th>
                    <th class="right-text">Periodo</th>
                    <th class="number_col">Egresos</th>
                </tr>

                <tbody>
                <? foreach($estimative_expenses as $expense): ?>
                    <tr>
                        <td><?= $expense->expense_tag->name ?></td>
                        <td><?= $expense->date ?></td>
                        <td class="number_col"><?= number_format($expense->value, 2) ?></td>
                    </tr>
                <? endforeach; ?>
                </tbody>
            </table>

        <? endif; ?>
    </div>

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