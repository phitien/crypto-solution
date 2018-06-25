<?php
Router::add('index', [
  'controller' => 'Index',
  'actions' => [
    'json' => ['mime-type' => 'json'],
    'json' => ['public' => true, 'mime-type' => 'json'],
    'json' => ['public' => true, 'method' => 'get', 'mime-type' => 'json'],
    'json' => ['public' => true, 'method' => ['handler' => 'json', 'mime-type' => 'json']],
    'json' => ['public' => true, 'method' => ['get' => 'json', 'post' => '*json', 'mime-type' => 'json']],
    'json' => ['public' => true, 'method' => [
      'get' => ['handler' => 'json', 'mime-type' => 'json'],
      'post' => 'json',
      'mime-type' => 'json'
    ]],
  ]
]);
