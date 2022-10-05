<!doctype html>
<html>
<head>
    <meta name="viewport" content="width=device-width"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title><?= $title; ?></title>
    <style>
        body {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            font-family: Helvetica, sans-serif;
        }

        table {
            max-width: 500px;
            padding: 0 10px;
            background: #ffffff;
        }

        .content {
            font-size: 16px;
            margin-bottom: 25px;
            padding-bottom: 5px;
            border-bottom: 1px solid #EEEEEE;
        }
        .whats{
            padding: 10px;
            background: green;
            border-radius: 5px;
            text-decoration: none;
            color:white !important;
            font-size: 12px;
        }
        .whats:link{ color:white !important; text-decoration: none;}
        .whats:visited{ color:white !important ;text-decoration: none;}
        .whats:hover{ color:white !important;}
        .whats:active{ color:white !important; text-decoration: none;}
        

        .content p {
            margin: 25px 0;
        }

        .group p{
            margin: 5px 0;
            font-size: 13px;
        }

        .footer {
            font-size: 14px;
            color: #888888;
            font-style: italic;
        }

        .footer p {
            margin: 0 0 2px 0;
        }
    </style>
</head>
<body>
<table role="presentation" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <div class="content">
                <?= $v->section("content"); ?>
                <p>Ats,</p>
                <p>Carlos Freitas</p>
                
            </div>
            <div class="footer">
                <p><?= CONF_SITE_NAME; ?> - <?= CONF_SITE_TITLE; ?></p>
                <p><?= CONF_SITE_DOMAIN; ?></p>
            </div>
        </td>
    </tr>
</table>
</body>
</html>
