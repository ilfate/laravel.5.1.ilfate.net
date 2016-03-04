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
namespace Ilfate\MageSurvival;

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
abstract class Mage
{
    /**
     * @var \Ilfate\Mage
     */
    protected $mageEntity;

    protected $x;
    protected $y;
    protected $d;
    protected $data;
    protected $items;
    protected $spells = [];

    /**
     * @var Game
     */
    protected $game;

    protected $isUpdated = false;
    protected $config;
    protected $itemsChanges;
    protected $spellsChanges;

    public function __construct(\Ilfate\Mage $mageEntity)
    {
        $this->config = \Config::get('mageSurvival');
        $this->mageEntity = $mageEntity;
        $this->data = json_decode($mageEntity->data, true);
        $this->items = json_decode($mageEntity->items, true);
        $this->spells = json_decode($mageEntity->spells, true);
        if (isset($this->data['x'])) {
            $this->x = $this->data['x'];
        }
        if (isset($this->data['y'])) {
            $this->y = $this->data['y'];
        }
        if (isset($this->data['d'])) {
            $this->d = $this->data['d'];
        }
    }

    public function viewExport()
    {
        $data = [
            'd' => $this->getD(),
            'items' => $this->exportItems(),
            'spells' => $this->exportSpells(),
        ];
        return $data;
    }

    protected function exportItems()
    {
        $return = [];
        if (!$this->items) {
            return [];
        }
        foreach ($this->items as $itemId => $quantity) {
            $itemConfig = $this->config['items'][$itemId];
            $type = $itemConfig['type'];
            $itemConfig['quantity'] = $quantity;
            $return[$type][$itemId] = $itemConfig;
        }
        return $return;
    }

    public function getUpdatedItems()
    {
        $return = [];
        foreach ($this->itemsChanges as $itemId => $quantity) {
            $itemConfig = $this->config['items'][$itemId];
            $type = $itemConfig['type'];
            $itemConfig['quantity'] = $quantity;
            $return[$type][$itemId] = $itemConfig;
        }
        return $return;
    }

    protected function exportSpells()
    {
        $return = [];
        if (!$this->spells) {
            return [];
        }
        $spellsViewData = \Config::get('mageSpells.list');
        $spellsPatterns = \Config::get('mageSpellPatterns.list');
        foreach ($this->spells as $spellId => $spell) {

            list($name, $schoolId, $level) = explode('#', $spell['code']);
            $return[$schoolId][$spellId] = [
                'name' => $name,
                'schoolId' => $schoolId,
                'level' => $level,
                'config' => $spell['config'],
                'viewData' => $spellsViewData[$name],
            ];
            if (!empty($spell['config']['pattern'])) {
                $return[$schoolId][$spellId]['pattern'] = $spellsPatterns[$spell['config']['pattern']];
            }
        }
        return $return;
    }

    public function getUpdatedSpells()
    {
        $return = [];
        if (empty($this->spellsChanges)) {
            return $return;
        }
        $spellsViewData = \Config::get('mageSpells.list');
        foreach ($this->spellsChanges as $spellId => $spell) {
            list($name, $schoolId, $level) = explode('#', $spell['code']);
            $return[$schoolId][$spellId] = [
                'name' => $name,
                'schoolId' => $schoolId,
                'level' => $level,
                'config' => $spell['config'],
                'viewData' => $spellsViewData[$name],
                'status' => $spell['status']
            ];
        }
        return $return;
    }

    public function move($data) {
        $return = [];
        $radius = $this->game->getConfig()['game']['screen-radius'];
        switch ($this->getD()) {
            case 0: $this->y -= 1;
                for ($x = -$radius; $x <= $radius; $x++) {
                    $return['map'][$this->getY() - $radius][$this->getX() + $x] = false;
                }
                break;
            case 1: $this->x += 1;
                for ($y = -$radius; $y <= $radius; $y++) {
                    $return['map'][$this->getY() + $y][$this->getX() + $radius] = false;
                }
                break;
            case 2: $this->y += 1;
                for ($x = -$radius; $x <= $radius; $x++) {
                    $return['map'][$this->getY() + $radius][$this->getX() + $x] = false;
                }
                break;
            case 3: $this->x -= 1;
                for ($y = -$radius; $y <= $radius; $y++) {
                    $return['map'][$this->getY() + $y][$this->getX() - $radius] = false;
                }
                break;
        }
        $this->save();
        $return['mage']['d'] = $this->getD();
        return $return;
    }

    public function rotate($data)
    {
        $return = ['oldD' => $this->getD()];
        if (empty($data['d'])) {
            throw new \Exception('Rotation without direction is not possible');
        }
        switch ($data['d']) {
            case 'right':
                $this->d += 1;
                if ($this->d > 3) $this->d = 0;
                break;
            case 'left':
                $this->d -= 1;
                if ($this->d < 0) $this->d = 3;
                break;
            default:
                throw new \Exception('ROtation direction is wrong');
        }
        $this->save();
        $this->game->updateMage();
        $return['d'] = $this->getD();
        return $return;
    }

