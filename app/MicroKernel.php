<?php

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Class MicroKernel
 */
class MicroKernel extends Kernel
{
    /**
     * @return string
     */
    public function getCacheDir()
    {
        return dirname(__DIR__) . '/var/cache/' . $this->getEnvironment();
    }

    /**
     * @return string
     */
    public function getLogDir()
    {
        return dirname(__DIR__) . '/var/logs';
    }

    /**
     * @return string
     */
    public function getRootDir()
    {
        return __DIR__;
    }

    /**
     * Configure your configuration files here
     *
     * @param LoaderInterface $loader
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        # Load application configuration
        $loader->load($this->getRootDir() . '/config/config_' . $this->getEnvironment() . '.yml');
    }

    /**
     * Register all your bundles here
     *
     * @return array
     */
    public function registerBundles()
    {
        # Production bundles
        $bundles = [
            # Required bundles
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new \Symfony\Bundle\TwigBundle\TwigBundle(),

            # Application bundles
            new \AppBundle\AppBundle()
        ];

        # Development bundles
        if ('prod' != $this->getEnvironment()) {
            $bundles[] = new \Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new \Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new \Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new \Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }
}