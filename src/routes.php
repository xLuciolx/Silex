<?php

use TechNews\Controller\Provider\NewsControllerProvider;
use TechNews\Controller\Provider\AdminControllerProvider;

/*gestion de nos controller via ControllerProvider*/
$app->mount('/', new NewsControllerProvider());
$app->mount('/admin', new AdminControllerProvider());
