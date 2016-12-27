<?php
/**
 * TODO: Package description.
 *
 * PHP version 5
 *
 * @category
 * @package
 * @author    Ilya Rubinchik <ilfate@gmail.com>
 * @copyright 2016 Watchmaster GmbH
 * @license   Proprietary license.
 * @link      http://www.watchmaster.de
 */
namespace Ilfate\WhiteHorde;
use Illuminate\Support\Collection;

/**
 * TODO: Short description.
 * TODO: Long description here.
 *
 * PHP version 5
 *
 * @category
 * @package
 * @author    Ilya Rubinchik <ilfate@gmail.com>
 * @link      http://ilfate.net
 */
class WHTrait extends Collection
{
    const TYPE_POSITIVE = 'positive';
    const TYPE_NEGATIVE = 'negative';
    const TYPE_PROFESSION = 'profession';

    const STRONG = 'strong';
    const WEAK = 'weak';
    
    public static $fullList = [
        self::STRONG,
        self::WEAK,
    ];

    protected $name = '';

    protected static $positive = [
        self::STRONG,
    ];

    protected static $negative = [
        self::WEAK,
    ];
    protected static $profession = [

    ];
    
    

    /**
     *
     * @param  string $name
     * @return void
     */
    public function __construct($name)
    {
        $this->name = $name;
        
        
    }

    public static function isType($name, $type) {
        return in_array($name, self::$$type);
    }

}