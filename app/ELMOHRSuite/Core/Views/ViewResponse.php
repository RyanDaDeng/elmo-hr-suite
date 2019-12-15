<?php
/**
 * Created by PhpStorm.
 * User: dadeng
 * Date: 2019/12/15
 * Time: 4:49 PM
 */

namespace App\ELMOHRSuite\Core\Views;


class ViewResponse
{
    private $errors = [];
    private $isFailed = false;
    private $data = [];

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     * @return ViewResponse
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return ViewResponse
     */
    public static function create()
    {
        return new self;
    }

    public function addError($name, $error)
    {

        $this->errors[$name] = $error;
        return $this;
    }



    public function setFailed()
    {
        $this->isFailed = true;
        return $this;
    }

    public function isFailed()
    {
        return $this->isFailed;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param array $errors
     * @return ViewResponse
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;
        return $this;
    }


}
