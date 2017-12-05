<div id="site_content">
    <h1>Ingresos</h1>
    
    <div class="div_border col div_building_income">
        <div class="col-2">
            <div class="col income_center_title">
                Edificio:
            </div>
            <div class="col">
                <select name="building" id="building">
                    <?= get_select_building(set_value('building'));?>
                </select>        
            </div>
        </div>

        <div class="col-2 operations_div result_div">
        
        </div>

    </div>
    
    <div id="tabs">
            <ul>
                <li><a href="#tabs-1">Resumen</a></li>
                <li><a href="#tabs-2">Pago de expensas</a></li>
                <li><a href="#tabs-3">Pago de Extraordinarias</a></li>
                <!--<li><a href="#tabs-4">Pago de Ordinarias y Extraordinarias</a></li>-->
                <li><a href="#tabs-5">Adicionales</a></li>
            </ul>
            <div id="tabs-1">
                <div class="default_div">
                    En el presente recuadro se visualizará un resumen de los ingresos recaudados por medio de las expensas asi como ingresos adicionales para un consorcio seleccionado anteriomente
                </div>
                <div class="summary_div result_div"></div>
            </div>
            <div id="tabs-2">
                <div class="default_div">
                    Esta pestaña esta abocada a la registracion de las expensas cobradas en el mes actual, permitirá la carga de pagos en primer vencimiento como en segundo y en el caso de que el responsable del pago lo necesite un pago a cuenta.
                </div>
                <div class="payments_div result_div"></div>
            </div>
            <div id="tabs-3">
                <div class="default_div">
                    En la presente se permite la carga de expensas Extraordinarias
                </div>
                <div class="extraordinary_div result_div"></div>
            </div>
            <!--<div id="tabs-4">
                <div class="default_div">
                    En la presente se permite realizar pagos de expensas ordinarias y de expensas Extraordinarias
                </div>
                <div class="ordinary_and_extraordinary_div result_div"></div>
            </div>-->
            <div id="tabs-5">
                <div class="default_div">
                    En la presente se permite la carga de ingresos adicionales.
                </div>
                <div class="aditional_div result_div"></div>
            </div>
    </div>
</div>