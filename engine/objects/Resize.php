<?php
/**
 * Created by PhpStorm.
 * User: VarYan
 * Date: 07.02.2016
 * Time: 17:09
 */

namespace engine\objects;

use engine\config\ResizeConfig;
use engine\interfaces\ResizeSchema;

/**
 * Class Resize
 * @package engine\objects
 */
class Resize implements ResizeSchema
{
    /**\
     * @var int $quality_jpg
     */
    public $quality_jpg = 100;
    /**
     * @var int $quality_png
     */
    public $quality_png = 0;
    /**
     * @var int $interlace
     */
    public $interlace = 0;
    /**
     * @var $source_type
     */
    public $source_type;
    /**
     * @var $source_image
     */
    protected $_source_image;
    /**
     * @var float $dest_x
     */
    protected $_dest_x = 0;
    /**
     * @var float $dest_y
     */
    protected $_dest_y = 0;
    /**
     * @var float $original_h
     */
    protected $_original_h;
    /**
     * @var float $original_w
     */
    protected $original_w;
    /**
     * @var float $dest_h
     */
    protected $dest_h = 0;
    /**
     * @var float $dest_w
     */
    protected $dest_w = 0;
    /**
     * @var $source_x
     */
    protected $source_x;
    /**
     * @var $source_y
     */
    protected $source_y;
    /**
     * @var $source_w
     */
    protected $source_w;
    /**
     * @var $source_h
     */
    protected $source_h;
    /**
     * @var string $filename
     */
    private $filename;

    /**
     * Loads image source and its properties to the instanciated object
     * @param string $filename
     * @throws \Exception
     */
    public function __construct($filename)
    {
        $this->filename = $filename;
        $image_info = @getimagesize($filename);
        if (!$image_info) {
            throw new \Exception('Could not read file');
        }
        list (
            $this->original_w,
            $this->_original_h,
            $this->source_type
            ) = $image_info;
        switch ($this->source_type) {
            case IMAGETYPE_GIF:
                $this->_source_image = imagecreatefromgif($filename);
                break;
            case IMAGETYPE_JPEG:
                $this->_source_image = $this->imageCreateJpegFromExif($filename);

                // set new width and height for image, maybe it has changed
                $this->original_w = imagesx($this->_source_image);
                $this->_original_h = imagesy($this->_source_image);

                break;
            case IMAGETYPE_PNG:
                $this->_source_image = imagecreatefrompng($filename);
                break;
            default:
                throw new \Exception('Unsupported image type');
                break;
        }
        return $this->resize(ResizeConfig::getOriginalW(), ResizeConfig::getOriginalH());
    }

    /**
     * @param string $image_data
     * @return ResizeSchema
     */
    public static function createFromString(string $image_data) : ResizeSchema
    {
        $resize = new self('data://application/octet-stream;base64,' . base64_encode($image_data));
        return $resize;
    }

    /**
     * @param string $filename
     * @return mixed
     */
    public function imageCreateJpegFromExif(string $filename)
    {
        $img = imagecreatefromjpeg($filename);
        $exif = exif_read_data($filename);

        if (!$exif || !isset($exif['Orientation'])) {
            return $img;
        }

        $orientation = $exif['Orientation'];
        if ($orientation === 6 || $orientation === 5) {
            $img = imagerotate($img, 270, null);
        } else if ($orientation === 3 || $orientation === 4) {
            $img = imagerotate($img, 180, null);
        } else if ($orientation === 8 || $orientation === 7) {
            $img = imagerotate($img, 90, null);
        }
        if ($orientation === 5 || $orientation === 4 || $orientation === 7) {
            imageflip($img, IMG_FLIP_HORIZONTAL);
        }

        return $img;
    }

