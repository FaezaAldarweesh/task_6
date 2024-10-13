<?php 

namespace App\Http\Trait;

// trait  customize the methods for successful , failed , authentecation responses.
trait ApiResponseTrait 
{
    public function api_Response($data,$token,$message,$status){
        $array = [
            'data'=>$data,
            'message'=>$message,
            'access_token'=>$token,
            'token_type'=>'bearer',
        ];

        return response()->json($array,$status);
    }

    //========================================================================================================================
    
    public function failed_Response($message,$status){
        return response()->json($message,$status);
    }
    //========================================================================================================================
    public function success_Response($data,$message,$status){
    $array = [
        'data'          => $data,
        'message'       => $message,
    ];
    return response()->json($array,$status);
    }
}


    
