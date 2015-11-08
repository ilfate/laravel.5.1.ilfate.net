<?php
$cell_type_normal = 'normal';
$cell_type_passage = 'passage';
return array(
    'game' => [
        'ship' => [
            'modules' => [
                'type1' => [
                    'cells' => [
                        // X X X
                        // X X X
                        //   X
                        0 => [
                            -1 => ['passableDirections' => [1,2]],
                            0 => ['passableDirections' => [1,2,3]],
                            1 => ['passableDirections' => [2,3]],
                        ],
                        1 => [
                            -1 => ['passableDirections' => [0,2]],
                            0 => ['passableDirections' => [0,1,2,3]],
                            1 => ['passableDirections' => [0,3]],
                        ],
                        2 => [
                            0 => ['passableDirections' => [0, 2], 'doors' => [2]],
                        ],
                    ]
                ],
                'Connection1' => [
                    'cells' => [
                        // X
                        // X
                        0 => [
                            0 => ['type' => $cell_type_passage, 'passableDirections' => [0,2], 'doors' => [0]],
                        ],
                        1 => [
                            0 => ['type' => $cell_type_passage, 'passableDirections' => [0,2], 'doors' => [2]],
                        ],
                    ]
                ],
                'Connection2' => [
                    'cells' => [
                        // X
                        // X X
                        0 => [
                            0 => ['type' => $cell_type_passage, 'passableDirections' => [0,2], 'doors' => [0]],
                        ],
                        1 => [
                            0 => ['type' => $cell_type_passage, 'passableDirections' => [0,1]],
                            1 => ['type' => $cell_type_passage, 'passableDirections' => [3,1], 'doors' => [1]],
                        ],
                    ]
                ],
            ],
            'systems' => [
                'type1' => [
                    'cells' => [
                        0 => [
                            0 => ['require' => $cell_type_normal]
                        ]
                    ]
                ]
            ],
        ]
    ],

);