    public function craftSpell($data)
    {
        $carrierId = $data['carrier'];
        $itemIds = $data['ingredients'];
        if (empty($this->items[$carrierId])) {
            throw new \Exception('Item for crafting is missing');
        }
        $this->addItem($carrierId, -1);
        foreach ($itemIds as $itemId) {
            if (empty($this->items[$itemId])) {
                throw new MessageException('Item for crafting is missing');
            }
            $this->addItem($itemId, -1);
        }
        // ok we spend items let`s get the spell
        $result = Spell::craftSpellFromItems($carrierId, $itemIds);
        if (!empty($result['spell'])) {
            $this->addSpell($result['spell']);
        }

        if ($this->isUpdated) {
            $this->save();
        }
    }

    public function castSpell($data)
    {
        if (empty($data['id'])) {
            throw new \Exception('Spell Id is wrong');
        }
        $spellId = $data['id'];
        if (empty($this->spells[$spellId])) {
            throw new \Exception('Spell not found');
        }
        $spellData = $this->spells[$spellId];
        $spell = Spell::createSpellByCode(
            $spellData['code'],
            $spellData['config'],
            $spellId,
            $this->game,
            $this->game->getWorld(),
            $this
        );

        $spell->cast($data);

        if ($this->isUpdated) {
            $this->save();
        }
    }

    public function updateSpell(Spell $spell)
    {
        $exported = $spell->export();
        if ($spell->getUsages() < 1) {
            unset($this->spells[$spell->getId()]);
            $exported['status'] = 'deleted';
        } else {
            $this->spells[$spell->getId()] = $exported;
            $exported['status'] = 'updated';
        }
        $this->spellsChanges[$spell->getId()] = $exported;
        $this->game->setIsSpellsUpdated(true);
        $this->isUpdated = true;
    }

    public function getAllPossibleActions(World $world)
    {
        $object = $world->getObject($this->getX(), $this->getY());
        if ($object) {
            return $object->getActions();
        }
        return [];
    }

    public function interactWithObject(World $world, $method)
    {
        $object = $world->getObject($this->getX(), $this->getY());
        if ($object && method_exists($object, $method)) {
            $result = $object->$method($this);
        } else {
            throw new \Exception('No object was found to interact with');
        }
        if ($this->isUpdated) {
            $this->save();
        }
        return $result;
    }

    public function addItem($itemId, $quantity = 1)
    {
        if (isset($this->items[$itemId])) {
            $this->items[$itemId] += $quantity;
        } else {
            $this->items[$itemId] = $quantity;
        }
        if ($this->items[$itemId] < 0) {
            throw new MessageException('Wrong item used');
        }
        if (isset($this->itemsChanges[$itemId])) {
            $this->itemsChanges[$itemId] += $quantity;
        } else {
            $this->itemsChanges[$itemId] = $quantity;
        }

        $this->game->setIsItemsUpdated(true);
        if (!$this->items[$itemId]) {
            unset($this->items[$itemId]);
        }

        $this->isUpdated = true;
    }

    public function addSpell(Spell $spell)
    {
        $spell = $spell->export();
        $spellId = $spell['id'];
        unset($spell['id']);
        $this->spells[$spellId] = $spell;

        $spell['status'] = 'new';
        $this->spellsChanges[$spellId] = $spell;
        $this->game->setIsSpellsUpdated(true);
    }

    public function save()
    {
        $data = $this->data;
        $data['x'] = $this->getX();
        $data['y'] = $this->getY();
        $data['d'] = $this->getD();
        $this->mageEntity->data = json_encode($data);
        $this->mageEntity->items = json_encode($this->items);
        $this->mageEntity->spells = json_encode($this->spells);
        $this->mageEntity->save();
    }

    /**
     * @return \Ilfate\Mage
     */
    public function getMageEntity()
    {
        return $this->mageEntity;
    }

    /**
     * @param \Ilfate\Mage $mageEntity
     */
    public function setMageEntity($mageEntity)
    {
        $this->mageEntity = $mageEntity;
    }

    /**
     * @return mixed
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @param mixed $x
     */
    public function setX($x)
    {
        $this->x = $x;
    }

    /**
     * @return mixed
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * @param mixed $y
     */
    public function setY($y)
    {
        $this->y = $y;
    }

    /**
     * @return mixed
     */
    public function getD()
    {
        return $this->d ?: 0;
    }

    /**
     * @param mixed $d
     */
    public function setD($d)
    {
        $this->d = $d;
    }

    /**
     * @return Game
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * @param Game $game
     */
    public function setGame($game)
    {
        $this->game = $game;
    }
}