<?php
/**
 * Created by PhpStorm.
 * User: NUIZ
 * Date: 11/1/2558
 * Time: 21:50
 */

namespace Main\CTL;
use Imagecow\Image;
use Main\DB\Medoo\MedooFactory;
use Main\Helper\URL;

/**
 * @Restful
 * @uri /image
 */
class ImageCTL extends BaseCTL {
    protected $basePath = "private/images", $table = "images";

    /**
     * @POST
     */
    public function add(){
        $image = $image = Image::create($_FILES['image']['tmp_name']);

        $mime = $image->getMimeType();
        if(!in_array($mime, ['image/jpeg'])){
            return ['error'=> 'extension not support'];
        }

        $ext = str_replace('image/', '', $mime);
        $name = uniqid("img_").'.'.$ext;
        $des = $this->basePath.'/'.$name;
        $image->save($des);

        $masterDB = MedooFactory::getInstance();
        $id = $masterDB->insert($this->table, ['path'=> $name]);

        return $this->_get($id);
    }

    /**
     * @GET
     * @uri /[:id]
     */
    public function get(){
        $id = $this->reqInfo->urlParam('id');
        $item = $this->_get($id);

        if(is_null($item)){
            http_response_code(404);
            exit();
        }

        $image = Image::create($this->basePath.'/'.$item['path']);
        $image->show();
        exit();
    }

    /**
     * @DELETE
     * @uri /[:id]
     */
    public function delete(){
        $id = $this->reqInfo->urlParam('id');
        $item = $this->_get($id);

        if(!is_null($item)){
            unlink($this->basePath.'/'.$item['path']);

            $masterDB = MedooFactory::getInstance();
            $masterDB->delete($this->table, ['id'=> $id]);
        }

        return ['success'=> true];
    }

    public function _get($id){
        $masterDB = MedooFactory::getInstance();
        $result = $masterDB->select($this->table, '*', ['id'=> $id, "LIMIT"=> 1]);
        if(isset($result[0])){
            $result[0]['url'] = URL::absolute('/image/'.$id);
            return $result[0];
        }
        else {
            return null;
        }
    }
}