    /**
     * @param string|null $filename
     * @param string|null $image_type
     * @param int|null $quality
     * @param int|null $permissions
     * @return ResizeSchema
     */
    public function save(string $filename = null, string $image_type = null, int $quality = null, int $permissions = null) : ResizeSchema
    {
        $this->filename = !is_null($filename) ? $filename : $this->filename;

        $image_type = $image_type ?: $this->source_type;
        $dest_image = imagecreatetruecolor(ResizeConfig::getDestW(), ResizeConfig::getDestH());
        imageinterlace($dest_image, $this->interlace);
        switch ($image_type) {
            case IMAGETYPE_GIF:
                $background = imagecolorallocatealpha($dest_image, 255, 255, 255, 1);
                imagecolortransparent($dest_image, $background);
                imagefill($dest_image, 0, 0, $background);
                imagesavealpha($dest_image, true);
                break;
            case IMAGETYPE_JPEG:
                $background = imagecolorallocate($dest_image, 255, 255, 255);
                imagefilledrectangle($dest_image, 0, 0, ResizeConfig::getDestW(), ResizeConfig::getDestH(), $background);
                break;
            case IMAGETYPE_PNG:
                imagealphablending($dest_image, false);
                imagesavealpha($dest_image, true);
                break;
        }
        imagecopyresampled(
            $dest_image,
            $this->_source_image,
            $this->_dest_x,
            $this->_dest_y,
            $this->source_x,
            $this->source_y,
            ResizeConfig::getDestW(),
            ResizeConfig::getDestH(),
            $this->source_w,
            $this->source_h
        );
        switch ($image_type) {
            case IMAGETYPE_GIF:
                imagegif($dest_image, $filename);
                break;
            case IMAGETYPE_JPEG:
                if ($quality === null) {
                    $quality = $this->quality_jpg;
                }
                imagejpeg($dest_image, $filename, $quality);
                break;
            case IMAGETYPE_PNG:
                if ($quality === null) {
                    $quality = $this->quality_png;
                }
                imagepng($dest_image, $filename, $quality);
                break;
        }
        if ($permissions) {
            chmod($filename, $permissions);
        }
        return $this;
    }

    /**
     * @param string|null $image_type
     * @param int|null $quality
     * @return string
     */
    public function getImageAsString(string $image_type = null, int $quality = null) : string
    {
        $string_temp = tempnam('', '');
        $this->save($string_temp, $image_type, $quality);
        $string = file_get_contents($string_temp);
        unlink($string_temp);
        return $string;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getImageAsString();
    }

    /**
     * @param string|null $image_type
     * @param int|null $quality
     * @return void
     */
    public function output(string $image_type = null, int $quality = null)
    {
        $image_type = $image_type ?: $this->source_type;
        header('Content-Type: ' . image_type_to_mime_type($image_type));
        $this->save(null, $image_type, $quality);
    }

    /**
     * @param float $height
     * @param bool $allow_enlarge
     * @return ResizeSchema
     */
    public function resizeToHeight(float $height, bool $allow_enlarge = false) : ResizeSchema
    {
        $ratio = $height / ResizeConfig::getOriginalH();
        $width = ResizeConfig::getOriginalW() * $ratio;
        $this->resize($width, $height, $allow_enlarge);
        return $this;
    }

    /**
     * @param float $width
     * @param bool $allow_enlarge
     * @return ResizeSchema
     */
    public function resizeToWidth(float $width, bool $allow_enlarge = false) : ResizeSchema
    {
        $ratio = $width / ResizeConfig::getOriginalW();
        $height = ResizeConfig::getOriginalH() * $ratio;
        $this->resize($width, $height, $allow_enlarge);
        return $this;
    }

