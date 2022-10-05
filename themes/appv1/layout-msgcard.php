<section class="layout-modal-msg -gray animate__animated animate__faster">

  <?php if (isset($modal) && $modal == 'msg-grouping') : ?>
    <!-- MSG CONFIRMA EXCLUSÃO -->
    <div class="card color-vtsd p-20 animate__animated" id="card-delete">
      <a href="#" data-close class="float-close p-5">
        <p class="cf-text-5 --black bold color-vtsd">X</p>
      </a>
      <form action="<?= url(""); ?>" method="post" enctype="multipart/form-data">
        <?= (empty($csrf)) ? csrf_input() : $csrf ?>
        <div class="pt-20">
          <p class="cf-text-5 black normal">Deseja realmente excluir <span id="item-delete"></span></p>
        </div>
        <input type="hidden" name="grouping_id" id="grouping_id"/>
        <div class="flex-center mt-30 fb-100 ">
          <input type="submit" value="CONFIRMA" name="delete" class="btn-default color-secondary --white cf-text-3 flex-center pointer" />
        </div>
      </form>
    </div>
  <?php endif; ?>

  <?php if (isset($modal) && $modal == 'msg-moduleconfig') : ?>
    <!-- MSG CONFIRMA EXCLUSÃO -->
    <div class="card color-vtsd p-20 animate__animated" id="card-delete">
      <a href="#" data-close class="float-close p-5">
        <p class="cf-text-5 --black bold color-vtsd">X</p>
      </a>
      <form action="<?= url(""); ?>" method="post" enctype="multipart/form-data">
        <?= (empty($csrf)) ? csrf_input() : $csrf ?>
        <div class="pt-20">
          <p class="cf-text-5 black normal">Deseja realmente excluir <span id="item-delete"></span>?</p>
        </div>
        <input type="hidden" name="grouping_id" id="grouping_id"/>
        <div class="flex-center mt-30 fb-100 ">
          <input type="submit" value="CONFIRMA" name="delete" class="btn-default color-secondary --white cf-text-3 flex-center pointer" />
        </div>
      </form>
    </div>
  <?php endif; ?>
</section>

<?php $v->push("scripts-after"); ?>

<script type="text/javascript">
  $(document).ready(function($) {
    var $utility = new cemf.js.Utility();
    
    $("[data-close]").on("click", function(e) {
      e.preventDefault();
      $utility.animateCSS(".layout-modal-msg", 'zoomOut').then((message) => {
        $(".layout-modal-msg").css({
          "transform": "scale(0)"
        });
        $("#card-delete").css({
          "transform": "scale(0)"
        });
      });
    });
  });
</script>
<?php $v->end(); ?>