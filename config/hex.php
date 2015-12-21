<?php
$cell_type_normal = 'normal';
$cell_type_passage = 'passage';
return array(
    'game' => [
        'typesInRotation' => [
            'cube',
        ],
        'wallTypes' => [
            'start' => [
                [0, -2, [0]],
                [2, 0, [2]],
                [-2, 0, [2]],
                [0, 2, [0]],
                [2, -2, [1]],
                [-2, 2, [1]],
            ],
            'cube' => [
                [0, 0, [0, 1, 2]]
            ],
            'lineY' => [
                [0, -1, 'same'],
                [0, 0, 'same'],
                [0, 1, 'same'],
            ],
            'type2' => [
                [-1, 0],
                [0, 0],
                [0, 1],
            ],
        ]
    ]

);