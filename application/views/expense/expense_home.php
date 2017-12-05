<div id="site_content">
    <h1>Egresos</h1>
    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Egresos Ordinarios</a></li>
            <li><a href="#tabs-2">Egresos Extraordinarios</a></li>
            <li><a href="#tabs-3">Egresos Especiales</a></li>
            <li><a href="#tabs-4">Egresos Estimativos</a></li>
        </ul>
        <div id="tabs-1">            
            <? $this->load->view("/expense/ordinary_expense"); ?>
        </div>
        <div id="tabs-2">
            <? $this->load->view("/expense/extraordinary_expense"); ?>
        </div>
        <div id="tabs-3">
            <? $this->load->view("/expense/special_expense"); ?>
        </div>
        <div id="tabs-4">
            <? $this->load->view("/expense/estimative_expense"); ?>
        </div>
    </div>
</div>