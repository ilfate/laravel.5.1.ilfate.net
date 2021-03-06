<?php
/**
 * ilfate.net
 * @autor Ilya Rubinchik ilfate@gmail.com
 * 2014
 */

namespace Ilfate\Tcg;

use ClassPreloader\Config;

abstract class Unit
{
    use UnitMove;

    const RENDER_TYPE_UNIT = 'unit';
    const RENDER_TYPE_CARD = 'card';

    const CONFIG_VALUE_TOTAL_HEALTH = 'totalHealth';
    const CONFIG_VALUE_TEXT         = 'text';
    const CONFIG_VALUE_ATTACK       = 'attack';
    const CONFIG_VALUE_ARMOR        = 'armor';
    const CONFIG_VALUE_KEYWORDS     = 'keywords';
    const CONFIG_VALUE_NAME         = 'name';

    const DEFAULT_MOVE_DISTANCE = 1;
    const DEFAULT_ATTACK_RANGE  = 1;

    const KEYWORD_BLOODTHIRST = 'bloodthirst';
    const KEYWORD_FOCUS       = 'focus';
    const KEYWORD_SHIELD      = 'shield';

    const MOVE_TYPE_NORMAL   = 1;
    const MOVE_TYPE_DIAGONAL = 2;
    const MOVE_TYPE_AROUND   = 3;
    const MOVE_TYPE_JUMP     = 4;

    const ATTACK_TYPE_NORMAL  = 1;
    const ATTACK_TYPE_AHEAD_1 = 2;
    const ATTACK_TYPE_DIAGONAL = 3;

    protected static $exportValues = array(
        'maxHealth',
        'currentHealth',
        'maxArmor',
        'armor',
        'x',
        'y',
        'stepsMade',
        'keywords',
        'attack',
        'data',
        'attackRange',
        'attackType',
        'kills',
        'death'
    );

    /**
     * totalHealth
     * text
     *
     * @var array
     */
    public $config;
    public $name;

    public $maxHealth;
    public $currentHealth;
    public $maxArmor = 0;
    public $armor = 0;
    public $attack = [0, 0];
    public $attackType = 1;
    public $attackRange = 0;
    public $x;
    public $y;
    public $isKing = false;

    public $data;

    /**
     * @var Card
     */
    public $card;

    public $keywords  = [];
    public $stepsMade = 0;
    public $kills = 0;
    public $death = false;

    public static function createFromConfig($config, Card $card)
    {
        $unit         = new $config['unit']();
        $unit->config = $config;
        $unit->card   = $card;
        $unit->name   = $config[self::CONFIG_VALUE_NAME];

        if (isset($config['attackType'])) {
            $unit->attackType = $config['attackType'];
        }

        return $unit;
    }

    public static function import($data, $unitId, $card)
    {
        $unit = Unit::createFromConfig(\Config::get('tcg.units.' . $unitId), $card);

        foreach (self::$exportValues as $valueName) {
            $unit->{$valueName} = $data[$valueName];
        }

        return $unit;
    }

    public function export()
    {
        $data = [];
        foreach (self::$exportValues as $valueName) {
            $data[$valueName] = $this->{$valueName};
        }
        return $data;
    }

    public function deploy()
    {
        $this->currentHealth = $this->config[self::CONFIG_VALUE_TOTAL_HEALTH];
        $this->maxHealth     = $this->config[self::CONFIG_VALUE_TOTAL_HEALTH];
        $this->attack        = $this->config[self::CONFIG_VALUE_ATTACK];

        if (!empty($this->config['attackRange'])) {
            $this->attackRange = $this->config['attackRange'];
        }

        if (!empty($this->config[self::CONFIG_VALUE_ARMOR])) {
            $this->armor    = $this->config[self::CONFIG_VALUE_ARMOR];
            $this->maxArmor = $this->config[self::CONFIG_VALUE_ARMOR];
        }
        if (!empty($this->config[self::CONFIG_VALUE_KEYWORDS])) {
            $this->keywords = $this->config[self::CONFIG_VALUE_KEYWORDS];
        }

        $this->card->game->log->logDeploy($this->card->game->playerTurnId, $this->card->id);

        $this->card->game->triggerEvent(Game::EVENT_TRIGGER_UNIT_DEPLOY, $this->card->id);
        $this->card->game->triggerEvent(Game::EVENT_TRIGGER_UNIT_DEPLOY_TO_CELL, $this->x . '_' . $this->y, ['cardId' => $this->card->id]);
    }

