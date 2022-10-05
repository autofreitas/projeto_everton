<section class="main-panel">

  <div class="flex wrap">
    <div class="compact">

      <!-- DADOS SENSOR -->
      <div class="my-data wrap p-10 pl-20 ">
        <div class="content-group fb-100">
          <div class="card-group mt-10 p-15">
            <div class="data">
              <div class="header">
                <p class="cf-text-6 bold --color-primary flex-center">Monitoramento de Humidade e Temperatura</p>
              </div>
              <div class="content">
                <div class="item flex mt-5 pt-10">
                  <p class="cf-text-3 --black bold" style="width:60px">Modelo</p>
                  <p class="cf-text-3 --black pr-5">:</p>
                  <p class="cf-text-3 --black"><?= $module->description ?></p>
                </div>
                <div class="item flex" style="align-items:center;">
                  <p class="cf-text-3 --black bold" style="width:60px">Token</p>
                  <p class="cf-text-3 --black pr-5">:</p>
                  <p class="cf-text-3 --black" style="width:100px"><?= $module->token ?></p>
                  <!-- Não utilizado
                    <a href="" class="pl-10 " title="Copiar token">
                      <span class="material-icons --gray">content_copy</span>
                    </a>
                  -->
                </div>
                <p class="cf-text-3 --gray pt-10">Última alteração dia <?= date_fmt($module->updated_at) ?></p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- CONFIGURAÇÕES -->
      <div class="my-data wrap p-10 pl-20 ">
        <div class="content-group fb-100">
          <header class="name-group flex">
            <span class="material-icons --gray">settings</span>
            <p class="cf-text-4 --black p-5">Configurações</p>
          </header>

          <form class="form-config" action="<?= url("app/modulo/" . $module->token); ?>" method="post" enctype="multipart/form-data">
            <?= (empty($csrf)) ? csrf_input() : $csrf ?>
            <div class="card card-group mt-10 p-20">
              <div class="data-config">
                <div class="content">

                  <div class="item">
                    <label class="cf-text-3" for="title">Título</label>
                    <input type="text" name="title" value="<?= $module->configArray->title ?>">
                  </div>
                  <div>
                    <label class="cf-text-3" for="subtitle">Subtítulo</label>
                    <input type="text" name="subtitle" value="<?= $module->configArray->subtitle ?>">
                  </div>
                  <div class="item">
                    <div class="flex align-center mt-10">
                      <span class="material-icons --color-primary">thermostat</span>
                      <p class="cf-text-4 --black p-5">SetPoint Temperatura</p>
                    </div>
                    <div class="flex-center">
                      <div class="flex align-center p-5">
                        <p class="cf-text-4">min:</p>
                        <input type="number" name="setTempMax" style="width:70px;" min="-30" max="50" class="mr-5 ml-5" value="<?= $module->configArray->setTempMax ?>">
                        <p class="cf-text-3">Cº</p>
                      </div>
                      <div class="flex align-center p-5">
                        <p class="cf-text-4">max:</p>
                        <input type="number" name="setTempMin" style="width:70px;" min="-30" max="50" class="mr-5 ml-5" value="<?= $module->configArray->setTempMin ?>">
                        <p class="cf-text-3">Cº</p>
                      </div>
                    </div>
                  </div>
                  <div class="item none">
                    <div class="flex align-center mt-10">
                      <span class="material-icons --color-primary">water_drop</span>
                      <p class="cf-text-4 --black p-5">SetPoint Umidade</p>
                    </div>
                    <div class="flex-center">
                      <div class="flex align-center p-5">
                        <p class="cf-text-4">min:</p>
                        <input type="number" style="width:70px;" min="-30" max="50" class="mr-5 ml-5">
                        <p class="cf-text-3">%</p>
                      </div>
                      <div class="flex align-center p-5">
                        <p class="cf-text-4">max:</p>
                        <input type="number" style="width:70px;" min="-30" max="50" class="mr-5 ml-5">
                        <p class="cf-text-3">%</p>
                      </div>
                    </div>
                  </div>
                  <div class="item mt-10">
                    <label class="check chk-container">
                      <input type="checkbox" <?= (($module->configArray->alert) ? "checked" : ""); ?> name="save" />
                      <span class="checkmark"></span>
                      <span class="--black cf-text-4"><u>Disparar alerta no grupo</u></span>
                    </label>
                  </div>

                </div>
              </div>
            </div>
            <div class="btn-actions flex-center wrap pt-20 pointer">
              <div class="btn-save flex-center mt-10 fb-100">
                <input type="submit" value="SALVAR" class="btn-default color-secondary --white cf-text-3 flex-center fb-100" />
              </div>
              <div class="btn-delete flex-center mt-30 fb-100 pointer">
                <a href="#" class="btn-default --red cf-text-3 flex-center fb-100" data-delete data-method="module-delete" data-delete-id="01" data-delete-info="TITULO">EXCLUIR</a>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="expanded">
        <!-- COMPONENTE -->
        <div class="my-data wrap p-10 pl-20 ">
          <div class="content-group plans">

          </div>
        </div>
      </div>
    </div>
</section>