<?php


namespace Source\Models;


use Source\Core\Model;
use Source\Models\Publish as ModelsPublish;

/**
 * Class Address
 * @package Source\Models
 */
class Publish extends Model
{

    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct("publish", ["id"], ["module_id", "json"]);
    }

    /**
     * Undocumented function
     *
     * @param integer $module_id
     * @param string $json
     * @return Publish|null
     */
    public function bootstrap(
        int $module_id,
        string $json
    ): ?Publish {
        $this->module_id = $module_id;
        $this->json = $json;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param [type] $module_id
     * @return Publish|null
     */
    public function lastRegister($module_id): ?Publish
    {
        $find = $this->find("module_id = :i", "i={$module_id}")
            ->order("id DESC");
        return $find->fetch();
    }
}
