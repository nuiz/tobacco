<?php
/**
 * Created by PhpStorm.
 * User: NUIZ
 * Date: 11/1/2558
 * Time: 22:33
 */

namespace Main\CTL;
use Main\Helper\ArrayHelper;
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
    protected $basePatth = "private/ebook", $table = "ebooks";

    /**
     * @POST
     */
    public function add(){
        $params = $this->reqInfo->params();

        $v = new Validator($params);
        $v->rule('required', ['title', 'cover_id']);

        if(!$v->validate()){
            return ResponseHelper::validateError($v->errors());
        }
        if(!isset($_FILES['ebook'])){
            return ResponseHelper::validateError(['ebook'=> 'ebook is required']);
        }

        $ext = explode('.', $_FILES['ebook']['name']);
        $ext = array_pop($ext);

        if(strtolower($ext) != 'pdf'){
            return ResponseHelper::error('PDF only.');
        }
        $fileName = uniqid("ebook_").'.'.$ext;
        $des = $this->basePatth.'/'.$fileName;
        move_uploaded_file($_FILES['ebook']['tmp_name'], $des);

        $cover = ImageHelper::makeResponse($params['cover_id']);
        if(is_null($cover)){
            return ResponseHelper::error(['Not found cover_id.']);
        }

        $params = ArrayHelper::filterKey(['title', 'cover_id'], $params);

        $now = time();
        $insertParams = array_merge([
            'created_at'=> $now,
            'updated_at'=> $now,
            'ebook_path'=> $fileName
        ], $params);

        $masterDB = MedooFactory::getInstance();
        $id = $masterDB->insert($this->table, $insertParams);
        if($id == 0){
            var_dump($masterDB->error());
            exit();
        }

        $item = $this->_get($id);
        $v = new JsonView($item);
        return $v;
    }

    /**
     * @POST
     * @uri /edit/[:id]
     */
    public function edit(){
        $id = $this->reqInfo->urlParam('id');
        $params = $this->reqInfo->params();

        $params = ArrayHelper::filterKey(['title', 'cover_id'], $params);

        if(isset($params['cover_id'])){
            $cover = ImageHelper::makeResponse($params['cover_id']);
            if(is_null($cover)){
                return ResponseHelper::error(['Not found cover_id.']);
            }
        }

        $now = time();
        $insertParams = array_merge([
            'created_at'=> $now,
            'updated_at'=> $now
        ], $params);

        if(isset($_FILES['ebook'])){
            $ext = explode('.', $_FILES['ebook']['name']);
            $ext = array_pop($ext);

            if(strtolower($ext) != 'pdf'){
                return ResponseHelper::error('PDF only.');
            }
            $fileName = uniqid("ebook_").'.'.$ext;
            $des = $this->basePatth.'/'.$fileName;
            move_uploaded_file($_FILES['ebook']['tmp_name'], $des);
            $insertParams['ebook_path'] = $fileName;
        }

        $masterDB = MedooFactory::getInstance();
        $masterDB->update($this->table, $insertParams, ['id'=> $id]);

        $item = $this->_get($id);
        $v = new JsonView($item);
        return $v;
    }

    /**
     * @GET
     * @uri /file/[:id]
     */
    public function file(){
        $id = $this->reqInfo->urlParam('id');
        $item = $this->_get($id);
        if(is_null($item)){
            http_response_code(404);
            exit();
        }

        header('Content-type: application/pdf');
        $des = $this->basePatth.'/'.$item['ebook_path'];
        echo file_get_contents($des);
    }

    /**
     * @GET
     * @uri /[:id]
     */
    public function get(){
        $id = $this->reqInfo->urlParam('id');

        $item = $this->_get($id);
        $v = new JsonView($item);
        return $v;
    }

    /**
     * @DELETE
     * @uri /[:id]
     */
    public function delete(){
        $id = $this->reqInfo->urlParam('id');

        $masterDB = MedooFactory::getInstance();
        $result = $masterDB->delete($this->table, ['id'=> $id]);

        return (bool)$result;
    }

    /**
     * @GET
     */
    public function _gets(){
        $options = $this->reqInfo->params();
        $default = array(
            "page"=> 1,
            "limit"=> 100
        );
        $options = array_merge($default, $options);
        $skip = ($options['page']-1)*$options['limit'];

        $masterDB = MedooFactory::getInstance();
        $data = $masterDB->select($this->table, '*', [
            'LIMIT'=> [$skip, $options['limit']],
            'ORDER'=> 'created_at DESC'
        ]);

        $total = $masterDB->count($this->table);
        foreach($data as $key=> $value){
            $value['ebook_url'] = URL::absolute('/ebook/file/'.$value['id']);
            $value['cover'] = ImageHelper::makeResponse($value['cover_id']);
            $data[$key] = $value;
        }

        $res = [
            'length'=> count($data),
            'total'=> $total,
            'data'=> $data,
            'paging'=> [
                'page'=> (int)$options['page'],
                'limit'=> (int)$options['limit']
            ]
        ];
        return $res;
    }

    public function _get($id){
        // get ebook data
        $masterDB = MedooFactory::getInstance();
        $result = $masterDB->select($this->table, '*', ['id'=> $id, "LIMIT"=> 1]);
        if(isset($result[0])){
            $result[0]['ebook_url'] = URL::absolute('/ebook/file/'.$id);
            $result[0]['cover'] = ImageHelper::makeResponse($result[0]['cover_id']);
            return $result[0];
        }
        else {
            return null;
        }
    }
}