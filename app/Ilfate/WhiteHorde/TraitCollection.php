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
class TraitCollection
{

    protected $traits;

    /**
     * Create a new collection.
     *
     * @param  mixed  $traits
     * @return void
     */
    public function __construct($traits = [])
    {
        $this->traits = is_array($traits) ? $traits : [];
    }

    public function push($trait, $value = 0)
    {
        $this->traits[$trait] = $value;
    }

    public function contains($trait)
    {
        return isset($this->traits[$trait]);
    }

    public function toArray()
    {
        return $this->traits;
    }

    public function export()
    {
        $export = [];
        foreach ($this->traits as $trait => $value) {
            $export[] = [$trait, $value];
        }
        return $export;
    }

    public function remove($trait)
    {
        unset($this->traits[$trait]);
    }

    public function getByType($type) {
//        return $this->filter(function ($value, $key) use ($type) {
//            return WHTrait::isType($value, $type);
//        });
        $return = [];
        foreach ($this->traits as $trait => $value) {

            if (WHTrait::isType($trait, $type)) {
                $return[$trait] = $value;
            }
        }
        return $return;
    }

}