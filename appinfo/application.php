<?php
namespace OCA\Usage_Amount\AppInfo;

use \OCP\AppFramework\App;
use \OCA\Usage_Amount\Controller\PageController;

class Application extends App {
    public function __construct(array $urlParams=array()){
        parent::__construct('Usage_Amount', $urlParams);

        $container = $this->getContainer();
        $container->registerService('PageController', function($c) {
            return new PageController(
                $c->query('AppName'),
                $c->query('Request')
            );
        });
    }
}