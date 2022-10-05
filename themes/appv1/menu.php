<div class="float-menu">
    <div class="item-menu --white flex-center">
        <a href="" title="Mostrar menu">
            <span class="material-icons --black p-10">menu</span>
        </a>
    </div>
</div>
<section class="menu -black">
    <div class="action pt-5">
        <div class="item-menu --white flex-center">
            <a href="<?= url("login") ?>" title="Esconder menu">
                <span class="material-icons --white p-10">arrow_back</span>
            </a>
        </div>
    </div>
    <div class="top pt-20">
        <div class="item-menu --white flex-center">
            <a href="<?= url("app/dashboard") ?>" class="<?= ($menuSelected == 'layout-dashboard')? "selected": "" ?>" title="Painel principal">
                <span class="material-icons --white p-10">dashboard</span>
            </a>
        </div>
        <div class="item-menu --white flex-center">
            <a href="<?= url("app/agrupamentos") ?>" class="<?= ($menuSelected == 'layout-grouping')? "selected": "" ?>" title="Gerenciar agrupamentos">
                <span class="material-icons --white p-10">spoke</span>
            </a>
        </div>
        <div class="item-menu --white flex-center">
            <a href="" class="" title="Mensagens de alerta">
                <span class="material-icons --white p-10">send</span>
            </a>
        </div> 
    </div>
    <div class="footer">
        <div class="item-menu --white flex-center">
            <a href="<?= url("app/gerenciador") ?>" class="" title="Painel administrador">
                <span class="material-icons --white p-10">admin_panel_settings</span>
            </a>
        </div>
        <div class="item-menu --white flex-center">
            <a href="<?= url("app/meus-dados") ?>" class="" title="Dados do usuários"  class="<?= ($menuSelected == 'layout-mydata')? "selected": "" ?>">
                <span class="material-icons --white p-10">account_circle</span>
            </a>
        </div>
        <div class="item-menu --white flex-center">
            <a href="<?= url("app/logout") ?>" class="" title="Sair do Sistema"  class="<?= ($menuSelected == 'layout-mydata')? "selected": "" ?>">
                <span class="material-icons --white p-10">logout</span>
            </a>
        </div>
    </div>
   
</section>
