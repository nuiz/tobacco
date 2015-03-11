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
     * @POST
     */
    public function add(){
        $params = $this->getReqInfo()->params();
        $insert = ArrayHelper::filterKey(['faq_question', 'faq_answer'], $params);

        return $this->getRepo()->add($insert);
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