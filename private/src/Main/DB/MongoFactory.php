<?php
namespace Main\DB;

class MongoFactory {
	private static $cn = null;
	public static function getConnection(){
		if(is_null(self::$cn)){
			self::$cn = new \MongoClient("mongodb://192.168.0.236");
			//new MongoClient( "mongodb://example.com:65432" );
		}
		return self::$cn;
	}
}