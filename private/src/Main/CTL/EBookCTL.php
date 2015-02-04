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
    protected $basePatth = "private/ebook", $table = "ebook";

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

    public function isHasPermission($category_id){
        $auth_account = $this->reqInfo->getAuthAccount();
        $catPermissions = AccountPermission::getCatPermission($auth_account["account_id"]);
        return in_array($category_id, $catPermissions);
    }
}