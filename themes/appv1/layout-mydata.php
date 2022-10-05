<section class="main-panel">
  <div class="">
    <header class="title p-15">
      <p class="cf-text-5 --black bold">Meus Dados</p>
    </header>
  </div>

  <div class="flex wrap">
    <div class="compact">

      <!-- DADOS PESSOAIS -->
      <div class="my-data wrap p-10 pl-20 ">
        <div class="content-group fb-100">
          <div class="card-group mt-10 p-20">
            <div class="data">
              <div class="header">
                <p class="cf-text-6 bold default flex-center"><?= str_title($user->name) ?></p>
              </div>
              <div class="content">
                <p class="cf-text-3 --gray"><?= strtolower($user->email) ?></p>
                <div class="item flex mt-5">
                  <p class="cf-text-3 --black" style="width:40px">Fone</p>
                  <p class="cf-text-3 --black pr-5">:</p>
                  <p class="cf-text-3 --black"><?= phone_br($user->phone) ?></p>
                </div>
                <div class="item flex">
                  <p class="cf-text-3 --black" style="width:40px"><?= $user->document_type ?></p>
                  <p class="cf-text-3 --black pr-5">:</p>
                  <p class="cf-text-3 --black"><?= ($user->document_type == 'CPF') ? cpf($user->document_number) : cnpj($user->document_number) ?></p>
                </div>
              </div>
            </div>
            <div class="action">
              <a href="#" data-add-user>
                <span class="material-icons --gray">edit</span>
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- ENDEREÇOS -->
      <div class="my-data wrap p-10 pl-20 ">
        <div class="content-group fb-100">
          <header class="name-group flex">
            <span class="material-icons --gray">maps</span>
            <p class="cf-text-4 --black p-5">Endereços</p>
          </header>

          <?php if (!empty($user->address)) : ?>
            <?php $address = $user->address; //foreach ($user->address as $address) : ?>

              <div class="card card-group mt-10 p-20">
                <div class="data">
                  <div class="content">
                    <p class="cf-text-3 --black"><?= $address->street . ", " . $address->number . " - " . $address->complement ?></p>
                    <div class="item flex mt-5">
                      <p class="cf-text-3 --black" style="width:30px">CEP</p>
                      <p class="cf-text-3 --black"><?= cep($address->zipcode) ?></p>
                    </div>
                    <div class="item flex">
                      <p class="cf-text-3 --black"><?= $address->city . " - " . $address->state ?></p>
                    </div>
                  </div>
                </div>
                <div class="action">
                  <a href="#" data-add-address>
                    <span class="material-icons --gray">edit</span>
                  </a>
                </div>
              </div>
            <?php //endforeach; ?>
          <?php endif; ?>
          <?php if (empty($user->address)) : ?>
            <div class="group-add flex-center">
              <a href="#" class="color-primary flex-center center" data-add-address>
                <span class="material-icons --white">add</span>
              </a>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- PASSWORD -->
      <div class="my-data wrap p-10 pl-20 ">
        <a href="#" class="pointer" data-add-password>
          <div class="content-group fb-100">
          <header class="name-group flex">
            <span class="material-icons --gray">lock</span>
            <p class="cf-text-4 --black p-5">Segurança</p>
          </header>
            <div class="card card-group mt-10 p-20">
              <div class="data">
                <div class="content flex">
                  <span class="material-icons --gray">password</span>
                  <p class="cf-text-3 --gray">Alterar Senha</p>
                </div>
              </div>
              <div class="action">
                <a href="#" data-add-password>
                  <span class="material-icons --gray">key</span>
                </a>
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>

    <div class="expanded">
      <!-- PLANOS -->
      <div class="my-data wrap p-10 pl-20 ">
        <div class="content-group plans">
          <div class="card card-group mt-10 p-20 plans">
            <div class="header">
              <p class="cf-text-5 --black">Plano Lite</p>
            </div>
            <div class="content fb-100">
              <div class="mt-10 mb-10">
                <div class="flex between">
                  <p class="cf-text-2 bold">Módulos Ativos</p>
                  <p class="cf-text-2">2 de 4 utilizados</p>
                </div>
                <div class="progress-bar">
                  <span class="barra -gray-light"></span>
                  <span class="barra sob color-primary" style="width:50%"></span>
                </div>
              </div>
              <div class="info-plan">
                <div class="flex">
                  <p class="cf-text-3 bold">Grupos</p>
                  <p class="cf-text-4 bold pr-5 pl-5">:</p>
                  <p class="cf-text-4 ">2/10 utilizados</p>
                </div>
                <div class="flex">
                  <p class="cf-text-3 bold">Compartilhamentos</p>
                  <p class="cf-text-4 bold pr-5 pl-5">:</p>
                  <p class="cf-text-4">2/4 utilizados</p>
                </div>
              </div>
            </div>
            <div class="footer mt-20">
              <p class="cf-text-2 ">Para conhecer outros planos entre em contato</p>
              <div class="btn-save mt-15">
                <a href="" class="btn-default color-secondary --white cf-text-3">CONTATO</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php $v->insert("layout-modal", ["modal" => "form-mydata"]); ?>

<?php $v->push("scripts-after"); ?>
<script type="text/javascript">
  $(document).ready(function($) {

    var $utility = new cemf.js.Utility();
    console.log($utility);

    $("[data-add-address]").on("click", function(e) {
      e.preventDefault();

      $(".layout-modal").css({
        "transform": "scale(1)"
      });

      $utility.animateCSS(".layout-modal", 'zoomIn').then((message) => {

        $("#card-address").css({
          "transform": "scale(1)",
          "display": "inherit"
        });
        $utility.animateCSS("#card-address", 'bounceIn');
      });
    });

    $("[data-add-password]").on("click", function(e) {
      e.preventDefault();

      $(".layout-modal").css({
        "transform": "scale(1)",
        "display": "inherit"
      });

      $utility.animateCSS(".layout-modal", 'zoomIn').then((message) => {

        $("#card-password").css({
          "transform": "scale(1)",
          "display": "inherit"
        });
        $utility.animateCSS("#card-password", 'bounceIn');
      });
    });

    $("[data-add-user]").on("click", function(e) {
      e.preventDefault();

      $(".layout-modal").css({
        "transform": "scale(1)",
        "display": "inherit"
      });

      $utility.animateCSS(".layout-modal", 'zoomIn').then((message) => {

        $("#card-user").css({
          "transform": "scale(1)",
          "display": "inherit"
        });
        $utility.animateCSS("#card-user", 'bounceIn');
      });
    });


  });
</script>
<?php $v->end(); ?>