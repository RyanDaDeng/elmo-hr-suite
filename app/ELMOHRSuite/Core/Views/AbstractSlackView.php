<?php
/**
 * Created by PhpStorm.
 * User: dadeng
 * Date: 2019/12/15
 * Time: 4:47 PM
 */

namespace App\ELMOHRSuite\Core\Views;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

abstract class AbstractSlackView
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


    /**
     * @param array $body
     * @return ViewResponse
     */
    public function validate(array $body)
    {
        $data = $this->collectData($body);

        $validator = Validator::make($data, $this->rules());

        $errorBug = ViewResponse::create();
        $errorBug->setData($data);
        if ($validator->fails()) {
            $errorBug->setFailed();
            foreach ($validator->errors()->messages() as $errorKey => $messages) {
                $errorBug->addError($errorKey, $messages[0]);
            }
        }

        return $errorBug;
    }


    public function collectData($view)
    {
        $data = [];
        foreach ($view['view']['state'] as $blockId => $fields) {

            foreach ($fields as $fieldId => $field) {

                $value = $field[$fieldId];
                switch ($value['type']) {
                    case 'static_select':
                        $data[$fieldId] = $value['selected_option']['value'];
                        break;
                    case 'plain_text_input':
                        $data[$fieldId] = $value['value'];
                        break;
                    case 'users_select':
                        $data[$fieldId] = $value['selected_user'];
                        break;
                }
            }
        }

        return $data;
    }
}
