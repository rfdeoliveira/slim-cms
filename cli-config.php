<?php

use \Symfony\Component\Console\Helper\HelperSet;
use \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;

require 'init.php';

return new HelperSet(array(
    'em' => new EntityManagerHelper($container['em'])
));
