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
class TraitCollection extends Collection
{
    /**
     * Create a new collection.
     *
     * @param  mixed  $items
     * @return void
     */
    public function __construct($items = [])
    {
        $this->items = is_array($items) ? $items : $this->getArrayableItems($items);
        
        
    }

    public function getByType($type) {
        return $this->filter(function ($value, $key) use ($type) {
            return WHTrait::isType($value, $type);
        });
    }

}