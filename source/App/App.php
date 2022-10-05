<?php

namespace Source\App;


use Source\Core\Controller;
use Source\Core\Session;
use Source\Models\User\User;
use Source\Models\User\Auth;
use Source\Support\MyLog;
use Source\Models\Module;
use Source\Models\Address;
use Source\Models\Shared;
use Source\Core\View;
use Source\Core\Connect;
use Source\Models\Grouping;
use Source\Models\GroupingModule;
use Source\Models\Publish;

/**
 * Class Web
 * @package Source\App
 */
class App extends Controller
{

    /*** @var */
    private $logger;

    /*** @var */
    private $user;

    /**
     * Web constructor.
     */
    public function __construct()
    {

        parent::__construct(__DIR__ . "/../../themes/" . CONF_VIEW_THEME_APP . "/");

        $this->user = Auth::user();
        /** ADD LOG */
        $this->logger = new MyLog("appv1", $this->user ?? null);
    }

    /**
     *  SITE PAGE CONSTRUCT
     */
    public function pageConstruct(): void
    {
        $head = $this->seo->render(
            CONF_SITE_NAME . " - " . CONF_SITE_TITLE,
            "",
            url(),
            url("/shared/images/social/share.jpg")
        );
        echo $this->view->render("page-construct", [
            "head" => $head,
        ]);
    }

    /**
     *  LOGIN PARA ENTRADA NO SISTEMA
     */
    public function login(?array $data): void
    {


        /** INICIO */
        $session = new Session();

        if (Auth::user()) {
            redirect("/app/dashboard");
        }

        if (!empty($data['csrf'])) {

            // habilitar para teste
            // $session->unset("appLogin");
            if (request_limit('appLogin', 5, 5 * 60)) {
                $json['message'] = $this->message->error("Você já efetuou 5 tentativas, esse é o limite. Por favor, aguarde por 5 minutos para tentar novamente !")->render();
                echo json_encode($json);
                return;
            }

            if (empty($data['email']) || empty($data['password'])) {
                $json['message'] = $this->message->warning("Informe seu Email e senha para entrar")->render();
                echo json_encode($json);
                return;
            }



            $save = (!empty($data['save']) ? true : false);

            $authUser = new Auth();
            $login = $authUser->login(trim($data['email']), $data['password'], $save);


            if ($login) {

                // Sessão que controla as tentativas
                $session->unset("appLogin");
                $json['redirect'] = url("/app/dashboard");
                $json['message'] = $this->message->info("Seja bem vindo")->flash();
            } else {
                $json['message'] = $authUser->message()->before("Ooops! ")->render();
            }

            echo json_encode($json);
            return;
        }


        /** FIM */

        // if (!empty($data['csrf'])) {
        //     //$json['message'] = $this->message->success("Deu tudo certo, mas ainda estamos em desenvolvimento!")->render();
        //     sleep(1);
        //     $json['redirect'] = url("app/dashboard");
        //     echo json_encode($json);
        //     return;
        // }


        $head = $this->seo->render(
            "ENTRAR - " . CONF_SITE_NAME,
            "",
            url(),
            url("/shared/images/social/share.jpg")
        );
        echo $this->view->render("login", [
            "head" => $head,
            "cookie" => filter_input(INPUT_COOKIE, "authEmail")
        ]);
    }


