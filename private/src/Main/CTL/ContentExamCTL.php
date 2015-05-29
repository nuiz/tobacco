<?php
/**
 * Created by PhpStorm.
 * User: NUIZ
 * Date: 28/5/2558
 * Time: 12:27
 */

namespace Main\CTL;
use Main\Helper\ArrayHelper;
use Main\DB\Medoo\MedooFactory;
use Main\Helper\ResponseHelper;

/**
 * @Restful
 * @uri /content/exam
 */
class ContentExamCTL extends BaseCTL {
    /**
     * @POST
     */
    public function add(){
        $params = $this->getReqInfo()->params();
        if(empty($params["questions"])){
            return ResponseHelper::error("questions is empty");
        }
        if(!is_array($params["questions"])){
            return ResponseHelper::error("questions is not array");
        }
        foreach($params["questions"] as $key=> $q){
            if(empty($q["question"])){
                return ResponseHelper::error("questions[{$key}] is empty");
            }

            if(empty($q["choices"])){
                return ResponseHelper::error("questions[{$key}].choices is empty");
            }
            if(!is_array($q["choices"])){
                return ResponseHelper::error("questions[{$key}].choices is not array");
            }

            $ans = 0;
            foreach($q["choices"] as $key2=> $c){
                if(empty($c["choice"])){
                    return ResponseHelper::error("questions[{$key}].choices[{$key2}].choice is empty");
                }
                if(!empty($c["is_answer"]) && $c["is_answer"]==1){
                    $ans++;
                }
            }

            if($ans == 0){
                return ResponseHelper::error("questions[{$key}] is not have answer");
            }
            if($ans > 1){
                return ResponseHelper::error("questions[{$key}] is more answer than 1");
            }
        }
        if(empty($params["content_id"])){
            return ResponseHelper::error("content_id is empty");
        }

        $db = MedooFactory::getInstance();
        $foundContent = $db->count("content", "*", ["content_id"=> $params["content_id"]]) != 0;

        if(!$foundContent){
            return ResponseHelper::error("Not found content by content_id=".$params["content_id"]);
        }

        $db->pdo->beginTransaction();

        foreach($params["questions"] as $key=> $q){
            $insert = ArrayHelper::filterKey(["question", "content_id"], $q);
            $insert["content_id"] = $params["content_id"];

            $q_id = $db->insert("content_exam_question", $insert);

            $e = $db->error();
            if($e[1]!=0){
                $db->pdo->rollBack();
                return ResponseHelper::error("database error: ".$e[2]);
            }

            foreach($q["choices"] as $key2=> $c){
                $insert = ArrayHelper::filterKey(["choice"], $c);
                $insert["is_answer"] = ($c["is_answer"] == 0)? 0: 1;
                $insert["question_id"] = $q_id;
                $db->insert("content_exam_choice", $insert);

                $e = $db->error();
                if($e[1]!=0){
                    $db->pdo->rollBack();
                    return ResponseHelper::error("database error: ".$e[2]);
                }
            }
        }

        $db->pdo->commit();

        return ["success"=> true];
    }

    /**
     * @GET
     * @uri /[i:id]
     */
    public function gets(){
        $db = MedooFactory::getInstance();
        $questions = $db->select("content_exam_question", "*", ["content_id"=> $this->getReqInfo()->urlParam("id")]);
        foreach($questions as $key=> $q){
            $questions[$key]["choices"] = $db->select("content_exam_choice", "*", ["question_id"=> $q["question_id"]]);
        }

        return $questions;
    }

    /**
     * @DELETE
     * @uri /question/[i:question_id]
     */
    public function delete(){
        $q_id = $this->getReqInfo()->urlParam("question_id");
        $db = MedooFactory::getInstance();
        $db->delete("content_exam_question", ["question_id"=> $q_id]);
        $db->delete("content_exam_choice", ["question_id"=> $q_id]);

        return ['success'=> true];
    }
}