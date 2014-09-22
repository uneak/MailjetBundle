<?php

namespace Uneak\MailjetBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Config\Definition\Processor;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class MailjetExtension extends Extension {

	/**
	 * {@inheritDoc}
	 */
	public function load(array $configs, ContainerBuilder $container) {
		$processor = new Processor();
		$configuration = new Configuration();
		$loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
		
		$config = $processor->processConfiguration($configuration, $configs);
		$container->setParameter('mailjet.api_key', $config['api_key']);
		$container->setParameter('mailjet.api_secret', $config['api_secret']);
		$loader->load('mailjet.yml');
		$loader->load('twig.yml');
	}

}
