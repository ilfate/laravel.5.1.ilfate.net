<?php
/**
 * ilfate.net
 * @autor Ilya Rubinchik ilfate@gmail.com
 * 2014
 */

namespace Ilfate\Tcg\Unit;

use ClassPreloader\Config;
use Ilfate\Tcg\Game;
use Ilfate\Tcg\Unit;
use Ilfate\Tcg\Card;

class AxeThrower extends UnitCanThrowAxe {

    public function deploy()
    {
        parent::deploy();
        $this->setAttack([6, 6]);
        $this->set('attackRange', 3);
        $this->data['axe'] = true;
    }
}