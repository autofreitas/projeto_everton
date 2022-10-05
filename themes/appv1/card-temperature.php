<?php

if (!empty($module)) :
    $jsonConfig = (empty($module->config)) ? "" : json_decode($module->config);
    $publish = (empty($module->publish->json)) ? "" : json_decode($module->publish->json);
endif;

?>
<div class="card widget -white mr-10 ml-10 mb-10 animate__animated bounceIn">
    <a href="<?= url("app/modulo/" . $module->token) ?>">
        <div class="content pt-20 pl-20 pr-10 pb-10" id="<?= $module->token ?>">
            <div class="title fb-100">
                <p class="cf-text-5 bold --black"><?= $jsonConfig->title ?></p>
                <p class="cf-text-4 normal --gray"><?= $jsonConfig->subtitle ?></p>
            </div>
            <div class="content pb-10">
                <div class="widgets temperature">
                    <div class="info">
                        <p class="cf-text-10 info-value black --color-primary" id="temperature">00</p>
                        <p class="cf-text-4 info-value black --color-primary" id="celsius">Cº</p>
                        <span class="material-icons --gray pl-5">thermostat</span>
                    </div>
                    <div class="info">
                        <p class="--gray"><span id="humidity">00</span>%</p>
                        <span class="material-icons --gray pl-5" id="percent">water_drop</span>
                    </div>
                </div>
                <div class="widgets temperature">
                    <div class="config">
                        <p class="cf-text-3 --red fb-100 center">Max : <?= $jsonConfig->setTempMax ?>Cº</p>
                        <p class="cf-text-3 --blue-light fb-100 center">Min : <?= $jsonConfig->setTempMin ?>Cº</p>
                    </div>
                    <div class="config none">
                        <p class="cf-text-3 --red fb-100 center">Max : <?= $jsonConfig->setTempMax ?>%</p>
                        <p class="cf-text-3 --blue fb-100 center">Min : <?= $jsonConfig->setTempMin ?>%</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer color-primary" id="<?= $module->token ?>-footer">
            <p class="cf-text-3 --white p-5">
                <?= $module->description . " | " . ((empty($module->publish->updated_at)) ? "" : date_fmt_br($module->publish->updated_at)); ?>
            </p>
        </div>
    </a>
</div>