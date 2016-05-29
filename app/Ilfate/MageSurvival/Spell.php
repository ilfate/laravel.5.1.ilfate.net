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
abstract class Spell
{
    const KEY_CHANCE_TO_CREATE_SPELL = 'chance-to-create-spell';
    const KEY_SCHOOL_CHANCES         = 'school-chances';
    const KEY_CARRIER_USAGES_FROM    = 'carrier-usages-from';
    const KEY_CARRIER_USAGES_TO      = 'carrier-usages-to';
    const KEY_COOLDOWN               = 'cooldown';
    const KEY_ITEMS_SUM_VALUE        = 'items-sum-value';

    const STAT_SPELL  = 'spell';
    const STAT_SCHOOL = 'school';
    const STAT_USAGES = 'usages';
    const STAT_COOLDOWN = 'cooldown';

    const CONFIG_NO_TARGET_SPELL = 'noTargetSpell';
    const CONFIG_DIRECT_TARGET_SPELL = 'directTargetSpell';
    const CONFIG_NO_AUTO_ANIMATION_TRIGGER = 'noAutoAnimationTrigger';

    const CONFIG_FIELD_PATTERN = 'pattern';
    const CONFIG_FIELD_COOLDOWN = 'cooldown';
    const CONFIG_FIELD_COOLDOWN_MARK = 'cooldownMark';

    const ENERGY_SOURCE_FIRE = 'fire';
    const ENERGY_SOURCE_WATER = 'water';
    const ENERGY_SOURCE_AIR = 'air';
    const ENERGY_SOURCE_EARTH = 'earth';
    const ENERGY_SOURCE_MELEE = 'melee';

    protected $defaultCooldownMin = 2;
    protected $defaultCooldownMax = 2;
    protected $damageMax = 1;
    protected $damageMin = 1;
    protected $availablePatterns = [];

    /**
     * @var Game
     */
    protected $game;
    /**
     * @var World
     */
    protected $world;
    /**
     * @var Mage
     */
    protected $mage;

    protected $id;
    protected $d = false;
    protected $name;
    protected $number;
    protected $schoolId;
    protected $config;
    protected $level;
    protected $configuration = [];
    protected $pattern = false;

    protected $targets = [];
    protected $affectedCells = [];

    protected $animationStep = Game::ANIMATION_STAGE_MAGE_ACTION;


    public function __construct(
        $number,
        $schoolId,
        $config,
        $id = null, Game $game = null, World $world = null, Mage $mage = null
    )
    {
        if (!$id) {
            $this->id = str_random(20);
        } else {
            $this->id = $id;
        }
        if ($world) {
            $this->world = $world;
        }
        if ($game) {
            $this->game = $game;
        }
        if ($mage) {
            $this->mage = $mage;
        }
        $this->schoolId = $schoolId;
        $this->config   = $config;
        $this->configuration = \Config::get('mageSpells.list.' . $schoolId. '.' . $number);
        if (!empty($this->config[self::CONFIG_FIELD_PATTERN])) {
            $this->pattern = \Config::get('mageSpellPatterns.list.' . $this->config[self::CONFIG_FIELD_PATTERN]);

        }
        $this->number = $number;
    }

