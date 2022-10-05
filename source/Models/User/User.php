<?php


namespace Source\Models\User;



use Source\Core\Model;
use Source\Core\Session;
use Source\Models\Address;
use Source\Models\AffiliateCredits;
use Source\Models\Company\Company;
use Source\Models\Company\CompanyShared;
use Source\Models\Grouping;
use Source\Models\GroupingModule;
use Source\Models\Module;
use Source\Models\Pay\AppPlan;
use Source\Models\Pay\AppSubscription;
use Source\Models\Shared;
use Source\Models\Voucher;

/**
 * Class User
 * @package Source\Models
 */
class User extends Model
{

    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct("user", ["id"], ["name", "email", "password", "status"]);
    }


    public function bootstrap(
        string $name,
        string $email,
        string $password,
        string $status = 'actived'

    ): ?User {
        $this->name = $name;
        $this->email = mb_strtolower($email);
        $this->password = $password;
        $this->status = $status;
        return $this;
    }


    /**
     * @param $email
     * @param string $columns
     * @return user|null
     */
    public function findByEmail(string $email, string $columns = "*"): ?User
    {
        $email = mb_strtolower($email);
        $find = $this->find("email=:email", "email={$email}", $columns);
        return $find->fetch();
    }

    /**
     *  Procura todos os dados dos grupos do usuário
     */
    public function myGrouping(): User
    {
        // Procurar todos os grupos do usuário

        $group = (new Grouping)->findMyGroup($this->id);
        if ($group) {
            foreach ($group as $key => $g) {

                // Procurar todos os módulos registrados para cada grupo
                $group[$key]->module = (new GroupingModule)->countModules($g->id);

                // Procurar todos os compartilhamentos de cada grupo
                $group[$key]->shared = (new Shared)->sharedGroup($g->id);
            }

            $this->grouping = $group;
        }

        return $this;
    }

    /**
     *  Procura qual o grupo default do usuário
     */
    public function myGroupingDefault(): User
    {
        // Procurar todos os grupos do usuário

        $group = (new Grouping)->findMyGroupDefault($this->id);

        if ($group) {
            $modules = (new GroupingModule)->findDataModule($group->id);
            $group->modules = $modules;
        }
        $this->grouping_default = $group;

        return $this;
    }

    /**
     *  Procura Grupos compartilhados com o usuario 
     */
    public function sharedMe(): User
    {
        // Procurar os grupos compartilhados comigo
        $this->sharedme = (new Shared)->sharedUser($this->id);
        return $this;
    }


    /**
     * @return bool
     */
    public function save(): bool
    {
        /*Verifica os campos obrigatórios*/

        if (!$this->required()) {
            $this->message->warning("Preenchimento Incompleto do Formulário");
            return false;
        }

        /* Verifica o formato de e-mail válido*/
        if (!is_email($this->email)) {
            $this->message->warning("O email informado não tem um formato válido");
            return false;
        }


        if (!is_passwd($this->password)) {
            $min = CONF_PASSWD_MIN_LEN;
            $max = CONF_PASSWD_MAX_LEN;
            $this->message->warning("A senha deve ter entre {$min} e {$max} caracteres");
            return false;
        }


        /** User Update */
        if (!empty($this->id)) {
            $userId = $this->id;

            if ($user = $this->find("email= :e AND id != :i", "e={$this->email}&i={$userId}", "id")->fetch()) {
                $this->message->warning("O e-mail informado já está cadastrado");
                return false;
            }

            $this->update($this->safe(), "id=:id", "id={$userId}");
            if ($this->fail()) {
                $this->message->error("Erro ao atualizar, verifique os dados")->flash();
                return false;
            }
        }

        /** User Create */
        if (empty($this->id)) {
            if ($this->findByEmail($this->email)) {
                $this->message->warning("O e-mail informado já está cadastrado");
                return false;
            }

            $userId = $this->create($this->safe());
            if ($this->fail()) {
                $this->message->error("Erro ao cadastrar, verifique os dados");
                return false;
            }
        }

        $this->data = ($this->find("id=:id", "id={$userId}")->fetch())->data();
        return true;
    }
}
