<?php
/**
 * ilfate.net
 * @autor Ilya Rubinchik ilfate@gmail.com
 * 2014
 */

namespace Ilfate\Tcg\Spell;

use ClassPreloader\Config;
use Ilfate\Tcg\Game;
use Ilfate\Tcg\Spell;
use Ilfate\Tcg\Card;
use Ilfate\Tcg\Unit;

class Axe extends Spell {

    public function castUnit(Card $target)
    {

        $target->unit->applyDamage(6, $this);

        $x = $target->unit->x;
        $y = $target->unit->y;
        $this->card->game->addEvent(
            Game::EVENT_TRIGGER_UNIT_MOVE_TO_CELL,
            $x . '_' . $y,
            '\Tcg\Events\GetAxe'
        );

        $this->logCast();

        //$this->card->game->log->logText(__CLASS__ . " spell was cast on " . $target->unit->name );
    }


}