    public static function craftSpellFromItems(array $itemIds)
    {
        $game = GameBuilder::getGame();
        $result = ['spell' => false];
        $spellsConfig = \Config::get('mageSpells');
        $itemsConfig = \Config::get('mageItems.list');

        $spellRandomizerConfig = [
            self::KEY_SCHOOL_CHANCES => $spellsConfig[self::KEY_SCHOOL_CHANCES],
            //self::KEY_CHANCE_TO_CREATE_SPELL => 0,
            self::KEY_CARRIER_USAGES_FROM => 5,
            self::KEY_CARRIER_USAGES_TO => 10,
            self::KEY_ITEMS_SUM_VALUE => 0,
        ];

        foreach ($itemIds as $itemId) {
            $item = $itemsConfig[$itemId];
            if ($item['type'] == Mage::ITEM_TYPE_INGREDIENT) {
                $spellRandomizerConfig[self::KEY_ITEMS_SUM_VALUE] += $item['value'];
            } else if ($item['type'] == Mage::ITEM_TYPE_CATALYST) {
                $spellRandomizerConfig[self::KEY_SCHOOL_CHANCES] = [$item['school']];
            }
            if (empty($item['stats'])) { continue; }
            foreach ($item['stats'] as $statName => $statValue) {
                switch ($statName) {
                    case self::STAT_USAGES:
                        list($from, $to) = explode('-', $statValue);
                        $spellRandomizerConfig[self::KEY_CARRIER_USAGES_FROM] = $from;
                        $spellRandomizerConfig[self::KEY_CARRIER_USAGES_TO]   = $to;
                        break;
                    case self::STAT_SPELL:
                        $spellRandomizerConfig[self::KEY_CHANCE_TO_CREATE_SPELL] += $statValue;
                        break;
                    case self::STAT_SCHOOL:
                        foreach ($statValue as $school => $value) {
                            foreach ($spellsConfig['schools'] as $schoolId => $schoolConfig) {
                                if ($schoolConfig['name'] == $school) {
                                    for ($i = 0; $i < $value; $i++) {
                                        $spellRandomizerConfig[self::KEY_SCHOOL_CHANCES][] = $schoolId;
                                    }
                                    break;
                                }
                            }
                        }
                        break;
                    case self::STAT_COOLDOWN:
                        if (!empty($spellRandomizerConfig[self::KEY_COOLDOWN])) {
                            foreach ($statValue as $key => $value) {
                                if (isset($spellRandomizerConfig[self::KEY_COOLDOWN][$key])) {
                                    if (in_array($key, ['min', 'max'])) {
                                        $spellRandomizerConfig[self::KEY_COOLDOWN][$key] += $value;
                                    }
                                } else {
                                    $spellRandomizerConfig[self::KEY_COOLDOWN][$key] = $value;
                                }
                            }
                        } else {
                            $spellRandomizerConfig[self::KEY_COOLDOWN] = $statValue;
                        }
                        break;
                }
            }
        }
        // ok config is done
//        if (!ChanceHelper::chance($spellRandomizerConfig[self::KEY_CHANCE_TO_CREATE_SPELL])) {
//            $game->addMessage(
//                     'You had a chance of ' . $spellRandomizerConfig[self::KEY_CHANCE_TO_CREATE_SPELL] . '% and you failed to create a spell. Next time bro!'
//            );
//            return $result;
//        }
        // yea we got a new spell
//        $game->addMessage(
//                 'You had a chance of ' . $spellRandomizerConfig[self::KEY_CHANCE_TO_CREATE_SPELL] . '% and you successfully created a spell.'
//        );

        $schoolId = ChanceHelper::oneFromArray($spellRandomizerConfig[self::KEY_SCHOOL_CHANCES]);
        $schoolName = $spellsConfig['schools'][$schoolId]['name'];
        $game->addMessage('School of your new spell is ' . $schoolName);

        $allPossibleSpells = $spellsConfig['list'][$schoolId];
        $baseSumValue = $spellRandomizerConfig[self::KEY_ITEMS_SUM_VALUE];
        $game->addMessage('Base value = ' . $baseSumValue);
        $translatedSumValue = GameBuilder::getGame()->getMage()->translateItemValueForMage($baseSumValue);
        $game->addMessage('Translated value = ' . $translatedSumValue);
        $spellConfiguration = self::getSpellByValue($allPossibleSpells, $translatedSumValue);
        $spellName = $spellConfiguration['class'];

        $spellConfig = [];
        $spellConfig['usages'] = mt_rand(
                $spellRandomizerConfig[self::KEY_CARRIER_USAGES_FROM], $spellRandomizerConfig[self::KEY_CARRIER_USAGES_TO]
            );
        $class = self::getSpellClass($schoolName, $spellName);
        if (!class_exists($class)) {
            throw new \Exception('Spell with name "' . $spellName . '" not found at "' . $class . '"' );
        }
        /**
         * @var Spell $spell
         */
        $spell = new $class($spellConfiguration['number'], $schoolId, $spellConfig);
        $spell->generateCoolDown(isset($spellRandomizerConfig[self::KEY_COOLDOWN]) ? $spellRandomizerConfig[self::KEY_COOLDOWN] : []);
        $spell->setUpPattern();
        $spell->setUsages();
        //$spell->setLevel($level);
        $result['spell'] = $spell;

        GameBuilder::getGame()->addAnimationEvent(Game::EVENT_NAME_SPELL_CRAFT, [
            'spell' => $spell->getId()
        ], Game::ANIMATION_STAGE_MAGE_ACTION);

        return $result;
    }

