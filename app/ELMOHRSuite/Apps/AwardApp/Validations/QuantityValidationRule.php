<?php


namespace App\ELMOHRSuite\Apps\AwardApp\Validations;


use Illuminate\Contracts\Validation\Rule;

class QuantityValidationRule implements Rule
{

    private $limit;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($limit)
    {
        //
        $this->limit = $limit;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //

        if($this->limit !==null){
            return $value <= $this->limit;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'You do not have enough balance to give kudos.';
    }

}