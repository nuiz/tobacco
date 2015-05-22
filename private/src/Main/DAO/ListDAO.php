<?php
/**
 * Created by PhpStorm.
 * User: NUIZ
 * Date: 4/2/2558
 * Time: 16:43
 */
namespace Main\DAO;

use Main\DB\Medoo\MedooFactory;

class ListDAO {
    public static function gets($table, $options = []){
        $default = self::_getDefaultOptionList();
        $options = array_merge($default, $options);
        $skip = ($options['page']-1)*$options['limit'];

        $where = $options["where"];
        $where["LIMIT"] = [$skip, $options['limit']];

        $db = MedooFactory::getInstance();
        if(isset($options["join"])){
            $data = $db->select($table, $options["join"], $options["field"], $where);
            $total = $db->count($table, $options["join"], "*", $where);
        }
        else {
            $data = $db->select($table, $options["field"], $where);
            $total = $db->count($table, "*", $where);
        }

        unset($where["LIMIT"]);
        unset($where["ORDER"]);

        $res = [
            'length'=> count($data),
            'total'=> $total,
            'data'=> $data,
            'paging'=> [
                'page'=> (int)$options['page'],
                'limit'=> (int)$options['limit']
            ]
        ];

        if($total > $skip + (int)$options['limit']){
            $res['paging']['next'] = self::_buildUrl($options['url'], ['page'=> (int)$options['page']+1, 'limit'=> (int)$options['limit']]);
        }

        if((int)$options['page'] > 1){
            $res['paging']['prev'] = self::_buildUrl($options['url'], ['page'=> (int)$options['page']-1, 'limit'=> (int)$options['limit']]);
        }

        return $res;
    }

    private static function _getDefaultOptionList(){
        return ["limit"=> 100, "page"=> 1, "where"=> [], "field" => "*", "url"=>
            "http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}"];
    }

    private static function _buildUrl($url, $attr){
        $old = parse_url($url);

        $oldQuery = [];
        if(isset($old["query"])){
            parse_str($old["query"], $oldQuery);
        }

        $query = array_merge($oldQuery, $attr);
        $newUrl = "";
        if(isset($old["scheme"])){
            $newUrl .= $old["scheme"]."://";
        }
        $newUrl .= $old["host"].$old["path"];
        return $newUrl."?".http_build_query($query);
    }
}