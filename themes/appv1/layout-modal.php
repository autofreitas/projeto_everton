<section class="layout-modal color-primary animate__animated animate__faster">
  <div class="menu-float-back">
    <a href="#" class="flex-center --white" data-back>
      <span class="material-icons ">arrow_back</span>
      <p class="cf-text-5 --white pl-10">Voltar</p>
    </a>
  </div>
  <?php if (isset($modal) && $modal == 'form-module') : ?>

    <!-- INSERIR UM NOVO MÓDULO -->
    <div class="p-20 animate__animated" id="card-module">
      <form action="<?= url("app/owner"); ?>" method="post" enctype="multipart/form-data" style="padding:100px 10px 10px 10px">
        <?= (empty($csrf)) ? csrf_input() : $csrf ?>

        <div class="pt-20">
          <label class="cf-text-3 --white" for="subtitle">Informe o código do sensor</label>

          <div class="input-icon begin">
            <span class="material-icons ">key</span>
            <input type="text" name="token">
          </div>

        </div>
        <div>
          <p class="cf-text-3 --white">Você pode localizar o código na parte de baixo do módulo</p>
        </div>
        <div class="flex-center mt-20 fb-100 ">
          <input type="submit" value="VALIDAR" name="validate" class="btn-default color-secondary --white cf-text-3 flex-center pointer" />
        </div>
      </form>
    </div>
  <?php endif; ?>

  <?php if (isset($modal) && $modal == 'form-mydata') : ?>

    <!-- INSERIR/ALTERAR USUÁRIO -->
    <div class="card color-vtsd p-20 animate__animated" id="card-user">
      <form action="<?= url("app/user"); ?>" method="post" enctype="multipart/form-data">
        <?= (empty($csrf)) ? csrf_input() : $csrf ?>
        <header>
          <h1 class="cf-text-5 bold --black">Meus Dados</h1>
        </header>
        <input type="hidden" name="id" value="<?= $user->id ?? "" ?>">
        <div class="pt-20">
          <label class="cf-text-3" for="subtitle">Nome Completo</label>
          <input type="text" name="name" value="<?= $user->name ?? "" ?>">
        </div>
        <div>
          <label class="cf-text-3" for="subtitle">Email</label>
          <input type="email" name="email" <?= ($user->id) ? "class='-gray-light' readonly" : "" ?> value="<?= $user->email ?? "" ?>">
        </div>
        <div>
          <label class="cf-text-3" for="subtitle">Telefone</label>
          <input type="text" name="phone" id="phone" value="<?= phone_br($user->phone) ?? "" ?>">
        </div>
        <div>
          <label class="cf-text-3" for="subtitle">Documento</label>
          <select name="document_type" id="document_type" <?= ($user->id) ? "readonly" : "" ?>>
            <option value="CPF" <?= ($user->document_type == 'CPF') ? "selected" : " " ?>>CPF</option>
            <option value="CNPJ" <?= ($user->document_type == 'CNPJ') ? "selected" : " " ?>>CNPJ</option>
          </select>
        </div>
        <div>
          <label class="cf-text-3" for="subtitle">Número do documento</label>
          <input type="text" name="document_number" id="document_number" <?= ($user->id) ? "class='-gray-light' readonly" : "" ?> value="<?= ($user->document_type = 'CPF') ? cpf($user->document_number) : cnpj($user->document_number) ?? "" ?>">
        </div>
        <div class="flex-center mt-20 fb-100 ">
          <input type="submit" value="SALVAR" name="save" class="btn-default color-secondary --white cf-text-3 flex-center pointer" />
        </div>
      </form>
    </div>

    <!-- INSERIR/ALTERAR ENDEREÇO -->
    <div class="card color-vtsd p-20 animate__animated" id="card-address">
      <form action="<?= url("app/address"); ?>" method="post" enctype="multipart/form-data">
        <?= (empty($csrf)) ? csrf_input() : $csrf ?>
        <header>
          <h1 class="cf-text-5 bold --black">Meu Endereço</h1>
        </header>
        <input type="hidden" name="id" value="<?= (!empty($user->address))? $user->address->id : 0 ?>">
        <input type="hidden" name="user_id" value="<?= (!empty($user->address))? $user->address->user_id : "" ?>">
        <div class="pt-20">
          <label class="cf-text-3" for="street">Rua</label>
          <input type="text" name="street" value="<?= (!empty($user->address))? $user->address->street : "" ?>">
        </div>
        <div>
          <label class="cf-text-3" for="number">Número</label>
          <input type="text" name="number" value="<?= (!empty($user->address))? $user->address->number : "" ?>">
        </div>
        <div>
          <label class="cf-text-3" for="complement">Complemento</label>
          <input type="text" name="complement" value="<?= (!empty($user->address))? $user->address->complement : "" ?>">
        </div>
        <div>
          <label class="cf-text-3" for="cep">CEP</label>
          <input type="text" name="zipcode" id="zipcode" value="<?= (!empty($user->address))? cep($user->address->zipcode) : "" ?>">
        </div>
        <div>
          <label class="cf-text-3"  for="uf">Estado</label>
          <select name="state" value="<?= (!empty($user->address))? $user->address->state : "" ?>">
            <option value="SC">SC</option>
          </select>
        </div>
        <div>
          <label class="cf-text-3" for="city">Cidade</label>
          <select name="city" value="<?= (!empty($user->address))? $user->address->city : "" ?>">
            <option value="Itapema">Itapema</option>
          </select>
        </div>
        <div class="flex-center mt-20 fb-100 ">
          <input type="submit" value="SALVAR" name="save" class="btn-default color-secondary --white cf-text-3 flex-center pointer" />
        </div>
      </form>
    </div>

    <!-- ALTERAR SENHA  -->
    <div class="card color-vtsd p-20 " id="card-password">
      <form action="<?= url("app/password"); ?>" method="post" enctype="multipart/form-data">
        <?= (empty($csrf)) ? csrf_input() : $csrf ?>
        <header>
          <h1 class="cf-text-5 bold --black">Alterar Senha</h1>
        </header>
        <input type="hidden" name="id" value="<?= $user->id ?? "" ?>">
        <div class="pt-20">
          <label class="cf-text-3" for="subtitle">Digite a senha atual</label>
          <input type="password" required name="password">
        </div>
        <div class="mt-10">
          <label class="cf-text-3" for="subtitle">Digite a Nova Senha</label>
          <input type="password" required name="new_password">
        </div>
        <div>
          <label class="cf-text-3" for="subtitle">Repita Nova Senha</label>
          <input type="password" required name="repeat_password">
        </div>
        <div class="flex-center mt-20 fb-100 ">
          <input type="submit" value="SALVAR" name="save" class="btn-default color-secondary --white cf-text-3 flex-center pointer" />
        </div>
      </form>
    </div>
  <?php endif; ?>
  <?php if (isset($modal) && $modal == 'form-grouping') : ?>

    <!-- ALTERAR SENHA  -->
    <div class="card color-vtsd p-20 " id="card-grouping">
      <form action="<?= url(""); ?>" method="post" enctype="multipart/form-data">
        <?= (empty($csrf)) ? csrf_input() : $csrf ?>
        <header>
          <h1 class="cf-text-5 bold --black">Agrupamentos</h1>
        </header>

        <div class="pt-20">
          <label class="cf-text-3" for="subtitle">Nome do Grupo</label>
          <input type="text" name="name_group">
        </div>
        <div class="flex-center mt-20 fb-100 ">
          <input type="submit" value="SALVAR" name="save" class="btn-default color-secondary --white cf-text-3 flex-center pointer" />
        </div>
      </form>
    </div>
  <?php endif; ?>
