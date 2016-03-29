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
abstract class Mage implements AliveInterface
{
    const DEFAULT_MAX_HEALTH = 100;
    /**
     * @var \Ilfate\Mage
     */
    protected $mageEntity;

    protected $x;
    protected $y;
    protected $d;
    protected $health;
    protected $armor = 0;
    protected $maxHealth;
    protected $was = [];
    protected $data;
    protected $items;
    protected $turn;
    protected $spells = [];

    /**
     * @var Game
     */
    protected $game;

    protected $isUpdated = false;
    protected $config;
    protected $itemsChanges;
    protected $spellsChanges;
    protected $events = [];

    public function __construct(\Ilfate\Mage $mageEntity)
    {
        $this->config = \Config::get('mageSurvival');
        $this->mageEntity = $mageEntity;
        $this->data = json_decode($mageEntity->data, true);
        $this->items = json_decode($mageEntity->items, true);
        $this->spells = json_decode($mageEntity->spells, true);
        Event::import(json_decode($mageEntity->events, true));
        $this->turn = $mageEntity->turn;
        if (isset($this->data['x'])) {
            $this->x = $this->data['x'];
            $this->was['x'] = $this->data['x'];
        }
        if (isset($this->data['y'])) {
            $this->y = $this->data['y'];
            $this->was['y'] = $this->data['y'];
        }
        if (isset($this->data['d'])) {
            $this->d = $this->data['d'];
            $this->was['d'] = $this->data['d'];
        }
        if (isset($this->data['health'])) {
            $this->health = $this->data['health'];
            $this->was['health'] = $this->data['health'];
            if (empty($this->data['maxHealth'])) {
                $this->maxHealth = static::DEFAULT_MAX_HEALTH;
                $this->was['maxHealth'] = static::DEFAULT_MAX_HEALTH;
            } else {
                $this->maxHealth = $this->data['maxHealth'];
                $this->was['maxHealth'] = $this->data['maxHealth'];
            }
            if (!empty($this->data['armor'])) {
                $this->armor = $this->data['armor'];
            }
        } else {
            $this->health = static::DEFAULT_MAX_HEALTH;
            $this->maxHealth = static::DEFAULT_MAX_HEALTH;
            $this->was['health'] = static::DEFAULT_MAX_HEALTH;
            $this->was['maxHealth'] = static::DEFAULT_MAX_HEALTH;
        }
    }

    public function save()
    {
        $data = $this->data;
        $data['x'] = $this->getX();
        $data['y'] = $this->getY();
        $data['d'] = $this->getD();
        $data['health'] = $this->getHealth();
        $data['armor'] = $this->getArmor();
        $data['maxHealth'] = $this->maxHealth;
        $this->mageEntity->data   = json_encode($data);
        $this->mageEntity->items  = json_encode($this->items);
        $this->mageEntity->spells = json_encode($this->spells);
        $this->mageEntity->turn   = $this->turn;
        $this->mageEntity->save();
    }

    public function viewExport()
    {
        $data = [
            'd' => $this->getD(),
            'health' => $this->getHealth(),
            'maxHealth' => $this->maxHealth,
            'armor' => $this->getArmor(),
            'items' => $this->exportItems(),
            'spells' => $this->exportSpells(),
            'spellSchools' => $this->exportSchools(),
        ];
        return $data;
    }

    public function exportMage()
    {
        $data = [
            'd' => $this->getD(),
            'x' => $this->getX(),
            'y' => $this->getY(),
            'health' => $this->getHealth(),
            'armor' => $this->getArmor(),
            'maxHealth' => $this->maxHealth,
            'was' => $this->was,
        ];
        return $data;
    }

    public function exportMageHealth($value) {
        return [
            'health' => $this->getHealth(),
            'maxHealth' => $this->maxHealth,
            'armor' => $this->getArmor(),
            'value' => $value
        ];
    }

    protected function exportItems()
    {
        $return = [];
        if (!$this->items) {
            return [];
        }
        foreach ($this->items as $itemId => $quantity) {
            $itemConfig = $this->config['items'][$itemId];
            $itemConfig['quantity'] = $quantity;
            $itemConfig['id'] = $itemId;
            $return[$itemId] = $itemConfig;
        }
        return $return;
    }

