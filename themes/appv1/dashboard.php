<?php $v->layout("_theme"); ?>
<div class="frame-back color-vtsd">
  <div class="flex-center">
    <?php $v->insert("menu"); ?>
    <section class="dashboard fb-100">
        <?php $v->insert($menuSelected); ?>
    </section>
  </div>
</div>