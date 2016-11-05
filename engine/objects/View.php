<?php
/**
 * Created by PhpStorm.
 * User: Var Yan
 * Date: 5/28/2016
 * Time: 9:25 PM
 */

namespace engine\objects;

use engine\abstracts\ViewParent;
use engine\Engine;

/**
 * Class View
 * @package VS\MVC
 */
class View extends ViewParent
{
    /**
     * @var array $_sections
     */
    protected static $_sections = [];
    /**
     * @var string $activeSection
     */
    protected static $_activeSection = "";

    /**
     * @param string $sectionName
     */
    public function yield(string $sectionName)
    {
        echo @self::$_sections[$sectionName];
    }

    /**
     * @param string $parentViewName
     * @return void
     */
    public function extends(string $parentViewName)
    {
        $args = func_get_args();
        unset($args[0]);
        $args = (empty($args[1]) || !is_array($args[1]))
            ? self::$_loadVars
            : $args[1];

        @ob_start();
        extract($args);
        include RELEASE.Engine::release().DS."view".DS."{$parentViewName}".EXT;
        @ob_flush();
        @ob_end_clean();
    }

    /**
     * @param string $sectionName
     */
    public function section(string $sectionName)
    {
        self::$_activeSection = $sectionName;
        self::$_sections[$sectionName] = "";
        ob_start();
    }

    /**
     * @return void
     */
    public function endSection()
    {
        self::$_sections[self::$_activeSection] = ob_get_contents();
        @ob_end_clean();
    }
}