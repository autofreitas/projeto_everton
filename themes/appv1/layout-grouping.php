<?php

use Source\Models\Shared; ?>
<section class="main-panel">
  <div class="">
    <header class="title p-15">
      <p class="cf-text-5 --black bold">Meus Agrupamentos</p>
    </header>
  </div>
  <div class="grouping wrap p-10 pl-20 ">
    <div class="content-group fb-100">
      <header class="name-group flex">
        <span class="material-icons --gray">spoke</span>
        <p class="cf-text-4 --black p-5">Grupos</p>
      </header>

      <?php if (!empty($user->grouping)) : ?>
        <?php foreach ($user->grouping as $group) : ?>
          <div class="card card-group mt-10 p-20">
            <div class="header">
              <p class="cf-text-4 bold default flex-center"><?= $group->description ?> <span class="circle-default color-primary ml-5"></span></p>
              <div class="flex-center">
                <span class="material-icons --gray pr-5">router</span>
                <p class="cf-text-4 --black bold"><?= (!empty($group->module))? $group->module: 0; ?></p>
              </div>
            </div>
            <div class="shared-with mt-10">
              <div class="header p-5">

                <a href="#" data-edit data-method="edit" data-delete-id="02">
                  <span class="material-icons --gray">edit</span>
                </a>
                <a href="#" data-shared data-method="shared" data-delete-id="02">
                  <span class="material-icons --gray">shared</span>
                </a>
                <a href="#" data-shared data-method="shared" data-delete-id="02">
                  <span class="material-icons --green">done_all</span>
                </a>
                <a href="#" data-delete-group data-method="delete" data-delete-id="01" data-delete-info="CPD ITAPEMA">
                  <span class="material-icons --gray">delete</span>
                </a>
              </div>
            </div>
            <div class="shared-with mt-10">
              <div class="header p-5">
                <p class="cf-text-3 light default">Grupo compartilhado com:</p>
              </div>
              <div class="content p-10">
                <ul>
                  <?php
                  if (!empty($group->shared)) :
                    foreach ($group->shared as $shared) :
                  ?>
                      <li>
                        <div class="shared">
                          <p class="cf-text-3 --gray light"><?= $shared->email ?? ""; ?></p>
                          <a href="#" data-delete-shared data-method="shared" data-delete-id="01" data-delete-info="carlos@autofreitas.com.br">
                            <span class="material-icons --gray">delete</span>
                          </a>
                        </div>
                      </li>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </ul>
              </div>
            </div>
          </div>

        <?php endforeach; ?>
      <?php endif; ?>

      <div class="group-add flex-center">
        <a href="#" class="color-primary flex-center center" data-add-grouping>
          <span class="material-icons --white">add</span>
        </a>
      </div>
    </div>
  </div>

  <div class="grouping wrap p-10 pl-20 ">
    <div class="content-group fb-100">
      <header class="name-group flex">
        <span class="material-icons --gray">shared</span>
        <p class="cf-text-4 --black p-5">Compartilhados Comigo</p>
      </header>

      <?php if (!empty($user->sharedme)) : ?>
        <?php foreach ($user->sharedme as $sharedme) : ?>

          <div class="card card-group mt-10 p-20">
            <div class="header">
              <p class="cf-text-4 bold default flex-center"><?= $sharedme->description ?></p>
              <a href="#" data-delete data-method="shared-me" data-delete-id="<?= $sharedme->grouping_id ?>" data-delete-info="<?= $sharedme->description ?>">
                <span class="material-icons --gray">delete</span>
              </a>
            </div>
          </div>

        <?php endforeach; ?>
      <?php endif; ?>


    </div>
</section>

<?php $v->insert("layout-modal", ["modal" => "form-grouping"]); ?>
<?php $v->insert("layout-msgcard", ["modal" => "msg-grouping"]); ?>

<?php $v->push("scripts-after"); ?>
<script type="text/javascript">
  $(document).ready(function($) {

    var $utility = new cemf.js.Utility();
    console.log($utility);

    $("[data-add-grouping]").on("click", function(e) {
      e.preventDefault();

      $(".layout-modal").css({
        "transform": "scale(1)"
      });

      $utility.animateCSS(".layout-modal", 'zoomIn').then((message) => {

        $("#card-grouping").css({
          "transform": "scale(1)",
          "display": "inherit"
        });
        $utility.animateCSS("#card-grouping", 'bounceIn');
      });
    });

    $("[data-delete-group]").on("click", function(e) {
      e.preventDefault();

      var id = $(this).data("delete-id");
      var text = $(this).data("delete-info");
      var method = $(this).data("method");


      $("#item-delete").html("o grupo <b>" + text + "</b>?<p class='cf-text-4 --gray'><i>Todos os compartilhamentos existentes também serão excluídos</i></p>");
      $("#grouping_id").val(id);


      $(".layout-modal-msg").css({
        "transform": "scale(1)"
      });

      $utility.animateCSS(".layout-modal-msg", 'zoomIn').then((message) => {

        $("#card-delete").css({
          "transform": "scale(1)",
          "display": "inherit"
        });
        $utility.animateCSS("#card-delete", 'bounceIn');
      });
    });

    $("[data-delete-shared]").on("click", function(e) {
      e.preventDefault();

      var id = $(this).data("delete-id");
      var text = $(this).data("delete-info");
      var method = $(this).data("method");

      if (method == 'shared-me') {
        $("#item-delete").html("o compartilhamento do grupo <b>" + text + "</b> feito com você ?");
      } else {
        $("#item-delete").html("o compartilhamento com <b>" + text + "</b> ?");
      }
      $("#grouping_id").val(id);


      $(".layout-modal-msg").css({
        "transform": "scale(1)"
      });

      $utility.animateCSS(".layout-modal-msg", 'zoomIn').then((message) => {

        $("#card-delete").css({
          "transform": "scale(1)",
          "display": "inherit"
        });
        $utility.animateCSS("#card-delete", 'bounceIn');
      });
    });


  });
</script>
<?php $v->end(); ?>