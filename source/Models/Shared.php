<?php


namespace Source\Models;


use Source\Core\Model;

/**
 * Class Address
 * @package Source\Models
 */
class Shared extends Model
{

    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct("shared_grouping", [""], ["user_id", "grouping_id"]);
    }


    public function bootstrap(
        int $user_id,
        int $grouping_id
    ): ?Shared {
        $this->user_id = $user_id;
        $this->grouping_id = $grouping_id;

        return $this;
    }

    public function sharedGroup(int $grouping_id): ?array
    {
        $find = $this->find("grouping_id = :gi","gi={$grouping_id}", "m.user_id,grouping_id,name,email,description")
        ->innerJoin("user as u","m.user_id = u.id")
        ->innerJoin("grouping as g","m.grouping_id = g.id");


        //var_dump($find);
        
        return $find->fetchJoin(true);
    }

    public function sharedUser(int $user_id): ?array
    {       

        $find = $this->find("m.user_id = :ui","ui={$user_id}", "m.user_id,grouping_id,name,email,description")
        ->innerJoin("user as u","m.user_id = u.id")
        ->innerJoin("grouping as g","m.grouping_id = g.id");
        
        return $find->fetchJoin(true);
    }
}
