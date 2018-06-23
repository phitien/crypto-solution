<?php
$_REQUEST['routes'] = [
  'index' => [
    'controller' => 'Index',
    'actions' => [
      'signup' => ['public' => true, 'method' => 'post'],
      'signin' => ['public' => true, 'method' => 'post'],
      'forget' => ['public' => true, 'method' => 'post'],
      'reset' => ['public' => true, 'method' => 'post'],
      'activate' => ['public' => true, 'method' => 'get'],
      'password' => ['public' => false, 'method' => 'post'],
      'signout' => ['public' => false, 'method' => '*'],
      'profile' => ['public' => false, 'method' => ['get' => 'profile', 'post' => 'profileupdate']],
    ]
  ],
  'category' => [
    'controller' => 'Index',
    'actions' => [
      'index' => ['public' => true, 'method' => ['get' => 'listall', 'post' => 'create', 'put' => 'update', 'delete' => 'delete']],
    ]
  ],
  'country' => [
    'controller' => 'Index',
    'actions' => [
      'index' => ['public' => true, 'method' => ['get' => 'listall', 'post' => 'create', 'put' => 'update', 'delete' => 'delete']],
    ]
  ],
];
