<?php
    namespace App\Http\Traits;
    trait ApiUseTrait{
        protected function responseSuccess(bool $success,string $message,?object $data=null,int $status){
            return response()->json(["success"=>$success,"message"=>$message,"data"=>$data,],$status);
        }
        protected function responseFail(bool $success,string $message,int $status){
            return response()->json(["success"=>$success,"message"=>$message],$status);
        }
    }
?>