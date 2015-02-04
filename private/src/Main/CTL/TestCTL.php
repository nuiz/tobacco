<?php
/**
 * Created by PhpStorm.
 * User: NUIZ
 * Date: 14/1/2558
 * Time: 9:36
 */

namespace Main\CTL;
use Main\DB\Medoo\MedooFactory;
use Main\Helper\LangHelper;
use Main\Permission\AccountPermission;
use Main\Service\AccountService;
use Main\View\JsonView;


/**
 * @Restful
 * @uri /test
 */
class TestCTL extends BaseCTL {
    /**
     * @GET
     */
    public function test(){
        $authAccount = $this->reqInfo->getAuthAccount();
        $permissionCats = AccountPermission::getCatPermission($authAccount["account_id"]);
        return $permissionCats;
    }
}