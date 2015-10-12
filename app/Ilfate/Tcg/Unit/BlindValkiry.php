<?php
/**
 * ilfate.net
 * @autor Ilya Rubinchik ilfate@gmail.com
 * 2014
 */

namespace Ilfate\Tcg\Unit;

use ClassPreloader\Config;
use Ilfate\Tcg\Unit;
use Ilfate\Tcg\Card;

class BlindValkiry extends Unit {

	protected function afterAttack($damage, Card $target) {
    	$this->applyDamage(2, $this->card);    
    }
}