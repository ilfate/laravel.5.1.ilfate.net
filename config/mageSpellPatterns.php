<?php

return array(


    'list' => [
        1 => [ // small ring
            [-1, -1],
            [0, -1],
            [1, -1],
            [1, 0],
            [1, 1],
            [0, 1],
            [-1, 0],
            [-1, 1],
        ],
        2 => [ // big ring
            [0, -2],
            [-2, -2],
            [2, 0],
            [2, -2],
            [0, 2],
            [2, 2],
            [-2, 0],
            [-2, 2],
        ],
        3 => [
            [0, -2],
            [2, 0],
            [0, 2],
            [-2, 0],
            [-1, -1],
            [1, -1],
            [1, 1],
            [-1, 1],
        ],
        4 => [
            [-1, -1],
            [0, -1],
            [1, -1],
            [-2, -2],
            [-1, -2],
            [0, -2],
            [1, -2],
            [2, -2],
        ],
        5 => [ // front blast
            [0, -1],
            [-1, -2],
            [0, -2],
            [1, -2],
            [-1, -3],
            [0, -3],
            [1, -3],
            [-1, -4],
            [0, -4],
            [1, -4],
        ],
        6 => [ // front blast wide
            [0, -1],
            [-1, -2],
            [0, -2],
            [1, -2],
            [-2, -3],
            [-1, -3],
            [0, -3],
            [1, -3],
            [2, -3],
        ],
        7 => [ // front blast long and narrow
            [0, -1],
            [0, -2],
            [0, -3],
            [-1, -4],
            [0, -4],
            [1, -4],
            [-1, -5],
            [0, -5],
            [1, -5],
            [-1, -6],
            [1, -6],
        ],
        8 => [ // One distant dot in each direction
            [0, -4],
        ],
        9 => [ // One distant dot in each direction
            [0, -5],
        ],
        10 => [ //for rain of fire
            [-2, -3],[-1, -3],[0, -3],[1, -3],[2, -3],    
            [-2, -2],[-1, -2],[0, -2],[1, -2],[2, -2],
        ],
        11 => [ //for rain of fire
            [-1, -4],[0, -4],[1, -4],    
            [-1, -3],[0, -3],[1, -3],    
            [-1, -2],[0, -2],[1, -2],
        ],
        12 => [ //for rain of fire
            [-2, -5],[-1, -5],[0, -5],[1, -5],[2, -5],
            [-2, -4],[-1, -4],[0, -4],[1, -4],[2, -4],    
            [-2, -3],[-1, -3],[0, -3],[1, -3],[2, -3],
        ],
        13 => [ //for rain of fire
            [-4, -5],[-3, -5],[-2, -5],[-1, -5],[0, -5],[1, -5],[2, -5],[3, -5],[4, -5],
            [-4, -4],[-3, -4],[-2, -4],[-1, -4],[0, -4],[1, -4],[2, -4],[3, -4],[4, -4],
        ],
        14 => [ // for Ice Wall // Used in animations
            [-2, -2],[-1, -2],[0, -2],[1, -2],[2, -2],
        ],
        15 => [ // for Ice Wall // Used in animations
            [-2, -3],[-1, -3],[0, -3],[1, -3],[2, -3],
        ],
        16 => [ // for Ice Cone
            [-2, -3],[-1, -3],[0, -3],[1, -3],[2, -3],
            [-1, -2],[0, -2],[1, -2],
            [0, -1],
        ],
        17 => [ // for Ice Cone
            [1, -3],[2, -2],[3, -1],
            [1, -2],[2, -1],
            [1, -1],
        ],
        18 => [ // for Ice Cone
            [2, -4],[3, -3],[4, -2],
            [2, -3],[3, -2],
            [2, -2],
            [1, -2],[2, -1],
            [1, -1],
        ],
        19 => [ // for FreshWaterFountain
            [0, -1],
        ],
        20 => [ // Sky Fist
            [0, -3],
        ],
        21 => [ // Sky Fist
            [0, -2],
        ],
        22 => [ // Sky Fist
            [3, -3],
        ],
        23 => [ // Sky Fist
            [2, -2],
        ],
        24 => [ // HardLanding
            [0, -4],
            [4, -4],
            [-4, -4],
        ],
        25 => [ // HardLanding
            [0, -3],
            [3, -3],
            [-3, -3],
        ],
        26 => [ // Wind Sword
            [0, -1],
            [0, -2],
            [0, -3],
            [0, -4],
            [0, -5],
        ],
        27 => [ // Wind Sword
            [1, -1],
            [2, -2],
            [3, -3],
            [4, -4],
            [5, -5],
        ],
    ],

);