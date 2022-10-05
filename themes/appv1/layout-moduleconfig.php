<?php $v->layout("_theme"); ?>
<div class="frame-back color-vtsd">
  <div class="flex menu-bar -gray">
    <div class="action pt-5">
      <div class="item-menu --white flex-center">
        <a href="<?= url("app/dashboard"); ?>" title="Esconder menu">
          <span class="material-icons --white p-10">arrow_back</span>
        </a>
        <p class="cf-text-4 --white">CPD-XX1</p>
      </div>
    </div>
  </div>
  <div class="dashboard">
    <?php
    if (!empty($module)) :
      switch ($module->type_module_id):
        case 1:
          $v->insert("config-temperature");
          break;
      endswitch;
    endif;
    ?>
  </div>
</div>

<?php $v->insert("layout-msgcard", ["modal" => "msg-moduleconfig"]); ?>

<?php $v->push("scripts-after"); ?>
<script type="text/javascript">
  $(document).ready(function($) {

    var $utility = new cemf.js.Utility();
    console.log($utility);


    $("[data-delete]").on("click", function(e) {
      e.preventDefault();

      var id = $(this).data("delete-id");
      var text = $(this).data("delete-info");
      var method = $(this).data("method");


      $("#item-delete").html(" o módulo <b>" + text + "</b> ");
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