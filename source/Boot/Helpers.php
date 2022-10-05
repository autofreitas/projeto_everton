<?php

use Source\Core\Session;
use Source\Support\Message;

date_default_timezone_set("Brazil/East");

/**
 * @param string $mail
 * @return bool
 */
function is_email(string $mail): bool
{
    return filter_var($mail, FILTER_VALIDATE_EMAIL);
}

/**
 * @param string $password
 * @return bool
 */
function is_passwd(string $password): bool
{
    if (password_get_info($password)['algo'] || (mb_strlen($password) >= CONF_PASSWD_MIN_LEN && mb_strlen($password) <= CONF_PASSWD_MAX_LEN)) {
        return true;
    }
    return false;
}


/**
 * ##################
 * ###   STRING   ###
 * ##################
 */

/**
 * @param string $string
 * @return string
 *
 * Adiciona os hífens para url
 * Ex:  (São José dos Pinhais, PR)  fica São-José-dos-Pinhais-PR
 */
function city_encode_filter_url(string $city, string $uf): string
{
    $city = filter_var($city, FILTER_SANITIZE_STRIPPED);
    $uf = filter_var($uf, FILTER_SANITIZE_STRIPPED);
    $filter = str_replace(" ", "-", $city);
    $filter .= "-" . $uf;
    return $filter;
}

/**
 * @param string $string
 * @return array
 *
 * Retira os Hífens do nome da Cidade
 * Ex: São-José-dos-Pinhais-PR  fica (São José dos Pinhais, PR)
 */
function city_decode_filter_url(string $string): array
{
    $string = filter_var($string, FILTER_SANITIZE_STRIPPED);
    $param = strripos($string, "-");   //  Procura o último caractere "-" que separa a cidade da UF
    $city = str_replace("-", " ", substr($string, 0, $param));  // Quebra a string na parte inicial CIDADE
    $state = substr($string, $param + 1); // Quebra a string na parte final UF

    $filter = array(
        "city" => $city,
        "state" => $state
    );
    return $filter;
}

/**
 * @param string $string
 * @return string
 */
function str_slug(string $string): string
{
    $string = filter_var(mb_strtolower($string), FILTER_SANITIZE_STRIPPED);
    $formats = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr"!@#$%&*()_-+={[}]/?;:.,\\\'<>°ºª';
    $replace = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr                                 ';

    $slug = str_replace(["-----", "----", "---", "--"], "-",
        str_replace(" ", "-", trim(strtr(utf8_decode($string), utf8_decode($formats), $replace))));
    return $slug;
}

/**
 * @param string $string
 * @return string
 */
function str_studly_case(string $string): string
{
    $string = str_slug($string);
    $studlyCase = str_replace(" ", "", mb_convert_case(str_replace("-", " ", $string), MB_CASE_TITLE));
    return $studlyCase;
}

/**
 * @param string $string
 * @return string
 */
function str_camel_case(string $string): string
{
    return lcfirst(str_studly_case($string));
}

/**
 * @param string $string
 * @return string
 */
function str_first_up(string $string): string
{
    return ucfirst(strtolower($string));
}

/**
 * @param string $string
 * @return string
 */
function str_first_name(string $string): string
{   
    return ucfirst(strtolower(explode(' ',$string)[0]));
}


/**
 * @param string $text
 * @return string|string[]|null
 */
function only_numbers(string $text)
{
    return preg_replace("/[^0-9]/", "", $text);
}


/**
 * @param string $string
 * @return string
 */
function str_title(string $string): string
{
    return mb_convert_case(filter_var($string, FILTER_SANITIZE_FULL_SPECIAL_CHARS), MB_CASE_TITLE);
}

/**
 * @param string $string
 * @param int $limit
 * @param string $pointer
 * @return string
 */
