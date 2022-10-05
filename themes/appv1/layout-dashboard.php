<section class="main-panel">
  <div class="float-button ">
    <a href="" class="flex-center --white color-primary" data-add-module>
      <span class="material-icons ">add</span>
    </a>
  </div>

  <?php if (!empty($user->grouping_default)) : ?>
    <div class="bg-background -gray">
      <header class="title p-15">
        <p class="cf-text-5 --white">Grupo: <?= $user->grouping_default->description ?></p>
      </header>
    </div>
    <div class="group-cards wrap">
      <?php if (!empty($user->grouping_default->modules)) : ?>
        <?php
        foreach ($user->grouping_default->modules as $module) :

          switch ($module->type_module_id):
            case 1: // Temperatura 
              $v->insert("card-temperature", ["module" => $module]);
              break;
            case 2:

              break;
            case 3:
              break;
          endswitch;


        endforeach;
        ?>
    </div>
  <?php endif; ?>
<?php endif; ?>
</section>

<?php $v->insert("layout-modal", ["modal" => "form-module"]); ?>

<?php $v->push("scripts-after"); ?>
<script type="text/javascript">
  $(document).ready(function($) {

    var $utility = new cemf.js.Utility();
    console.log($utility);

    $("[data-add-module]").on("click", function(e) {
      e.preventDefault();
      $(".layout-modal").css({
        "transform": "scale(1)"
      });

      $utility.animateCSS(".layout-modal", 'zoomIn').then((message) => {

        if ($utility.exists("#card-module")) {

          $("#card-module").css({
            "transform": "scale(1)",
            "display": "inherit"
          });
          $utility.animateCSS("#card-module", 'fadeIn');
        }

      });
    });

  });
</script>
<?php $v->end(); ?>