    public static function getSpellClass($schoolName, $spellName)
    {
        return '\\Ilfate\\MageSurvival\\Spells\\'. ucfirst($schoolName) . '\\' . $spellName;
    }
    
    public static function addSpell($spellName, $school, $number, $schoolId, $usages)
    {
        $class = Spell::getSpellClass($school, $spellName);
        if (!class_exists($class)) {
            throw new \Exception('Spell with name "' . $spellName .  '" not found at "' . $class . '"' );
        }
        /**
         * @var Spell $spellName
         */
        // 0 is Fireball
        // 1 is Fire school
        $spell = new $class($number, $schoolId, ['usages' => $usages]);
        $spell->generateCoolDown();
        $spell->setUpPattern();
        $spell->setUsages();
        $mage = GameBuilder::getGame()->getMage();
        $mage->addSpell($spell);
        $mage->update();
    }

    /**
     * @param            $code
     * @param            $schoolId
     * @param            $config
     * @param            $id
     * @param Game|null  $game
     * @param World|null $world
     * @param Mage|null  $mage
     *
     * @return Spell
     */
    public static function createSpell($code, $schoolId, $config, $id, Game $game = null, World $world = null, Mage $mage = null)
    {
        $spellConfiguration = \Config::get('mageSpells.list.' . $schoolId . '.' . $code);
        $className = $spellConfiguration['class'];
        $schoolName = \Config::get('mageSpells.schools.' . $schoolId)['name'];
        $class = self::getSpellClass($schoolName, $className);
        return new $class($code, $schoolId, $config, $id, $game, $world, $mage);
    }

    private static function getSpellByValue($allPossibleSpells, $value)
    {
        $value = abs($value);
        if (!empty($allPossibleSpells[$value])) {
            $allPossibleSpells[$value]['number'] = $value;
            return $allPossibleSpells[$value];
        }
        end($allPossibleSpells);
        $lastKey = key($allPossibleSpells);
        if ($value > $lastKey) {
            $newValue = $lastKey - ($value % $lastKey);
        } else {
            $newValue = $value - 1;
        }
        return self::getSpellByValue($allPossibleSpells, $newValue);
    }

