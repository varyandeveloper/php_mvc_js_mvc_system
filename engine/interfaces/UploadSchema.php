<?php
/**
 * Created by PhpStorm.
 * User: Var Yan
 * Date: 6/11/2016
 * Time: 6:51 PM
 */

namespace engine\interfaces;

use engine\config\UploadConfig;

/**
 * Interface UploadSchema
 * @package engine\interfaces
 */
interface UploadSchema extends EngineSchema
{
    /**
     * @param UploadConfig|null $uploadConfig
     * @return UploadSchema
     */
    public static function __init__(UploadConfig $uploadConfig = null) : UploadSchema;

    /**
     * @param UploadConfig $uploadConfig
     * @return UploadSchema
     */
    public function reConfig(UploadConfig $uploadConfig) : UploadSchema;

    /**
     * @param string $fileName
     * @return void
     */
    public function one(string $fileName);

    /**
     * @param string $fileName
     * @return void
     */
    public function multiple(string $fileName);

    /**
     * @return string|null
     */
    public function getUploadedName();

    /**
     * @return array|null
     */
    public function getUploadedNames();

    /**
     * @return array|false
     */
    public function getError();
}