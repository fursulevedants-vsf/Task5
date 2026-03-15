<?php

/**
 * Reusable Validator Class
 */
class Validator {
    private $data;
    private $errors = [];

    public function __construct(array $data) {
        $this->data = $data;
    }

    public function validate(array $rules) {
        foreach ($rules as $field => $fieldRules) {
            $value = $this->data[$field] ?? null;
            $ruleList = explode('|', $fieldRules);

            foreach ($ruleList as $rule) {
                $parameters = [];
                if (strpos($rule, ':') !== false) {
                    [$rule, $paramString] = explode(':', $rule);
                    $parameters = explode(',', $paramString);
                }

                $method = 'validate' . ucfirst($rule);
                if (method_exists($this, $method)) {
                    $error = $this->$method($field, $value, $parameters);
                    if ($error) {
                        $this->errors[$field][] = $error;
                    }
                }
            }
        }
        return empty($this->errors);
    }

    public function getErrors() {
        return $this->errors;
    }

    public function getFirstError() {
        if (empty($this->errors)) return null;
        $firstField = array_key_first($this->errors);
        return $this->errors[$firstField][0];
    }

    // Validation Methods

    private function validateRequired($field, $value) {
        if (is_null($value) || (is_string($value) && trim($value) === '')) {
            return ucfirst($field) . " is required.";
        }
        return null;
    }

    private function validateEmail($field, $value) {
        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return "Invalid email format.";
        }
        return null;
    }

    private function validateMin($field, $value, $params) {
        $min = (int)$params[0];
        if (!empty($value) && strlen($value) < $min) {
            return ucfirst($field) . " must be at least $min characters.";
        }
        return null;
    }

    private function validateMax($field, $value, $params) {
        $max = (int)$params[0];
        if (!empty($value) && strlen($value) > $max) {
            return ucfirst($field) . " cannot exceed $max characters.";
        }
        return null;
    }

    private function validateAlphanumeric($field, $value) {
        if (!empty($value) && !preg_match('/^[a-zA-Z0-9]+$/', $value)) {
            return ucfirst($field) . " must be alphanumeric.";
        }
        return null;
    }

    private function validateNumeric($field, $value) {
        if (!empty($value) && !is_numeric($value)) {
            return ucfirst($field) . " must be numeric.";
        }
        return null;
    }
}

/**
 * Global helper for sanitization
 */
function sanitize($value) {
    if (is_array($value)) {
        return array_map('sanitize', $value);
    }
    return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
}
