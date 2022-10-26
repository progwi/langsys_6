<?php
// bootstrap.php
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

require_once __DIR__ . "/../vendor/autoload.php";

// Create a simple "default" Doctrine ORM configuration for Annotations
$isDevMode = true;
$proxyDir = null;
$cache = null;
$useSimpleAnnotationReader = false;
$config = ORMSetup::createAnnotationMetadataConfiguration(array(__DIR__ . ""), $isDevMode, $proxyDir, $cache, $useSimpleAnnotationReader);

// database configuration parameters
/**
 * sudo apt-get install php7.4-mysql
 */
$conn = array(
	'driver' => 'pdo_mysql',
	'user' => 'root',
	'password' => 'root',
	'dbname' => 'langsys_db'
);

// obtaining the entity manager
$entityManager = EntityManager::create($conn, $config);
