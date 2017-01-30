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
use Ilfate\MageSurvival\ChanceHelper;
use Illuminate\Support\Collection;

/**
 * TODO: Short description.
 * TODO: Long description here.
 *
 * PHP version 5
 *
 * @method wasUpdated
 *
 * @category
 * @package
 * @author    Ilya Rubinchik <ilfate@gmail.com>
 * @link      http://ilfate.net
 */
trait ItemsStorageTrait
{
    public function getInventory()
    {
        if (is_array($this->inventory)) {
            return $this->inventory;
        }
        if ($this->inventory) {
            $this->inventory = json_decode($this->inventory, true);
        }
        return $this->inventory;
    }


    public function addItem($itemName, $quantity = 1)
    {
        $inventory = $this->getInventory();
        if (!empty($inventory[$itemName])) {
            $inventory[$itemName] += $quantity;
        } else {
            $inventory[$itemName] = $quantity;
        }
        $this->inventory = $inventory;
        $this->wasUpdated();
    }

    public function hasItem($itemName)
    {
        $inventory = $this->getInventory();
        return !empty($inventory[$itemName]);
    }

    public function removeItem($itemName, $quantity = 1)
    {
        $inventory = $this->getInventory();
        if (!empty($inventory[$itemName]) || $inventory[$itemName] - $quantity < 0) {
            $inventory[$itemName] -= $quantity;
        } else {
            throw new \Exception('There is no item ' . $itemName . ' in settlement inventory');
        }
        $this->inventory = $inventory;
        $this->wasUpdated();
    }

}