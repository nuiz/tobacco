<?php
/**
 * Created by PhpStorm.
 * User: NUIZ
 * Date: 10/2/2558
 * Time: 11:15
 */

namespace Main\DB\Medoo;


class Medoo extends \medoo{
    // rewrite
    protected function column_quote($string)
    {
        // fix * in array
        if($string == "*") return $string;
        // fix " to `
        else return '`' . str_replace('.', '`.`', preg_replace('/(^#|\(JSON\))/', '', $string)) . '`';
    }
}