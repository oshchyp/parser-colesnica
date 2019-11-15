<?php
/**
 * Event manager configuration
 */
return [
        'EventManager' => [
            'Component\TestComponent::test' => [
                'before' => function($name, $price) {
                    return ['mod_'.$name,'mod_'.$price];
                },
            ],
        ]
];