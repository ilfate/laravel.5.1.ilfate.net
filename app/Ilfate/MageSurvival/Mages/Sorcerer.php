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
class Sorcerer extends Mage
{

    public function viewExport()
    {
        $data = parent::viewExport(); // TODO: Change the autogenerated stub
        if (!empty($this->data['tutorial'])) {
            $game = GameBuilder::getGame();
            if ($game->getStatus() == Game::STATUS_BATTLE && $game->getTurn() == 0 && $this->game->getWorldGenerator() instanceof WorldGeneratorTutorial) {

                $data['firstTutorial'] = true;
            }
        }
        return $data;
    }
    
    public function craftSpell($data)
    {
        if (GameBuilder::getWorldGenerator() instanceof WorldGeneratorTutorial) {
            if (!GameBuilder::getGame()->getMage()->getStat(Mage::STAT_KEY_SPELL_CRAFTED)) {
                $itemIds = $data['ingredients'];
                foreach ($itemIds as $itemId) {
                    if (empty($this->items[$itemId])) {
                        throw new MessageException('Item for crafting is missing');
                    }
                    $this->addItem($itemId, -1);
                }
                $class = Spell::getSpellClass('fire', 'Fireball');
                if (!class_exists($class)) {
                    throw new \Exception('Spell with name "Fireball" not found at "' . $class . '"' );
                }
                /**
                 * @var Spell $spell
                 */
                // 0 is Fireball
                // 1 is Fire school
                $spell = new $class(0, 1, ['usages' => 1]);
                $spell->generateCoolDown();
                $this->addSpell($spell);
                $this->addStat(Mage::STAT_KEY_SPELL_CRAFTED);
                $this->save();

                GameBuilder::getGame()->addAnimationEvent(Game::EVENT_NAME_SPELL_CRAFT, [
                    'spell' => $spell->getId()
                ], Game::ANIMATION_STAGE_MAGE_ACTION);
                return;
            }
        }
        parent::craftSpell($data); // TODO: Change the autogenerated stub
    }
}