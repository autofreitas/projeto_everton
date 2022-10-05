<?php


namespace Source\Models;


use Source\Core\Model;

/**
 * Class User
 * @package Source\Models
 */
class Plans extends Model
{

    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct("tb_plano", ["PK_plano"], ["FK_usuario", "FK_tipo_plano", "data_entrada_plano","data_expira_plano"]);
    }


    public function bootstrap(
        int $FK_usuario,
        int $FK_tipo_plano,
        string $data_entrada_plano,
        string $data_expira_plano        
    ): ?Plans {
        $this->FK_usuario = $FK_usuario;
        $this->FK_tipo_plano = $FK_tipo_plano;
        $this->data_entrada_plano = $data_entrada_plano;
        $this->data_expira_plano = $data_expira_plano;        

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


        /** User Update */
        if (!empty($this->PK_plano)) {
            $planId = $this->PK_plano;

            $this->update($this->safe(), "PK_plano=:id", "id={$planId}");
            if ($this->fail()) {
                $this->message->error("Erro ao atualizar, verifique os dados")->flash();
                return false;
            }
        }

        /** User Create */
        if (empty($this->PK_plano)) {

            if ($this->find("FK_usuario = :id", "id{$this->FK_usuario}")->fetch()) {
                $this->message->warning("Usuário já tem um Plano Cadastrado");
                return false;
            }

            $planId = $this->create($this->safe());
            if ($this->fail()) {
                $this->message->error("Erro ao cadastrar, verifique os dados");
                return false;
            }
        }

        $this->data = ($this->find("PK_plano=:id", "id={$planId}")->fetch())->data();
        return true;
    }
}
