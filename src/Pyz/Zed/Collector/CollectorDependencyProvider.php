<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Collector;

use Pyz\Zed\Category\Communication\Plugin\CategoryNodeDataPageMapPlugin;
use Pyz\Zed\Cms\Communication\Plugin\CmsPageDataPageMapPlugin;
use Pyz\Zed\Collector\Communication\Plugin\AttributeMapCollectorStoragePlugin;
use Pyz\Zed\Collector\Communication\Plugin\AvailabilityCollectorStoragePlugin;
use Pyz\Zed\Collector\Communication\Plugin\BlockCollectorStoragePlugin;
use Pyz\Zed\Collector\Communication\Plugin\CategoryNodeCollectorSearchPlugin;
use Pyz\Zed\Collector\Communication\Plugin\CategoryNodeCollectorStoragePlugin;
use Pyz\Zed\Collector\Communication\Plugin\CmsPageCollectorSearchPlugin;
use Pyz\Zed\Collector\Communication\Plugin\NavigationCollectorStoragePlugin;
use Pyz\Zed\Collector\Communication\Plugin\PageCollectorStoragePlugin;
use Pyz\Zed\Collector\Communication\Plugin\ProductAbstractCollectorStoragePlugin;
use Pyz\Zed\Collector\Communication\Plugin\ProductCollectorSearchPlugin;
use Pyz\Zed\Collector\Communication\Plugin\ProductConcreteCollectorPlugin;
use Pyz\Zed\Collector\Communication\Plugin\ProductOptionCollectorStoragePlugin;
use Pyz\Zed\Collector\Communication\Plugin\RedirectCollectorStoragePlugin;
use Pyz\Zed\Collector\Communication\Plugin\TranslationCollectorStoragePlugin;
use Pyz\Zed\Collector\Communication\Plugin\UrlCollectorStoragePlugin;
use Pyz\Zed\ProductSearch\Communication\Plugin\ProductDataPageMapPlugin;
use Spryker\Shared\Availability\AvailabilityConfig;
use Spryker\Shared\Category\CategoryConstants;
use Spryker\Shared\Cms\CmsConstants;
use Spryker\Shared\ProductSearch\ProductSearchConfig;
use Spryker\Shared\Product\ProductConfig;
use Spryker\Zed\Collector\CollectorDependencyProvider as SprykerCollectorDependencyProvider;
use Spryker\Zed\Glossary\Business\Translation\TranslationManager;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductOption\ProductOptionConfig;
use Spryker\Zed\ProductSearch\Communication\Plugin\ProductSearchConfigExtensionCollectorPlugin;
use Spryker\Zed\Url\UrlConfig;

class CollectorDependencyProvider extends SprykerCollectorDependencyProvider
{

    const FACADE_PROPEL = 'propel facade';
    const FACADE_PRICE = 'price facade';
    const FACADE_SEARCH = 'search facade';
    const FACADE_PRODUCT_SEARCH = 'product search facade';
    const FACADE_PRODUCT = 'FACADE_PRODUCT';
    const FACADE_PRODUCT_OPTION_EXPORTER = 'product option exporter facade';

    const QUERY_CONTAINER_PRICE = 'price query container';
    const QUERY_CONTAINER_CATEGORY = 'category query container';
    const QUERY_CONTAINER_PRODUCT_CATEGORY = 'product category query container';
    const QUERY_CONTAINER_PRODUCT_IMAGE = 'product image query container';

    const PLUGIN_PRODUCT_DATA_PAGE_MAP = 'PLUGIN_PRODUCT_DATA_PAGE_MAP';
    const PLUGIN_CATEGORY_NODE_DATA_PAGE_MAP = 'PLUGIN_CATEGORY_NODE_DATA_PAGE_MAP';
    const PLUGIN_CMS_PAGE_DATA_PAGE_MAP = 'PLUGIN_CMS_PAGE_DATA_PAGE_MAP';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container[self::FACADE_PROPEL] = function (Container $container) {
            return $container->getLocator()->propel()->facade();
        };

        $container[self::FACADE_PRICE] = function (Container $container) {
            return $container->getLocator()->price()->facade();
        };

        $container[self::QUERY_CONTAINER_PRICE] = function (Container $container) {
            return $container->getLocator()->price()->queryContainer();
        };

        $container[self::QUERY_CONTAINER_CATEGORY] = function (Container $container) {
            return $container->getLocator()->category()->queryContainer();
        };

        $container[self::QUERY_CONTAINER_PRODUCT_CATEGORY] = function (Container $container) {
            return $container->getLocator()->productCategory()->queryContainer();
        };

        $container[self::FACADE_SEARCH] = function (Container $container) {
            return $container->getLocator()->search()->facade();
        };

        $container[self::FACADE_PRODUCT] = function (Container $container) {
            return $container->getLocator()->product()->facade();
        };

        $container[self::QUERY_CONTAINER_PRODUCT_IMAGE] = function (Container $container) {
            return $container->getLocator()->productImage()->queryContainer();
        };

        $container[self::SEARCH_PLUGINS] = function (Container $container) {
            return [
                ProductConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT => new ProductCollectorSearchPlugin(),
                CategoryConstants::RESOURCE_TYPE_CATEGORY_NODE => new CategoryNodeCollectorSearchPlugin(),
                CmsConstants::RESOURCE_TYPE_PAGE => new CmsPageCollectorSearchPlugin(),
            ];
        };

        $container[self::STORAGE_PLUGINS] = function (Container $container) {
            return [
                ProductConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT => new ProductAbstractCollectorStoragePlugin(),
                ProductConfig::RESOURCE_TYPE_PRODUCT_CONCRETE => new ProductConcreteCollectorPlugin(),
                ProductConfig::RESOURCE_TYPE_ATTRIBUTE_MAP => new AttributeMapCollectorStoragePlugin(),
                AvailabilityConfig::RESOURCE_TYPE_AVAILABILITY_ABSTRACT => new AvailabilityCollectorStoragePlugin(),
                CategoryConstants::RESOURCE_TYPE_CATEGORY_NODE => new CategoryNodeCollectorStoragePlugin(),
                CategoryConstants::RESOURCE_TYPE_NAVIGATION => new NavigationCollectorStoragePlugin(),
                TranslationManager::TOUCH_TRANSLATION => new TranslationCollectorStoragePlugin(),
                CmsConstants::RESOURCE_TYPE_PAGE => new PageCollectorStoragePlugin(),
                CmsConstants::RESOURCE_TYPE_BLOCK => new BlockCollectorStoragePlugin(),
                UrlConfig::RESOURCE_TYPE_REDIRECT => new RedirectCollectorStoragePlugin(),
                UrlConfig::RESOURCE_TYPE_URL => new UrlCollectorStoragePlugin(),
                ProductSearchConfig::RESOURCE_TYPE_PRODUCT_SEARCH_CONFIG_EXTENSION => new ProductSearchConfigExtensionCollectorPlugin(),
                ProductOptionConfig::RESOURCE_TYPE_PRODUCT_OPTION => new ProductOptionCollectorStoragePlugin(),
            ];
        };

        $container[self::PLUGIN_PRODUCT_DATA_PAGE_MAP] = function (Container $container) {
            return new ProductDataPageMapPlugin();
        };

        $container[self::PLUGIN_CATEGORY_NODE_DATA_PAGE_MAP] = function (Container $container) {
            return new CategoryNodeDataPageMapPlugin();
        };

        $container[self::PLUGIN_CMS_PAGE_DATA_PAGE_MAP] = function (Container $container) {
            return new CmsPageDataPageMapPlugin();
        };

        return $container;
    }

}