    /**
     * @param float $max_width
     * @param float $max_height
     * @param bool $allow_enlarge
     * @return ResizeSchema
     */
    public function resizeToBestFit(float $max_width, float $max_height, bool $allow_enlarge = false) : ResizeSchema
    {
        if (ResizeConfig::getOriginalW() <= $max_width && ResizeConfig::getOriginalH() <= $max_height && $allow_enlarge === false) {
            return $this;
        }
        $ratio = ResizeConfig::getOriginalH() / ResizeConfig::getOriginalW();
        $width = $max_width;
        $height = $width * $ratio;
        if ($height > $max_height) {
            $height = $max_height;
            $width = $height / $ratio;
        }
        return $this->resize($width, $height, $allow_enlarge);
    }

    /**
     * @param float $scale
     * @return ResizeSchema
     */
    public function scale(float $scale) : ResizeSchema
    {
        $width = ResizeConfig::getOriginalW() * $scale / 100;
        $height = ResizeConfig::getOriginalH() * $scale / 100;
        $this->resize($width, $height, true);
        return $this;
    }

    /**
     * @param float $width
     * @param float $height
     * @param bool $allow_enlarge
     * @return ResizeSchema
     */
    public function resize(float $width, float $height, bool $allow_enlarge = false) : ResizeSchema
    {
        if (!$allow_enlarge) {
            // if the user hasn't explicitly allowed enlarging,
            // but either of the dimensions are larger then the original,
            // then just use original dimensions - this logic may need rethinking
            if ($width > ResizeConfig::getOriginalW() || $height > ResizeConfig::getOriginalH()) {
                $width = ResizeConfig::getOriginalW();
                $height = ResizeConfig::getOriginalH();
            }
        }
        $this->source_x = 0;
        $this->source_y = 0;
        $this->dest_w = $width;
        $this->dest_h = $height;
        $this->source_w = ResizeConfig::getOriginalW();
        $this->source_h = ResizeConfig::getOriginalH();
        return $this;
    }

    /**
     * @param float $width
     * @param float $height
     * @param bool $allow_enlarge
     * @param int $position
     * @return ResizeSchema
     */
    public function crop(float $width, float $height, bool $allow_enlarge = false, int $position = self::CROPCENTER) : ResizeSchema
    {
        if (!$allow_enlarge) {
            // this logic is slightly different to resize(),
            // it will only reset dimensions to the original
            // if that particular dimenstion is larger
            if ($width > ResizeConfig::getOriginalW()) {
                $width = ResizeConfig::getOriginalW();
            }
            if ($height > ResizeConfig::getOriginalH()) {
                $height = ResizeConfig::getOriginalH();
            }
        }
        $ratio_source = ResizeConfig::getOriginalW() / ResizeConfig::getOriginalH();
        $ratio_dest = $width / $height;
        if ($ratio_dest < $ratio_source) {
            $this->resizeToHeight($height, $allow_enlarge);
            $excess_width = (ResizeConfig::getDestW() - $width) / ResizeConfig::getDestW() * ResizeConfig::getOriginalW();
            $this->source_w = ResizeConfig::getOriginalW() - $excess_width;
            $this->source_x = $this->_getCropPosition($excess_width, $position);
            $this->dest_w = $width;
        } else {
            $this->resizeToWidth($width, $allow_enlarge);
            $excess_height = (ResizeConfig::getDestH() - $height) / ResizeConfig::getDestH() * ResizeConfig::getOriginalH();
            $this->source_h = ResizeConfig::getOriginalH() - $excess_height;
            $this->source_y = $this->_getCropPosition($excess_height, $position);
            $this->dest_h = $height;
        }
        return $this;
    }

    /**
     * Gets crop position (X or Y) according to the given position
     *
     * @param integer $expectedSize
     * @param integer $position
     * @return integer
     */
    protected function _getCropPosition($expectedSize, $position = self::CROPCENTER)
    {
        $size = 0;
        switch ($position) {
            case self::CROPBOTTOM:
            case self::CROPRIGHT:
                $size = $expectedSize;
                break;
            case self::CROPCENTER:
            case self::CROPCENTRE:
                $size = $expectedSize / 2;
                break;
        }
        return $size;
    }
}