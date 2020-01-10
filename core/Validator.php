<?php


namespace Core;


class Validator
{
    public static function make(array $data, array $rules){
        foreach ($rules as $ruleKey => $ruleValue){
            foreach ($data as $dataKey => $dataValue){
                if($ruleKey == $dataKey){

                    switch ($ruleValue){
                        case 'required':
                            if($dataValue == '' || empty($dataValue)){
                                $errors["{$ruleKey}"] = "O Campo  {$ruleKey} deve ser preenchido";
                            }
                            break;
                        case strpos('<') > -1:
                            $value = intval(substr($ruleValue,1));
                            if(strlen($dataValue) > $value){
                                $errors["{$ruleKey}"] = "O Campo {$ruleKey} deve conter menos de 100 caracteres";
                            }
                            break;
                    }
                }
            }

            if($errors){
                Session::set("errors",$errors);
                Session::set("inputs",$data);
                return true;
            }else{
                Session::destroy(["errors","inputs"]);
                return false;
            }
        }
    }
}