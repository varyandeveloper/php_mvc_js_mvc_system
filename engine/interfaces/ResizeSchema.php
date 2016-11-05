<?php
/**
 * Created by PhpStorm.
 * User: Var Yan
 * Date: 6/11/2016
 * Time: 7:05 PM
 */

namespace engine\interfaces;

/**
 * Interface ResizeSchema
 * @package engine\interfaces
 */
interface ResizeSchema extends EngineSchema
{
    const CROPTOP = 1;
    const CROPCENTRE = 2;
    const CROPCENTER = 2;
    const CROPBOTTOM = 3;
    const CROPLEFT = 4;
    const CROPRIGHT = 5;

    /**
     * @param string $image_data
     * @return ResizeSchema
     */
    public static function createFromString(string $image_data) : ResizeSchema;

    /**
     * @param string $filename
     * @return mixed
     */
    public function imageCreateJpegFromExif(string $filename);

    /**
     * @param string|null $filename
     * @param string|null $image_type
     * @param int|null $quality
     * @param int|null $permissions
     * @return ResizeSchema
     */
    public function save(string $filename = null, string $image_type = null, int $quality = null, int $permissions = null) : ResizeSchema;

    /**
     * @param string|null $image_type
     * @param int|null $quality
     * @return string
     */
    public function getImageAsString(string $image_type = null, int $quality = null) : string;

    /**
     * @param string|null $image_type
     * @param int|null $quality
     * @return void
     */
    public function output(string $image_type = null, int $quality = null);

    /**
     * @param float $height
     * @param bool $allow_enlarge
     * @return ResizeSchema
     */
    public function resizeToHeight(float $height, bool $allow_enlarge = false) : ResizeSchema;

    /**
     * @param float $width
     * @param bool $allow_enlarge
     * @return ResizeSchema
     */
    public function resizeToWidth(float $width, bool $allow_enlarge = false) : ResizeSchema;

    /**
     * @param float $max_width
     * @param float $max_height
     * @param bool $allow_enlarge
     * @return ResizeSchema
     */
    public function resizeToBestFit(float $max_width, float $max_height, bool $allow_enlarge = false) : ResizeSchema;

    /**
     * @param float $scale
     * @return ResizeSchema
     */
    public function scale(float $scale) : ResizeSchema;

    /**
     * @param float $width
     * @param float $height
     * @param bool $allow_enlarge
     * @return ResizeSchema
     */
    public function resize(float $width, float $height, bool $allow_enlarge = false) : ResizeSchema;

    /**
     * @param float $width
     * @param float $height
     * @param bool $allow_enlarge
     * @param int $position
     * @return ResizeSchema
     */
    public function crop(float $width, float $height, bool $allow_enlarge = false, int $position = self::CROPCENTER) : ResizeSchema;
}