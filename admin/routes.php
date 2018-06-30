<?php
Router::add('index', [
  'controller' => 'Index',
  'actions' => [
    'index' => ['public' => false],
  ]
]);