    /**
     *  PAINEL DE CONTROLE PRINCIPAL
     */
    public function dashboard(?array $data): void
    {

        if (!$this->user) {
            $this->message->info("Informe o seu usuário de acesso")->flash();
            redirect("login");
        }

        $layout = filter_var(isset($data["layout"]) ? $data["layout"] : NULL, FILTER_UNSAFE_RAW);
        $token = filter_var(isset($data["token"]) ? $data["token"] : NULL, FILTER_UNSAFE_RAW);

        if (!empty($data['csrf'])) {
            $json['message'] = $this->message->success("Deu tudo certo, mas ainda estamos em desenvolvimento!")->render();
            echo json_encode($json);
            return;
        }

        // Existem o envio do token de do módulo
        if ($token) {
            $module = (new Module)->findById($token);
        }


        $menuSelected = "layout-dashboard";

        if ($layout) {
            switch ($layout) {
                case 'agrupamentos':
                    $menuSelected = "layout-grouping";
                    $this->user->myGrouping();
                    $this->user->sharedMe();
                    break;
                case 'modulo':
                    $menuSelected = "layout-modulo";

                    break;
                case 'meus-dados':
                    $menuSelected = "layout-mydata";
                    $this->user->address = (new Address())->find("user_id = :u", "u={$this->user->id}")->fetch();
                    break;
                case 'gerenciador':
                    $menuSelected = "layout-admin";
                    break;
                case 'logout':
                    Auth::logout();
                    redirect("login");
                    break;
                default:
                    // Busca o grupo default do usuário, os módulos cadastrados e seus dados
                    $this->user->myGroupingDefault();
                    $menuSelected = 'layout-dashboard';
            }
        }


        $head = $this->seo->render(
            CONF_SITE_NAME . " - " . CONF_SITE_TITLE,
            "",
            url(),
            url("/shared/images/social/share.jpg")
        );
        echo $this->view->render('dashboard', [
            "head" => $head,
            "menuSelected" => $menuSelected,
            "module" => $module ?? NULL,
            "user" => $this->user,
        ]);
    }

     /**
     *  Adicionando propriedade a um módulo
     */
    public function OwnerModule(?array $data): void
    {    
         $token = filter_var(isset($data["token"]) ? $data["token"] : NULL, FILTER_UNSAFE_RAW);

         if (empty($token)) {
            $json['message'] = $this->message->warning("Faltam parâmetros na requisição.")->render();
            echo json_encode($json);
            return;
        }

        if (!empty($data['csrf'])) {
            
            $module = (new Module)->findBytoken($token);

            //  Verifica se o módulo existe
            if(empty($module)){
                $json['message'] = $this->message->warning("Módulo não localizado!")->render();
                echo json_encode($json);
                return;
            }

            
            // Verifica se já não está cadastrado no grupo de outro usuário

            $owner = (new GroupingModule)->find("module_id = :mi","mi={$module->id}")->count();

              //  Verifica se o módulo existe
              if(!empty($owner)){
                $json['message'] = $this->message->warning("Este módulo já está cadastrado em outro grupo!")->render();
                echo json_encode($json);
                return;
            }

            
            // Procura qual o grupo defaul do usuário
            $this->user->myGroupingDefault();

            if(empty($this->user->grouping_default->id)){
                $json['message'] = $this->message->warning("Não localizado o grupo atual do usuário.")->render();
                echo json_encode($json);
                return;
            }

            // Efetua o Cadastro Para o usuario atual.
            $owner = (new GroupingModule)->bootstrap($this->user->grouping_default->id,$module->id);


            if(!$owner->save()){
                    $json['message'] = $owner->message()->render();
                    echo json_encode($json);
                    return;
            }


            $json['message'] = $this->message->success("Módulo adicionado com sucesso!")->render();
            $json['reload'] = true;
            echo json_encode($json);
            return;
        }

    }

