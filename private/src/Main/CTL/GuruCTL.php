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
use Main\Repository\GuruRepo;
use Main\View\JsonView;

/**
 * @Restful
 * @uri /guru
 */
class GuruCTL extends BaseCTL {
    /**
     * @var GuruRepo $repo;
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
     * @uri /category
     */
    public function getCats(){
        $params = $this->getReqInfo()->params();
        return new JsonView($this->getRepo()->getCats($params));
    }

    /**
     * @GET
     * @uri /[i:id]
     */
    public function get(){
        $id = $this->getReqInfo()->urlParam("id");

        return new JsonView($this->getRepo()->_get($id));
    }

    /**
     * @POST
     */
    public function add(){
        $params = $this->getReqInfo()->params();

        return $this->getRepo()->add($params);
    }

    /**
     * @PUT
     * @uri /[i:id]
     */
    public function edit(){
        $params = $this->getReqInfo()->params();

        $id = $this->getReqInfo()->urlParam("id");

        return $this->getRepo()->edit($id, $params);
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
        $this->setRepo(new GuruRepo());
        $this->getRepo()->setDB(MedooFactory::getInstance());
    }

    //internal function
    /**
     * @return GuruRepo
     */
    public function getRepo()
    {
        return $this->repo;
    }

    /**
     * @param GuruRepo $repo
     */
    public function setRepo($repo)
    {
        $this->repo = $repo;
    }
}