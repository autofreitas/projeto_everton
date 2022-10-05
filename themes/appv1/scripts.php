<?php $v->push('link'); ?>
<!--    Styles -->
<link rel="shortcut icon" type="image/png" href="<?= url("shared/images/favicon/favicon_32x32.png"); ?>"/>
<link rel="icon" type="image/png" href="<?= url("/shared/images/favicon/favicon_192x192.png"); ?>" sizes="192x192"/>
<link rel="apple-touch-icon" href="<?= url("/shared/images/favicon/touch-icon-iphone-180x180.png"); ?>" sizes="180x180"/>
<link rel="apple-touch-startup-image" href="<?= url("/shared/images/social/share.jpg"); ?>"/>


<link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">
<link href="<?= url("/shared/styles/styles-icons.css"); ?>" rel="stylesheet">
<?php $v->end(); ?>

<?php $v->push('scripts'); ?>

<!--    Scripts -->
<script src="<?= url('/shared/scripts/jquery.min.js'); ?>"></script>
<script src="<?= url('/shared/scripts/jquery.form.js'); ?>"></script>
<script src="<?= url('/shared/scripts/jquery.mask.js'); ?>"></script>
<script src="<?= url('/shared/scripts/jquery-ui.js'); ?>"></script>
<script src="<?= url('/shared/scripts/cemf.js'); ?>"></script>

<!--    Scripts REDES SOCIAS -->

<?php $v->end(); ?>

<?php if (isLocal()): ?>

    <?php $v->push('link'); ?>
    <link href="<?= url("/shared/styles/material.cemf.default.css"); ?>" rel="stylesheet">
    <link href="<?= theme('/assets/css/material.cemf.appv1.css',CONF_VIEW_THEME_APP);?>"
          rel="stylesheet">
    <?php $v->end(); ?>


    <?php $v->push('scripts'); ?>
    <!--    Scripts -->
        <script src="<?= url('/shared/scripts/cemf-utility.js'); ?>"></script>
        <script src="<?= theme('/assets/js/scripts-web.js',CONF_VIEW_THEME_APP); ?>"></script>
    <?php $v->end(); ?>
<?php else:; ?>

    <?php $v->push('link'); ?>
     <link href="<?= url("/shared/build/css/".CONF_CSS_DEFAULT); ?>" rel="stylesheet">
     <link href="<?= theme('/assets/build/css/'.CONF_CSS_APP_CLIENT,CONF_VIEW_THEME_APP); ?>"
          rel="stylesheet">
   
    <?php $v->end(); ?>

    <?php $v->push('scripts'); ?>
        <?php  //require "./shared/tracker/fb_track.php" ?>
        <script src="<?= url('/shared/build/js/'.CONF_JS_DEFAULT); ?>"></script>
        <script src="<?= theme('/assets/build/js/'.CONF_JS_APP_CLIENT,CONF_VIEW_THEME_APP); ?>"></script>
    
    <?php $v->end(); ?>

<?php endif; ?>

