<?php

namespace Source\Models\User;

use Source\Core\Model;
use Source\Core\Session;
use Source\Core\View;
use Source\Support\Email;

/**
 * Class Auth
 * @package Source\Models
 */
class Auth extends Model
{
    /**
     * authUser constructor.
     */
    public function __construct()
    {
        parent::__construct("user", ["id"], ["email", "password"]);
    }

    /**
     * @return null|User
     */
    public static function user(): ?User
    {
        $session = new Session();
        $cookie = filter_input(INPUT_COOKIE, "USER");

        if($cookie){
            $session->set("USER",$cookie);
        }
        //$session->unset("authUser");
        if (!$session->has("USER")) {
            return null;
        }
        return (new User())->findById($session->USER);
    }

    /**
     * log-out
     */
    public static function logout(): void
    {
        $session = new Session();
        $session->unset("USER");
        $session->unset("EMAIL");
        $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
        unsetCookie("USER","/",$domain,false);
        unsetCookie("EMAIL","/",$domain,false);
        unsetCookie("TOKEN","/",$domain,false);

    }

    /**
     * @param User $user
     * @return bool
     */
    public function register(User $user): bool
    {

        if (!$user->save()) {
            $this->message = $user->message;
            return false;
        }

        (new Session())->set("USER", $user->id);

        // $token = base64_encode($user->email_usuario);

        // $view = new View(__DIR__ . "/../../../shared/views/email");
        // $subject = "[CADASTRO CONFIRMADO]! {$user->nome_usuario} estes são seus dados de acesso ao sistema";

        // $message = $view->render("mail", [
        //     "subject" =>  $subject,
        //     "headline" => "Obrigado por se cadastrar para obter o !! Agora somos do mesmo TIME.",
        //     "message" => "<p>Utilize os dados abaixo para começar uma nova experiência com seu arduino. </p><p><b>DADOS DE ACESSO</b></p><div class='group' style='border: 1px solid #000; padding:10px 5px;'><p style='font-size:16px;'><b>Usuário:</b>{$user->email_usuario}</p><p style='font-size:16px;color:gray' ><b>Senha:</b>automacaonaveia</p><p style='color:green;font-weight:bold'><br><i>Clique no link abaixo para acessar</i></p><p style='margin-top:12px;'><a href='" . url("/acesso-gratis/{$token}") . "' >COMEÇAR AGORA</p></a></div><br><p><small><i>Se tiver alguma dificuldade é só entrar em contato com a nossa equipe respondendo este e-mail</i></small></p>",
        // ]);

        // (new Email())->bootstrap(
        //     $subject,
        //     $message,
        //     $user->email_usuario,
        //     "{$user->nome_usuario}"
        // )->queue();
      

        return true;
    }

    public function attempt(string $email, string $password, int $level = 1): ?User
    {
        if (!is_passwd($password)) {
            $this->message->warning("A senha informada não é válida");
            return null;
        }

        /* Verifica o formato de e-mail válido*/
        if (!is_email($email)) {
            $this->message->warning("O email informado não tem um formato válido");
            return null;
        }
        $user = (new User())->findByEmail($email);
            
        if (!$user) {
            $this->message->error("O Email informado não está cadastrado");
            return null;
        }

        if (strcmp(md5($password), $user->password)) {
            $this->message->error("A senha informada não confere");
            return null;
        }

               
        $user->last_login = date_timestamp();
        $user->save();

        return $user;
    }

    /**
     * @param string $email
     * @param string $password
     * @param bool $save
     * @return bool
     */
    public function login(string $email, string $password, bool $save = false): bool
    {

        $user = $this->attempt($email, $password, 1);
        if (!$user) {
            return false;
        }

        if ($save) {
            $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
            setcookie("EMAIL", $email, time() + (60 * 60 * 24 * 15), "/",$domain,false);
            setcookie("USER", $user->id, time() + (60 * 60 * 24 * 15) , "/",$domain,false);
            setcookie("TOKEN", $user->password, time() + (60 * 60 * 24 * 15) , "/",$domain,false);
        } else {
            setcookie("EMAIL", null, time() - 3600, "/");
        }

        //LOGIN
        (new Session())->set("USER", $user->id);
        (new Session())->set("EMAIL", $user->email);

        return true;
    }

    /**
     * @param string $email
     * @return bool
     */
    public function forget(string $email): bool
    {
        $user = (new User())->findByEmail($email);

        if (!$user) {
            $this->message->warning("O e-mail informado não está cadastrado.");
            return false;
        }

        $user->forget = md5(uniqid(rand(), true));
        $user->save();

        $view = new View(__DIR__ . "/../../../shared/views/email");
        $message = $view->render("forget", [
            "first_name" => $user->name,
            "forget_link" => url("/recuperar/{$user->email}|{$user->forget}")
        ]);

        (new Email())->bootstrap(
            "Recupere sua senha na ProtePOP",
            $message,
            $user->email,
            "{$user->name}"
        )->queue();

        return true;
    }


    /**
     * @param string $email
     * @param string $code
     * @param string $password
     * @param string $passwordRe
     * @return bool
     */
    public function reset(string $email, string $code, string $password, string $passwordRe): bool
    {
        $user = (new User())->findByEmail($email);

        if (!$user) {
            $this->message->warning("A conta para recuperação não foi encontrada.");
            return false;
        }

        if ($user->forget != $code) {
            $this->message->error("Desculpe, mas o código de verificação não é válido.");
            return false;
        }

        if (!is_passwd($password)) {
            $min = CONF_PASSWD_MIN_LEN;
            $max = CONF_PASSWD_MAX_LEN;
            $this->message->info("Sua senha deve ter entre {$min} e {$max} caracteres.");
            return false;
        }

        if ($password != $passwordRe) {
            $this->message->warning("Você informou duas senhas diferentes.");
            return false;
        }

        $user->password = md5($password);
        $user->forget = null;
        $user->save();
        return true;
    }
}