<?php

/**
 *  ARQUIVOS ATUALIZADOR CSS E JAVASCRIPT SERVER
 */
define("CONF_CSS_DEFAULT", "styles-default-1658509935.min.css");
define("CONF_JS_DEFAULT", "scripts-default-1658509964.min.js");


define("CONF_CSS_APP_CLIENT", "styles-appv1-1658509909.min.css");
define("CONF_JS_APP_CLIENT", "scripts-appv1-1658319339.min.js");


/**
 * PROJECT URLs
 */
define("CONF_URL_BASE", "https://protepop.cemf.com.br");
define("CONF_URL_TEST", "https://www.localhost/projetos/CEMF/protepop");


/**
 *  VIEW
 */
define("CONF_VIEW_THEME_APP", "appv1");
define("CONF_VIEW_EXT", "php");


/**
 *  SITE
 */
define("CONF_SITE_NAME", "ProtePop");
define("CONF_SITE_TITLE", "Sistema de monitoramento");
define("CONF_SITE_DESC", "");
define("CONF_SITE_LANG", "pt_BR");
define("CONF_SITE_DOMAIN", "protepot.cemf.com.br");


/**
 * SOCIAL
 */

define("CONF_SOCIAL_TELEGRAM_BOT_KEY", "1483884339:AAEL1P4immZSeQhh_wsZ1d-ieBxwjq6WX-M");
define("CONF_SOCIAL_TELEGRAM_BOT_CHANNEL", "-1001264175917"); //@AutomacaoNaVeiaBot


/**
 *   DATES
 */
define("CONF_DATE_BR", "d/m/Y H:i:s");
define("CONF_DATE_APP", "Y-m-d H:i:s");


/**
 *  UPLOAD
 */
define("CONF_UPLOAD_DIR", "storage");
define("CONF_UPLOAD_IMAGE_DIR", "images");
define("CONF_UPLOAD_FILE_DIR", "files");
define("CONF_UPLOAD_MEDIA_DIR", "medias");

// define("CONF_UPLOAD_MEDIA_DIR_OFFER","offers");
// define("CONF_UPLOAD_MEDIA_DIR_COMPANY","companies");
// define("CONF_UPLOAD_MEDIA_DIR_USER","users");

// define("CONF_IMAGE_USER_ANONIMOUS",CONF_UPLOAD_IMAGE_DIR."/".CONF_UPLOAD_MEDIA_DIR_USER."/anonimous.png");
// define("CONF_IMAGE_OFFER_DEFAULT",CONF_UPLOAD_IMAGE_DIR."/".CONF_UPLOAD_MEDIA_DIR_OFFER."/offer_image_default.jpg");
// define("CONF_IMAGE_OFFER_THUMB_DEFAULT",CONF_UPLOAD_IMAGE_DIR."/".CONF_UPLOAD_MEDIA_DIR_OFFER."/ic_new_photo.png");
// define("CONF_IMAGE_COMPANY_DEFAULT",CONF_UPLOAD_IMAGE_DIR."/".CONF_UPLOAD_MEDIA_DIR_COMPANY."/company_default.jpg");
// define("CONF_IMAGE_COMPANY_COVER_DEFAULT",CONF_UPLOAD_IMAGE_DIR."/".CONF_UPLOAD_MEDIA_DIR_COMPANY."/company_cover_default.jpg");


/**
 * PASSWORD
 */
define("CONF_PASSWD_MIN_LEN", 4);
define("CONF_PASSWD_MAX_LEN", 40);
define("CONF_PASSWD_ALGO", PASSWORD_DEFAULT);
define("CONF_PASSWD_OPTION", ["cost" => 10]);

define("CONF_PASSWORD_DEFAULT_AV", 'automacaonaveia'); // Senha inicial para novos cadastros

/**
 * IMAGES
 */
// define("CONF_IMAGE_CACHE", CONF_UPLOAD_DIR . "/" . CONF_UPLOAD_IMAGE_DIR . "/cache");
// define("CONF_IMAGE_SIZE_DEFAULT", 2000); // tamanho máxima das imagens 2M
// define("CONF_IMAGE_SIZE_COVER_DEFAULT", 4000);  ///  Tamanho maximo da Imagem de fundo 4M
// define("CONF_IMAGE_QUALITY", ["jpg" => 75, "png" => 5]);

/** LARGURA WIDTH MÁXIMA DAS IMAGENS */
// define("CONF_IMAGE_SIZE_OFFER",1095);
// define("CONF_IMAGE_SIZE_LIST_OFFER",500);
// define("CONF_IMAGE_SIZE_THUMB",200);
// define("CONF_IMAGE_SIZE_USER",500);
// define("CONF_IMAGE_SIZE_COVER",1900);




// SENDGRID CONFIG
//define("CONF_MAIL_HOST", "smtp.sendgrid.net");
//define("CONF_MAIL_PORT", "587");
//define("CONF_MAIL_USER", "apikey");
//define("CONF_MAIL_PASS", "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");
define("CONF_SENDGRID_API_KEY", "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");
define("CONF_MAIL_HOST", "mail.cemf.com.br");
define("CONF_MAIL_PORT", "465");
define("CONF_MAIL_USER", "suporte@cemf.com.br");
define("CONF_MAIL_PASS", "O)Ph7S)8rhNS"); //LoVZW;9K{RFQ      //dia01Mes01
//define("CONF_MAIL_SENDER", ["name" => "CEMF Tecnologia", "address" => "suporte@cemf.com.br"]);    // remetende para envio de e-mail
define("CONF_MAIL_SENDER", ["name" => "Carlos Freitas | Automação na Veia", "address" => "carlos@autofreitas.com.br"]);    // remetende para envio de e-mail
define("CONF_MAIL_SUPPORT", "carlos@autofreitas.com.br"); // e-mail do suporte
define("CONF_MAIL_OPTION_LANG", "br");
define("CONF_MAIL_OPTION_HTML", true);
define("CONF_MAIL_OPTION_AUTH", true);
//define("CONF_MAIL_OPTION_SECURE", "tls");
define("CONF_MAIL_OPTION_SECURE", "ssl");
define("CONF_MAIL_OPTION_CHARSET", "utf-8");


define("CONF_LOG_FILENAME", "log_av.txt");
define("CONF_LOG_DIR", __DIR__ . "/../../" . CONF_LOG_FILENAME);




/** EMAILS DE CONTATO */
define("CONF_SUPPORT_WHATSAPP", "47999622575");



