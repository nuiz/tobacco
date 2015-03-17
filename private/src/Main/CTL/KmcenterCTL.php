<?php
/**
 * Created by PhpStorm.
 * User: NUIZ
 * Date: 11/3/2558
 * Time: 11:52
 */

namespace Main\CTL;
use Main\DB\Medoo\MedooFactory;
use Main\Helper\ArrayHelper;
use Main\Repository\FAQRepo;
use Main\Repository\KmcenterRepo;
use Main\View\JsonView;

/**
 * @Restful
 * @uri /kmcenter
 */
class KmcenterCTL extends BaseCTL {
    /**
     * @var KmcenterRepo $repo;
     */
    protected $repo;

    /**
     * @GET
     */
    public function gets(){
        $params = $this->getReqInfo()->params();
        return new JsonView($this->getRepo()->gets($params));
    }

    /**
     * @GET
     * @uri /[i:id]
     */
    public function get(){
        return new JsonView($this->getRepo()->_get($this->getReqInfo()->urlParam("id")));
    }

    /**
     * @POST
     */
    public function add(){
        $params = $this->getReqInfo()->params();
        $insert = ArrayHelper::filterKey(['kmcenter_name', 'kmcenter_description'], $params);
        $files = $this->getReqInfo()->files();
        return $this->getRepo()->add($insert, $files);
    }

    /**
     * @PUT
     * @uri /[i:id]
     */
    public function edit(){
        $params = $this->getReqInfo()->params();
        $insert = ArrayHelper::filterKey(['kmcenter_name', 'kmcenter_description'], $params);
        $files = $this->getReqInfo()->files();

        $id = $this->getReqInfo()->urlParam("id");

        return $this->getRepo()->edit($id, $insert, $files);
    }

    /**
     * @DELETE
     * @uri /[i:id]
     */
    public function delete(){
        $id = $this->getReqInfo()->urlParam("id");
        return $this->getRepo()->delete($id);
    }

    // dependency injection before action
    public function beforeAction(){
        $this->setRepo(new KmcenterRepo());
        $this->getRepo()->setDB(MedooFactory::getInstance());
    }

    //internal function
    /**
     * @return KmcenterRepo
     */
    public function getRepo()
    {
        return $this->repo;
    }

    /**
     * @param KmcenterRepo $repo
     */
    public function setRepo($repo)
    {
        $this->repo = $repo;
    }
}