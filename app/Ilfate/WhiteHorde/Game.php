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
use Ilfate\Settlement;
use Ilfate\User;
use Ilfate\WHCharacter;

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
class Game
{

    public function loadMainScreen()
    {
        $data = [];
        
        $data['settlement'] = WH::getOrCreateSettlement()->export();
        $data['characters'] = WH::exportAllCharacters();
        $data['buildings'] = WH::exportAllBuildings();

        return $data;
    }
}