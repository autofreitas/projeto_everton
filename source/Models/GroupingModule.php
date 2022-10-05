<?php


namespace Source\Models;


use Source\Core\Model;

/**
 * Class Address
 * @package Source\Models
 */
class GroupingModule extends Model
{

    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct("grouping_x_module", [""], ["grouping_id", "module_id"]);
    }

    public function bootstrap(
        int $grouping_id,
        int $module_id

    ): ?GroupingModule {
        $this->grouping_id = $grouping_id;
        $this->module_id = $module_id;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @param integer $grouping_id
     * @return integer|null
     */
    public function countModules(int $grouping_id): ?int
    {
        $find = $this->find("grouping_id = :i", "i={$grouping_id}");
        return $find->count();
    }

    
    /**
     * Undocumented function
     *
     * @param integer $grouping_id
     * @return array|null
     */
    public function findDataModule(int $grouping_id): ?array
    {
        $find = $this->find("grouping_id = :i", "i={$grouping_id}", "mo.id,type_module_id,t.description,token,config,mo.status")
            ->innerJoin("module as mo", "mo.id = module_id")
            ->innerJoin("type_module as t", "t.id = mo.type_module_id")
            ->fetchJoin(true);

        if ($find) {
            foreach ($find as $key => $f) {
                $publish = (new Publish)->lastRegister($f->id);
              
                 $find[$key]->publish = $publish;
   
            }
        }
        return $find;
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

       
        $owner = $this->find("module_id = :mi","mi={$this->module_id}")->count();

         //  Verifica se o módulo existe
         if(!empty($owner)){
            $this->message->warning("Este módulo já está cadastrado em outro grupo!");
            return false;
        }

        $this->create($this->safe());
        if ($this->fail()) {
            $this->message->error("Erro ao cadastrar, verifique os dados");
            return false;
        }
    
       // $this->data = ($this->find("grouping_id = :gid AND module_id=:mid", "gid={$this->grouping_id}&mid={$this->module_id}")->fetch())->data();
        return true;
    }

}
