<?php
Router::add('index', [
  'controller' => 'Index',
  'actions' => [
    'signup' => ['method' => 'post'],
    'signin' => ['method' => 'post'],
    'forget' => ['method' => 'post'],
    'reset' => ['method' => 'post'],
    'signout' => ['method' => '*'],
    'activate' => ['method' => 'get'],
    'password' => ['public' => false, 'method' => 'post'],
    'profile' => ['public' => false, 'method' => ['get' => 'profile', 'post' => 'profileupdate']],
  ]
]);
Router::add('group', [
  'controller' => 'Group',
  'actions' => [
    'index' => ['method' => ['get' => '*page', 'post' => '*create']],
    'users/:uid' => ['public' => false, 'method' => 'get'],
    'assign/:uid' => ['public' => false, 'method' => 'post'],
    'remove/:uid' => ['public' => false, 'method' => 'delete'],
  ]
]);
Router::add('menu');
Router::add('country');
