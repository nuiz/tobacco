<?php
/**
 * Created by PhpStorm.
 * User: NUIZ
 * Date: 2/2/2558
 * Time: 16:52
 */
namespace Main\Permission;

use Main\View\JsonView;

class AccountPermission {
//    /**
//     * @var array[] $accountCategoryPermissions;
//     */
//    private static $accountCategoryPermissions = array();
//    public static function getCatPermission($account_id){
//        if(!isset(self::$accountCategoryPermissions[$account_id])){
//            $db = \Main\DB\Medoo\MedooFactory::getInstance();
//            $accP = $db->get("account_permission", "*", array("account_id"=> $account_id));
//            $cats = $db->select("cluster_category", "*", array("cluster_id"=> $accP["cluster_id"]));
//            $cats2 = array();
//            foreach($cats as $key=> $value){
//                $cats2[] = $value['category_id'];
//            }
//            self::$accountCategoryPermissions[$account_id] = $cats2;
//        }
//        return self::$accountCategoryPermissions[$account_id];
//    }

    const
        ID_SUPER_ADMIN = 1,
        ID_CLUSTER_IT = 2,
        ID_CLUSTER = 3,
        ID_WRITER = 4;

    public static function checkPermission($level_id, array $permission){
        return in_array($level_id, $permission);
    }

    public static function requirePermission($user, array $permission){
        if(!self::checkPermission($user["level_id"], $permission)){
            $jsonView = new JsonView([
                "error"=> [
                    "code"=> 403,
                    "message"=> "you don't have permission"
                ]
            ]);

            $jsonView->render();
            exit();
        }
    }
}