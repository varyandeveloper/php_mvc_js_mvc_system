<?php
/**
 * Created by PhpStorm.
 * User: Var Yan
 * Date: 6/11/2016
 * Time: 5:40 PM
 */

namespace engine\abstracts;

use engine\config\AppConfig;
use engine\config\ValidatorConfig;
use engine\Engine;
use engine\interfaces\ValidatorSchema;

/**
 * Class ValidatorParent
 * @package engine\abstracts
 */
abstract class ValidatorParent implements ValidatorSchema
{
    /**
     * @var array $validParts
     * */
    private $__validParts;
    /**
     * @var string $error
     * */
    private $__error = false;
    /**
     * @var boolean $status
     * @default value boolean true
     * */
    private $__status = true;
    /**
     * @var array $fields
     * */
    private $__fields = array();
    /**
     * @var array $rules
     * */
    private $__rules;

    /**
     * setRules method
     * @param array $validationArray
     * @return ValidatorSchema object
     * */
    public function setRules(array $validationArray) : ValidatorSchema
    {
        $this->__rules = $validationArray;
        return $this;
    }

    /**
     * getRules method
     * @return array
     * */
    public function getRules() : array
    {
        return $this->__rules;
    }

    /**
     * lastError method
     * @return false|array
     * */
    public function getError()
    {
        $messages = Engine::loadLang('validator', AppConfig::getCurrentLanguage());
        if (is_array($this->__error)) {
            foreach ($this->__error as $key => $value) {
                if (array_key_exists($value, $messages)) {
                    switch ($value) {
                        case "maxLength":
                        case "minLength":
                            $this->__error[$key] = str_replace("{label}", $key, $messages[$value]);
                            $this->__error[$key] = str_replace("{start}", $this->__validParts[$key]['start'], $this->__error[$key]);
                            break;
                        case "between":
                            $this->__error[$key] = str_replace("{label}", $key, $messages[$value]);
                            $this->__error[$key] = str_replace("{start}", $this->__validParts[$key]['start'], $this->__error[$key]);
                            $this->__error[$key] = str_replace("{end}", $this->__validParts[$key]['end'], $this->__error[$key]);
                            break;
                        case "matchWith":
                            $this->__error[$key] = str_replace("{label}", $key, $messages[$value]);
                            $this->__error[$key] = str_replace("{other}", $this->__validParts[$key]['other'], $this->__error[$key]);
                            break;
                        default:
                            $this->__error[$key] = str_replace("{label}", $key, $messages[$value]);
                            break;
                    }
                }
            }
        }

        return implode("", array_values($this->__error));
    }

    /**
     * getStatus method
     * @return bool
     * */
    public function getStatus() : bool
    {
        if (is_array($this->getRules()) && sizeof($this->getRules()) > 0) {
            foreach ($this->getRules() as $key => $rule) {
                if (Engine::isAssoc($rule))
                    $this->__fields[] = $this->_validationAssoc($key, $rule);
                elseif (is_array($rule))
                    $this->__fields[] = $this->_validationArray($rule);
                else
                    exit('invalidValidRule');

            }
        }
        $this->_finishValidation();
        return $this->__status;
    }

    /**
     * validationAssoc method
     * @param string $field
     * @param array $details
     * @return array
     * */
    protected function _validationAssoc($field, $details)
    {
        $label = (isset($details['label']) && !empty($details['label'])) ? $details['label'] : $field;
        $rules = isset($details['rules']) ? (is_string($details['rules']) ? explode('|', $details['rules']) : (is_array($details['rules']) ? $details['rules'] : null)) : null;

        return array(
            'name' => $field,
            'label' => $label,
            'rules' => $rules,
        );
    }

    /**
     * validationArray method
     * @param array $rule
     * @return array
     * */
    protected function _validationArray($rule)
    {
        $field = isset($rule[0]) ? $rule[0] : null;
        $label = isset($rule[1]) ? $rule[1] : null;
        $rules = isset($rule[2]) ? (is_string($rule[2]) ? explode('|', $rule[2]) : (is_array($rule[2]) ? $rule[2] : null)) : null;

        return array(
            'name' => $field,
            'label' => $label,
            'rules' => $rules,
        );
    }

    /**
     * finishValidation method
     * @return array
     * */
    protected function _finishValidation()
    {
        foreach ($this->__fields as $field) {
            for ($i = 0; $i < sizeof($field['rules']); $i++) {
                $args = array();
                $args[] = isset($_REQUEST[$field['name']])
                    ? (is_array($_REQUEST[$field['name']])
                        ? $_REQUEST[$field['name']][0]
                        : $_REQUEST[$field['name']])
                    : null;
                if (strpos($field['rules'][$i], '[') !== FALSE) {

                    $cleanString = Engine::getStringBetween($field['rules'][$i]);

                    $arg = str_replace('[', '', str_replace(']', '', $cleanString));
                    $rule = str_replace($cleanString, '', $field['rules'][$i]);

                    switch ($rule) {
                        case "minLength":
                            break;
                        case "matchWith":
                            $args[] = isset($_REQUEST[$arg]) ? $_REQUEST[$arg] : null;
                            $this->__validParts[$field['label']]['other'] = ucfirst($arg);
                            break;
                        case "between":
                            $betweenParts = explode(',', $arg);
                            if (isset($betweenParts[0])) {
                                if (preg_match('/^[0-9]*$/', $betweenParts[0])) {
                                    $args[] = $betweenParts[0];
                                } else {
                                    exit('between first argument must be integer');
                                }
                                if (isset($betweenParts[1])) {
                                    if (preg_match('/^[0-9]*$/', $betweenParts[1])) {
                                        $args[] = $betweenParts[1];

                                        $this->__validParts[$field['label']]['start'] = $betweenParts[0];
                                        $this->__validParts[$field['label']]['end'] = $betweenParts[1];
                                    } else {
                                        exit('between second argument must be integer');
                                    }
                                } else {
                                    exit('between second argument is required');
                                }
                            } else {
                                exit('between first argument is required');
                            }
                            break;
                    }
                    $args[] = $arg;
                } else {
                    $rule = $field['rules'][$i];
                }
                $key = str_replace('[', '', str_replace(']', '', $rule));

                $types = ValidatorConfig::getTypes();
                if(!is_array($types))
                    exit("Validator ::__init__ method not initialized in release config folder");

                if (!call_user_func_array($types[$key], $args))
                    $this->__error[$field['label']] = $key;
            }
        }

        $this->__status = is_array($this->__error) ? false : true;
    }
}