<?php

namespace App\Rules;

use Closure;
use Exception;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CountryPhoneNumber implements DataAwareRule, ValidationRule
{
    protected $data = [];

    public function __construct(private string $code = 'country_code')
    { 
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Check whether the country code is supported
        $countryCode = $this->data[$this->code] ?? null;
        $codeValidator = Validator::make(
            [$this->code => $countryCode],
            [
                $this->code => [
                    'required',
                    'string',
                    Rule::in(array_keys(config('country')))
                ],
            ]
        );

        if($codeValidator->fails()){
            throw new ValidationException($codeValidator);
        }

        // Check the length of the phone number

        $configCountry = config("country." . $countryCode);

        $lengthValidator = Validator::make(
            [$attribute => $value],
            [
                $attribute => [
                    'required',
                    'numeric',
                    'digits:' . $configCountry['length']
                ] 
            ]
        );

        if($lengthValidator->fails()){
            throw new ValidationException($lengthValidator);
        }

        request()->merge([$attribute => $configCountry['code'] . $value ]);
    }     
        
    function setData(array $data)
    {
        $this->data = $data;

        return $this;
    }
}