    /**
     *  TELA DE CONFIGURAÇÃO DO MODULO SELECIONADO
     */
    public function moduleConfig(?array $data): void
    {


        $token = filter_var(isset($data["token"]) ? $data["token"] : NULL, FILTER_UNSAFE_RAW);

        if (!empty($data['csrf'])) {

            $mod = (new Module)->findBytoken($token);
            if ($mod) {

                $title = filter_var(isset($data["title"]) ? $data["title"] : NULL, FILTER_UNSAFE_RAW);
                $subtitle = filter_var(isset($data["subtitle"]) ? $data["subtitle"] : NULL, FILTER_UNSAFE_RAW);
                $setTempMin = filter_var(isset($data["setTempMin"]) ? $data["setTempMin"] : NULL, FILTER_VALIDATE_INT);
                $setTempMax = filter_var(isset($data["setTempMax"]) ? $data["setTempMax"] : NULL, FILTER_VALIDATE_INT);
                $alert = filter_var(isset($data["alert"]) ? $data["alert"] : NULL, FILTER_VALIDATE_BOOLEAN);


                $mod->config = json_encode(array(
                    "title" => $title,
                    "subtitle" => $subtitle,
                    "setTempMin" => $setTempMin,
                    "setTempMax" => $setTempMax,
                    "alert" => $alert
                ));

                // Removendo a descrição para poder salvar os dados
                unset($mod->data()->description);

                if(!$mod->save()){
                    $json['message'] = $mod->message()->render();
                    $json['data'] = $mod->data();
                    echo json_encode($json);
                    return;
                }

                $json['message'] = $this->message->success("Dados alterados!")->flash();
                $json['redirect'] = url("app/dashboard");
                echo json_encode($json);
                return;
            }

            $json['message'] = $this->message->success("Deu tudo certo, mas ainda estamos em desenvolvimento!")->render();
            $json['data'] = $data;
            echo json_encode($json);
            return;
        }

        // Existem o envio do token de do módulo
        if ($token) {
            $module = (new Module)->findBytoken($token);
            $module->configArray = json_decode($module->config);
        }


        $head = $this->seo->render(
            CONF_SITE_NAME . " - " . CONF_SITE_TITLE,
            "",
            url(),
            url("/shared/images/social/share.jpg")
        );
        echo $this->view->render('layout-moduleconfig', [
            "head" => $head,
            "module" => $module ?? NULL
        ]);
    }



    /**
     *  THEMA ATUAL
     */
    public function theme(): void
    {

        $head = $this->seo->render(
            CONF_SITE_NAME . " - " . CONF_SITE_TITLE,
            "",
            url(),
            url("/shared/images/social/share.jpg")
        );
        echo $this->view->render("theme-default", [
            "head" => $head,
        ]);
    }



    /** Rotas de Alteração de usuário*/
    public function user(array $data): void
    {
        $id = filter_var((isset($data["id"]) ? $data["id"] : null), FILTER_VALIDATE_INT);

        // Se existir um ID é porque é uma alteração
        if ($id) {
            // Verifica se o ID enviado é o mesmo ID da sessão.
            if ($this->user->id == $id) {
                $name = filter_var((isset($data["name"]) ? $data["name"] : null), FILTER_UNSAFE_RAW);
                $phone = filter_var((isset($data["phone"]) ? $data["phone"] : null), FILTER_UNSAFE_RAW);

                $phone = only_numbers($phone);

                $this->user->phone = $phone;
                $this->user->name = $name;

                if (!$this->user->save()) {
                    $json['message'] = $this->user->message()->render();
                    echo json_encode($json);
                    return;
                }
                $json["reload"] = true;
            }
        }

        $json["user"] = $this->user->data() ?? null;
        $json["data"] = $data;
        echo json_encode($json);
        return;
    }

