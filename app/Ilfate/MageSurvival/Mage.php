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
abstract class Mage extends AliveCommon
{
    const ITEM_TYPE_INGREDIENT = 'ingredient';
    const ITEM_TYPE_CATALYST = 'catalyst';

    const STAT_KEY_SPELL_FIRE = 'sf';
    const STAT_KEY_SPELL_WATER = 'sw';
    const STAT_KEY_SPELL_AIR = 'sa';
    const STAT_KEY_SPELL_EARTH = 'se';
    const STAT_KEY_SPELL_LIGHT = 'sl';
    const STAT_KEY_SPELL_DEATH = 'sd';
    const STAT_KEY_SPELL_NATURE = 'sn';
    const STAT_KEY_SPELL_ARCANE = 'sarc';
    const STAT_KEY_UNIT_KILL = 'uk';
    const STAT_KEY_SPELL_CRAFTED = 'sc';

    const DEFAULT_MAX_HEALTH = 20;
    const DEFAULT_MAX_ARMOR = 20;
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
    protected $spells = [];

    /**
     * @var Game
     */
    protected $game;

    protected $isUpdated = false;
    protected $config;
    protected $itemsConfig;
    protected $itemsChanges;
    protected $spellsChanges;

    public function __construct(\Ilfate\Mage $mageEntity, Game $game)
    {
        $this->setGame($game);
        $this->config = \Config::get('mageSurvival');
        $this->mageEntity = $mageEntity;
        $this->data = json_decode($mageEntity->data, true);
        $this->items = json_decode($mageEntity->items, true);
        $this->spells = json_decode($mageEntity->spells, true);
        
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
            // this mage is just created
            $this->initMage();
            //let`s give him some Items
//            $this->addItems([1 => 3, 2 => 3, 3 => 3, 4 => 3, 5 => 3, 1001 => 2]);
        }
    }

    protected function initMage()
    {
        $this->health = static::DEFAULT_MAX_HEALTH;
        $this->maxHealth = static::DEFAULT_MAX_HEALTH;
        $this->was['health'] = static::DEFAULT_MAX_HEALTH;
        $this->was['maxHealth'] = static::DEFAULT_MAX_HEALTH;
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
        $this->mageEntity->save();
    }

    public function viewExport()
    {
        $flags = empty($this->data[self::DATA_FLAG_KEY]) ? [] : $this->data[self::DATA_FLAG_KEY];
        $data = [
            'd' => $this->getD(),
            'health' => $this->getHealth(),
            'maxHealth' => $this->maxHealth,
            'armor' => $this->getArmor(),
            'items' => $this->exportItems(),
            'spells' => $this->exportSpells(),
            'spellSchools' => $this->exportSchools(),
            'flags' => $flags,
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
        $config = $this->getItemsConfig();
        $return = [];
        if (!$this->items) {
            return [];
        }
        foreach ($this->items as $itemId => $quantity) {
            $itemConfig = $config['list'][$itemId];
            $itemConfig['quantity'] = $quantity;
            $itemConfig['id'] = $itemId;
            $return[$itemId] = $itemConfig;
        }
        return $return;
    }

    public function getUpdatedItems()
    {
        $config = $this->getItemsConfig();
        $return = [];
        foreach ($this->itemsChanges as $itemId => $quantity) {
            $itemConfig = $config['list'][$itemId];
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
        $spellsViewData = \Config::get('mageSpells.list');
        $itemConfigs = \Config::get('mageItems.list');
        $spellsPatterns = \Config::get('mageSpellPatterns.list');
        foreach ($this->spells as $spellId => $spell) {
            $return[$spellId] = [
                'id' => $spellId,
                'schoolId' => $spell['school'],
                'config' => $spell['config'],
                'viewData' => $spellsViewData[$spell['school']][$spell['code']],
                'ingredients' => [],
            ];
            if (!empty($spell['config'][Spell::CONFIG_FIELD_INGREDIENTS])) {
                foreach ($spell['config'][Spell::CONFIG_FIELD_INGREDIENTS] as $item) {
                    $return[$spellId]['ingredients'][] = [
                        'icon' => $itemConfigs[$item]['icon'], 
                        'iconColor' => $itemConfigs[$item]['iconColor']
                    ];
                }
            }
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
            //list($name, $schoolId, $number) = explode('#', $spell['code']);
            if (empty($return[$spell['school']])) {
                $return[$spell['school']] = $spellsViewData[$spell['school']];
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
        $itemConfigs = \Config::get('mageItems.list');
        $spellsViewData = \Config::get('mageSpells.list');
        $schoolsViewData = \Config::get('mageSpells.schools');
        $spellsPatterns = \Config::get('mageSpellPatterns.list');
        foreach ($this->spellsChanges as $spellId => $spell) {
            
            $return[$spellId] = [
                'id' => $spellId,
                'config' => $spell['config'],
                'viewData' => $spellsViewData[$spell['school']][$spell['code']],
                'status' => $spell['status'],
                'schoolId' => $spell['school'],
                'ingredients' => []
            ];
            if (!empty($spell['config'][Spell::CONFIG_FIELD_INGREDIENTS])) {
                foreach ($spell['config'][Spell::CONFIG_FIELD_INGREDIENTS] as $item) {
                    $return[$spellId]['ingredients'][] = [
                        'icon' => $itemConfigs[$item]['icon'],
                        'iconColor' => $itemConfigs[$item]['iconColor']
                    ];
                }
            }
            if ($spell['status'] == 'new') {
                $return[$spellId]['schoolViewData'] = $schoolsViewData[$spell['school']];
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
        if ($web = $this->getFlag('web')) {
            if ($web >= $this->game->getTurn()) {
                    throw new MessageException('Your are stuck in web for some time and can`t move for now.');
            }
        }
        $wasX = $x = $this->getX();
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
            $unitThere = false;
            if ($this->getFlag(self::FLAG_WATER_BODY) >= $this->game->getTurn()) {
                $unitThere = !$this->world->getUnit($x, $y);
            }
            if (!$unitThere) {
                throw new MessageException('You can`t move in this direction');
            }
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

        $eventData = Event::trigger(Event::EVENT_MAGE_AFTER_MOVE, ['x' => $x, 'y' => $y, 'd' => $this->d]);
        $this->x = $eventData['x'];
        $this->y = $eventData['y'];
        $this->update();
        $this->game->setIsMageMoved();
        $data = [
            'mage' => $this->exportMage(),
            'map' => $this->game->getWorldGenerator()->exportMapForView($this),
            'objects' => $this->game->getWorldGenerator()->exportVisibleObjects(),
            'units' => $this->game->getWorldGenerator()->exportVisibleUnits(),
        ];
        if (!empty($eventData['isUpdated'])) {
            $data['bigMove'] = true;
        }
        $this->game->addAnimationEvent('mage-move', $data, $stageForMove);
        $this->game->registrationCheck();
    }

    public function forceMove($x, $y, $animationStage)
    {
        $this->x = $x;
        $this->y = $y;
        $this->update();
        $this->game->setIsMageMoved();
        $this->game->addAnimationEvent('mage-move', [
            'force' => true,
            'mage' => $this->exportMage(),
            'map' => $this->game->getWorldGenerator()->exportMapForView($this),
            'objects' => $this->game->getWorldGenerator()->exportVisibleObjects(),
            'units' => $this->game->getWorldGenerator()->exportVisibleUnits(),
        ], $animationStage);
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
        $itemIds = $data['ingredients'];
        if (!empty($data['blender'])) {
            // IT IS BLENDER TIME!
            $spells = [];
            foreach ($itemIds as $itemId) {
                if (empty($this->spells[$itemId])) {
                    throw new MessageException('Spell for blending is missing');
                }
                $spells[] = $this->spells[$itemId];
                $spellData = $this->spells[$itemId];
                $spell = Spell::createSpell(
                    $spellData['code'],
                    $spellData['school'],
                    $spellData['config'],
                    $itemId,
                    $this->game,
                    $this->game->getWorld(),
                    $this
                );
                $spell->spend(-1000);
                $this->updateSpell($spell);
            }
            // ok we spend items let`s get the spell
            $result = Spell::craftSpellFromSpells($spells);
        } else {
            foreach ($itemIds as $itemId) {
                if (empty($this->items[$itemId])) {
                    throw new MessageException('Item for crafting is missing');
                }
                $this->addItem($itemId, -1);
            }
            // ok we spend items let`s get the spell
            $result = Spell::craftSpellFromItems($itemIds);
            $result['spell']->setIngredients($itemIds);
        }
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
        $spell = Spell::createSpell(
            $spellData['code'],
            $spellData['school'],
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
            [0, -1, 'icon-arrow'],
            [1, 0, 'icon-arrow'],
            [0, 1, 'icon-arrow'],
            [-1, 0, 'icon-arrow'],
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
            } else {
                $actions[] = [
                    'name' => $key,
                    'method' => 'wall-' . $key,
                    'icon' => 'icon-brick-wall',
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
            Event::trigger(Event::EVENT_MAGE_AFTER_OBJECT_ACTIVATE, ['target' => $object]);
        } else {
            throw new \Exception('No object was found to interact with');
        }
        $this->update();
        return $result;
    }

    public function addItems($items) {
        foreach ($items as $item => $quantyty) {
            $this->addItem($item, $quantyty);
        }
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

    public function damage($value, $animationStage, $sourceType)
    {
        $eventData = Event::trigger(Event::EVENT_MAGE_BEFORE_GET_DAMAGE, ['value' => $value, 'source' => $sourceType]);
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
        if ($this->health <= 0) {
            $this->death();
        }
        $this->update();
    }

    public function death()
    {
        if (!$this->alive) { return; }
        $this->alive = false;
        $this->mageEntity->status = \Ilfate\Mage::MAGE_STATUS_DEAD;
        $this->update();
        
        $stats = $this->getAllStats();
        $mageUser = $this->game->getMageUser();
        $userStats = $mageUser->getStats();
        foreach (Spell::$energyToStats as $energy => $statName) {
            if (!empty($stats[$statName])) {
                if (empty($userStats[$energy])) {
                    $userStats[$energy] = $stats[$statName];
                } else {
                    $userStats[$energy] += $stats[$statName];
                }
            }
        }
        $mageUser->stats = json_encode($userStats);
        $mageUser->save();

        $this->say('My time here is over...', Game::ANIMATION_STAGE_MESSAGE_TIME_2);
        GameBuilder::animateEvent(Game::EVENT_NAME_MAGE_DEATH, [], Game::ANIMATION_STAGE_MESSAGE_TIME_3);
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
        if ($this->armor + $value > static::DEFAULT_MAX_ARMOR) {
            $value = static::DEFAULT_MAX_ARMOR - $this->armor;
        }
        $this->armor += $value;
        if ($value > 0) {
            GameBuilder::animateEvent(Game::EVENT_NAME_MAGE_ADD_ARMOR, $this->exportMageHealth($value), $animationStage);
        }
    }

    public function getDamage($damage, $source) {
        $buff = $this->getBuff($source);
        if ($buff) {
            switch ($buff[1]) {
                case '+': $damage += $buff[0]; break;
                case '*': $damage *= $buff[0]; break;
            }
        }
        return $damage;
    }

    public function update()
    {
        $this->isUpdated = true;
    }

    public function getRelativeCoordinats($x, $y)
    {
        return [$x - $this->getX(), $y - $this->getY()];
    }

    public function getRandomIngredientsSeed()
    {
//        $result = [];
//        $arr = [0,1,2,3,4,5,6,7,8,9];
//        for ($i = 0; $i < 10; $i++) {
//            $key = array_rand($arr);
//            $result[] = $arr[$key];
//            unset($arr[$key]);
//        }
//        return implode('', $result);
        $randomPresets = [
            '0123456789',
            '1032547698',
            '1302456978',
            '1032456978',
            '0214365879',
            '2014365897',
            '2104635897',
            '2104365978',
            '0132457689',
            '0134275689',
            '1034275689',
            '1034275698',
            '1023456789',
            '2103456789',
            '1023456798',
            '1203475698',
            '1204375698',
            '0132546879',
            '0132546897',
        ];
        return ChanceHelper::oneFromArray($randomPresets);
    }

    public function translateItemValueForMage($value) {
        $isNegative = false;
        if ($value < 0) {
            $isNegative = true;
            $value = abs($value);
        }
        $base = floor($value / 10);
        $flexPart = $value % 10;
        $translatedFlexPart = $this->getIngredientsSeed()[$flexPart];
        return (int) ($base . $translatedFlexPart) * ($isNegative ? -1 : 1);
    }

    /**
     * @return \Ilfate\Mage
     */
    public function getIngredientsSeed()
    {
        $iseed = $this->game->getMageUser()->iseed;
        if (!$iseed) {
            $iseed = $this->getRandomIngredientsSeed();
            $this->game->getMageUser()->iseed = $iseed;
            $this->game->mageUserUpdated();
        }
        return $iseed;
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

    public function getItemsConfig() {
        if (!$this->itemsConfig) {
            $this->itemsConfig = \Config::get('mageItems');
        }
        return $this->itemsConfig;
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
     * @return mixed
     */
    public function getArmor()
    {
        return $this->armor;
    }

    public function leaveWorld()
    {
        $config = $this->world->getWorldConfig();
        if (!empty($config['is-delete-on-exit'])) {
            $this->world->destroy();
        }
        if ($this->spells) {
            foreach ($this->spells as &$spell) {
                $spell['config'][Spell::CONFIG_FIELD_COOLDOWN_MARK] = 0;
            }
        }
        $this->removeAllBuffsAndFlags();
        $this->game->getWorldGenerator()->onLeaveWorld();
        $this->mageEntity->world_id = 0;
        $this->update();
    }

    /**
     * @return int
     */
    public function getMaxHealth()
    {
        return $this->maxHealth;
    }
    
    public function setDataKey($key, $value)
    {
        $this->data[$key] = $value;
        $this->update();
    }
    
    public function getDataKey($key)
    {
        if (!empty($this->data[$key])) {
            return $this->data[$key];
        }
        return null;
    }

    public function addBuff($source, $turns, $value, $type = '+')
    {
        $turnEnd = $this->game->getTurn() + $turns;
        $this->data[self::DATA_BUFF_KEY][$source] = [$value, $type, $turnEnd];
        $this->update();
    }

    public function removeBuff($source)
    {
        unset($this->data[self::DATA_BUFF_KEY][$source]);
        $this->update();
    }

    public function getBuff($source)
    {
        if (isset($this->data[self::DATA_BUFF_KEY][$source])) {
            $buff = $this->data[self::DATA_BUFF_KEY][$source];
            if ($this->game->getTurn() >= $buff[2]) {
                $this->removeBuff($source);
                return false; 
            }
            return $this->data[self::DATA_BUFF_KEY][$source];
        }
        return false;
    }

    public function removeAllBuffsAndFlags()
    {
        $this->data[self::DATA_BUFF_KEY] = [];
        $this->data[self::DATA_FLAG_KEY] = [];
        $this->update();
    }
    
    public function deleteAllSpells()
    {
        $this->spells = [];
    }

    public function getUnitType()
    {
        return self::UNIT_TYPE_MAGE;
    }

    public function addStat($stat, $value = 1)
    {
        if (empty($this->data[self::DATA_STAT_KEY][$stat])) {
            $this->data[self::DATA_STAT_KEY][$stat] = $value;
        } else {
            $this->data[self::DATA_STAT_KEY][$stat] += $value;
        }
        $this->update();
    }

    public function getStat($stat)
    {
        if (!empty($this->data[self::DATA_STAT_KEY][$stat])) {
            return $this->data[self::DATA_STAT_KEY][$stat];
        }
        return null;
    }

    public function getAllStats()
    {
        return $this->data[self::DATA_STAT_KEY];
    }
}