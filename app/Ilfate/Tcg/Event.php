<?php
/**
 * ilfate.net
 * @autor Ilya Rubinchik ilfate@gmail.com
 * 2014
 */

namespace Ilfate\Tcg;

abstract class Event {

    /**
     * @var Game
     */
    public $game;

    public $data;

    public $eventTrigger;
    public $eventTarget;
    public $eventId;

    public function __construct($data, $game) {
        $this->data = $data;
        $this->game = $game;
    }

    abstract public function execute($target, $data = null);
} 