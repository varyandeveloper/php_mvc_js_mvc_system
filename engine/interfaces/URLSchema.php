<?php
/**
 * Created by PhpStorm.
 * User: 37498_000
 * Date: 10.06.2016
 * Time: 15:01
 */

namespace engine\interfaces;

/**
 * Interface URLSchema
 * @package engine\interfaces
 */
interface URLSchema extends EngineSchema
{
    /**
     * @return string
     */
    public function get() : string;

    /**
     * @param string $url
     * @param bool $refresh
     * @return void
     */
    public function to(string $url = '', bool $refresh = false);

    /**
     * @param int $position
     * @return string|null
     */
    public function segment(int $position);
}