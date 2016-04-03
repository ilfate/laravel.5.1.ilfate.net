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

    const STAT_SPELL  = 'spell';
    const STAT_SCHOOL = 'school';
    const STAT_USAGES = 'usages';
    const STAT_COOLDOWN = 'cooldown';

    const CONFIG_NO_TARGET_SPELL = 'noTargetSpell';
    const CONFIG_DIRECT_TARGET_SPELL = 'directTargetSpell';

    const CONFIG_FIELD_PATTERN = 'pattern';
    const CONFIG_FIELD_COOLDOWN = 'cooldown';
    const CONFIG_FIELD_COOLDOWN_MARK = 'cooldownMark';

    protected $defaultCooldownMin = 2;
    protected $defaultCooldownMax = 2;
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
    protected $schoolId;
    protected $config;
    protected $level;
    protected $configuration = [];
    protected $pattern = false;

    protected $targets = [];

    protected $animationStep = Game::ANIMATION_STAGE_MAGE_ACTION;


    public function __construct(
        $name,
        $schoolId,
        $level,
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
        $this->name     = $name;
        $this->schoolId = $schoolId;
        $this->level    = $level;
        $this->config   = $config;
        $this->configuration = \Config::get('mageSpells.list.' . $schoolId. '.' . $level . '.' . $name);
        if (!empty($this->config[self::CONFIG_FIELD_PATTERN])) {
            $this->pattern = \Config::get('mageSpellPatterns.list.' . $this->config[self::CONFIG_FIELD_PATTERN]);

        }
    }

    public static function craftSpellFromItems($carrierId, array $itemIds)
    {
        $game = GameBuilder::getGame();
        $result = ['spell' => false];
        $spellsConfig = \Config::get('mageSpells');
        $itemsConfig = \Config::get('mageSurvival.items');

        $spellRandomizerConfig = [
            self::KEY_SCHOOL_CHANCES => $spellsConfig[self::KEY_SCHOOL_CHANCES],
            self::KEY_CHANCE_TO_CREATE_SPELL => 0,
            self::KEY_CARRIER_USAGES_FROM => 0,
            self::KEY_CARRIER_USAGES_TO => 0,
        ];
        $itemIds[] = $carrierId;
        foreach ($itemIds as $itemId) {
            $item = $itemsConfig[$itemId];
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
        if (!ChanceHelper::chance($spellRandomizerConfig[self::KEY_CHANCE_TO_CREATE_SPELL])) {
            $game->addMessage(
                     'You had a chance of ' . $spellRandomizerConfig[self::KEY_CHANCE_TO_CREATE_SPELL] . '% and you failed to create a spell. Next time bro!'
            );
            return $result;
        }
        // yea we got a new spell
        $game->addMessage(
                 'You had a chance of ' . $spellRandomizerConfig[self::KEY_CHANCE_TO_CREATE_SPELL] . '% and you successfully created a spell.'
        );

        $schoolId = ChanceHelper::oneFromArray($spellRandomizerConfig[self::KEY_SCHOOL_CHANCES]);
        $schoolName = $spellsConfig['schools'][$schoolId]['name'];
        $game->addMessage('School of your spell is ' . $schoolName);

        $level = 1;
        $game->addMessage('Your new spell is level ' . $level);

        $allPossibleSpells = $spellsConfig['list'][$schoolId][$level];
        $spellName = array_rand($allPossibleSpells);

        $spellConfig = [
            'usages' => mt_rand(
                $spellRandomizerConfig[self::KEY_CARRIER_USAGES_FROM], $spellRandomizerConfig[self::KEY_CARRIER_USAGES_TO]
            ),
        ];
        $class = self::getSpellClass($schoolName, $spellName);
        if (!class_exists($class)) {
            throw new \Exception('Spell with name "' . $spellName . '" not found at "' . $class . '"' );
        }
        /**
         * @var Spell $spell
         */
        $spell = new $class($spellName, $schoolId, $level, $spellConfig);
        $spell->generateCoolDown(isset($spellRandomizerConfig[self::KEY_COOLDOWN]) ? $spellRandomizerConfig[self::KEY_COOLDOWN] : []);
        $spell->setUpPattern();
        $spell->setLevel($level);
        $result['spell'] = $spell;

        return $result;

    }

    public static function getSpellClass($schoolName, $spellName)
    {
        return '\\Ilfate\\MageSurvival\\Spells\\'. ucfirst($schoolName) . '\\' . $spellName;
    }

    /**
     * @param            $code
     * @param            $config
     * @param            $id
     * @param Game|null  $game
     * @param World|null $world
     * @param Mage|null  $mage
     *
     * @return Spell
     */
    public static function createSpellByCode($code, $config, $id, Game $game = null, World $world = null, Mage $mage = null)
    {
        list($name, $schoolId, $level) = explode('#', $code);
        $schoolName = \Config::get('mageSpells.schools.' . $schoolId)['name'];
        $class = self::getSpellClass($schoolName, $name);
        return new $class($name, $schoolId, $level, $config, $id, $game, $world, $mage);
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
        if (!empty($this->configuration[self::CONFIG_DIRECT_TARGET_SPELL])) {
            // we need a target
            if (!isset($data['x']) || !isset($data['y'])) {
                throw new MessageException('No target for spell selected');
            }
            $mageX = $this->mage->getX();
            $mageY = $this->mage->getY();
            $mageD = $this->mage->getD();
            $d = $this->fixDirection($mageD, $data['x'], $data['y']);
            if ($mageD != $d) {
                $this->mage->rotate($d, Game::ANIMATION_STAGE_MAGE_ACTION);
                $this->setNexStage();
            }
            $this->targets = [$this->world->getUnit($mageX + $data['x'], $mageY + $data['y'])];
            $this->game->addAnimationEvent(Game::EVENT_NAME_MAGE_SPELL_CAST, [
                'spell' => $this->name, 'target-x' => $data['x'], 'target-y' => $data['y'],
            ], $this->getNormalCastStage());
            $this->setEffectStage();
            $isSuccess = $this->spellEffect($data);
        } else if (!empty($this->configuration[self::CONFIG_NO_TARGET_SPELL])) {
            $this->game->addAnimationEvent(Game::EVENT_NAME_MAGE_SPELL_CAST, [
                'spell' => $this->name, 'd' => $this->d
            ], $this->getNormalCastStage());
            $this->setEffectStage();
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
            $d = $this->fixDirection($mageD, $data['x'], $data['y']);
            if ($mageD != $d) {
                $this->mage->rotate($d, Game::ANIMATION_STAGE_MAGE_ACTION);
                $this->setNexStage();
            }
            $this->d = $d;
            $this->rotatePattern($d);
            $mageX = $this->mage->getX();
            $mageY = $this->mage->getY();
            foreach ($this->pattern as $patternCell) {
                if ($unit = $this->world->getUnit($mageX + $patternCell[0], $mageY + $patternCell[1]))
                {
                    $this->targets[] = $unit;
                }
            }
            $this->game->addAnimationEvent(Game::EVENT_NAME_MAGE_SPELL_CAST, [
                'spell' => $this->name, 'd' => $this->d
            ], $this->getNormalCastStage());
            $this->setEffectStage();
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

    public function exportSpellCode()
    {
        return $this->name . '#' . $this->schoolId . '#' . $this->level;
    }

    public function exportConfig()
    {
        return $this->config;
    }

    public function export()
    {
        return [
            'id' => $this->id,
            'code' => $this->exportSpellCode(),
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

    public function rotatePattern($d)
    {
        foreach ($this->pattern as &$patternCell) {
            $patternCell = $this->rotatePatternCoordinats($patternCell[0], $patternCell[1], $d);
        }
    }

    public function rotatePatternCoordinats ($x, $y, $d) {
        switch ($d) {
            case 0: return [$x, $y];
            case 1: return [-$y, $x];
            case 2: return [-$x, -$y];
            case 3: return [$y, -$x];
        }
    }

    public function fixDirection($currentD, $x, $y)
    {
        if (!is_numeric($x) || !is_numeric($y)) {
            throw new MessageException('Wtf are those coordinats?');
        }
        if (abs($y) > abs($x)) {
            if ($y > $x) return 2;
            if ($y < $x) return 0;
        }
        if (abs($y) < abs($x)) {
            if ($y > $x) return 3;
            if ($y < $x) return 1;
        }
        if ($x == $y && $x > 0) {
            if (in_array($currentD, [1,2])) return $currentD;
            return ChanceHelper::oneFromArray([1,2]);
        }
        if ($x == $y) {
            if (in_array($currentD, [3,0])) return $currentD;
            return ChanceHelper::oneFromArray([3,0]);
        }
        if ($x > $y) {
            if (in_array($currentD, [1,0])) return $currentD;
            return ChanceHelper::oneFromArray([1,0]);
        }
        if (in_array($currentD, [3,2])) return $currentD;
        return ChanceHelper::oneFromArray([3,2]);
    }
}