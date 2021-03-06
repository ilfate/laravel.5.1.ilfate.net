<?php
/**
 * ilfate.net
 * @autor Ilya Rubinchik ilfate@gmail.com
 * 2014
 */

namespace Ilfate\Tcg\Spell;

use ClassPreloader\Config;
use Ilfate\Tcg\Events\ChangeUnit;
use Ilfate\Tcg\Game;
use Ilfate\Tcg\Spell;
use Ilfate\Tcg\Card;
use Ilfate\Tcg\Unit;

class Focus extends Spell {

    public function castUnit(Card $target)
    {

        $target->unit->addKeyword(Unit::KEYWORD_FOCUS);

        $this->card->game->addEvent(
            Game::EVENT_TRIGGER_END_OF_TURN,
            Game::EVENT_TARGET_NONE,
            '\Tcg\Events\ChangeUnit',
            ['action' => ChangeUnit::ACTION_REMOVE_KEYWORD, 'value' => ['word' => Unit::KEYWORD_FOCUS], 'target' => $target->id]
        );

        $this->logCast();

        //$this->card->game->log->logText(__CLASS__ . " spell was cast on " . $target->unit->name );
    }


}