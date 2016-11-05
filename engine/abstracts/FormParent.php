<?php
/**
 * Created by PhpStorm.
 * User: Var Yan
 * Date: 6/11/2016
 * Time: 8:34 PM
 */

namespace engine\abstracts;

use engine\interfaces\FormSchema;
use engine\objects\Factory;
use engine\objects\Form;
use engine\objects\HTML;
use engine\objects\Session;
use engine\traits\Generate;

/**
 * Class FormParent
 * @package engine\abstracts
 */
abstract class FormParent implements FormSchema
{
    /**
     * @var string $__output
     */
    private $__output = "";
    /**
     * @var string $_action
     */
    protected $_action = "";
    /**
     * @var string
     */
    protected $_method = "POST";
    /**
     * @var string $_optional
     */
    protected $_optional = "";
    /**
     * @var string $secretKey
     */
    protected static $_secretKey;

    /**
     * FormParent constructor.
     * @param $action
     * @param $method
     * @param $optional
     */
    public function __construct(string $action = "", string $method = "POST", string $optional = "")
    {
        if(empty($_POST)):
            self::$_secretKey = Generate::token("36",true);
            Session::set("__formKey",self::$_secretKey);
        endif;

        $this->_action = $action;
        $this->_method = $method;
        $this->_optional = $optional;
    }

    /**
     * FormParent clone.
     */
    private function __clone(){}

    /**
     * @param string $action
     * @param string $method
     * @param string $optional
     * @return FormSchema
     */
    public static function create(string $action = "", string $method = "POST", string $optional = "") : FormSchema
    {
        return Factory::create(Form::class,$action,$method,$optional);
    }

    /**
     * @param string $tagName
     * @param string $optional
     * @param string $text
     * @return FormSchema
     */
    public function openTag(string $tagName, string $optional = "", string $text = "") : FormSchema
    {
        $this->__output .= HTML::getInstance()->{$tagName}([
            'optional'=>$optional,
            'inner'=>$text
        ]);
        return $this;
    }

    /**
     * @return FormSchema
     */
    public function closeTag() : FormSchema
    {
        $this->__output .= HTML::getInstance()->close();
        return $this;
    }

    /**
     * @param string $text
     * @param string $for
     * @param string $optional
     * @return FormSchema
     */
    public function label(string $text, string $for = "", string $optional = "") : FormSchema
    {
        $this->__output .= HTML::label([
            'inner'=>$text,
            'for'=>$for,
            'optional'=>$optional
        ])->close();
        return $this;
    }

    /**
     * @param string $name
     * @param string $type
     * @param string $optional
     * @return FormSchema
     */
    public function input(string $name, string $type = "text", string $optional = "") : FormSchema
    {
        $this->__output .= HTML::input([
            'name'=>$name,
            'type'=>$type,
            'optional'=>$optional
        ])->close();
        return $this;
    }

    /**
     * @param string $value
     * @param string $type
     * @param string $optional
     * @return FormSchema
     */
    public function button(string $value, string $type = "submit", string $optional = "") : FormSchema
    {
        $this->__output .= HTML::button([
            'inner'=>$value,
            'type'=>$type,
            'optional'=>$optional
        ])->close();;
        return $this;
    }

    /**
     * @param string $name
     * @param array $options
     * @param string|null $value
     * @param string $optional
     * @param string|null $placeholder
     * @param array|null $optionFields
     * @return FormSchema
     */
    public function select(string $name, array $options, string $value = null, string $optional = "", string $placeholder = null, array $optionFields = null) : FormSchema
    {
        $this->__output .= HTML::select(['name'=>$name,'optional'=>$optional]);
        if(!is_null($placeholder))
            $this->__output .= HTML::option(['value'=>"",'selected'=>"",'inner'=>$placeholder]);

        if(is_array($optionFields))
        {
            foreach($options as $option):
                $optionOptional = "";
                if($option->{$optionFields['key']} == $value || $option->{$optionFields['value']} == $value)
                    $optionOptional = "selected";

                $this->__output .= HTML::option([
                    "inner"=>$option->{$optionFields['value']},
                    "optional"=>$optionOptional,
                    "value"=>$option->{$optionFields['key']}
                ])->close();
            endforeach;
        }
        else
        {
            foreach($options as $key => $option):
                $optionOptional = "";
                if($key == $value || $option == $value)
                    $optionOptional = "selected";

                $this->__output .= HTML::option([
                    "inner"=>$option,
                    "optional"=>$optionOptional,
                    "value"=>$key
                ])->close();
            endforeach;
        }

        $this->__output .= HTML::getInstance()->close();
        return $this;
    }

    /**
     * @param string $name
     * @param string $value
     * @param string $optional
     * @return FormSchema
     */
    public function textarea(string $name, $value = "", string $optional = "") : FormSchema
    {
        $this->__output .= HTML::textarea([
            'inner'=>$value,
            'name'=>$name,
            'optional'=>$optional
        ])->close();
        return $this;
    }

    /**
     * @param string $action
     * @return FormSchema
     */
    public function action(string $action) : FormSchema
    {
        $this->_action = $action;
        return $this;
    }

    /**
     * @param string $method
     * @return FormSchema
     */
    public function method(string $method) : FormSchema
    {
        $this->_method = $method;
        return $this;
    }

    /**
     * @param string $optional
     * @return FormSchema
     */
    public function optional(string $optional) : FormSchema
    {
        $this->_optional = $optional;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        $form = HTML::form(['method'=>$this->_method,'action'=>$this->_action,'optional'=>$this->_optional])->input([
            'name'=>"__formKey",
            'type'=>"hidden",
            'value'=>self::$_secretKey
        ])->close().$this->__output.HTML::getInstance()->close();
        return $form;
    }

}