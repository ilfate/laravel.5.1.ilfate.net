<?php
/**
 * ilfate.net
 * @autor Ilya Rubinchik ilfate@gmail.com
 * 2014
 */

namespace Ilfate\Helper;

class MetaTags
{

    const DEFAULT_PAGE = 'landing';

    protected static $pageName;
    protected static $tagsData = array();

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'metaTagsHelper';
    }

    public static function getTag($name)
    {
        if (!isset(self::$tagsData[$name])) {
            $page = self::getPageName();
            $configPath = 'header.metaTags.' . $page . '.' . $name;
            $fromConfig = \Config::get($configPath);
            if (!$fromConfig && $page != self::DEFAULT_PAGE) {
                $configPath = 'header.metaTags.' . self::DEFAULT_PAGE . '.' . $name;
                return \Config::get($configPath);
            }
            return $fromConfig;
        } else {
            return self::$tagsData[$name];
        }
    }

    public static function getPageName()
    {
        return self::$pageName ?: self::DEFAULT_PAGE;
    }

    /**
     * @param mixed $pageName
     */
    public static function setPageName($pageName)
    {
        self::$pageName = $pageName;
    }

} 