    /**
     * @param $data
     */
    public function cast($data)
    {
        if ($this->config['usages'] < 1) {
            throw new \Exception('This spell is empty (id = ' . $this->id . ')');
        }
        if ($this->config[self::CONFIG_FIELD_COOLDOWN_MARK] > $this->mage->getTurn()) {
            throw new MessageException('Spell is on cooldown');
        }
        $this->name = $this->configuration['class'];
        if (!empty($this->configuration[self::CONFIG_DIRECT_TARGET_SPELL])) {
            // we need a target
            if (!isset($data['x']) || !isset($data['y'])) {
                throw new MessageException('No target for spell selected');
            }
            $mageX = $this->mage->getX();
            $mageY = $this->mage->getY();
            $mageD = $this->mage->getD();
            $d = self::fixDirection($mageD, $data['x'], $data['y']);
            if ($mageD != $d) {
                $this->mage->rotate($d, Game::ANIMATION_STAGE_MAGE_ACTION);
                $this->setNexStage();
            }
            $this->targets = [$this->world->getUnit($mageX + $data['x'], $mageY + $data['y'])];
            $this->game->addAnimationEvent(Game::EVENT_NAME_MAGE_SPELL_CAST, [
                'spell' => $this->configuration['class'], 'targetX' => $data['x'], 'targetY' => $data['y'],
            ], $this->getNormalCastStage());
            $this->setEffectStage();
            $isSuccess = $this->spellEffect($data);
        } else if (!empty($this->configuration[self::CONFIG_NO_TARGET_SPELL])) {
            if (empty($this->configuration[self::CONFIG_NO_AUTO_ANIMATION_TRIGGER])) {
                // do not trigger animation
                $this->game->addAnimationEvent(Game::EVENT_NAME_MAGE_SPELL_CAST, [
                    'spell' => $this->configuration['class'], 'd' => $this->d
                ], $this->getNormalCastStage());
                $this->setEffectStage();
            }
            $isSuccess = $this->spellEffect($data);
        } else {
            // pattern here
            if (empty($this->config['pattern'])) {
                throw new MessageException('Pattern is missing');
            }
            if (!isset($data['d'])) {
                throw new MessageException('Direction for casting pattern spell is necessary');
            }
            if (!in_array($data['d'], [0,1,2,3])) {
                throw new MessageException('Wtf? You kidding me? Try harder bitch!');
            }
            $mageD = $this->mage->getD();
            $d = self::fixDirection($mageD, $data['x'], $data['y']);
            $this->rotatePattern($d, $data['x'], $data['y']);
            if ($mageD != $d) {
                $this->mage->rotate($d, Game::ANIMATION_STAGE_MAGE_ACTION);
                $this->setNexStage();
            }
            $this->d = $d;

            $mageX = $this->mage->getX();
            $mageY = $this->mage->getY();
            foreach ($this->pattern as $patternCell) {
                if ($unit = $this->world->getUnit($mageX + $patternCell[0], $mageY + $patternCell[1]))
                {
                    $this->targets[] = $unit;
                }
                $this->affectedCells[] = [$mageX + $patternCell[0], $mageY + $patternCell[1]];
            }
            if (empty($this->configuration[self::CONFIG_NO_AUTO_ANIMATION_TRIGGER])) {
                $this->game->addAnimationEvent(Game::EVENT_NAME_MAGE_SPELL_CAST, [
                    'spell' => $this->configuration['class'], 'd' => $this->d, 'patternId' => $this->config[self::CONFIG_FIELD_PATTERN], 'pattern' => $this->pattern
                ], $this->getNormalCastStage());
                $this->setEffectStage();
            }
            $isSuccess = $this->spellEffect($data);
        }
        if ($isSuccess) {
            $this->spend();
            $this->cooldown();
            $this->mage->updateSpell($this);
        }
    }

    public function cooldown()
    {
        $this->config[self::CONFIG_FIELD_COOLDOWN_MARK] = $this->mage->getTurn()
            + $this->config[self::CONFIG_FIELD_COOLDOWN] + 1;
    }

    protected function getNormalCastStage()
    {
        return $this->animationStep;
    }

    protected function setNexStage()
    {
        $setNextStage = false;
        foreach (Game::$stagesList as $stage) {
            if ($setNextStage) {
                $this->animationStep = $stage;
                return;
            }
            if ($stage == $this->animationStep) {
                $setNextStage = true;
            }
        }
    }

    protected function getPreviousStage()
    {
        $currentKey = array_search($this->animationStep, Game::$stagesList);
        if ($currentKey > 0) {
            return Game::$stagesList[$currentKey - 1];
        }
        return $this->animationStep;
    }

    protected function setEffectStage()
    {
        $this->animationStep = Game::ANIMATION_STAGE_MAGE_ACTION_EFFECT;
    }

    public function spend($value = -1)
    {
        $usages = $this->config['usages'];
        $usages += $value;
        $this->config['usages'] = $usages;

    }

    public function exportConfig()
    {
        return $this->config;
    }

    public function export()
    {
        return [
            'id' => $this->id,
            'code' => $this->getNumber(),
            'school' => $this->getSchoolId(),
            'config' => $this->exportConfig()
        ];
    }

    public function getUsages()
    {
        return $this->config['usages'];
    }

    public function generateCoolDown($modifiers = [])
    {
        $min = $this->defaultCooldownMin;
        $max = $this->defaultCooldownMax;
        if (!empty($modifiers['min'])) {
            $min += $modifiers['min'];
        }
        if (!empty($modifiers['max'])) {
            $max += $modifiers['max'];
        }
        $this->config[self::CONFIG_FIELD_COOLDOWN] = mt_rand($min, $max);
        $this->config[self::CONFIG_FIELD_COOLDOWN_MARK] = 0;
    }

