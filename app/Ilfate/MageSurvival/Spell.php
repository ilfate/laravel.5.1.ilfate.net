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

    const CONFIG_FIELD_COOLDOWN = 'cooldown';
    const CONFIG_FIELD_COOLDOWN_MARK = 'cooldownMark';

    protected $defaultCooldownMin = 3;
    protected $defaultCooldownMax = 7;
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
    protected $name;
    protected $schoolId;
    protected $config;
    protected $level;
    protected $configuration = [];


    public function __construct($name, $schoolId, $config, $id = null, Game $game = null, World $world = null, Mage $mage = null)
    {
        if (!$id) {
            $this->id = str_random(30);
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
        $this->config   = $config;
        $this->configuration = \Config::get('mageSpells.list.' . $name);
    }

    public static function craftSpellFromItems($carrierId, array $itemIds)
    {
        $game = GameBuilder::getGame();
        $result = ['spell' => false];
        $config = \Config::get('mageSurvival.spells');
        $itemsConfig = \Config::get('mageSurvival.items');

        $spellRandomizerConfig = [
            self::KEY_SCHOOL_CHANCES => $config[self::KEY_SCHOOL_CHANCES],
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
                            $schoolId = array_search($school, $config['schools']);
                            for ($i = 0; $i < $value; $i++) {
                                $spellRandomizerConfig[self::KEY_SCHOOL_CHANCES][] = $schoolId;
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
        $schoolName = $config['schools'][$schoolId];
        $game->addMessage('School of your spell is ' . $schoolName);

        $level = 1;
        $game->addMessage('Your new spell is level ' . $level);

        $allPossibleSpells = $config['list'][$schoolId][$level];
        $spellName = ChanceHelper::oneFromArray($allPossibleSpells);

        $spellConfig = [
            'usages' => mt_rand(
                $spellRandomizerConfig[self::KEY_CARRIER_USAGES_FROM], $spellRandomizerConfig[self::KEY_CARRIER_USAGES_TO]
            ),
        ];
        $class = self::getSpellClass($schoolName, $spellName);
        if (!class_exists($class)) {
            throw new \Exception('Spell with name "' . $spellName . '" not found at "' . $class . '"' );
        }
        $spell = new $class($spellName, $schoolId, $spellConfig);
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

    public static function createSpellByCode($code, $config, $id, Game $game = null, World $world = null, Mage $mage = null)
    {
        list($name, $schoolId, $level) = explode('#', $code);
        $schoolName = \Config::get('mageSurvival.spells.schools.' . $schoolId);
        $class = self::getSpellClass($schoolName, $name);
        return new $class($name, $schoolId, $config, $id, $game, $world, $mage);
    }

    /**
     * @param $data
     */
    public function cast($data)
    {
        if ($this->config['usages'] < 1) {
            throw new \Exception('This spell is empty (id = ' . $this->id . ')');
        }
        if (!empty($this->configuration[self::CONFIG_DIRECT_TARGET_SPELL])) {
            // we need a target
            if (empty($data['target'])) {
                throw new MessageException('No target for spell selected');
            }
            $isSuccess = $this->spellEffect($data);
        } else if (!empty($this->configuration[self::CONFIG_NO_TARGET_SPELL])) {
            $isSuccess = $this->spellEffect($data);
        } else {
            // pattern here
            if (empty($this->config['pattern'])) {
                throw new MessageException('Pattern is missing');
            }
            $isSuccess = $this->spellEffect($data);
        }
        if ($isSuccess) {
            $this->spend();
            $this->mage->updateSpell($this);
        }
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
        if ($modifier) {
            $this->config['pattern'] = $modifier;
        } else if($this->availablePatterns) {
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
}