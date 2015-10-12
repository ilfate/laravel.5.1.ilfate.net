<?php
/**
 * ilfate.net
 * @autor Ilya Rubinchik ilfate@gmail.com
 * 2014
 */

namespace Ilfate\Tcg\Spell;

use ClassPreloader\Config;
use Ilfate\Tcg\Events\ChangeUnit;
use Ilfate\Tcg\Exception;
use Ilfate\Tcg\Game;
use Ilfate\Tcg\Spell;
use Ilfate\Tcg\Card;
use Ilfate\Tcg\Unit;

class Heal extends Spell {

    public function castUnit(Card $target)
    {
        if ($target->unit->currentHealth >= $target->unit->maxHealth) {
            throw new Exception("This unit have full health", 1);
        }
        $target->unit->healDamage($this->config['data']['value'], $this->card);

        $this->logCast();
    }


}