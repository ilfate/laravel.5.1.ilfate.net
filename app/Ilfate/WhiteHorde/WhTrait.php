<?php
/**
 * TODO: Package description.
 *
 * PHP version 5
 *
 * @category
 * @package
 * @author    Ilya Rubinchik <ilfate@gmail.com>
 * @copyright 2016 Watchmaster GmbH
 * @license   Proprietary license.
 * @link      http://www.watchmaster.de
 */
namespace Ilfate\WhiteHorde;
use Ilfate\MageSurvival\ChanceHelper;
use Illuminate\Support\Collection;

/**
 * TODO: Short description.
 * TODO: Long description here.
 *
 * PHP version 5
 *
 * @category
 * @package
 * @author    Ilya Rubinchik <ilfate@gmail.com>
 * @link      http://ilfate.net
 */
class WHTrait extends Collection
{
    const TYPE_POSITIVE = 'positive';
    const TYPE_NEGATIVE = 'negative';
    const TYPE_COMBAT_NEGATIVE = 'combatNegative';
    const TYPE_COMBAT_POSITIVE = 'combatPositive';
    const TYPE_BODY_INJURIES = 'bodyInjuries';
    const TYPE_PROFESSION = 'profession';
    const TYPE_SEXUAL_DISORDER = 'sexualDisorder';
    const TYPE_ONLY_FEMALE = 'onlyFemale';
    const TYPE_ONLY_MALE = 'onlyMale';

    const STRONG         = 'strong';
    const WEAK           = 'weak';
    const INFIRM         = 'infirm';
    const COWARD         = 'coward';
    const BRAVE          = 'brave';
    const SMART          = 'smart';
    const STUPID         = 'stupid';
    const GREEDY         = 'greedy';
    const GENEROUS       = 'generous';
    const SLOW           = 'slow';
    const FAST           = 'fast';
    const SHORT_SIGHTED  = 'short-sighted';
    const FARSIGHTED     = 'farsighted';
    const EGOIST         = 'egoist';

    const STERILE        = 'sterile';

    const DEAF       = 'deaf';
    const BLIND      = 'blind';
    const ONE_EYED   = 'one-eyed';
    const ONE_HANDED = 'one-handed';
    const ONE_LEGGED = 'one-legged';
    const ADACTYL    = 'adactyl';

    public static $fullList = [
        self::STRONG,
        self::WEAK,
    ];

    protected $name = '';

    protected static $positive = [
        self::STRONG,
        self::BRAVE,
        self::SMART,
        self::GENEROUS,
        self::FAST,
    ];

    protected static $negative = [
        self::WEAK,
        self::INFIRM,
        self::COWARD,
        self::STUPID,
        self::GREEDY,
        self::SLOW,
        self::SHORT_SIGHTED,
        self::FARSIGHTED,
        self::BLIND,
        self::DEAF,
        self::ONE_EYED,
        self::ONE_HANDED,
        self::ONE_LEGGED,
        self::ADACTYL,
        self::STERILE,
        self::EGOIST,
    ];
    protected static $combatNegative = [
        self::WEAK,
        self::INFIRM,
        self::COWARD,
        self::SLOW,
        self::SHORT_SIGHTED,
    ];
    protected static $combatPositive = [
        self::STRONG,
        self::BRAVE,
        self::FAST,
    ];
    protected static $bodyInjuries = [
        self::BLIND,
        self::DEAF,
        self::ONE_EYED,
        self::ONE_HANDED,
        self::ONE_LEGGED,
        self::ADACTYL,
    ];
    protected static $onlyMale = [

    ];
    protected static $onlyFemale = [

    ];
    protected static $profession = [

    ];
    protected static $opposite = [
        self::WEAK => self::STRONG,
        self::STRONG => self::WEAK,
        self::COWARD => self::BRAVE,
        self::BRAVE => self::COWARD,
        self::SMART => self::STUPID,
        self::STUPID => self::SMART,
        self::GREEDY => self::GENEROUS,
        self::GENEROUS => self::GREEDY,
        self::SLOW => self::FAST,
        self::FAST => self::SLOW,
        self::FARSIGHTED => self::SHORT_SIGHTED,
        self::SHORT_SIGHTED => self::FARSIGHTED,
    ];

    protected static $sexualDisorder = [
        self::STERILE,
    ];

    /**
     *
     * @param  string $name
     * @return void
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    public static function isType($name, $type) {
        return in_array($name, self::$$type);
    }

    public static function getOpposite($name)
    {
        if (!empty(self::$opposite[$name])) {
            return self::$opposite[$name];
        }
        return null;
    }

    public static function getRandomFromType($type) {
        if (empty(self::$$type)) {
            throw new \Exception('wtf? trait type ' . $type . ' does not exist');
        }
        return ChanceHelper::oneFromArray(self::$$type);
    }

}