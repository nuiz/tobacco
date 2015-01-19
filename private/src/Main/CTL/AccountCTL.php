<?php
/**
 * Created by PhpStorm.
 * User: NUIZ
 * Date: 19/1/2558
 * Time: 13:31
 */

namespace Main\CTL;
use Main\Helper\ResponseHelper;
use Valitron\Validator;
use Main\DB\Medoo\MedooFactory;
use Main\Service\AccountService\AccountServiceException;
use Main\Service\AccountService;
use Main\View\JsonView;

/**
 * @Restful
 * @uri /account
 */
class AccountCTL extends BaseCTL {
    /**
     * @POST
     */
    public function add(){
        MedooFactory::getInstance()->pdo->beginTransaction();
        $params = $this->reqInfo->params();
        try {
            $id = AccountService::getInstance()->add($params);
            MedooFactory::getInstance()->pdo->commit();
            $item = AccountService::getInstance()->get($id);
            return new JsonView($item);
        }
        catch (AccountServiceException $e){
            MedooFactory::getInstance()->pdo->rollBack();
            error_log($e);
            return new JsonView(ResponseHelper::error('Error'));
        }
    }
}