<?php

class Validator {
    private $errors = [];

    public function validate($data, $rules) {
        foreach ($rules as $field => $ruleString) {
            $rulesArray = explode('|', $ruleString);
            $value = $data[$field] ?? null;

            foreach ($rulesArray as $rule) {
                if (strpos($rule, ':') !== false) {
                    list($ruleName, $param) = explode(':', $rule);
                } else {
                    $ruleName = $rule;
                    $param = null;
                }

                $method = 'validate' . ucfirst($ruleName);
                if (method_exists($this, $method)) {
                    $this->$method($field, $value, $param);
                }
            }
        }
        return empty($this->errors);
    }

    public function getErrors() {
        return $this->errors;
    }

    protected function validateRequired($field, $value) {
        if ($value === null || $value === '') {
            $this->addError($field, "The {$field} field is required.");
        }
    }

    protected function validateEmail($field, $value) {
        if ($value && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->addError($field, "The {$field} field must be a valid email address.");
        }
    }

    protected function validateNumeric($field, $value) {
        if ($value && !is_numeric($value)) {
            $this->addError($field, "The {$field} field must be numeric.");
        }
    }

    protected function validateMin($field, $value, $param) {
        if ($value && strlen($value) < (int)$param) {
            $this->addError($field, "The {$field} field must be at least {$param} characters.");
        }
    }

    protected function validateMax($field, $value, $param) {
        if ($value && strlen($value) > (int)$param) {
            $this->addError($field, "The {$field} field may not be greater than {$param} characters.");
        }
    }

    protected function validateIn($field, $value, $param) {
        $allowed = explode(',', $param);
        if ($value && !in_array($value, $allowed)) {
            $this->addError($field, "The {$field} field is invalid.");
        }
    }

    public function validateFile($field, $file) {
        if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
            $this->addError($field, "The {$field} file is required.");
        }
    }

    public function validateMaxFileSize($field, $file, $maxSize) {
        if (isset($file['size']) && $file['size'] > $maxSize) {
            $maxMb = $maxSize / (1024 * 1024);
            $this->addError($field, "The {$field} file must not exceed {$maxMb}MB.");
        }
    }

    public function validateFileType($field, $file, $allowedTypesStr) {
        if (isset($file['tmp_name']) && !empty($file['tmp_name'])) {
            $allowedTypes = explode(',', $allowedTypesStr);
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowedTypes)) {
                $this->addError($field, "The {$field} must be a file of type: {$allowedTypesStr}.");
            }
        }
    }

    protected function addError($field, $message) {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = [];
        }
        $this->errors[$field][] = $message;
    }
}
