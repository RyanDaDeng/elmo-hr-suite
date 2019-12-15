<?php
/**
 * Created by PhpStorm.
 * User: dadeng
 * Date: 2019/12/15
 * Time: 4:47 PM
 */

namespace App\ELMOHRSuite\Core\Dialogs;
use Illuminate\Support\Facades\Validator;

abstract class AbstractSlackDialog
{
    /**
     * @return array
     */
    abstract protected function rules();

    /**
     * @param $data
     * @return mixed
     */
    abstract public function template($data);


    public function validate(array $body)
    {
        $validator = Validator::make($body['submission'], $this->rules());

        $errorBug = ViewResponse::create();
        if ($validator->fails()) {
            $errorBug->setFailed();
            foreach ($validator->errors()->messages() as $errorKey => $messages) {
                $errorBug->addError($errorKey, $messages[0]);
            }
        }
        return $errorBug;
    }
}