    /** Rotas de Alteração de endereço*/
    public function address(array $data): void
    {


        $id = filter_var((isset($data["id"]) ? $data["id"] : null), FILTER_VALIDATE_INT);
        $user_id = filter_var((isset($data["user_id"]) ? $data["user_id"] : null), FILTER_VALIDATE_INT);

        // Se existir um ID é porque é uma alteração
        if ($id && $user_id) {
            // Verifica se o User_ID enviado é o mesmo ID do usuário da sessão.
            if ($this->user->id == $user_id) {
                $street = filter_var((isset($data["street"]) ? $data["street"] : null), FILTER_UNSAFE_RAW);
                $number = filter_var((isset($data["number"]) ? $data["number"] : null), FILTER_UNSAFE_RAW);
                $complement = filter_var((isset($data["complement"]) ? $data["complement"] : null), FILTER_UNSAFE_RAW);
                $zipcode = filter_var((isset($data["zipcode"]) ? $data["zipcode"] : null), FILTER_UNSAFE_RAW);
                $state = filter_var((isset($data["state"]) ? $data["state"] : null), FILTER_UNSAFE_RAW);
                $city = filter_var((isset($data["city"]) ? $data["city"] : null), FILTER_UNSAFE_RAW);

                $address = (new Address())->find("user_id = :u", "u={$this->user->id}")->fetch();

                if ($address) {
                    $address->street = $street;
                    $address->number = $number;
                    $address->complement = $complement;
                    $address->zipcode = only_numbers($zipcode);
                    $address->state = $state;
                    $address->city = $city;

                    if (!$address->save()) {
                        $json['message'] = $address->message()->render();
                        echo json_encode($json);
                        return;
                    }
                    $json["reload"] = true;
                }
            }
        } else if ($id == 0) {

            $street = filter_var((isset($data["street"]) ? $data["street"] : null), FILTER_UNSAFE_RAW);
            $number = filter_var((isset($data["number"]) ? $data["number"] : null), FILTER_UNSAFE_RAW);
            $complement = filter_var((isset($data["complement"]) ? $data["complement"] : null), FILTER_UNSAFE_RAW);
            $zipcode = filter_var((isset($data["zipcode"]) ? $data["zipcode"] : null), FILTER_UNSAFE_RAW);
            $state = filter_var((isset($data["state"]) ? $data["state"] : null), FILTER_UNSAFE_RAW);
            $city = filter_var((isset($data["city"]) ? $data["city"] : null), FILTER_UNSAFE_RAW);

            $address = (new Address())->find("user_id = :u", "u={$this->user->id}")->fetch();

            if ($address) {
                $json['message'] = $address->message->info("Usuario já possui um endereço cadastrado")->render();
                echo json_encode($json);
                return;
            }

            $address = (new Address())->bootstrap(
                $this->user->id,
                only_numbers($zipcode),
                $street,
                $number,
                $state,
                $city
            );

            $address->complement = $complement;


            if (!$address->save()) {
                $json['message'] = $address->message()->render();
                echo json_encode($json);
                return;
            }
            $json["reload"] = true;
        }
        $json["data"] = $data;
        echo json_encode($json);
        return;
    }

    /** Rotas de Alteração de senha*/
    public function password(array $data): void
    {
        $id = filter_var((isset($data["id"]) ? $data["id"] : null), FILTER_VALIDATE_INT);

        // Se existir um ID é porque é uma alteração
        if ($id) {
            // Verifica se o User_ID enviado é o mesmo ID do usuário da sessão.
            if ($this->user->id == $id) {
                $password = filter_var((isset($data["password"]) ? $data["password"] : null), FILTER_UNSAFE_RAW);
                $new_password = filter_var((isset($data["new_password"]) ? $data["new_password"] : null), FILTER_UNSAFE_RAW);
                $repeat_password = filter_var((isset($data["repeat_password"]) ? $data["repeat_password"] : null), FILTER_UNSAFE_RAW);

                if ($password) {

                    // Verifica se a senha atual e igual a senha do usuário da sessão
                    if (strcmp(md5($password), $this->user->password)) {
                        $json["message"] = $this->message->error("A senha atual não confere")->render();
                        echo json_encode($json);
                        return;
                    }



                    // Verifica se a nova senha foi digitada corretamente
                    if (strcmp($new_password, $repeat_password)) {
                        $json["message"] = $this->message->error("A nova senha está incorreta")->render();
                        echo json_encode($json);
                        return;
                    }

                    if (strlen($new_password) < 6) {
                        $json["message"] = $this->message->error("A nova senha deve conter mais de 6 caracteres")->render();
                        echo json_encode($json);
                        return;
                    }

                    // Faz a alteração da senha
                    $this->user->password = md5($new_password);

                    if (!$this->user->save()) {
                        $json['message'] = $this->user->message()->render();
                        echo json_encode($json);
                        return;
                    }

                    $json["message"] = $this->message->success("Sua senha foi alterada com sucesso")->flash();
                    $json["reload"] = true;
                } else {
                    $json["message"] = $this->message->error("A senha atual não pode ser vazio")->render();
                    echo json_encode($json);
                    return;
                }
            }
        }
        $json["data"] = $data;
        echo json_encode($json);
        return;
    }





