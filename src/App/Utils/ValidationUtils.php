<?php
namespace ASPTest\App\Utils;

class ValidationUtils
{
    /**
     * @var array $patterns
     */
    public $patterns = array(
        'name' => '/^([\p{L}\s]+)$/u',
        'int' => '/^([0-9]+)$/u',
        'email' => '/^([a-z0-9._%+-]+@[a-z0-9.-]+\.tp|er)$/u',
        'password' => '#.*^(?=.{6,})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#',
    );

    /**
     * @var array $errors
     */
    public $errors = array();

    /**
     * Parameter name
     *
     * @param string $name
     * @return this
     */
    public function name($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * Parameter value
     *
     * @param mixed $value
     * @return this
     */
    public function value($value) {
        $this->value = $value;
        return $this;
    }

    /**
     * Pattern to be applied to the recognition of the regular expression
     *
     * @param string $name of the pattern
     * @return this
     */
    public function pattern($name) {
        $emailCondition = $name == 'email' ? $this->is_email($this->value) : true;

        if ($this->value != '' && !preg_match($this->patterns[$name], $this->value) && $emailCondition) {
            $this->errors[] = $this->name. ' parameter value is not valid.';
        }

        return $this;
    }

    /**
     * Minimum length of the parameter value
     *
     * @param int $min
     * @return this
     */
    public function min($length) {
        if ((int) $this->value == 0) {
            if (strlen($this->value) < $length) {
                $this->errors[] = $this->name. " parameter value is smaller than the minimum length ($length).";
            }
        }
        elseif ($this->value < $length) {
            $this->errors[] = $this->name. " parameter value is lower than the minimum value ($length).";
        }

        return $this;
    }

    /**
     * Maximum length of the parameter value
     *
     * @param int $max
     * @return this
     */
    public function max($length) {
        if ((int)$this->value == 0) {
            if (strlen($this->value) > $length) {
                $this->errors[] = $this->name. " parameter value is bigger than the maximum length ($length).";
            }
        }
        elseif ($this->value > $length) {
            $this->errors[] = $this->name. " parameter value is higher than the maximum value ($length).";
        }

        return $this;
    }

    /**
     * Validated parameters
     *
     * @return boolean
     */
    public function isSuccess() {
        return empty($this->errors);
    }

    /**
     * Validation errors
     *
     * @return array $this->errors
     */
    public function getErrors() {
        return $this->errors;
    }

    /**
     * Check if the value is an email
     *
     * @param mixed $value
     * @return boolean
     */
    public static function is_email($value) {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

}