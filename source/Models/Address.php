<?php


namespace Source\Models;


use Source\Core\Model;

/**
 * Class Address
 * @package Source\Models
 */
class Address extends Model
{

    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct("address", ["id"], ["user_id", "zipcode", "street","number","state","city","status"]);
    }


    public function bootstrap(
        int $user_id,
        string $zipcode,
        string $street,
        string $number,           
        string $state,      
        string $city,      
        string $status = 'default'     
    ): ?Address {
        $this->user_id = $user_id;
        $this->zipcode = $zipcode;
        $this->street = $street;
        $this->number = $number;              
        $this->state = $state;        
        $this->city = $city;        
        $this->status = $status;        

        return $this;
    }

}
