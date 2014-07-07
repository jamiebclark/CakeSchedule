<?php
$this->extend('default');

echo $this->Layout->menu([
	['Projects', ['controller' => 'projects', 'action' => 'index']],
	['Tasks', ['controller' => 'tasks', 'action' => 'index']],
	['Time', ['controller' => 'times', 'action' => 'index']],
	['Week', ['controller' => 'weeks', 'action' => 'index']]
], ['class' => 'nav nav-tabs']);

echo $this->fetch('content');