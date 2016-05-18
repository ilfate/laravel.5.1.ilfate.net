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
namespace Ilfate\MageSurvival\MapObjects;

use Ilfate\MageSurvival\Game;
use Ilfate\MageSurvival\GameBuilder;
use Ilfate\MageSurvival\Mage;
use Ilfate\MageSurvival\MapObject;
use Ilfate\MageSurvival\ChanceHelper;
use Ilfate\MageSurvival\World;

/**
 * TODO: Short description.
 * TODO: Long description here.
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
class IceWall extends MapObject
{

    protected $isPassable = false;

    public function __construct(World $world, $type, $x, $y, $id = null, $data = null)
    {
        parent::__construct($world, $type, $x, $y, $id, $data);
        if (!isset($this->data['turn'])) {
            $this->data['turn'] = GameBuilder::getGame()->getTurn() + 5;
        }
    }

    public function activate()
    {
        if ($this->data['turn'] == GameBuilder::getGame()->getTurn()) {
            $this->delete(Game::ANIMATION_STAGE_TURN_END_EFFECTS);
        }
    }
}