<?php $v->layout("_theme",["textWhats"=> "Olá, estou entrando na página de erro"]); ?>

<?php $v->start("menu"); ?>
<a class="link transition radius" title="Home" href="<?= url(""); ?>">Home</a>
<?php $v->end(); ?>
<section class="optin color-primary">
    <header>
        <div class="header_top">
            <img src="<?= url("shared/images/icons/logo-cemf-e.png") ?>" alt="">
            <p class="cf-text-3 --white normal">&nbsp;DO INICIANTE AO AVANÇADO | <span class="--color-secondary">ARDUINO DO JEITO CERTO</span></p>
        </div>
    </header>
</section>
<article class="not_found">
    <div class="container content">
        <header class="not_found_header">
            <p class="error">&bull;<?= $error->code; ?>&bull;</p>
            <h1 class="flex-center "><i class="material-icons --color-secondary">thumb_down</i>&nbsp; <?= $error->title; ?></h1>
            <p><?= $error->message; ?></p>

            <?php if ($error->link) : ?>
                <a class="not_found_btn btn-default color-primary font-theme-2 bold" title="<?= $error->linkTitle; ?>" href="<?= $error->link; ?>"><?= $error->linkTitle; ?></a>
            <?php endif; ?>
        </header>
    </div>
</article>