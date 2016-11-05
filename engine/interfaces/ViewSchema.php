<?php
/**
 * Created by PhpStorm.
 * User: Var Yan
 * Date: 6/11/2016
 * Time: 7:39 PM
 */

namespace engine\interfaces;

/**
 * Interface ViewSchema
 * @package engine\interfaces
 */
interface ViewSchema extends EngineSchema
{
    /**
     * @param string $filename
     * @param array|\stdClass|null $args
     * @param bool $as_output
     * @return ViewSchema
     */
    public function render(string $filename, $args = null, bool $as_output = false) : ViewSchema;

    /**
     * @param array|\stdClass $args
     * @return ViewSchema
     */
    public function withVars($args) : ViewSchema;

    /**
     * @return array
     */
    public function getOutput() : array;

    /**
     * @return string
     */
    public function immediately() : string;

    /**
     * @param string $sectionName
     * @return mixed
     */
    public function yield(string $sectionName);

    /**
     * @param string $parentViewName
     * @return mixed
     */
    public function extends(string $parentViewName);

    /**
     * @param string $sectionName
     * @return mixed
     */
    public function section(string $sectionName);

    /**
     * @return mixed
     */
    public function endSection();
}