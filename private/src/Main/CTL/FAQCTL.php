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
use Main\View\JsonView;

/**
 * @Restful
 * @uri /faq
 */
class FAQCTL extends BaseCTL {
    /**
     * @var FAQRepo $repo;
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
        $id = $this->getReqInfo()->urlParam("id");
        return new JsonView($this->getRepo()->_get($id));
    }

    /**
     * @POST
     */
    public function add(){
        $params = $this->getReqInfo()->params();
        $insert = ArrayHelper::filterKey(['faq_question', 'faq_answer'], $params);

        return $this->getRepo()->add($insert);
    }

    /**
     * @PUT
     * @uri /[i:id]
     */
    public function edit(){
        $params = $this->getReqInfo()->params();
        $insert = ArrayHelper::filterKey(['faq_question', 'faq_answer'], $params);

        $id = $this->getReqInfo()->urlParam("id");

        return $this->getRepo()->edit($id, $insert);
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
        $this->setRepo(new FAQRepo());
        $this->getRepo()->setDB(MedooFactory::getInstance());
    }

    //internal function
    /**
     * @return FAQRepo
     */
    public function getRepo()
    {
        return $this->repo;
    }

    /**
     * @param FAQRepo $repo
     */
    public function setRepo($repo)
    {
        $this->repo = $repo;
    }
}