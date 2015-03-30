<?php
/**
 * Created by PhpStorm.
 * User: NUIZ
 * Date: 30/3/2558
 * Time: 15:20
 */

namespace Main\CTL;
use Main\DB\Medoo\MedooFactory;
use Main\Repository\BookPlaceRepo;

/**
 * @Restful
 * @uri /book_place
 */
class BookPlaceCTL extends BaseCTL {
    private $repo;
    /**
     * @GET
     */
    public function gets(){
        return $this->getRepo()->gets();
    }

    // dependency injection before action
    public function beforeAction(){
        $this->setRepo(new BookPlaceRepo());
        $this->getRepo()->setDB(MedooFactory::getInstance());
    }

    //internal function
    /**
     * @return BookPlaceRepo
     */
    public function getRepo()
    {
        return $this->repo;
    }

    /**
     * @param BookPlaceRepo $repo
     */
    public function setRepo($repo)
    {
        $this->repo = $repo;
    }
}