    public function render($playerId)
    {
        $data      = [
            'config' => $this->config,
            'attack' => $this->attack,
        ];
        list ($x, $y) = $this->card->game->convertCoordinats($this->x, $this->y, $playerId);

        $data['x'] = $x;
        $data['y'] = $y;
        $data['keywords'] = $this->keywords;
        $data['armor']    = $this->armor;

        $data['moveType']    = $this->getMoveType();
        $data['moveSteps']   = $this->getMoveSteps();
        $data['attackRange'] = $this->getAttackRange();
        $data['attackType'] = $this->attackType;


        if ($this->card->location == Card::CARD_LOCATION_FIELD) {
            $data['currentHealth'] = $this->currentHealth;
            $data['maxHealth']     = $this->maxHealth;
        }
        return $data;
    }

    public function attack()
    {
        if ($this->card->location != Card::CARD_LOCATION_FIELD) {
            throw new \Exception('Unit is trying to attack, but he is not on the Field!!');
        }

        $targets = $this->getTargets();

        if (!$targets) {
            $this->attackNoTargets();
            return;
        }
        // we have possible targets
        $target = $this->choseTarget($targets);
        $damage = $this->getDamage($target);

        $this->beforeAttack($damage, $target);

        $this->card->game->log->logAttack($this->card->id, $target->id);

        $damage = $target->unit->applyDamage($damage, $this);

        $this->afterAttack($damage, $target);

    }

    protected function getTargets()
    {
        $range   = $this->getAttackRange();
        $enemies = $this->card->game->getAllPlayerEnemies($this->card->owner);
        return $this->card->game->field->getAllPlayersUnitsInRange($this->x, $this->y, $range, $enemies);
    }

    /**
     * @param Card[] $targets
     *
     * @return Card
     */
    protected function choseTarget($targets)
    {
        if ($bloodthirstTaget = $this->isBloodthirst($targets)) {
            return $bloodthirstTaget;
        }
        $targets = $this->isFocusTargets($targets);
        return $targets[array_rand($targets)];
    }

    protected function isBloodthirst($targets)
    {
        if ($this->hasKeyword(self::KEYWORD_BLOODTHIRST)) {
            $theMostInjured = null;
            $theBiggestDamage = 0;
            foreach ($targets as $card) {
                $injure = $card->unit->maxHealth - $card->unit->currentHealth;
                if ($injure && $injure > $theBiggestDamage) {
                    $theMostInjured   = $card;
                    $theBiggestDamage = $injure;
                }
            }
            if ($theMostInjured) {
                return $theMostInjured;
            }
        }
        return false;
    }

    protected function isFocusTargets($targets)
    {
        $focusOnly = array_filter($targets, function ($card) {
            return $card->unit->hasKeyword(self::KEYWORD_FOCUS);
        });
        if ($focusOnly) {
            return $focusOnly;
        }
        return $targets;
    }

    protected function getDamage(Card $target)
    {
        if ($this->stepsMade > 0) {
            $damage = $this->attack[0];
        } else {
            $damage = rand($this->attack[0], $this->attack[1]);
        }
        $this->card->game->triggerEvent(Game::EVENT_UNIT_DEAL_DAMAGE, ['target' => $this, 'damage' => &$damage]);
        return $damage;
    }

    public function applyDamage($damage, $source = null)
    {
        if ($shield = $this->getShield()) {
            $damage -= $shield;
            if ($damage < 0) return 0;
        }
        if ($this->armor) {
            $dArmor = $this->changeArmor(-$damage);
            $damage += $dArmor;
        }
        if ($damage > 0) {
            $this->card->game->triggerEvent(Game::EVENT_UNIT_GET_DAMAGE, ['target' => $this, 'damage' => &$damage]);
        }
        $this->currentHealth -= $damage;

        $this->card->game->log->logUnitGetDamage($this->card->id, $this->currentHealth, $damage);

        if ($this->currentHealth <= 0) {
            $this->death($source);
        }
        return $damage;
    }

    public function healDamage($damage, Card $sourceCard) {
        $oldHealth = $this->currentHealth;
        $this->currentHealth += $damage;
        if ($this->currentHealth > $this->maxHealth) {
            $this->currentHealth = $this->maxHealth;
        }
        $damage = $oldHealth - $this->currentHealth;
        $this->card->game->log->logUnitGetDamage($this->card->id, $this->currentHealth, $damage);
        return $damage;
    }

    public function changeArmor($value) {
        $oldArmor = $this->armor;
        $this->armor += $value;
        if ($this->armor < 0) {
            $this->armor = 0;
        } else if ($this->armor > $this->maxArmor) {
            $this->armor = $this->maxArmor;
        }
        $dArmor = $this->armor - $oldArmor;

        $this->card->game->log->logUnitChangeArmor($this->card->id, $this->armor, $dArmor);

        return $dArmor;
    }