    /* TESTE DE INTERFACE DE EMAIL*/
    /**
     * @param array $data
     */
    public function email(array $data): void
    {

        $view = new View(__DIR__ . "/../../shared/views/email");

        if (!empty($data['id'])) {
            $stmt = Connect::getInstance()->query("SELECT * FROM mail_queue WHERE recipient_email = '" . $data['user'] . "' AND  id = '" . $data['id'] . "'");
            if ($stmt->rowCount()) {
                foreach ($stmt->fetchAll() as $send) {

                    $subject = $send->subject;

                    echo "<b>Subject : </b>" . $subject . "<br><br>";
                    echo $send->body;
                }
            }
        } else if (!empty($data['user'])) {
            $stmt = Connect::getInstance()->query("SELECT * FROM mail_queue WHERE recipient_email = '" . $data['user'] . "'");
            $user = (new User())->findByEmail($data['user']);

            if ($user) {
                echo "<b>Rotas</b>" . "<br>";
                echo "Pagamento : " . url("/pay/mercadopago/" . base64_encode($user->email_usuario)) . "<br>";
                if (!empty($user->forget)) {
                    echo "FORGET : " . $user->forget . "<br>";
                    echo "Após Pagamento : " . url("afterpay/" . base64_encode($user->email_usuario)) . "<br>";
                    echo "Aguardando Confirmação : " . url("aguardando-confirmacao-de-pagamento/" . base64_encode($user->email_usuario)) . "<br>";
                    echo "Falha no Pagamento : " . url("falha-no-pagamento/" . base64_encode($user->email_usuario)) . "<br>";
                }
                echo "Pagina de acesso ao produto : " . url("/produto/001/" . base64_encode($user->email_usuario)) . "<br>";
                echo "Link de Afiliado : " . url("/oferta?source=aff&ref=" . base64_encode($user->PK_usuario)) . "<br>";
                echo "-----------------------------------------------<br>";
                echo "<form action='" . url("service/sendAfterPay.php") . "' method='GET' enctype='multipart/form-data'>";
                echo "<input type='text' name='email' value='{$data['user']}'>";
                echo "<button>Enviar E-mail Após Pagamento</button>";
                echo "</form>";
                echo "<br><a href='" . url("email") . "'> <- Voltar</a><br>";
                echo "-----------------------------------------------<br>";
            }


            if ($stmt->rowCount()) {
                foreach ($stmt->fetchAll() as $send) {
                    //var_dump($send);        
                    echo "<a href='" . url("email/" . $data['user'] . "/" . $send->id) . "' >[" . $send->id . "] " . $send->subject . " - [" . ((!empty($send->sent_at)) ? date_fmt_br($send->sent_at) : 'null') . "]</a><br>";
                }
            }
        } else {
            $user = Auth::user();
            if (!$user)
                $user = (new User())->findByEmail("leonardo@autofreitas.com.br");

            $module_1 = (new Module())->bootstrap(1, "Central AV-PRELI5");  // Criando um novo Modulo PRELI5
            $module_2 = (new Module())->bootstrap(1, "Central AV-PRELI5");
            $module_3 = (new Module())->bootstrap(1, "Central AV-PRELI5");

            $token = base64_encode($user->email_usuario);
            $message = "";

            $email = filter_input(INPUT_GET, "email", FILTER_SANITIZE_EMAIL);
            if (!empty($email)) {
                redirect("email/{$email}");
            }

            echo "<p>Informe o e-mail do aluno</p><br>";
            echo "<form action='" . url("email") . "' method='GET' enctype='multipart/form-data'>";
            echo "<input type='text' name='email' value=''>";
            echo "<button>Acessar Lista de Email</button>";
            echo "</form>";
            echo "-----------------------------------------------<br>";

            if (false) {
                $subject = "[" . CONF_NAME_PROD_001 . "]! Procedimento para Inicio do Projeto";

                echo "Titulo : " . $subject . "<br><br>";
                $message = $view->render("mail", [
                    "subject" =>  $subject,
                    "headline" => "Parabéns " . $user->nome_usuario . "! Agora você faz parte do nosso Time.",
                    "message" => "<p>Utilize os dados abaixo para começar. </p><p><b>DADOS DE ACESSO</b></p><div class='group' style='border: 1px solid #000; padding:10px 5px;'><p style='font-size:16px;'><b>Usuário:</b>{$user->email_usuario}</p><p style='font-size:16px;color:gray' ><b>Senha:</b>automacaonaveia</p><p><i>Esta senha é crianda automaticamente somente se você é novo, caso já tenha acessa a intereface de controle utilize sua senha de costume.</i></p><br><p><b>SUAS CHAVES DE ACESSO</b></p><p>{$module_1->hash_central}</p><p>{$module_2->hash_central}</p><p>{$module_3->hash_central}</p><p style='color:green;font-weight:bold'><br><i>Clique no link abaixo para acessar o projeto</i></p><p style='margin-top:12px;'><a href='" . url("produto/001/{$token}") . "' >COMEÇAR AGORA</p></a></div><br><p><small><i>Se tiver alguma dificuldade é só entrar em contato com a nossa equipe respondendo este e-mail</i></small></p>",
                ]);
            }

            if (false) {
                $subject = "[ACESSO GRÁTIS]! {$user->nome_usuario} coloque seu arduino on-line em " . CONF_SITE_TEMPO_ARDUINO;

                echo "Titulo : " . $subject . "<br><br>";
                $message = $view->render("mail", [
                    "subject" =>  $subject,
                    "headline" => "Que legal, agora somos do mesmo TIME.",
                    "message" => "<p>Utilize os dados abaixo e já coloque o seu arduino on-line. </p><p><b>DADOS DE ACESSO</b></p><div class='group' style='border: 1px solid #000; padding:10px 5px;'><p style='font-size:16px;'><b>Usuário:</b>{$user->email_usuario}</p><p style='font-size:16px;color:gray' ><b>Senha:</b>automacaonaveia</p><p style='color:green;font-weight:bold'><br><i>Clique no link abaixo para acessar</i></p><p style='margin-top:12px;'><a href='" . url("/acesso-gratis/{$token}") . "' >COMEÇAR AGORA</p></a></div><br><p><small><i>Se tiver alguma dificuldade é só entrar em contato com a nossa equipe respondendo este e-mail</i></small></p>",
                ]);
            }
            if (false) {
                $subject = "[VAGA RESERVADA] {$user->nome_usuario}, sua vaga já está quase garantida.";

                echo "Titulo : " . $subject . "<br><br>";
                $message = $view->render("mail", [
                    "subject" =>  $subject,
                    "headline" => "Obrigado por se cadastrar para entrar no curso Automação na Veia! <p>Isso mostra que você realmente não está de brincadeira e quer aprender a criar projetos de automação profissionais com seu arduino.</p>",
                    "message" => "<p>O próximo passo é efetuar o pagamento, caso ainda não tenha feito!</p><p>Após o pagamento você receberá um e-mail com todos os dados para acesso a mais de 100 vídeo aulas que irão te ensinar passo a passo a criar projetos profissionais. </p><p style='color:green;font-weight:bold'><p>Eu vou deixar novamente o link para página de pagamento, caso tenha acontecido algum problema.</p><p style='padding-top:20px;'><a href='" . url("comprar/automacao-na-veia-001") . "' style='background:green;color:white;padding:15px 20px;border-radius:47px;text-decoration:none;'>RECOMEÇAR PAGAMENTO</p></a></div><br><p><small><i>Ps. Caso já tenha feito o pagamento é só aguardar o e-mail com os dados de acesso e qualquer dúvida pode entrar em contato direto comigo através do WhatsApp 47 99962-2575 ou respondendo este e-mail</i></small></p>",
                ]);
            }
            if (false) {
                $subject = "[PAGAMENTO APROVADO] Curso Automação com Arduino | Automação na Veia.";

                echo "Titulo : " . $subject . "<br><br>";
                $message = $view->render("mail", [
                    "subject" =>  $subject,
                    "headline" => "Olá {$user->nome_usuario}, seu pagamento para curso de automação com Arduino | Automação na Veia está APROVADO!.</p>",
                    "message" => "<p>Agora é só clicar no link abaixo e então em <b>\"FINALIZAR PROCESSO DE COMPRA\"</b></p><div><p style='margin-top:12px;'><a href='" . url("/afterpay/{$token}") . "' >" . url("/afterpay/{$token}") . "</p></a></div><p>Após, você receberá um e-mail com todos os seus dados de acesso ao portal do aluno</p><p>Qualquer dúvida é só entrar em contato</p>",
                ]);
            }

            echo $message;
        }
    }

