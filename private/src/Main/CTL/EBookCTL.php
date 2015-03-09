<?php
/**
 * Created by PhpStorm.
 * User: NUIZ
 * Date: 11/1/2558
 * Time: 22:33
 */

namespace Main\CTL;
use Main\Helper\ArrayHelper;
use Main\Permission\AccountPermission;
use Main\Repository\EBookRepo;
use Main\View\JsonView;
use Valitron\Validator;
use Main\Helper\ResponseHelper;
use Main\DB\Medoo\MedooFactory;
use Main\Helper\URL;
use Main\Helper\ImageHelper;

/**
 * @Restful
 * @uri /ebook
 */
class EBookCTL extends BaseCTL {
    /**
     * @var EBookRepo $repo;
     */
    protected $basePatth = "private/ebook", $table = "ebook", $repo;

    /**
     * @POST
     */
    public function add(){
        $params = $this->reqInfo->params();
        $params = ArrayHelper::filterKey(["ebook_name", "category_id"], $params);
        if(!$this->isHasPermission($params["category_id"])){
            return array(
                "error"=> array(
                    "code"=> 1,
                    "message"=> "You don't have permission for category_id ".$params["category_id"]
                )
            );
        }
    }

    /**
     * @GET
     */
    public function gets(){
        $params = $this->reqInfo->params();
        if(isset($params["book_type_id"])){
            $items = $this->getRepo()->getByTypeId($params["book_type_id"]);
        }
        else {
            $items = $this->getRepo()->gets($params);
        }
        foreach($items['data'] as $key=> $item){
            $this->build($items['data'][$key]);
        }
        return $items;
    }


    // internal function

    public function beforeAction(){
        $this->setRepo(new EBookRepo());
        $this->getRepo()->setDB(MedooFactory::getInstance());
    }

    public function isHasPermission($category_id){
        $auth_account = $this->reqInfo->getAuthAccount();
        $catPermissions = AccountPermission::getCatPermission($auth_account["account_id"]);
        return in_array($category_id, $catPermissions);
    }

    public function build(&$item){
        $item["book_url"] = URL::absolute("/public/book/".$item["book_path"]);
        $item["book_cover_url"] = URL::absolute("/public/book_cover/".$item["book_cover_path"]);
    }

    /**
     * @return EBookRepo
     */
    public function getRepo()
    {
        return $this->repo;
    }

    /**
     * @param EBookRepo $repo
     */
    public function setRepo($repo)
    {
        $this->repo = $repo;
    }
}