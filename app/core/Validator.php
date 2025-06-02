<?php

require_once 'HttpException.php';

class Validator {
    public static function validate($data, $rules) {
        foreach ($rules as $field => $ruleSet) {
            $rulesArr = explode('|', $ruleSet);

            foreach ($rulesArr as $rule) {
                $ruleName = $rule;
                $ruleParam = null;

                // Suporte a regras com parâmetros, ex: min:3
                if (strpos($rule, ':') !== false) {
                    [$ruleName, $ruleParam] = explode(':', $rule, 2);
                }

                // Verifica se o campo existe
                $valueExists = array_key_exists($field, $data);
                $value = $valueExists ? $data[$field] : null;

                switch ($ruleName) {
                    case 'required':
                        if (!$valueExists || $value === '' || $value === null) {
                            throw new HttpException("The $field field is required", 400);
                        }
                        break;

                    case 'numeric':
                        if ($valueExists && !is_numeric($value)) {
                            throw new HttpException("The $field field must be numeric", 400);
                        }
                        break;

                    case 'string':
                        if ($valueExists && !is_string($value)) {
                            throw new HttpException("The $field field must be a string", 400);
                        }
                        break;

                    case 'min':
                        if ($valueExists) {
                            if (is_string($value) && strlen($value) < (int)$ruleParam) {
                                throw new HttpException("The $field field must be at least $ruleParam characters", 400);
                            }
                            if (is_numeric($value) && $value < (int)$ruleParam) {
                                throw new HttpException("The $field field must be at least $ruleParam", 400);
                            }
                        }
                        break;

                    case 'max':
                        if ($valueExists) {
                            if (is_string($value) && strlen($value) > (int)$ruleParam) {
                                throw new HttpException("The $field field must be no more than $ruleParam characters", 400);
                            }
                            if (is_numeric($value) && $value > (int)$ruleParam) {
                                throw new HttpException("The $field field must be no more than $ruleParam", 400);
                            }
                        }
                        break;
                }
            }
        }
    }
}
