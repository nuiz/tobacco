<?php
/**
 * Created by PhpStorm.
 * User: NUIZ
 * Date: 29/1/2558
 * Time: 11:02
 */

namespace Main\Helper;


class LangHelper {
    public static function marionat_encode($data, $lang){
        if($lang != ''){
            if(!is_array($data)){
                $arrdata[$lang] = $data;
            }else{
                $arrdata = $data;
            }
            $returndata = '';
            $returndata .= '{';
            foreach($arrdata as $a => $b)
            {
                $returndata .= '"'.$a.'"(:)"'.$b.'"(,)';
            }
            $returndata .= '}';
            $returndata = str_replace('(,)}', '}', $returndata);
        }
        else{ return false ;}
        return $returndata;
    }

    public static function marionat_encode_array($data){
        if(is_array($data)){
            $arrdata = $data;
            $returndata = '';
            $returndata .= '{';
            foreach($arrdata as $a => $b)
            {
                $returndata .= '"'.$a.'"(:)"'.$b.'"(,)';
            }
            $returndata .= '}';
            $returndata = str_replace('(,)}', '}', $returndata);
        }
        else{ return false ;}
        return $returndata;
    }

    public static function marionat_decode($data){
        if ($data != "" )
        {
            $data = str_replace('}', '', $data);
            $data = str_replace('{', '', $data);
            $arr1 = explode('(,)',$data);
            foreach($arr1 as $arr1a => $arr1b)
            {
                $arr2 = explode('(:)',$arr1b);
                $arr2[0] = substr($arr2[0],1,-1); //str_replace('"', '', $arr2[0]);
                $arr2[1] = substr($arr2[1],1,-1); //str_replace('"', '', $arr2[1]);
                $arrdata[$arr2[0]] = $arr2[1];
            }
        }
        else{ return false ;}

        return $arrdata;
    }

    public static function marionat_decode_lang($data,$lang){
        if ($data != "" && $lang != "" )
        {
            $data = str_replace('}', '', $data);
            $data = str_replace('{', '', $data);
            $arr1 = explode('(,)',$data);
            foreach($arr1 as $arr1a => $arr1b)
            {
                $arr2 = explode('(:)',$arr1b);
                $arr2[0] = substr($arr2[0],1,-1); //str_replace('"', '', $arr2[0]);
                $arr2[1] = substr($arr2[1],1,-1); //str_replace('"', '', $arr2[1]);
                $arrdata[$arr2[0]] = $arr2[1];
            }
        }
        else{ return false ;}
        return $arrdata[$lang];
    }

    public static function getLang($value, $lang = null){
        $s = unserialize($value);
        if(is_null($lang)){
            return $s;
        }
        else if(!isset($s[$lang])){
            return array_shift($s);
        }
        else {
            return $s[$lang];
        }
    }
}