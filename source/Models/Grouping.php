<?php


namespace Source\Models;


use Source\Core\Model;

/**
 * Class Address
 * @package Source\Models
 */
class Grouping extends Model
{

    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct("grouping", ["id"], ["user_id", "description","status"]);
    }

    public function bootstrap(
        int $user_id,
        string $description,    
        string $status = 'default'     
    ): ?Grouping {
        $this->user_id = $user_id;
        $this->description = $description;
        $this->status = $status;        

        return $this;
    }

    public function findMyGroup($user_id): ?array
    {
        
        // Procura todos os grupos do usuário
        $find = $this->find("user_id = :u", "u={$user_id}")->order("description ASC");
        return $find->fetch(true);

    }

    public function findMyGroupDefault($user_id): ?Grouping
    {
        
        // Procura o grupo registrado para o usuário marcado como default
        $find = $this->find("user_id = :u AND status = 'default'", "u={$user_id}")->fetch();
        return $find;

    }

}
