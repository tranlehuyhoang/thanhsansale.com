<?php
namespace App\Services\Common;

use App\Core\Database;

class Validator
{
    protected $errors = [];
    private $context;

    public function __construct()
    {
        $this->context = Database::getInstance();
    }

    public function validate($data, $rules, $messages = [])
    {
        foreach ($rules as $field => $rule) {
            $ruleList = explode('|', $rule);
            foreach ($ruleList as $singleRule) {
                $ruleParts = explode(':', $singleRule);
                $ruleName = $ruleParts[0];
                $ruleParams = isset($ruleParts[1]) ? explode(',', $ruleParts[1]) : [];

                $method = 'validate' . ucfirst($ruleName);
                if (method_exists($this, $method)) {
                    $this->$method($field, $data[$field], $ruleParams, $messages);
                }
            }
        }

        return empty($this->errors);
    }

    protected function validateRequired($field, $value, $params, $messages)
    {
        if (empty($value)) {

            $this->addError($field, $messages[$field . '.required'] ?? 'Trường ' . $field . ' là bắt buộc.');

        }
    }
    protected function validateUnique($field, $value, $params, $messages)
    {
        list($table, $column) = $params;

        // Assume you have a database connection available
        $result = $this->context->query("SELECT COUNT(*) as count FROM $table WHERE $column = :value", ['value' => $value]);
        $count = $result->fetchColumn();

        if ($count > 0) {

            $this->addError($field, $messages[$field . '.unique'] ?? 'Trường ' . $field . ' phải là duy nhất.');

        }
    }
    protected function validatePasswordStrength($field, $value, $params, $messages)
    {
        $hasSpecialChar = preg_match('/[!@#$%^&*(),.?":{}|<>]/', $value);
        $hasUppercase = preg_match('/[A-Z]/', $value);
        $hasNumber = preg_match('/[0-9]/', $value);

        if (!$hasSpecialChar || !$hasUppercase || !$hasNumber) {

            $this->addError($field, $messages[$field . '.password_strength'] ?? 'Trường ' . $field . ' phải có ít nhất một ký tự đặc biệt, một chữ cái in hoa, và một số.');

        }
    }

    protected function validateMin($field, $value, $params, $messages)
    {
        if (strlen($value) < $params[0]) {
            $this->addError($field, $messages[$field . '.min'] ?? 'The ' . $field . ' field must be at least ' . $params[0] . ' characters.');
        }
    }

    protected function validateMax($field, $value, $params, $messages)
    {
        if (strlen($value) > $params[0]) {
            $this->addError($field, $messages[$field . '.max'] ?? 'The ' . $field . ' field may not be greater than ' . $params[0] . ' characters.');
        }
    }
    protected function validateEmail($field, $value, $params, $messages)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->addError($field, $messages[$field . '.email'] ?? 'The ' . $field . ' field must be a valid email address.');
        }
    }

    protected function validateConfirmed($field, $value, $params, $messages)
    {
        list($confirmValue) = $params;

        if ($value !== $confirmValue) {
            $this->addError($field, $messages[$field . '.confirmed'] ?? 'The ' . $field . ' field does not match the confirmation field.');
        }
    }

    protected function addError($field, $message)
    {
        $this->errors[$field][] = $message;
    }

    public function getErrors()
    {
        return $this->errors;
    }
    public function getFormattedErrors()
    {
        $formattedErrors = [];

        foreach ($this->errors as $field => $errors) {
            $formattedErrors[$field] = implode(' ', $errors);
        }

        return $formattedErrors;
    }
}