    /**
     *  Captura dados
     */
    public function push(?array $data): void
    {
        $gouping_id = filter_var($data["grouping_id"], FILTER_VALIDATE_INT);

        // Busca o grupo default do usuário, os módulos cadastrados e seus dados
        $modules = (new GroupingModule)->findDataModule($gouping_id);

        $json["data"] = json_decode($modules[0]->data()->publish->json);
        echo json_encode($json);
        return;
    }

    /**
     *  Atualiza Dados
     */
    public function publish(?array $data): void
    {
        $request = json_decode(file_get_contents('php://input'), true);

        if (isset($request["token"])) {
            $module = (new Module)->findBytoken($request["token"]);

            if ($module) {

                $publish = $module->getPublish();
                if ($publish) {

                    $publish->json = json_encode($request);
                    $publish->save();
                } else {
                    
                    $publish = (new Publish)->bootstrap($module->id, json_encode($request));
                    $publish->save();
                }

                $json["message"] = "Sucesso";
                $json["token"] = $request["token"];
            } else {
                $json["message"] = "Token Inexistente";
            }
        } else {
            $json["message"] = "Parâmetros inválidos";
        }

        echo json_encode($json);
        return;
    }


    /**
     *  Iniciando Checkou para comprar
     */
    public function session(): void
    {
        $session = new Session();
        var_dump($session->all(), $_COOKIE);
    }


