<?php
/**
 * Created by PhpStorm.
 * User: dadeng
 * Date: 2019/12/15
 * Time: 4:49 PM
 */

namespace App\ELMOHRSuite\Core\Dialogs;


class DialogResponse
{
    public $errors = [];
    public $isFailed = false;

    /**
     * @return ViewResponse
     */
    public static function create()
    {
        return new self;
    }

    public function addError($name, $error)
    {

        $this->errors[] = [
            'name' => $name,
            'error' => $error
        ];
        return $this;
    }

    public function toArray(): array
    {
        return [
            'is_failed'=>$this->isFailed,
            'errors' => $this->errors
        ];
    }

    public function getResponse(){
        if($this->isFailed){
            return [
                'errors' => $this->errors
            ];
        }else{
            return [];
        }
    }

    public function setFailed()
    {
        $this->isFailed = true;
        return $this;
    }

    public function isFailed(){
        return $this->isFailed;
    }
}
