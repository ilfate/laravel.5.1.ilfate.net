<?php
/**
 * TODO: Package description.
 *
 * PHP version 5
 *
 * @category
 * @package
 * @author    Ilya Rubinchik <ilfate@gmail.com>
 *
 * @license   Proprietary license.
 * @link      http://ilfate.net
 */
namespace Ilfate\MageSurvival\Mages;
use Ilfate\MageSurvival\Game;
use Ilfate\MageSurvival\GameBuilder;
use Ilfate\MageSurvival\Generators\WorldGeneratorTutorial;
use Ilfate\MageSurvival\Mage;
use Ilfate\MageSurvival\MessageException;
use Ilfate\MageSurvival\Spell;

/**
 *
 * PHP version 5
 *
 * @category
 * @package
 * @author    Ilya Rubinchik <ilfate@gmail.com>
 *
 * @license   Proprietary license.
 * @link      http://ilfate.net
 */
class Wizard extends Mage
{
    const DEFAULT_MAX_HEALTH = 30;

    protected function initMage()
    {
        parent::initMage();
        $this->addItems([1 => 10, 2 => 10, 3 => 10, 4 => 8, 5 => 8, 6 => 7, 7 => 6, 8 => 5, 9 => 4,
                         10 => 3,
                         11 => 2,
                         12 => 1,
        ]);

    }

    

}