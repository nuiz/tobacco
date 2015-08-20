<?php

namespace Main\CTL;
use Main\DB\Medoo\MedooFactory;
use Main\Helper\ArrayHelper;
use Main\Helper\ResponseHelper;
use Main\Helper\URL;
use Main\View\JsonView;

/**
 * @Restful
 * @uri /nfc
 */
class NfcCTL extends BaseCTL {
	/**
     * @GET
     */
    public function gets(){
        $params = $this->reqInfo->params();
        $db = MedooFactory::getInstance();
        try {
            $data = $db->select("account_nfc", ["[>]account"=> "account_id", "[>]client"=> ["register_client_id"=> "client_id"]], "*");
            $res = [
                "length"=> count($data),
                "data"=> $data
            ];
            return new JsonView($res);
        }
        catch (\Exception $e){
            Log($e);
            return new JsonView(ResponseHelper::error('Error'));
        }
    }

    /**
     * @DELETE
     * @uri /[i:id]
     */
    public function delete(){
        $id = $this->reqInfo->urlParam("id");
        $db = MedooFactory::getInstance();
        try {
            $data = $db->delete("account_nfc", ["account_id"=> $id]);
            return new JsonView([
        		"success"=> true
        	]);
        }
        catch (\Exception $e){
            Log($e);
            return new JsonView(ResponseHelper::error('Error'));
        }
    }
}