    public function getUpdatedItems()
    {
        $return = [];
        foreach ($this->itemsChanges as $itemId => $quantity) {
            $itemConfig = $this->config['items'][$itemId];
            $itemConfig['quantity'] = $quantity;
            $itemConfig['id'] = $itemId;
            $return[$itemId] = $itemConfig;
        }
        return $return;
    }

    protected function exportSpells()
    {
        $return = [];
        if (!$this->spells) {
            return [];
        }
        $spellsViewData = \Config::get('mageSpells');
        $spellsPatterns = \Config::get('mageSpellPatterns.list');
        //$turn = $this->getTurn();
        foreach ($this->spells as $spellId => $spell) {

            //$cooldownMark = $spell['config'][Spell::CONFIG_FIELD_COOLDOWN_MARK];
            //$spell['config'][Spell::CONFIG_FIELD_COOLDOWN_MARK] = $cooldownMark - $turn;
            list($name, $schoolId, $level) = explode('#', $spell['code']);
            $return[$spellId] = [
                'id' => $spellId,
                'name' => $name,
                'schoolId' => $schoolId,
                'level' => $level,
                'config' => $spell['config'],
                'viewData' => $spellsViewData['list'][$schoolId][$level][$name],
            ];
            if (!empty($spell['config']['pattern'])) {
                $return[$spellId]['pattern'] = $spellsPatterns[$spell['config']['pattern']];
            }
        }
        return $return;
    }