    public function death($source = null)
    {
        $this->death = true;
        $this->card->game->moveCards([$this->card], Game::LOCATION_FIELD, GAME::LOCATION_GRAVE);
        $this->card->game->log->logDeath($this->card->id);
        $this->onDeath();
        $this->card->game->triggerEvent(Game::EVENT_TRIGGER_UNIT_DEATH, $this->card->id);

        if ($source) {
            if ($source instanceof Unit) {
                $source->kills ++;
            }
        }
    }

    public function move($x, $y)
    {
        $this->beforeMove();
        $this->x = $x;
        $this->y = $y;
        $this->stepsMade += 1;

        $this->card->game->log->logMove($this->card->id, $this->x, $this->y);
    }

    public function checkIsUnitAbleToMove($x, $y)
    {
        //$distance = $this->card->game->field->getDistance($this->x, $this->y, $x, $y) + $this->stepsMade;
        $isPossibleMove = $this->checkMoveToCell($this->x, $this->y, $x, $y, $this->getMoveType());
        if (!$isPossibleMove) {
            throw new \Exception('This is not a possible cell to move');
        }
        $moveSteps = $this->getMoveSteps();

        return $moveSteps - $this->stepsMade;
    }

    public function endOfTurn()
    {
        $this->stepsMade = 0;
    }

    public function hasKeyword($word) {
        return in_array($word, $this->keywords);
    }
    public function addKeyword($word, $data = null) {
        if (!$this->hasKeyword($word)) {
            $this->keywords[] = $word;
        }
        switch ($word) {
            case self::KEYWORD_SHIELD:
                $this->data[self::KEYWORD_SHIELD] = $data;
                break;
        }
        $this->card->game->log->logUnitChange(
            $this->card->id,
            'keyword',
            ['words' => $this->keywords, 'data' => $this->data]
        );
    }
    public function removeKeyword($word)
    {
        if ($this->hasKeyword($word)) {
            $key = array_search($word, $this->keywords);
            unset($this->keywords[$key]);
            $this->card->game->log->logUnitChange($this->card->id, 'keyword', ['words' => $this->keywords, 'data' => $this->data]);
        }
    }
    public function setAttack($attack)
    {
        $this->attack = $attack;
        $this->card->game->log->logUnitChange($this->card->id, 'attack', ['value' => $attack[0] . ' - ' . $attack[1]]);
    }

    public function set($attribute, $value)
    {
        $this->{$attribute} = $value;
        $this->card->game->log->logUnitChange($this->card->id, $attribute, ['value' => $value]);
    }

    public function getShield()
    {
        if ($this->hasKeyword(self::KEYWORD_SHIELD)) {
            if (!isset($this->data[self::KEYWORD_SHIELD])) {
                throw new \Exception('Unit have shield, but value is missing');
            }
            return $this->data[self::KEYWORD_SHIELD];
        }
        return false;
    }

    public function getMoveType()
    {
        if (!empty($this->config['moveType'])) {
            return $this->config['moveType'];
        }
        return self::MOVE_TYPE_NORMAL;
    }

    public function getMoveSteps()
    {
        if (empty($this->config['moveSteps'])) {
            $moveSteps = self::DEFAULT_MOVE_DISTANCE;
        } else {
            $moveSteps = $this->config['moveSteps'];
        }
        return $moveSteps;
    }

    public function getAttackRange()
    {
        $range = self::DEFAULT_ATTACK_RANGE;
        if (isset($this->config['attackRange'])) {
            $range = $this->config['attackRange'];
        }
        if (!empty($this->attackRange)) {
            $range = $this->attackRange;
        }
        return $range;
    }

    protected function beforeAttack($damage, Card $target) {

    }
    protected function afterAttack($damage, Card $target) {

    }
    protected function onDeath() {

    }
    protected function beforeMove() {
        $this->card->game->triggerEvent(Game::EVENT_TRIGGER_BEFORE_UNIT_MOVE, $this->card->id);
        $this->card->game->triggerEvent(Game::EVENT_TRIGGER_UNIT_MOVE_FROM_CELL, $this->x . '_' . $this->y, ['cardId' => $this->card->id]);
    }
    public function afterMove() {
        $this->card->game->triggerEvent(Game::EVENT_TRIGGER_UNIT_MOVE_TO_CELL, $this->x . '_' . $this->y, ['cardId' => $this->card->id]);
        $this->card->game->triggerEvent(Game::EVENT_TRIGGER_AFTER_UNIT_MOVE, $this->card->id);
    }
    protected function attackNoTargets() {
        $this->card->game->log->logUnitSkip($this->card->id);
    }

}