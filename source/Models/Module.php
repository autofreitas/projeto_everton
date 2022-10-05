<?php


namespace Source\Models;


use Source\Core\Model;
use Source\Models\Publish;

/**
 * Class Address
 * @package Source\Models
 */
class Module extends Model
{

    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct("module", ["id"], ["type_module_id", "token", "config", "status"]);
    }

    public function bootstrap(
        int $type_module_id,
        string $token,
        string $config,
        string $status = 'actived'
    ): ?Module {
        $this->type_module_id = $type_module_id;
        $this->token = $token;
        $this->config = $config;
        $this->status = $status;

        return $this;
    }

    public function findBytoken($token): ?Module
    {
        $find = (new Module)->find("token = :t","t={$token}","*,(SELECT description from type_module Where module.id = type_module_id) as description");
        return $find->fetch();
    }

    /**
     * Undocumented function
     *
     * @return array|null
     */
    public function getPublish(): ?Publish
    {
        $find = (new Publish)->lastRegister($this->id);
        return $find;
    }
}
