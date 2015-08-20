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

/**
 * @Restful
 * @uri /usersearch
 */
class UserSearchCTL extends BaseCTL {
    private $table = "account";


    /**
     * @GET
     * @uri /makefullname
     */
    public function makeFullname(){
        $db = MedooFactory::getInstance();
        $users = $db->select("account", "*");

    }

    /**
     * @GET
     */
    public function gets(){
        $params = $this->reqInfo->params();
        $db = MedooFactory::getInstance();
        $pdo = $db->pdo;

        $sql = "SELECT * FROM account";
        $sql .= " WHERE (CONCAT(account.firstname, ' ', account.lastname) LIKE ?";
        $sql .= " OR account.username LIKE ?)";
        $sql .= " AND account.account_status < 3";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            '%'.$params['keyword'].'%',
            '%'.$params['keyword'].'%'
            ]);

        $data = $stmt->fetchAll();
        foreach($data as $key=> $item){
            $picPath = "public/image_users/".$item["username"].".png";
            if(file_exists($picPath)){
                $item["picture"] = URL::absolute("/").$picPath;
            }
            else {
                $item["picture"] = URL::absolute("/")."public/images/user.jpg";
            }
            $data[$key] = $item;
        }

        return $data;
    }
}