<?php
/**
 * Created by PhpStorm.
 * User: design
 * Date: 12/22/2015
 * Time: 9:53 AM
 */

namespace engine\objects;

use engine\interfaces\UploadSchema;
use engine\traits\Generate;
use engine\config\UploadConfig;
use engine\Engine;

/**
 * Class Upload
 * @package engine\objects
 */
class Upload implements UploadSchema
{
    /**
     * @var UploadConfig $UC
     */
    public static $UC;
    /**
     * @var string $ext
     * */
    private static $__ext;
    /**
     * @var string $name
     * */
    private static $__name;
    /**
     * @var string $type
     * */
    private static $__type;
    /**
     * @var array $multiNames
     * */
    private static $__multiNames = array();
    /**
     * @var string $tmp_name
     * */
    private static $__tmp_name;
    /**
     * @var string|array $error
     * */
    private static $__error = false;
    /**
     * @var integer $size
     * */
    private static $__size;

    /**
     * @param UploadConfig|null $uploadConfig
     * @return UploadSchema
     */
    public static function __init__(UploadConfig $uploadConfig = null) : UploadSchema
    {
        self::$UC = !is_null($uploadConfig) ? $uploadConfig : UploadConfig::get();
        return new self;
    }

    /**
     * @param UploadConfig $uploadConfig
     * @return UploadSchema
     */
    public function reConfig(UploadConfig $uploadConfig) : UploadSchema
    {
        self::$UC = $uploadConfig;
        return $this;
    }

    /**
     * @param $fileName
     * @return void
     */
    public function one(string $fileName)
    {
        $file = self::__isFile($fileName);

        self::$__name = $file['name'];
        self::$__type = $file['type'];
        self::$__tmp_name = $file['tmp_name'];
        self::$__size = $file['size'];
        self::__finish();
    }

    /**
     * @param $fileName
     * @return void
     */
    public function multiple(string $fileName)
    {
        $files = self::__isFile($fileName);
        $count = 0;

        if (isset($files['name']) && is_array($files['name'])) {
            $count = sizeof($files['name']);
        } else {
            self::$__error['useUploadOneMethod'] = '';
        }

        for ($i = 0; $i < $count; $i++) {
            self::$__name = $files['name'][$i];
            self::$__type = $files['type'][$i];
            self::$__tmp_name = $files['tmp_name'][$i];
            self::$__size = $files['size'][$i];
            self::__finish();
        }
    }

    /**
     * getUploadedName method
     * @return string|null
     * */
    public function getUploadedName()
    {
        return @self::$__name;
    }

    /**
     * getUploadedNames method
     * @return array|null
     * */
    public function getUploadedNames()
    {
        return @self::$__multiNames;
    }

    /**
     * getError method
     * @return false|array
     * */
    public function getError()
    {
        $messages = Engine::loadLang('upload');

        if (is_array(self::$__error)) {
            foreach (self::$__error as $key => $value) {
                if (array_key_exists($key, $messages)) {
                    self::$__error[$key] = str_replace("{allowed}", $value, $messages[$key]);
                }
            }
        }

        return self::$__error;
    }

    /**
     * isFile method
     * @param string $key
     * @return boolean
     * */
    private function __isFile($key)
    {
        if (isset($_FILES[$key]) && $_FILES[$key]['size'] > 0)
            return $_FILES[$key];

        self::$__error['fileInputMissed'] = $key;
        return false;
    }

    /**
     * finish method
     * @return void
     * */
    private function __finish()
    {
        if (self::__validate() && self::$__error === false) {
            self::$__name = (self::$UC->isRandomName()) ? Generate::token(9) . "_AYCEQART.AM_" . uniqid() . '.' . self::$__ext : self::$__name;

            if (!is_dir(self::$UC->getUploadPath())) {
                self::$__error['invalidUploadPath'] = self::$UC->getUploadPath();
            }

            if (move_uploaded_file(self::$__tmp_name, self::$UC->getUploadPath() . self::$__name))
                self::$__multiNames[] = self::$__name;
             else
                self::$__error['canNotUploadFile'] = '';
        }
    }

    /**
     * validate method
     * @return bool
     * */
    private function __validate()
    {
        $sizeDetails = @getimagesize(self::$__tmp_name);
        /**
         * @functionality check upload file width
         * */
        if (intval($sizeDetails[0]) > self::$UC->getMaxWidth())
            self::$__error['invalidImageWidth'] = self::$UC->getMaxWidth();
        /**
         * @functionality check upload file height
         * */
        if (intval($sizeDetails[1]) > self::$UC->getMaxHeight())
            self::$__error['invalidImageHeight'] = self::$UC->getMaxHeight();
        /**
         * @functionality check upload file size
         * */
        if (self::$__size > self::$UC->getMaxSize())
            self::$__error['invalidImageSize'] = self::$UC->getMaxSize();
        /**
         * @functionality check upload file type
         * */
        self::$__ext = pathinfo(self::$__name, PATHINFO_EXTENSION);

        if (!in_array(strtolower(self::$__ext), self::$UC->getAllowedTypes()))
            self::$__error['invalidImageType'] = implode(",", self::$UC->getAllowedTypes());

        return true;
    }
}