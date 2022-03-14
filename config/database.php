<?php

return [
    'default' => 'web',

    // 'connections' => [
    //     'erp' => [
    //         'driver'    => 'mysql',
    //         'host'      => '209.145.49.170',
    //         'database'  => 'pr_bovisa',
    //         'username'  => 'pr_bovisa',
    //         'password'  => 'Pr_bovisa_2021*',
    //         'charset'   => 'utf8',
    //         'collation' => 'utf8_unicode_ci',
    //         'prefix'    => '',
    //     ]
    // ],

    'connections' => [
        'web' => [
            'driver'    => 'mysql',
            'host'      => 'localhost',
            'database'  => 'web_provedores',
            'username'  => 'root',
            'password'  => '',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ],
        'avalanz' => [
			'driver'        => 'oracle',
            'service_name'  => 'Avalanz',
			'host'          => '10.12.0.3',
			'port'          => '1521',
			'database'      => 'DADOSADV',
			'username'      => 'CNCI',
			'password'      => 'cnci01',
			'charset'       => 'AL32UTF8',
			'prefix'        => '',
			'prefix_schema' => '',
		],
    ],
];

?>