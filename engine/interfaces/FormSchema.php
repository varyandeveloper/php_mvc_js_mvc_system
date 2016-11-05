<?php
/**
 * Created by PhpStorm.
 * User: Var Yan
 * Date: 6/11/2016
 * Time: 8:34 PM
 */

namespace engine\interfaces;

/**
 * Interface FormSchema
 * @package engine\interfaces
 */
interface FormSchema extends EngineSchema
{
    /**
     * @param string $tagName
     * @param string $optional
     * @param string $text
     * @return FormSchema
     */
    public function openTag(string $tagName, string $optional = "", string $text = "") : FormSchema;

    /**
     * @return FormSchema
     */
    public function closeTag() : FormSchema;

    /**
     * @param string $action
     * @param string $method
     * @param string $optional
     * @return FormSchema
     */
    public static function create(string $action = "", string $method = "POST", string $optional = "") : FormSchema;

    /**
     * @param string $text
     * @param string $for
     * @param string $optional
     * @return FormSchema
     */
    public function label(string $text, string $for = "", string $optional = "") : FormSchema;

    /**
     * @param string $name
     * @param string $type
     * @param string $optional
     * @return FormSchema
     */
    public function input(string $name, string $type = "text", string $optional = "") : FormSchema;

    /**
     * @param string $value
     * @param string $type
     * @param string $optional
     * @return FormSchema
     */
    public function button(string $value, string $type = "submit", string $optional = "") : FormSchema;

    /**
     * @param string $name
     * @param array $options
     * @param string|null $value
     * @param string $optional
     * @param string|null $placeholder
     * @param array $optionFields
     * @return FormSchema
     */
    public function select(string $name, array $options, string $value = null, string $optional = "", string $placeholder = null, array $optionFields) : FormSchema;

    /**
     * @param string $name
     * @param string $value
     * @param string $optional
     * @return FormSchema
     */
    public function textarea(string $name, $value = "", string $optional = "") : FormSchema;

    /**
     * @param string $action
     * @return FormSchema
     */
    public function action(string $action) : FormSchema;

    /**
     * @param string $method
     * @return FormSchema
     */
    public function method(string $method) : FormSchema;

    /**
     * @return string
     */
    public function __toString() : string;
}