    public function setUpPattern($modifier = null)
    {
        $config = \Config::get('mageSpells.list.' . $this->name);
        if ($modifier) {
            $this->config['pattern'] = $modifier;
        } else if($this->availablePatterns && empty($config['directTargetSpell']) && empty($config['noTargetSpell'])) {
            $this->config['pattern'] = ChanceHelper::oneFromArray($this->availablePatterns);
        } else {
            $this->config['pattern'] = '';
        }
    }

    /**
     * @param mixed $level
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }

    abstract protected function spellEffect($data);

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    public function rotatePattern($d, $x, $y)
    {
        if (in_array([$x, $y], $this->pattern)) {
            return; // no need to rotate
        }
        $rotatedPattern = [];
        foreach ($this->pattern as $patternCell) {
            $rotatedPattern[] = $this->rotatePatternCoordinats($patternCell[0], $patternCell[1], $d);
        }
        if (in_array([$x, $y], $rotatedPattern)) {
            $this->pattern = $rotatedPattern;
            return;
        }
        // well
        $directions = [1, 2, 3];
        unset($directions[array_search($d, $directions)]);

        foreach ($directions as $direction) {
            $rotatedPattern = [];
            foreach ($this->pattern as $patternCell) {
                $rotatedPattern[] = $this->rotatePatternCoordinats($patternCell[0], $patternCell[1], $direction);
            }
            if (in_array([$x, $y], $rotatedPattern)) {
                $this->pattern = $rotatedPattern;
                return;
            }
        }
        // wtf?
        throw new MessageException('There was problems locating targets of your spell.');
    }

    public function rotatePatternCoordinats ($x, $y, $d) {
        switch ($d) {
            case 0: return [$x, $y];
            case 1: return [-$y, $x];
            case 2: return [-$x, -$y];
            case 3: return [$y, -$x];
        }
    }

    public static function fixDirection($currentD, $x, $y, $centerX = 0, $centerY = 0)
    {
        if (!is_numeric($x) || !is_numeric($y)) {
            throw new MessageException('Wtf are those coordinats?');
        }
        $rx = $x - $centerX;
        $ry = $y - $centerY;
        if (abs($ry) > abs($rx)) {
            if ($ry > $rx) return 2;
            if ($ry < $rx) return 0;
        }
        if (abs($ry) < abs($rx)) {
            if ($ry > $rx) return 3;
            if ($ry < $rx) return 1;
        }
        if ($rx == $ry && $rx > 0) {
            if (in_array($currentD, [1,2])) return $currentD;
            return ChanceHelper::oneFromArray([1,2]);
        }
        if ($rx == $ry) {
            if (in_array($currentD, [3,0])) return $currentD;
            return ChanceHelper::oneFromArray([3,0]);
        }
        if ($rx > $ry) {
            if (in_array($currentD, [1,0])) return $currentD;
            return ChanceHelper::oneFromArray([1,0]);
        }
        if (in_array($currentD, [3,2])) return $currentD;
        return ChanceHelper::oneFromArray([3,2]);
    }

    protected function changeCellsBySpellSource($cells, $source) {
        foreach ($cells as $affectedCell) {
            $newCell = $this->game->getWorldGenerator()->getCellDestroyableBySource(
                $affectedCell[0], $affectedCell[1], $source);
            if ($newCell) {
                $mageX = $mage = $this->mage->getX();
                $mageY = $mage = $this->mage->getY();
                $this->world->setCell($affectedCell[0], $affectedCell[1], $newCell);
                $this->game->addAnimationEvent(Game::EVENT_CELL_CHANGE, [
                    'cell' => $newCell, 'targetX' => $affectedCell[0] - $mageX, 'targetY' => $affectedCell[1] - $mageY
                ], $this->getNormalCastStage());
            }
        }
    }

    /**
     * @return mixed
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @return mixed
     */
    public function getSchoolId()
    {
        return $this->schoolId;
    }

    public function setUsages()
    {

    }

    public function getDamage()
    {
        return 1;
    }
}