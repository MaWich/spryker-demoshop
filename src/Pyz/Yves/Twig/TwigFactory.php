<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\Twig;

use Pyz\Yves\Twig\Model\AssetUrlBuilder;
use Pyz\Yves\Twig\Model\MediaUrlBuilder;
use Pyz\Yves\Twig\Model\UrlParameterCacheBuster;
use Pyz\Yves\Twig\Model\YvesExtension;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Config\Config;
use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\Kernel\Application;

class TwigFactory extends AbstractFactory
{

    /**
     * @var \Pyz\Yves\Twig\TwigSettings
     */
    private $settings;

    /**
     * @param \Spryker\Yves\Kernel\Application
     *
     * @return \Pyz\Yves\Twig\Model\YvesExtension
     */
    public function createYvesTwigExtension(Application $application)
    {
        return new YvesExtension($application, $this->getSettings());
    }

    /**
     * @return \Pyz\Yves\Twig\TwigSettings
     */
    protected function getSettings()
    {
        if (!isset($this->settings)) {
            $this->settings = new TwigSettings();
        }

        return $this->settings;
    }

    /**
     * @param bool $isDomainSecured
     *
     * @return \Pyz\Yves\Twig\Model\AssetUrlBuilderInterface
     */
    public function createAssetUrlBuilder($isDomainSecured = false)
    {
        $host = Config::get(ApplicationConstants::HOST_STATIC_ASSETS);

        if ($isDomainSecured) {
            $host = Config::get(ApplicationConstants::HOST_SSL_STATIC_ASSETS);
        }

        return new AssetUrlBuilder($host, $this->createCacheBuster());
    }

    /**
     * @param bool $isDomainSecured
     *
     * @return \Pyz\Yves\Twig\Model\MediaUrlBuilderInterface
     */
    public function createMediaUrlBuilder($isDomainSecured = false)
    {
        $host = Config::get(ApplicationConstants::HOST_STATIC_MEDIA);

        if ($isDomainSecured) {
            $host = Config::get(ApplicationConstants::HOST_SSL_STATIC_MEDIA);
        }

        return new MediaUrlBuilder($host);
    }

    /**
     * @return \Pyz\Yves\Twig\Model\CacheBusterInterface
     */
    protected function createCacheBuster()
    {
        $cacheBust = 'dev';
        $hashFile = APPLICATION_ROOT_DIR . '/config/Yves/cache_bust.php';

        if (file_exists($hashFile)) {
            $cacheBust = file_get_contents($hashFile);
        }

        return new UrlParameterCacheBuster($cacheBust);
    }

}