    protected function exportSchools()
    {
        if (!$this->spells) {
            return [];
        }
        $return = [];
        $spellsViewData = \Config::get('mageSpells.schools');
        foreach ($this->spells as $spellId => $spell) {
            list($name, $schoolId, $level) = explode('#', $spell['code']);
            if (empty($return[$schoolId])) {
                $return[$schoolId] = $spellsViewData[$schoolId];
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
        $schoolsViewData = \Config::get('mageSpells.schools');
        $spellsPatterns = \Config::get('mageSpellPatterns.list');
        //$turn = $this->getTurn();
        foreach ($this->spellsChanges as $spellId => $spell) {
            //$cooldownMark = $spell['config'][Spell::CONFIG_FIELD_COOLDOWN_MARK];
            //$spell['config'][Spell::CONFIG_FIELD_COOLDOWN_MARK] = $cooldownMark - $turn;
            list($name, $schoolId, $level) = explode('#', $spell['code']);
            $return[$spellId] = [
                'id' => $spellId,
                'name' => $name,
                'schoolId' => $schoolId,
                'level' => $level,
                'config' => $spell['config'],
                'viewData' => $spellsViewData[$schoolId][$level][$name],
                'status' => $spell['status']
            ];
            if ($spell['status'] == 'new') {
                $return[$spellId]['schoolViewData'] = $schoolsViewData[$schoolId];
            }
            if (!empty($spell['config']['pattern'])) {
                $return[$spellId]['pattern'] = $spellsPatterns[$spell['config']['pattern']];
            }
        }
        return $return;
    }

    public function moveAction($data) {
        if (!isset($data['d']) || $data['d'] > 3 || $data['d'] < 0 || !is_numeric($data['d'])) {
            throw new \Exception('For movement direction is missing');
        }
        $x = $this->getX();
        $y = $this->getY();
        switch ($data['d']) {
            case 0: $y -= 1;
                break;
            case 1: $x += 1;
                break;
            case 2: $y += 1;
                break;
            case 3: $x -= 1;
                break;
        }
        if (!$this->game->getWorld()->isPassable($x, $y)) {
            throw new MessageException('You can`t move in this direction');
        }
        $d = $this->getD();
        $stageForMove = Game::ANIMATION_STAGE_MAGE_ACTION;
        if ($data['d'] != $d) {
            // we need to rotate first
            $this->d = $data['d'];
            GameBuilder::animateEvent(Game::EVENT_NAME_MAGE_ROTATE, [
                'd' => (int) $this->d, 'wasD' => (int) $d
            ], Game::ANIMATION_STAGE_MAGE_ACTION);
            $stageForMove = Game::ANIMATION_STAGE_MAGE_ACTION_2;
        }

        $this->x = $x;
        $this->y = $y;
        $this->update();
        $this->game->setIsMageMoved();
        $this->game->addAnimationEvent('mage-move', [
            'mage' => $this->exportMage(),
            'map' => $this->game->getWorldGenerator()->exportMapForView($this),
            'objects' => $this->game->getWorldGenerator()->exportVisibleObjects(),
            'units' => $this->game->getWorldGenerator()->exportVisibleUnits(),
        ], $stageForMove);
    }

    public function rotate($d, $animationStage)
    {
        $wasD = $this->d;
        $this->d = $d;
        $this->update();
        $this->game->addAnimationEvent(Game::EVENT_NAME_MAGE_ROTATE, [
            'd' => (int) $this->d, 'wasD' => (int) $wasD
        ], $animationStage);
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
        $this->update();
    }

    public function getAllPossibleActions(World $world)
    {
        $actions = [];
        $object = $world->getObject($this->getX(), $this->getY());
        if ($object) {
            $actions = array_merge($actions, $object->getActions());
        }
        $ds = [
            [0, -1, 'icon-0'],
            [1, 0, 'icon-1'],
            [0, 1, 'icon-2'],
            [-1, 0, 'icon-3'],
        ];
        $passableDirections = [];
        foreach ($ds as $key => $d) {
            if ($this->game->getWorld()->isPassable($this->getX() + $d[0], $this->getY() + $d[1])) {
                $actions[] = [
                    'name' => $key,
                    'method' => 'move-' . $key,
                    'icon' => $d[2],
                    'location' => 'move-' . $key
                ];
            }
        }
        return $actions;
    }

    public function interactWithObject(World $world, $method)
    {
        $object = $world->getObject($this->getX(), $this->getY());
        if ($object && method_exists($object, $method)) {
            $result = $object->$method($this);
        } else {
            throw new \Exception('No object was found to interact with');
        }
        $this->update();
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
        $this->update();
    }

    public function saveIfUpdated()
    {
        if ($this->isUpdated) {
            $this->save();
        }
    }

    public function damage($value, $animationStage)
    {
        $eventData = Event::trigger(Event::EVENT_MAGE_BEFORE_GET_DAMAGE, ['value' => $value]);
        $value = $eventData['value'];
        if ($this->armor > 0) {

            //$value -= $this->armor;
            if ($value <= $this->armor)  {
                $this->armor -= $value;
                $value = 0;
            } else {
                $value -= $this->armor;
                $this->armor = 0;
            }
        }
        $this->health -= $value;
        GameBuilder::animateEvent(Game::EVENT_NAME_MAGE_DAMAGE, $this->exportMageHealth($eventData['value']), $animationStage);
    }

    public function heal($value, $animationStage)
    {
        $healthWas = $this->getHealth();
        $eventData = Event::trigger(Event::EVENT_MAGE_BEFORE_HEAL, ['value' => $value]);
        $value = $eventData['value'];
        $this->health += $value;
        if ($this->health > $this->maxHealth) {
            $this->health = $this->maxHealth;
            $value = $this->health - $healthWas;
        }
        GameBuilder::animateEvent(Game::EVENT_NAME_MAGE_HEAL, $this->exportMageHealth($value), $animationStage);
    }

    public function armor($value, $animationStage)
    {
        $this->armor += $value;
        if ($value > 0) {
            GameBuilder::animateEvent(Game::EVENT_NAME_MAGE_ADD_ARMOR, $this->exportMageHealth($value), $animationStage);
        }
    }

    public function update()
    {
        $this->isUpdated = true;
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

    /**
     * @return mixed
     */
    public function getTurn()
    {
        return $this->turn;
    }

    /**
     * @param mixed $value
     */
    public function increaseTurn($value = 1)
    {
        $this->turn += $value;
        $this->update();
    }

    /**
     * @return array
     */
    public function getWas()
    {
        return $this->was;
    }

    /**
     * @return mixed
     */
    public function getHealth()
    {
        return $this->health;
    }

    public function getId()
    {
        return 'mage';
    }

    /**
     * @param array $events
     */
    public function setEvents($events)
    {
        $this->mageEntity->events = json_encode($events);
        $this->update();
    }

    /**
     * @return mixed
     */
    public function getArmor()
    {
        return $this->armor;
    }
}