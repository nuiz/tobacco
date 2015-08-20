<?php

require("bootstrap.php");
$mongoCn = Main\DB\MongoFactory::getConnection();
$db = $mongoCn->tobacco_search;
$coll = $db->object_keyword;

$medoo = Main\DB\Medoo\MedooFactory::getInstance();
$result = $medoo->query("SELECT * FROM
		(
		SELECT
			n.news_id as object_id,
			n.news_name as object_name,
			UNIX_TIMESTAMP(n.created_at) as created_at,
			'news' as object_type
			FROM news as n
		UNION SELECT
			c.content_id as object_id,
			c.content_name as object_name,
			c.created_at as created_at,
			c.content_type as object_type
			FROM content as c
		) as t
	ORDER BY created_at
");

if(!$result){
	var_dump($medoo->error());
}
else {
	$list = $result->fetchAll(PDO::FETCH_ASSOC);
	$list = array_map(function($item){
		// $item["created_at_datetime"] = date("Y-m-d H:i:s", (int)$item["created_at"]);
		$item["object_id"] = (int)$item["object_id"];
		return $item;
	}, $list);
}

foreach($list as $key=> $value){
	$keyword = $value["object_name"];
	// $oldDoc = $mongoCn->findOne([
	// 	'object_id'=> $value["object_id"],
	// 	'object_type'=> $value["object_type"]
	// ]);
	$doc = [
		"keyword"=> $value["object_name"],
		"object_type"=> $value["object_type"],
		"object_id"=> $value["object_id"]
	];
	$coll->update([
		'object_id'=> $value["object_id"],
		'object_type'=> $value["object_type"]
	], $doc, [
		'upsert'=> true
	]);
}

$mongoCn->close();