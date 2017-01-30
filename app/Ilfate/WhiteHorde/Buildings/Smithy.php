<?php

namespace Ilfate\WhiteHorde\Buildings;

use Ilfate\WhiteHorde;

class Smithy extends WhiteHorde\WHBuilding
{
    protected static $slots = [
        'master', 'apprentice'
    ];
    protected static $slotsIncome = [
        'master' => [
            [WhiteHorde\Settlement::RESOURCE_STEEL, 1]
        ],
        'apprentice' => [
            [WhiteHorde\Settlement::RESOURCE_GOLD, 5]
        ],
    ];

    protected static $slotsRequirements = [
        'master' => [
            self::REQUIREMENT_TYPE_TRAITS => [
                WhiteHorde\WHTrait::STRONG,
                WhiteHorde\WHTrait::SMART,
            ],
            self::REQUIREMENT_TYPE_AGE => ['min' => 16, 'max' => 60]
            
        ]
    ];


}
