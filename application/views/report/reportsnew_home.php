<div id="site_content">

    <h1>Reportes Nuevos</h1>

    <table>
        <thead>
            <th>Building ID</th><th>Name</th><th>Period</th><th>Actions</th>


        </thead>
        <tbody>
            <? foreach($buildings as $building): ?>
                    <tr data-building_id="<?=$building->id ?>">
                        <td><?=$building->id ?> </td><td><?=$building->name ?> </td>
                        <td></td>
                        <td> <a id="generate_report_ordinary_a" href="/reportsNew/report_expenses_bill/36" >A Generar Recibo</a>  <button id="generate_report_ordinary" onclick="return false;;" >Generar Recibo</button>
                            <button id="generate_report_extraordinary" onclick="return false;;" >Generar Recibo Extraordinaria</button>
                            <button id="generate_summary" onclick="return false;;" >Generar Resumen Ordinaria</button>
                            <button id="generate_summary_extraordinary" onclick="return false;;" >Generar Resumen ExtraOrdinaria</button>
                            <button id="generate_capitulation_ordinary" onclick="return false;;" >Generar Recibo</button>
                            <button id="generate_capitulation_extraordinary" onclick="return false;;" >Generar Balance ExtraOrdinaria</button>

                        </td>
                    </tr>
                 var_dump($building);

            <? endforeach; ?>

        </tbody>
    </table>


    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Pago de Expensas</a></li>
            <li><a href="#tabs-2">Resumen de Expensas</a></li>
            <li><a href="#tabs-3">Rendición de Cuenta</a></li>
            <li><a href="#tabs-4">Balances</a></li>
            <li><a href="#tabs-5">Recibos Adicionales</a></li>
            <li><a href="#tabs-6">Operaciones Adicionales</a></li>
        </ul>
        <div id="tabs-1">
            <h2>Pago de Expensas</h2>
            <h4>Expensas Ordinarias</h4>
            <form id="frm_report_expense_bill" action="<?= site_url().'reports/report_expenses_bill' ?>" method="post">
                <div>
                    <div class="div_border">

                        <div class="adv_row">
                            <div class="adv_row_div_label"><label for="building_expense" >Edificio : </label></div>
                            <select name="building_expense" id="building_expense">
                                <?= get_select_building(set_value('building'));?>
                            </select>
                        </div>

                        <div class="adv_row">
                            <button id="generate_report_ordinary" onclick="return false;;" >Generar Recibo</button>
                            <!-- <button id="generate_report_payment_ordinary" onclick="return false;;" >Generar Recibo para el Pago</button> -->
                            <!--    <div class="button" id="submit_report_expense_bill" >Generar Reporte</div>-->
                        </div>
                    </div>
                </div>
            </form>
            <h4>Expensas Extraordinarias</h4>
            <form id="frm_report_extraordinary_expense_bill" action="<?= site_url().'reports/report_extraordinary_expenses_bill' ?>" method="post">
                <div>
                    <div class="div_border">
                        <div class="adv_row">
                            <div class="adv_row_div_label"><label for="building_expense_extra" >Edificio : </label></div>
                            <select name="building_expense_extra" id="building_expense_extra">
                                <?= get_select_building(set_value('building'));?>
                            </select>
                        </div>

                        <div class="adv_row">
                            <button id="generate_report_extraordinary" onclick="return false;;">Generar Recibo Extraordinario</button>
                            <!-- <button id="generate_report_payment_extraordinary" onclick="return false;;" >Generar Recibo para el Pago</button> -->
                            <!--    <div class="button" id="submit_report_expense_bill" >Generar Reporte</div>-->
                        </div>
                    </div>
                </div>
            </form>
            <!-- <h4>Expensas Ordinarias y Extraordinarias</h4>
        <form id="frm_report_extraordinary_and_ordinary_expense_bill" action="<?= site_url().'reports/report_extraordinary_and_ordinary_expense_bill' ?>" method="post">
        <div>
            <div class="div_border">
                <label for="building_expense_extraordinary_and_ordinary" >Edificio : </label>
                <select name="building_expense_extraordinary_and_ordinary" id="building_expense_extraordinary_and_ordinary">
                    <?= get_select_building(set_value('building'));?>
                </select>
                <div class="adv_col">
                    <button id="generate_report_extraordinary_and_ordinary" onclick="return false;;">Generar Recibo</button>
                    <button id="generate_report_payment_extraordinary_and_ordinary" onclick="return false;;" >Generar Recibo para el Pago</button>
                   <div class="button" id="submit_report_expense_bill" >Generar Reporte</div>
                </div>
            </div>
        </div>
        </form> -->
        </div>
        <div id="tabs-2">
            <h2>Resumen de Expensas</h2>
            <form id="frm_report_summary" action="<?= '/reports/report_expenses_month_building' ?>" method="post">
                <div>
                    <div class="div_border">
                        <div class="adv_row">
                            <div class="adv_row_div_label"><label for="building_summary" >Edificio : </label></div>
                            <select name="building_summary" id="building_summary">
                                <?= get_select_building(set_value('building'));?>
                            </select>
                        </div>

                        <div id="pay_days">
                        </div>
                        <div class="adv_row">
                            <button id="generate_summary" onclick="return false;;" >Generar Resumen</button><br />
                        </div>
                    </div>
                </div>
            </form>
            <h2>Resumen de Expensas Extraordinarias</h2>
            <form id="frm_report_summary_extrordinary" action="<?= site_url().'reports/report_expenses_summary_building_only_extraordinary' ?>" method="post">
                <div>
                    <div class="div_border">
                        <div class="adv_row">
                            <div class="adv_row_div_label"><label for="building_summary_extraordinary" >Edificio : </label></div>
                            <select name="building_summary_extraordinary" id="building_summary_extraordinary">
                                <?= get_select_building(set_value('building'));?>
                            </select>
                        </div>

                        <div class="adv_row">
                            <button id="generate_summary_extraordinary_only" onclick="return false;;" >Generar Resumen Extraordinario</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div id="tabs-3">
            <h2>Rendición de Cuenta</h2>
            <!-- <h4>Rendicion de Cuenta son rendicion Extraordinaria incluida</h4>
        <form id="frm_report_capitulation" action="<?= site_url().'reports/monthly_capitulation' ?>" method="post">
        <div>
            <div class="div_border">
                <label for="building_capitulation" >Edificio : </label>
                <select name="building_capitulation" id="building_capitulation">
                    <?= get_select_building(set_value('building'));?>
                </select>
                <div class="adv_col">
                    <button id="generate_capitulation" onclick="return false;;" >Generar Pdf para el Pago</button>
                </div>
            </div>
        </div>
        </form> -->
            <h4>Rendicion de cuenta Ordinaria</h4>
            <form id="frm_report_capitulation_ordinary" action="<?= site_url().'reports/monthly_capitulation_only_ordinary' ?>" method="post">
                <div>
                    <div class="div_border">
                        <div class="adv_row">
                            <div class="adv_row_div_label"><label for="building_capitulation_ordinary" >Edificio : </label></div>
                            <select name="building_capitulation_ordinary" id="building_capitulation_ordinary">
                                <?= get_select_building(set_value('building'));?>
                            </select>
                        </div>

                        <div class="adv_row">
                            <button id="generate_capitulation_ordinary" onclick="return false;;" >Generar Rendición</button>
                        </div>
                    </div>
                </div>
            </form>
            <h4>Rendicion de cuenta Extraordinaria</h4>
            <form id="frm_report_capitulation_extraordinary" action="<?= site_url().'reports/monthly_capitulation_only_extraordinary' ?>" method="post">
                <div>
                    <div class="div_border">
                        <div class="adv_row">
                            <div class="adv_row_div_label"><label for="building_capitulation_extraordinary" >Edificio : </label></div>
                            <select name="building_capitulation_extraordinary" id="building_capitulation_extraordinary">
                                <?= get_select_building(set_value('building'));?>
                            </select>
                        </div>

                        <div class="adv_row">
                            <button id="generate_capitulation_extraordinary" onclick="return false;;" >Generar Rendición Extraordinaria</button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
        <div id="tabs-4">
            <h2>Balances</h2>
            <h4>Balances General de Consorcio</h4>
            <form id="frm_report_balance" action="<?= site_url().'reports/report_short_monthly_balance_capitulation' ?>" method="post">
                <div>
                    <div class="div_border">
                        <div class="adv_row">
                            <div class="adv_row_div_label"><label class="label_balance" for="building_balance_select" >Edificio : </label></div>
                            <select name="building_balance_select" id="building_balance_select">
                                <?= get_select_building(set_value('building'));?>
                            </select>
                        </div>

                        <div id="div_month_building_from" style="display: none;">
                            <div class="adv_row">
                                <div class="adv_row_div_label"><label class="label_balance" for="month_building_from" >Periodo Desde: </label></div>
                                <select name="month_building_from" id="month_building_from">

                                </select>
                            </div>
                        </div>
                        <div id="div_month_building_to" style="display: none;">
                            <div class="adv_row">
                                <div class="adv_row_div_label"><label class="label_balance" for="month_building_to" >Periodo Hasta: </label></div>
                                <select name="month_building_to" id="month_building_to">

                                </select>
                            </div>
                        </div>
                        <div class="adv_row">
                            <button id="generate_balance">Generar Balance</button>
                        </div>
                    </div>

                </div>
            </form>

            <br />
            <h4>Editar tags del Balance</h4>
            <form id="frm_go_to_edit_tags" action="<?= site_url().'editTags' ?>" method="post">

                <div class="div_border">
                    <div class="adv_row">
                        <button id="generate_balance">Ir a editar tags</button>
                    </div>
                </div>

            </form>

        </div>
        <div id="tabs-5">
            <h2>Recibos Adicionales</h2>

            <h4>Recibo en blanco</h4>
            <form id="frm_blank_expense" action="<?= site_url().'reports/blank_expense' ?>" method="post">
                <div>
                    <div class="div_border">
                        <div class="adv_row">
                            <button id="generate_blank_expense" onclick="return false;;" >Generar Pdf de Expensas en blanco</button>
                        </div>
                    </div>
                </div>
            </form>

            <h4>Recibo para una unidad funcional específica</h4>
            <form id="frm_blank_expense" action="<?= site_url().'reports/blank_expense' ?>" method="post">
                <div>
                    <div class="div_border">

                        <div class="adv_row">
                            <div class="adv_row_div_label"><label class="label_balance" for="building_blank_select" >Edificio : </label></div>
                            <select name="building_blank_select" id="building_blank_select">
                                <?= get_select_building(set_value('building'));?>
                            </select>
                        </div>

                        <div class="adv_row">
                            <div class="adv_row_div_label"><label class="label_balance" for="properties_blank_select" >Propiedad : </label></div>
                            <select name="properties_blank_select" id="properties_blank_select">
                            </select>
                        </div>

                        <div class="adv_row">
                            <div class="adv_row_div_label"><label for="bill_concept" >Concepto: </label></div>
                            <input name="bill_concept" id="bill_concept" type="text" />
                        </div>

                        <div class="adv_row">
                            <div class="adv_row_div_label"><label for="bill_description" >Descripción: </label></div>
                            <input name="bill_description" id="bill_description" type="text" />
                        </div>

                        <div class="adv_row">
                            <div class="adv_row_div_label"><label for="bill_value" >Valor: </label></div>
                            <input name="bill_value" id="bill_value" type="text" />
                        </div>

                        <div class="adv_row">
                            <button id="generate_blank_expense" onclick="return false;;" >Generar Recibo de Expensas</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div id="tabs-6">
            <h2>Operaciones Adicionales</h2>


            <h4>Planilla de propiedades a cobrar por banco</h4>
            <form id="frm_schedule_sheet" action="<?= site_url().'reports/bank_payment_sheet' ?>" method="post">

                <div class="div_border">
                    <div class="adv_row">
                        <button type="submit" id="btn_schedule_sheet" >Planilla de propiedades a pagar por banco</button>
                    </div>
                </div>

            </form>

            <h4>Planilla de dias por edificio</h4>
            <form id="frm_schedule_sheet" action="<?= site_url().'reports/schedule_sheet' ?>" method="post">
                <div>
                    <div class="div_border">
                        <div class="adv_row">
                            <div class="adv_row_div_label"><label for="date_schedule_sheet" >Fecha :</label></div>
                            <input type="text" class="input_date" name="date_schedule_sheet" id="date_schedule_sheet" />
                        </div>

                        <div class="adv_row">
                            <button type="submit" id="btn_schedule_sheet" >Ver Horarios de visita</button>
                        </div>
                    </div>
                </div>
            </form>

            <h4>Agregar Dias de Cobro Autogenerados</h4>
            <div>
                <div class="div_border">
                    <div class="adv_row">
                        <div class="adv_row_div_label"><label class="label_balance" for="building_autogenerated_days_select" >Edificio : </label></div>
                        <select name="building_autogenerated_days_select" id="building_autogenerated_days_select">
                            <?= get_select_building(set_value('building'));?>
                        </select>
                    </div>

                    <div id="autogenerated_pay_days">
                    </div>
                </div>
            </div>

            <h4 style="display: none;">Enviar Emails</h4>
            <form id="frm_send_email" action="<?= site_url().'reports/send_emails' ?>" method="post" style="display: none;">
                <div>
                    <div class="div_border">
                        <div class="adv_col">
                            <button id="send_email_expense" onclick="return false;;" >Enviar resumenes al mail</button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>