    /**
     * SITE NAV ERROR
     * @param array $data
     */
    public function error(array $data): void
    {
        $error = new \stdClass();

        switch ($data['errcode']) {
            case "problemas":
                $error->code = "OPS";
                $error->title = "Estamos enfrentando problemas!";
                $error->message = "Parece que nosso serviço não está diponível no momento. Já estamos vendo isso mas caso precise, envie um e-mail :)";
                $error->linkTitle = "ENVIAR E-MAIL";
                $error->link = "mailto:" . CONF_MAIL_SUPPORT;
                break;

            case "manutencao":
                $error->code = "OPS";
                $error->title = "Desculpe. Estamos em manutenção!";
                $error->message = "Voltamos logo! Por hora estamos trabalhando para melhorar nosso conteúdo para você controlar melhor as suas contas :P";
                $error->linkTitle = null;
                $error->link = null;
                break;

            default:
                $error->code = $data['errcode'];
                $error->title = "Ooops. Conteúdo indispinível :/";
                $error->message = "Sentimos muito, mas o conteúdo que você tentou acessar não existe, está indisponível no momento ou foi removido :/";
                $error->linkTitle = "Continue navegando!";
                $error->link = url_back();
                break;
        }


        $head = $this->seo->render(
            "{$error->code} | {$error->title}",
            $error->message,
            url("/ops/{$error->code}"),
            url("/shared/images/social/share.jpg"),
            false
        );

        echo $this->view->render("error", [
            "head" => $head,
            "error" => $error,
        ]);
    }
}