function str_limit_words(string $string, int $limit, string $pointer = "..."): string
{
    $string = trim(filter_var($string), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $arrWords = explode(" ", $string);
    $numWords = count($arrWords);

    if ($numWords < $limit) {
        return $string;
    }
    $words = implode(" ", array_slice($arrWords, 0, $limit));
    return "{$words}{$pointer}";
}

/**
 * @param string $string
 * @param int $limit
 * @param string $pointer
 * @return string
 */
function str_limit_chars(string $string, int $limit, string $pointer = "..."): string
{
    $string = trim(filter_var($string), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    if (mb_strlen($string) <= $limit) {
        return $string;
    }

    $chars = mb_substr($string, 0, mb_strrpos(mb_substr($string, 0, $limit), " "));
    return "{$chars}{$pointer}";
}

/**
 * Retorna o primeiro é o último nome
 *
 * @param String $name
 * @return object
 */
function flName(String $name): object
{

    $partes = explode(' ', $name);
    $obj = new StdClass();
    $obj->first_name = ucfirst(array_shift($partes));
    $obj->last_name = ucfirst(array_pop($partes));
    return $obj;
    //return ucfirst(substr($name, 0, (strpos($name, " ") ? strpos($name, " ") : strlen($name))));

}


/**
 *  Format Phone
 */
function phone_br($n)
{
    $tam = strlen(preg_replace("/[^0-9]/", "", $n));


    if ($tam == 13) {
        // COM CÓDIGO DE ÁREA NACIONAL E DO PAIS e 9 dígitos
        return "+" . substr($n, 0, $tam - 11) . " (" . substr($n, $tam - 11, 2) . ") " . substr($n, $tam - 9, 5) . "-" . substr($n, -4);
    }
    if ($tam == 12) {
        // COM CÓDIGO DE ÁREA NACIONAL E DO PAIS
        return "+" . substr($n, 0, $tam - 10) . " (" . substr($n, $tam - 10, 2) . ") " . substr($n, $tam - 8, 4) . "-" . substr($n, -4);
    }
    if ($tam == 11) {
        // COM CÓDIGO DE ÁREA NACIONAL e 9 dígitos
        return "(" . substr($n, 0, 2) . ") " . substr($n, 2, 5) . "-" . substr($n, 7, 11);
    }
    if ($tam == 10) {
        // COM CÓDIGO DE ÁREA NACIONAL
        return "(" . substr($n, 0, 2) . ") " . substr($n, 2, 4) . "-" . substr($n, 6, 10);
    }
    if ($tam <= 9) {
        // SEM CÓDIGO DE ÁREA
        return substr($n, 0, $tam - 4) . "-" . substr($n, -4);
    }
}

/**
 *  Format Phone
 */
function cpf($n)
{
    $n = preg_replace("/[^0-9]/", "", $n);

    // 00655225919
    return substr($n, 0, 3) . "." . substr($n, 3, 3) . "." . substr($n, 6, 3) . "-" . substr($n, 9);
}

/**
 *  Format CNPJ
 */
function cnpj($n)
{
    $n = preg_replace("/[^0-9]/", "", $n);

    // 453.323.222/0001-23
    return substr($n, 0, 3) . "." . substr($n, 3, 3) . "." . substr($n, 6, 3) . "/" . substr($n, 9, 4). "-" . substr($n, 13);
}

/**
 *  Format ZIPCODE (CEP)
 */
function cep($n)
{
    $n = preg_replace("/[^0-9]/", "", $n);

    // 88220-000
    return substr($n, 0, 5) . "-" . substr($n, 5);
}

/**
 * ##################
 * ###   IMAGE    ###
 * ##################
 */

/**
 * @param string $image
 * @param int $width
 * @param int|null $height
 * @return string
 */
function image(?string $image, int $width, int $height = null): ?string
{
    if ($image) {
       // return url() . "/" . (new \Source\Support\Thumb())->make($image, $width, $height);
    }
    return null;
}

/**
 * @param string $url_image
 * @param bool $is_thumb
 * @param string $image_error
 * @return string|null
 */
function load_image(
    string $url_image,
    string $image_error = ""
): ?string {
    if (file_exists(__DIR__ . "/../../" . CONF_UPLOAD_DIR . "/" . $url_image)) {
        //return url(CONF_UPLOAD_DIR."/".(($is_thumb)? "thumb-".$url_image:$url_image));
        return $url_image;
    }
    return $image_error;
}


/**
 * ###############
 * ###   URL   ###
 * ###############
 */

function isLocal(): bool
{
    if (strpos($_SERVER['HTTP_HOST'], "ocalhost")) {
        return true;
    }
    return false;
}

/**
 * @param string $string
 * @return string
 */
function url(string $path = null): string
{

    if (isLocal()) {
        if ($path) {
            return CONF_URL_TEST . "/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
        }
        return CONF_URL_TEST;
    }
    if ($path) {
        return CONF_URL_BASE . "/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
    }
    return CONF_URL_BASE;
}

/**
 * @return string
 */
function url_back(): string
{
    return ($_SERVER['HTTP_REFERER'] ?? url());
}

/**
 * @param string $url
 */
function redirect(string $url): void
{
    header("HTTP/1.1 302 Redirect");
    if (filter_var($url, FILTER_VALIDATE_URL)) {

        exit;
    }
    $location = url($url);
    header("Location: {$location}");
    exit;
}


/**
 * ##################
 * ###   ASSETS   ###
 * ##################
 */

/**
 * @return \Source\Models\User|null
 */
function user(): ?\Source\Models\User\User
{
    return \Source\Models\User\Auth::user();
}

/**
 * @return \Source\Core\Session
 */
function session(): \Source\Core\Session
{
    return new \Source\Core\Session();
}

/**
 * @param string|null $path
 * @param string $theme
 * @return string
 */
function theme(string $path = null, string $theme = CONF_VIEW_THEME_SITE): string
{

    if (isLocal()) {
        if ($path) {
            return CONF_URL_TEST . "/themes/{$theme}/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
        }

        return CONF_URL_TEST . "/themes/{$theme}";
    }

    if ($path) {
        return CONF_URL_BASE . "/themes/{$theme}/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
    }

    return CONF_URL_BASE . "/themes/{$theme}";
}


/**
 * ################
 * ###   DATE   ###
 * ################
 */
/**
 * @param string $date
 * @param string $format
 * @return string
 */
function date_timestamp(string $date = "now", string $format = "Y-m-d H:i:s"): string
{
    return (new DateTime($date))->format($format);
}

/**
 * @param string $date
 * @param string $format
 * @return string
 */
function date_fmt(string $date = "now", string $format = "d/m/Y H\hi"): string
{
    return (new DateTime($date))->format($format);
}

/**
 * @param string $date
 * @return string
 */
function date_fmt_br(string $date = "now"): string
{
    return (new DateTime($date))->format(CONF_DATE_BR);
}

/**
 * @param string $date
 * @return string
 */
function date_fmt_app(string $date = "now"): string
{
    return (new DateTime($date))->format(CONF_DATE_APP);
}

/**
 * @param string $data
 * @param string $now
 * @return bool
 * @throws Exception
 */
function compareDate(string $data, string $now = 'now')
{
    if ($now == 'now') {
        $now = (new DateTime('now'))->format("Y-m-d");
    }
    return (strtotime($data) >= strtotime($now)) ? true : false;
}

/**
 * @param string $data
 * @param string $now
 * @return int
 * @throws Exception
 * 
 * Calcula a diferença entre 2 datas mostrando o tempo em dias
 */
function diffDate(string $data, string $now = 'now')
{
    if ($now == 'now') {
        $now = (new DateTime('now'))->format("Y-m-d");
    }

    $dateBegin = new DateTime($data);
    $dateEnd = new DateTime($now);

    $dateInterval = $dateBegin->diff($dateEnd);
    return $dateInterval->days;
}

/**
 * @param string $data
 * @param string $now
 * @return DateTime Object
 * @throws Exception
 * 
 * Calcula a diferença entre 2 datas retornando o Objeto
 * Para saber a data
 *  obj->days;  dias
 *  obj->h;   horas
 *  obj->i;   minutos
  */
function diffDateStamp(string $data, string $now = 'now')
{
    if ($now == 'now') {
        $now = (new DateTime('now'))->format("Y-m-d H:i:s");
    }

    $dateBegin = new DateTime($data);
    $dateEnd = new DateTime($now);

    $dateInterval = $dateBegin->diff($dateEnd);
    return $dateInterval;
}

/*
    Adiciona X dias a Data atual
 */
function dateNowAddDays(string $days = '30'){
    
    $now = (new DateTime('now'))->format("Y-m-d H:i:s");
    $data = new DateTime($now);

    $data->add(new DateInterval('P'.$days.'D'));
    return $data->format('Y-m-d');

}


/**
 * ####################
 * ## NUMBER FORMAT  ##
 * ####################
 */

function number_format_pt(string $number): string
{
    return number_format($number, 2, ',', '.');
}

/**
 * @param string $number
 * @return string
 */
function number_format_us(string $number): string
{
    return number_format($number, 2, '.', ',');
}

/**
 * ####################
 * ###   PASSWORD   ###
 * ####################
 */

/**
 * @param string $password
 * @return string
 */
function passwd(string $password): string
{
    if (!empty(password_get_info($password)['algo'])) {
        return $password;
    }

    return password_hash($password, CONF_PASSWD_ALGO, CONF_PASSWD_OPTION);
}

/**
 * @param string $password
 * @param string $hash
 * @return bool
 */
function passwd_verify(string $password, string $hash): bool
{
    return password_verify($password, $hash);
}

/**
 * @param string $hash
 * @return bool
 */
function passwd_rehash(string $hash): bool
{
    return password_needs_rehash($hash, CONF_PASSWD_ALGO, CONF_PASSWD_OPTION);
}

/**
 * ###################
 * ###   REQUEST   ###
 * ###################
 */

/**
 * @return string
 */
function csrf_input(): string
{
    $session = new \Source\Core\Session();
//    if(!empty($session->csrf_token)){
//        $session->set("old",$session->csrf_token);
//    }
//
//    if(!empty($session->old)){
//        $session->set("old2",$session->old);
//    }
    $session->csrf();
    return "<input type='hidden' name='csrf' value='" . ($session->csrf_token ?? "") . "'/>";
}

/**
 * @param $request
 * @return bool
 */
function csrf_verify($request): bool
{
    $session = new \Source\Core\Session();
    if (empty($session->csrf_token) || empty($request['csrf']) || $request['csrf'] != $session->csrf_token) {
        return false;
    }
    return true;
}

/**
 * @return null|string
 */
function flash(): ?string
{
    $session = new \Source\Core\Session();
    if ($flash = $session->flash()) {
        echo $flash;
    }
    return null;
}

/**
 * @param string $key
 * @param int $limit
 * @param int $seconds
 * @return bool
 */
function request_limit(string $key, int $limit = 5, int $seconds = 60): bool
{
    $session = new Session();
    if ($session->has($key) && $session->$key->time >= time() && $session->$key->requests < $limit) {
        $session->set($key, [
            "time" => time() + $seconds,
            "requests" => $session->$key->requests + 1
        ]);
        return false;
    }

    if ($session->has($key) && $session->$key->time >= time() && $session->$key->requests >= $limit) {
        return true;
    }

    $session->set($key, [
        "time" => time() + $seconds,
        "requests" => 1
    ]);

    return false;
}


/**
 * @param string $field
 * @param string $value
 * @return bool
 */
function request_repeat(string $field, string $value): bool
{
    $session = new \Source\Core\Session();
    if ($session->has($field) && $session->$field == $value) {
        return true;
    }

    $session->set($field, $value);
    return false;
}


/**
 * ##################
 * ###   IMAGE    ###
 * ##################
 */
/*
 *  Calcula a distância entre 2 pontos
 * */
/**
 * @param $lat1
 * @param $lon1
 * @param $lat2
 * @param $lon2
 * @param string $unit
 * @return float|int
 */
function distance($lat1, $lon1, $lat2, $lon2, $unit = "K")
{
    $radius = 6378.137; // earth mean radius defined by WGS84
    $dlon = $lon1 - $lon2;
    $distance = acos(sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($dlon))) * $radius;

    if ($unit == "K") {
        return ($distance);
    } else {
        if ($unit == "M") {
            return ($distance * 0.621371192);
        } else {
            if ($unit == "N") {
                return ($distance * 0.539956803);
            } else {
                return 0;
            }
        }
    }
}


/**
 * ####################
 * ###   MASK   ###
 * ####################
 * mascara_string("(##) #####-####",$voucher->cell)
 */

function mascara_string(string $mascara, string $string): string
{
    // $string = str_replace(" ", "", $string);

     //  $mascara = (##) #####-####    $string 4732468244
    $string = only_numbers($string);
    $size = mb_strlen($string);
    for ($i = 0; $i < $size; $i++) {
        if (!empty(strpos($mascara, "#"))) {
            $mascara[strpos($mascara, "#")] = $string[$i];
        }
    }
    return $mascara;
}

/**
 * Unset cookies
 *
 * @param string $key    Nome do cookie
 * @param string $path   (Opcional) Se definido irá remover o cookie de caminhos especificos
 * @param string $domain (Opcional) Se definido irá remover o cookie de (sub)dominios especificos
 * @param bool $secure   (Opcional) Se definido irá remover o cookie em conexão segura (isto varia conforme o navegador)
 * @return bool
 */
function unsetCookie($key, $path = '', $domain = '', $secure = false)
{
    if (array_key_exists($key, $_COOKIE)) {
        if (false === setcookie($key, null, -1, $path, $domain, $secure)) {
            return false;
        }

        unset($_COOKIE[$key]);
    }

    return true;
}


/**
 * ####################
 * ###   Estados / SIGLA   ###
 * ####################
 */
function ufToState(string $uf): string
{
    switch ($uf) {
        case 'AC' :
            return 'Acre';
            break;
        case 'AL' :
            return 'Alagoas';
            break;
        case 'AM' :
            return 'Amazonas';
            break;
        case 'AP' :
            return 'Amapá';
            break;
        case 'BA' :
            return 'Bahia';
            break;
        case 'CE' :
            return 'Ceará';
            break;
        case 'DF' :
            return 'Distrito Federal';
            break;
        case 'ES' :
            return 'Espírito Santo';
            break;
        case 'GO' :
            return 'Goiás';
            break;
        case 'MA' :
            return 'Maranhão';
            break;
        case 'MG' :
            return 'Minas Gerais';
            break;
        case 'MS' :
            return 'Mato Grosso do Sul';
            break;
        case 'MT' :
            return 'Mato Grosso';
            break;
        case 'PA' :
            return 'Pará';
            break;
        case 'PB' :
            return 'Paraíba';
            break;
        case 'PE' :
            return 'Pernambuco';
            break;
        case 'PI' :
            return 'Piauí';
            break;
        case 'PR' :
            return 'Paraná';
            break;
        case 'RJ' :
            return 'Rio de Janeiro';
            break;
        case 'RN' :
            return 'Rio Grande do Norte';
            break;
        case 'RO' :
            return 'Rondônia';
            break;
        case 'RR' :
            return 'Roraima';
            break;
        case 'RS' :
            return 'Rio Grande do Sul';
            break;
        case 'SC' :
            return 'Santa Catarina';
            break;
        case 'SE' :
            return 'Sergipe';
            break;
        case 'SP' :
            return 'São Paulo';
            break;
        case 'TO' :
            return 'Tocantins';
            break;
        default:
            return $uf;
    }
}