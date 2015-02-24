<?php if (!defined('BASEPATH')) exit('No direct script access allowed.');
require_once(DOCTRINE_DIR . 'ORM/Tools/Setup.php');

use Doctrine\Common\ClassLoader,
    Doctrine\ORM\Configuration,
    Doctrine\ORM\EntityManager,
    Doctrine\Common\Cache\ArrayCache,
    Doctrine\DBAL\Logging\EchoSQLLogger;

class Doctrinelib
{
    private $_ci;
    private $em;

    function __construct()
    {
        require_once APPPATH . 'libs/Doctrine/Common/ClassLoader.php';

        $doctrineClassLoader = new ClassLoader('Doctrine', APPPATH . 'libs');
        $doctrineClassLoader->register();
        $entitiesClassLoader = new ClassLoader('Entities', APPPATH . 'models/cms/');
        $entitiesClassLoader->register();
		$entitiesClassLoader = new ClassLoader('Repositories', APPPATH . 'models/cms/');
        $entitiesClassLoader->register();
        $entitiesClassLoader = new ClassLoader('DoctrineExtensions', APPPATH . 'libs/Doctrine');
        $entitiesClassLoader->register();


        $config = new Configuration;
        $cache = new ArrayCache;
        $config->setMetadataCacheImpl($cache);
        $driverImpl = $config->newDefaultAnnotationDriver('Entities', APPPATH . 'models/cms/');
        $config->setMetadataDriverImpl($driverImpl);
        $config->setProxyDir(APPPATH . '/models/cms/proxies');
        $config->setProxyNamespace('Proxies');
        $config->setAutoGenerateProxyClasses(true);
        /*$logger = new EchoSQLLogger;
        $config->setSQLLogger($logger);*/

        $this->_ci = &get_instance();
        $this->_ci->load->database('default');
        $db = $this->_ci->db;
        $dbh = new PDO("mysql:host=$db->hostname;dbname=$db->database;charset=UTF8", $db->username, $db->password, array(
            PDO::ATTR_PERSISTENT => true
        ));

        $connectionParams = array(
            'pdo' => $dbh/*,
            'charset' => 'UTF8'*/
        );
        $this->em = EntityManager::create($connectionParams, $config);

        $this->em->getEventManager()->addEventSubscriber(
            new \Doctrine\DBAL\Event\Listeners\MysqlSessionInit('utf8', 'utf8_general_ci'));

        $doctrineConfig = $this->em->getConfiguration();
        $doctrineConfig->addCustomStringFunction('FIELD', 'DoctrineExtensions\Query\Mysql\Field');

    }

    /**
     * @return EntityManager
     */
    function get_entityManager()
    {
        return $this->em;
    }
}

