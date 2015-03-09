<?php
/**
 * Created by PhpStorm.
 * User: NUIZ
 * Date: 7/3/2558
 * Time: 16:53
 */

namespace Main\CTL;
use Main\Repository\ConfigSystemRepository;
use Main\DB\Medoo\MedooFactory;

/**
 * @Restful
 * @uri /config
 */
class ConfigCTL extends BaseCTL {
    /**
     * @GET
     */
    public function get(){
        $params = $this->getReqInfo()->params();
        if(isset($params["config_name"]) && !empty($params["config_name"])){
            return $this->getRepo()->get($params["config_name"]);
        }
        else {
            return $this->getRepo()->getAll();
        }
    }

    /**
     * @PUT
     */
    public function update(){
        $params = $this->getReqInfo()->params();
        if(count($params) > 0){
            return $this->getRepo()->update($params);
        }
        else {
            return [];
        }
    }

    // internal function
    private $repo;

    /**
     * @return ConfigSystemRepository
     */
    public function getRepo()
    {
        return $this->repo;
    }

    /**
     * @param ConfigSystemRepository $repo
     */
    public function setRepo(ConfigSystemRepository $repo)
    {
        $this->repo = $repo;
    }

    public function beforeAction(){
        $this->setRepo(new ConfigSystemRepository());
        $this->getRepo()->setDB(MedooFactory::getInstance());
    }
}