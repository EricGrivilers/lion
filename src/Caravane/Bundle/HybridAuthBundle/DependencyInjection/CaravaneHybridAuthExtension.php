<?php

namespace Caravane\Bundle\HybridAuthBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;


use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\YamlFileLoader;

use Symfony\Component\Routing\Generator\UrlGenerator;



class CaravaneHybridAuthExtension extends Extension
{

    public function load(array $configs, ContainerBuilder $container)
    {

        $host=$container->getParameter('host');
        $scheme=$container->getParameter('scheme');

        $env = $container->getParameter("kernel.environment");

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $base_url=null;
        if($env=="dev") {
            $base_url="/app_dev.php";
        }
        //php_sapi_name()=='cli'?$base='localhost':$base=$_SERVER['SERVER_NAME'];
        
        $context = new RequestContext($base_url,'GET',$host);

        $context->setHost($host);
        $context->setScheme($scheme);
        $context->setBaseUrl('');



        $locator = new FileLocator(array(__DIR__."/../Resources/config"));
        $loader = new YamlFileLoader($locator, $context);
        $collection = $loader->load('routing.yml');

        $generator = new UrlGenerator($collection, $context);
        $config['base_url'] = $generator->generate($config['base_url'], array(),true);
        $container->setParameter('caravane_hybrid_auth.config',$config);


        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }



}
