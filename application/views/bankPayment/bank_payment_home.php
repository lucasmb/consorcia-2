<div id="site_content">
    
    <h1>Pago por Banco Roela</h1>
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Creacion de Archivo Pago</a></li>
            <li><a href="#tabs-2">Recepcion de Pagos</a></li>
            <li><a href="#tabs-3">Operaciones Adicionales</a></li>
            <li><a href="#tabs-4">Backup</a></li>
        </ul>

        <div id="tabs-1">

            <!-- Creacion de Archivo Pago / Exportar archivo para insertar en SIRO -->
            <div class="div_border">    

                <h2>Creacion de Archivo Total</h2>

                <div class="div_border">  
                    Permite la exportacion del archivo que posee el formato adecuado para que SIRO lo procese. Una vez que se presiona en el boton, automaticamente se descarga el archivo con el nombre del CUIT del administrador mas la fecha actual.

                    Este archivo se debe subir a la pagina de SIRO.
                </div>
                <br />

                <div class="div_border">  
                    <form id="frm_generate_complete_file_bank_payment" action="<?= site_url().'bankPayment/generate_complete_file_bank_payment' ?>" method="post">
                        <button class="btn_full_width" id="btn_generate_bank_complete_file">Generar archivo de cobro para todos los consorcios</button>
                    </form>
                </div>

            </div>


            <div class="div_border">

                <h2>Creacion de Archivo Por Edificio</h2>
                <div class="div_border">
                    IMPORTANTE: Tener en cuenta que el archivo que se genera, el cual se debe subir a la pagina del Banco Roela, es admitido por SIRO diariamente(Un solo archivo por día). Por lo cual es preferible utilizar el metodo de generación de todos los consorcios.
                </div>

                <br />
                <div class="div_border">    
                    <form class="adv_col" id="frm_generate_building_file_bank_payment" action="<?= site_url().'bankPayment/generate_building_file_bank_payment' ?>" method="post">
                        <label id="labelBuilding" for="building" >Edificio : </label>
                        <select name="building" id="building">
                            <?= get_select_building_bank_payment(set_value('building'));?>
                        </select>
                        <button id="btn_generate_bank_file_for_building">Generar archivo</button>
                    </form>
                    
                </div>
                <div id="building_info">
                    
                </div>
                <div id="building_properties">
            
                </div>

            </div>
            
        </div>

        <div id="tabs-2">

            <!-- Cargar el archivo de Roela con los pagos -->
            <div class="div_border">
                <h2>Importación de pagos registrados del Banco Roela</h2>

                <div class="div_border">  
                    Permite la importación de los pagos realizados y registrados por el Banco Roela, se debe seleccionar el archivo que se ha exportado desde SIRO. A continuación se procesaran los datos, se validarán que ese pago no se haya realizado y en caso del archivo que posee el formato adecuado para que SIRO lo procese. 
                </div>
                <br />

                <div class="div_border">  
                    <form id="frm_upload_roela_file_bank_payments" action="<?= site_url().'bankPayment/upload_roela_file_bank_payments' ?>" method="post" enctype="multipart/form-data">
                        <input type="file" name="file_to_upload" id="file_to_upload">
                        <button id="btn_load_bank_complete_file">Cargar archivo de pago</button>
                    </form>
                </div>

                <div id="payment_info">
                        
                </div>

            </div>

            <!-- Cargar el archivo de Roela con los gastos de SIRO -->
            <div class="div_border">
                <h2>Importación de gastos por la utilización de SIRO</h2>

                <div class="div_border">  
                    Permite la importación de los gastos generados a partir de la utilización de SIRO, entre ellos se encuentra el impuesto por transacción del banco, el IVA y mantenimiento de las cuentas. Se debe seleccionar el item Extracto acumulado por concepto y por día dentro del tipo de listado en la pagina de SIRO y luego descargar el excel, ese excel es el que se debe cargar.
                </div>
                <br />

                <div class="div_border">  
                    <form id="frm_upload_roela_file_bank_expenses" action="<?= site_url().'bankPayment/upload_roela_file_bank_expenses' ?>" method="post" enctype="multipart/form-data">
                        <input type="file" name="file_to_upload_expenses" id="file_to_upload_expenses">
                        <button id="btn_load_bank_expenses_file">Generar archivo de cobro para todos los consorcios</button>
                    </form>
                </div>

                <div id="expenses_info">
                        
                </div>

            </div>
            
        </div>

        <div id="tabs-3">

            <!-- Cargar el archivo de Roela con los pagos -->
            <div class="div_border">
                <h2>Creacion de Archivo de validacion para el personal del Banco Roela</h2>

                <div class="div_border">  
                    Permite la exportacion del archivo que posee el codigo inferior al codigo de barra que sirve para que el personal del banco valide la correctitud de los mismos. Una vez que se presiona en el boton, automaticamente se descarga el archivo con el nombre del CUIT del administrador mas la fecha actual.                
                </div>
                <br />

                <div class="div_border">  
                    <form id="frm_generate_validation_file_bank_payment" action="<?= site_url().'bankPayment/generate_validation_file_bank_payment' ?>" method="post">
                        <button class="btn_full_width" id="btn_generate_bank_validation_file">Generar archivo de validacion para todos los consorcios</button>
                    </form>
                </div>

            </div>
            
        </div>

        <div id="tabs-4">

            <!-- Cargar el archivo de Roela con los pagos -->
            <div class="div_border">
                <h2>Backup - Exportacion</h2>

                <div class="div_border">  
                    Permite la exportacion de toda la base de datos en SQL.                
                </div>
                <br />

                <form id="frm_download_backup_sql" action="<?= site_url().'welcome/download_backup_sql' ?>" method="post">
                    <button class="btn_full_width">Exportar</button>
                </form>
            </div>

            <div class="div_border">

                <h2>Backup - Importacion</h2>
                <div class="div_border">  
                    Permite la importación de toda la base de datos en SQL.        
                </div>
                <br />

                <form id="frm_upload_backup_sql" action="<?= site_url().'welcome/upload_backup_sql' ?>" method="post" enctype="multipart/form-data">
                    <input type="file" name="file_to_upload" id="file_to_upload">
                    <button class="btn_full_width">Importar</button>
                </form>

            </div>
            
        </div>

    </div>

</div>