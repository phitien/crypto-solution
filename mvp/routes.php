<?php
Router::add('index', [
  'controller' => 'Index',
  'actions' => [
    'search' => [],
    'category/:uid' => [],
  ]
]);
