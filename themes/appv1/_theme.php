<?php

use Source\Core\Session;
use Source\Core\View;

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="<?= CONF_SITE_NAME ?>">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="application-name" content="<?= CONF_SITE_NAME ?>" />
    <meta name="msapplication-TileColor" content="#212121" />
    <meta name="msapplication-TileImage" content="<?= url("/shared/images/favicon/touch-icon-iphone.png"); ?>" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="keywords" content="protepop" />
    <?= $head; ?>
    <?php $v->insert("scripts") ?>
    <?= $v->section("link"); ?>
</head>

<body>
    <div class="ajax_response"> <?= flash(); ?></div>

    <main class="main_content">
        <?= $v->section("content"); ?>

    </main>
    <?php $v->insert("footer"); ?>

    <div class="ajax-modal">
        <?= $v->section("modal"); ?>
    </div>

    <?php if ($v->section("loading")) : ?>
        <?= $v->section("loading"); ?>
    <?php else : ?>
        <section class="loading modal" data-modal-loading style="display: none">
            <article class="modal-container card color-secondary">
                <div class="lds-facebook">
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
                <div class="loading-info">
                    <p class="theme-title-loading --white" data-info><?= $modal_title ?? 'Aguarde...' ?></p>
                </div>
            </article>
        </section>
    <?php endif; ?>
</body>
<?= $v->section('scripts') ?>
<?= $v->section('scripts-after') ?>

</html>