</section>

<?php $v->push("scripts-after"); ?>
<script type="text/javascript">
  $(document).ready(function($) {
    var $utility = new cemf.js.Utility();

    $("#phone").mask('(00) 00000-0000');
    $("#zipcode").mask('00000-000');

    <?php if ($user->document_type == 'CPF') : ?>
      $("#document_number").mask('000.000.000-00', {reverse: true});
    <?php elseif ($user->document_type == 'CNPJ') : ?>
      $("#document_number").mask('00.000.000/0000-00', {reverse: true});
    <?php endif; ?>

    $("#document_type").on("change", function() {
      $("#document_number").val("");
      if ($(this).val() == 'CPF') {
        $("#document_number").mask('000.000.000-00', {reverse: true});
      } else if ($(this).val() == 'CNPJ') {
        $("#document_number").mask('00.000.000/0000-00', {reverse: true});
      }

    });

    $("[data-back]").on("click", function(e) {
      e.preventDefault();
      $utility.animateCSS(".layout-modal", 'zoomOut').then((message) => {
        $(".layout-modal").css({
          "transform": "scale(0)"
        });

        $("#card-module").css({
          "transform": "scale(0)"
        });
        $("#card-password").css({
          "transform": "scale(0)",
          "display": "none"
        });
        $("#card-user").css({
          "transform": "scale(0)",
          "display": "none"
        });
        $("#card-address").css({
          "transform": "scale(0)",
          "display": "none"
        });
        $("#card-grouping").css({
          "transform": "scale(0)",
          "display": "none"
        });

      });

    });
  });
</script>
<?php $v->end(); ?>