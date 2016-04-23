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
class DoorQuest extends MapObject
{

    protected $isPassable = false;

    public function __construct(World $world, $type, $x, $y, $id = null, $data = null)
    {
        parent::__construct($world, $type, $x, $y, $id, $data);
        if (!isset($this->data['open'])) {
            $this->data['open'] = false;
        }
        $this->isPassable = $this->data['open'];
        if ($this->data['open']) {
            if (empty($this->viewData['class'])) {
                $this->viewData['class'] = '';
            }
            $this->viewData['class'] .= 'openDoor';
        }
    }

    public function open()
    {
        $this->data['open'] = true;
        $this->isPassable = true;
        GameBuilder::getGame()->addAnimationEvent(Game::EVENT_NAME_OBJECT_ACTIVATE, [
            'object' => $this->getId(), 'action' => 'doorOpen'
        ], Game::ANIMATION_STAGE_UNIT_ACTION);
    }

    public function close()
    {
        $this->data['open'] = false;
        $this->isPassable = false;
        GameBuilder::getGame()->addAnimationEvent(Game::EVENT_NAME_OBJECT_ACTIVATE, [
            'object' => $this->getId(), 'action' => 'doorClose'
        ], Game::ANIMATION_STAGE_UNIT_ACTION);
    }
}