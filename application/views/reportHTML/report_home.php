<script type="text/javascript">
    $(function() {
            $("#tabs").tabs().addClass('ui-tabs-vertical ui-helper-clearfix');
            $("#tabs li").removeClass('ui-corner-top').addClass('ui-corner-left');
    });
</script>
<div id="site_content">

<h1>Reportes</h1>
<div id="tabs">
    <ul>
            <li><a href="#tabs-1">Pago de Expensas</a></li>
            <li><a href="#tabs-2">Resumen de Expensas</a></li>
            <li><a href="#tabs-3">Rendición de Cuenta</a></li>
            <li><a href="#tabs-4">Operaciones Adicionales</a></li>
    </ul>
    <div id="tabs-1">
        <h2>Pago de Expensas</h2>
        <h4>Expensas Ordinarias</h4>
        <form id="frm_report_expense_bill" action="<?= site_url().'reports/report_expenses_bill' ?>" method="post">
        <div>
            <div class="div_border">
                <label for="building_expense" >Edificio : </label>
                <select name="building_expense" id="building_expense">
                    <?= get_select_building(set_value('building'));?>
                </select>
                <div class="adv_col">
                    <button id="generate_report_ordinary" onclick="return false;;" >Generar Pdf</button>
                    <button id="generate_report_payment_ordinary" onclick="return false;;" >Generar Pdf para el Pago</button>
                <!--    <div class="button" id="submit_report_expense_bill" >Generar Reporte</div>-->
                </div>
            </div>
        </div>
        </form>        
        <h4>Expensas Extraordinarias</h4>
        <form id="frm_report_extraordinary_expense_bill" action="<?= site_url().'reports/report_extraordinary_expenses_bill' ?>" method="post">
        <div>
            <div class="div_border">
                <label for="building_expense_extra" >Edificio : </label>
                <select name="building_expense_extra" id="building_expense_extra">
                    <?= get_select_building(set_value('building'));?>
                </select>
                <div class="adv_col">
                    <button id="generate_report_extraordinary" onclick="return false;;">Generar Pdf</button>
                    <button id="generate_report_payment_extraordinary" onclick="return false;;" >Generar Pdf para el Pago</button>
                <!--    <div class="button" id="submit_report_expense_bill" >Generar Reporte</div>-->
                </div>
            </div>
        </div>
        </form>
        <h4>Expensas Ordinarias y Extraordinarias</h4>
        <form id="frm_report_extraordinary_and_ordinary_expense_bill" action="<?= site_url().'reports/report_extraordinary_and_ordinary_expense_bill' ?>" method="post">
        <div>
            <div class="div_border">
                <label for="building_expense_extraordinary_and_ordinary" >Edificio : </label>
                <select name="building_expense_extraordinary_and_ordinary" id="building_expense_extraordinary_and_ordinary">
                    <?= get_select_building(set_value('building'));?>
                </select>
                <div class="adv_col">
                    <button id="generate_report_extraordinary_and_ordinary" onclick="return false;;">Generar Pdf</button>
                    <button id="generate_report_payment_extraordinary_and_ordinary" onclick="return false;;" >Generar Pdf para el Pago</button>
                <!--    <div class="button" id="submit_report_expense_bill" >Generar Reporte</div>-->
                </div>
            </div>
        </div>
        </form>
    </div>
    <div id="tabs-2">
        <h2>Resumen de Expensas</h2>
        <form id="frm_report_summary" action="<?= site_url().'reports/report_expenses_month_building' ?>" method="post">
        <div>
            <div class="div_border">
                <label for="building_summary" >Edificio : </label>
                <select name="building_summary" id="building_summary">
                    <?= get_select_building(set_value('building'));?>
                </select>
                <div id="pay_days">
                </div>
                <div class="adv_col">
                    <button id="generate_summary" onclick="return false;;" >Generar Pdf para el Pago</button><br />                   
                </div>
            </div>
        </div>
        </form>
        <h2>Resumen de Expensas Extraordinarias</h2>
        <form id="frm_report_summary_extrordinary" action="<?= site_url().'reports/report_expenses_summary_building_only_extraordinary' ?>" method="post">
        <div>
            <div class="div_border">
                <label for="building_summary_extraordinary" >Edificio : </label>
                <select name="building_summary_extraordinary" id="building_summary_extraordinary">
                    <?= get_select_building(set_value('building'));?>
                </select>
                
                <div class="adv_col">
                    <button id="generate_summary_extraordinary_only" onclick="return false;;" >Generar Pdf para el Pago Extraordinario</button>
                </div>
            </div>
        </div>
        </form>
    </div>
    <div id="tabs-3">
        <h2>Rendición de Cuenta</h2>
        <h4>Rendicion de Cuenta son rendicion Extraordinaria incluida</h4>
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
        </form>
        <h4>Rendicion de cuenta Ordinaria</h4>
        <form id="frm_report_capitulation_ordinary" action="<?= site_url().'reports/monthly_capitulation_only_ordinary' ?>" method="post">
        <div>
            <div class="div_border">
                <label for="building_capitulation_ordinary" >Edificio : </label>
                <select name="building_capitulation_ordinary" id="building_capitulation_ordinary">
                    <?= get_select_building(set_value('building'));?>
                </select>
                <div class="adv_col">
                    <button id="generate_capitulation_ordinary" onclick="return false;;" >Generar Pdf para el Pago</button>
                </div>
            </div>
        </div>
        </form>        
        <h4>Rendicion de cuenta Extraordinaria</h4>
        <form id="frm_report_capitulation_extraordinary" action="<?= site_url().'reports/monthly_capitulation_only_extraordinary' ?>" method="post">
        <div>
            <div class="div_border">
                <label for="building_capitulation_extraordinary" >Edificio : </label>
                <select name="building_capitulation_extraordinary" id="building_capitulation_extraordinary">
                    <?= get_select_building(set_value('building'));?>
                </select>
                <div class="adv_col">
                    <button id="generate_capitulation_extraordinary" onclick="return false;;" >Generar Pdf para el Pago</button>
                </div>
            </div>
        </div>
        </form>        
        
    </div>
    <div id="tabs-4">
        <h2>Operaciones Adicionales</h2>
        <form id="frm_blank_expense" action="<?= site_url().'reports/blank_expense' ?>" method="post">
        <div>
            <div class="div_border">
                <div class="adv_col">
                    <button id="generate_blank_expense" onclick="return false;;" >Generar Pdf de Expensas en blanco</button>
                </div>
            </div>
        </div>
        </form>
    </div>
</div>