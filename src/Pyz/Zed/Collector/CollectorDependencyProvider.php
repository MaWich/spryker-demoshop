<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Collector;

use Pyz\Zed\Category\Communication\Plugin\CategoryNodeDataPageMapPlugin;
use Pyz\Zed\Collector\Communication\Plugin\AttributeMapCollectorStoragePlugin;
use Pyz\Zed\Collector\Communication\Plugin\AvailabilityCollectorStoragePlugin;
use Pyz\Zed\Collector\Communication\Plugin\CategoryNodeCollectorSearchPlugin;
use Pyz\Zed\Collector\Communication\Plugin\CategoryNodeCollectorStoragePlugin;
use Pyz\Zed\Collector\Communication\Plugin\NavigationCollectorStoragePlugin;
use Pyz\Zed\Collector\Communication\Plugin\ProductAbstractCollectorStoragePlugin;
use Pyz\Zed\Collector\Communication\Plugin\ProductCollectorSearchPlugin;
use Pyz\Zed\Collector\Communication\Plugin\ProductConcreteCollectorPlugin;
use Pyz\Zed\Collector\Communication\Plugin\ProductOptionCollectorStoragePlugin;
use Pyz\Zed\Collector\Communication\Plugin\RedirectCollectorStoragePlugin;
use Pyz\Zed\Collector\Communication\Plugin\TranslationCollectorStoragePlugin;
use Pyz\Zed\ProductSearch\Communication\Plugin\ProductDataPageMapPlugin;
use Spryker\Shared\Availability\AvailabilityConfig;
use Spryker\Shared\Cms\CmsConstants;
use Spryker\Shared\CmsBlock\CmsBlockConfig;
use Spryker\Shared\CmsBlockCategoryConnector\CmsBlockCategoryConnectorConfig;
use Spryker\Shared\CmsBlockProductConnector\CmsBlockProductConnectorConstants;
use Spryker\Shared\Navigation\NavigationConfig;
use Spryker\Shared\Product\ProductConfig;
use Spryker\Shared\ProductCategoryFilter\ProductCategoryFilterConfig;
use Spryker\Shared\ProductCustomerPermission\ProductCustomerPermissionConfig;
use Spryker\Shared\ProductGroup\ProductGroupConfig;
use Spryker\Shared\ProductLabel\ProductLabelConstants;
use Spryker\Shared\ProductRelation\ProductRelationConstants;
use Spryker\Shared\ProductReview\ProductReviewConfig;
use Spryker\Shared\ProductSearch\ProductSearchConfig;
use Spryker\Shared\ProductSet\ProductSetConfig;
use Spryker\Zed\Category\CategoryConfig;
use Spryker\Zed\CmsBlockCategoryConnector\Communication\Plugin\CmsBlockCategoryConnectorCollectorPlugin;
use Spryker\Zed\CmsBlockCollector\Communication\Plugin\CmsBlockCollectorStoragePlugin;
use Spryker\Zed\CmsBlockProductConnector\Communication\Plugin\CmsBlockProductConnectorCollectorPlugin;
use Spryker\Zed\CmsCollector\Communication\Plugin\CmsVersionPageCollectorSearchPlugin;
use Spryker\Zed\CmsCollector\Communication\Plugin\CmsVersionPageCollectorStoragePlugin;
use Spryker\Zed\Collector\CollectorDependencyProvider as SprykerCollectorDependencyProvider;
use Spryker\Zed\Glossary\Business\Translation\TranslationManager;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\NavigationCollector\Communication\Plugin\NavigationMenuCollectorStoragePlugin;
use Spryker\Zed\ProductCategoryFilterCollector\Communication\Plugin\ProductCategoryFilterCollectorPlugin;
use Spryker\Zed\ProductCustomerPermissionCollector\Communication\Plugin\ProductCustomerPermissionCollectorSearchPlugin;
use Spryker\Zed\ProductCustomerPermissionCollector\Communication\Plugin\ProductCustomerPermissionCollectorStoragePlugin;
use Spryker\Zed\ProductGroupCollector\Communication\Plugin\ProductAbstractGroupsCollectorStoragePlugin;
use Spryker\Zed\ProductGroupCollector\Communication\Plugin\ProductGroupCollectorStoragePlugin;
use Spryker\Zed\ProductLabelCollector\Communication\Plugin\ProductLabelDictionaryCollectorStoragePlugin;
use Spryker\Zed\ProductLabelCollector\Communication\Plugin\ProductLabelProductAbstractRelationCollectorStoragePlugin;
use Spryker\Zed\ProductOption\ProductOptionConfig;
use Spryker\Zed\ProductRelationCollector\Communication\Plugin\ProductRelationCollectorPlugin;
use Spryker\Zed\ProductReviewCollector\Communication\Plugin\ProductAbstractReviewCollectorStoragePlugin;
use Spryker\Zed\ProductReviewCollector\Communication\Plugin\ProductReviewCollectorSearchPlugin;
use Spryker\Zed\ProductSearch\Communication\Plugin\ProductSearchConfigExtensionCollectorPlugin;
use Spryker\Zed\ProductSetCollector\Communication\Plugin\ProductSetCollectorSearchPlugin;
use Spryker\Zed\ProductSetCollector\Communication\Plugin\ProductSetCollectorStoragePlugin;
use Spryker\Zed\Url\UrlConfig;
use Spryker\Zed\UrlCollector\Communication\Plugin\UrlCollectorPlugin;

class CollectorDependencyProvider extends SprykerCollectorDependencyProvider
{
    const SERVICE_UTIL_DATA_READER = 'SERVICE_UTIL_DATA_READER';

    const FACADE_PROPEL = 'FACADE_PROPEL';
    const FACADE_PRICE_PRODUCT = 'FACADE_PRICE_PRODUCT';
    const FACADE_SEARCH = 'FACADE_SEARCH';
    const FACADE_PRODUCT = 'FACADE_PRODUCT';
    const FACADE_PRODUCT_OPTION = 'FACADE_PRODUCT_OPTION';
    const FACADE_PRODUCT_IMAGE = 'FACADE_PRODUCT_IMAGE';

    const QUERY_CONTAINER_CATEGORY = 'QUERY_CONTAINER_CATEGORY';
    const QUERY_CONTAINER_PRODUCT_CATEGORY = 'QUERY_CONTAINER_PRODUCT_CATEGORY';
    const QUERY_CONTAINER_PRODUCT_IMAGE = 'QUERY_CONTAINER_PRODUCT_IMAGE';
    const QUERY_CONTAINER_PRODUCT_OPTION = 'QUERY_CONTAINER_PRODUCT_OPTION';

    const PLUGIN_PRODUCT_DATA_PAGE_MAP = 'PLUGIN_PRODUCT_DATA_PAGE_MAP';
    const PLUGIN_CATEGORY_NODE_DATA_PAGE_MAP = 'PLUGIN_CATEGORY_NODE_DATA_PAGE_MAP';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $this->addPropelFacade($container);
        $this->addSearchFacade($container);
        $this->addProductFacade($container);
        $this->addProductImageFacade($container);
        $this->addPriceProductFacade($container);
        $this->addProductOptionFacade($container);

        $this->addCategoryQueryContainer($container);
        $this->addProductCategoryQueryContainer($container);
        $this->addProductImageQueryContainer($container);
        $this->addProductOptionQueryContainer($container);

        $this->addSearchPluginStack($container);
        $this->addStoragePluginStack($container);

        $this->getProductDataMapPlugin($container);
        $this->getCategoryNodeDataPageMapPlugin($container);

        $this->addUtilDataReaderService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addPropelFacade(Container $container)
    {
        $container[self::FACADE_PROPEL] = function (Container $container) {
            return $container->getLocator()->propel()->facade();
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addPriceProductFacade(Container $container)
    {
        $container[self::FACADE_PRICE_PRODUCT] = function (Container $container) {
            return $container->getLocator()->priceProduct()->facade();
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addSearchFacade(Container $container)
    {
        $container[self::FACADE_SEARCH] = function (Container $container) {
            return $container->getLocator()->search()->facade();
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addProductImageFacade(Container $container)
    {
        $container[static::FACADE_PRODUCT_IMAGE] = function (Container $container) {
            return $container->getLocator()->productImage()->facade();
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addProductFacade(Container $container)
    {
        $container[self::FACADE_PRODUCT] = function (Container $container) {
            return $container->getLocator()->product()->facade();
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addCategoryQueryContainer(Container $container)
    {
        $container[self::QUERY_CONTAINER_CATEGORY] = function (Container $container) {
            return $container->getLocator()->category()->queryContainer();
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addProductCategoryQueryContainer(Container $container)
    {
        $container[self::QUERY_CONTAINER_PRODUCT_CATEGORY] = function (Container $container) {
            return $container->getLocator()->productCategory()->queryContainer();
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addProductImageQueryContainer(Container $container)
    {
        $container[self::QUERY_CONTAINER_PRODUCT_IMAGE] = function (Container $container) {
            return $container->getLocator()->productImage()->queryContainer();
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addProductOptionQueryContainer(Container $container)
    {
        $container[static::QUERY_CONTAINER_PRODUCT_OPTION] = function (Container $container) {
            return $container->getLocator()->productOption()->queryContainer();
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addProductOptionFacade(Container $container)
    {
        $container[static::FACADE_PRODUCT_OPTION] = function (Container $container) {
            return $container->getLocator()->productOption()->facade();
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addSearchPluginStack(Container $container)
    {
        $container[self::SEARCH_PLUGINS] = function () {
            return [
                ProductConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT => new ProductCollectorSearchPlugin(),
                CategoryConfig::RESOURCE_TYPE_CATEGORY_NODE => new CategoryNodeCollectorSearchPlugin(),
                CmsConstants::RESOURCE_TYPE_PAGE => new CmsVersionPageCollectorSearchPlugin(),
                ProductSetConfig::RESOURCE_TYPE_PRODUCT_SET => new ProductSetCollectorSearchPlugin(),
                ProductReviewConfig::RESOURCE_TYPE_PRODUCT_REVIEW => new ProductReviewCollectorSearchPlugin(),
                ProductCustomerPermissionConfig::RESOURCE_TYPE_PRODUCT_CUSTOMER_PERMISSION => new ProductCustomerPermissionCollectorSearchPlugin(),
            ];
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addStoragePluginStack(Container $container)
    {
        $container[self::STORAGE_PLUGINS] = function () {
            return [
                ProductConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT => new ProductAbstractCollectorStoragePlugin(),
                ProductConfig::RESOURCE_TYPE_PRODUCT_CONCRETE => new ProductConcreteCollectorPlugin(),
                ProductConfig::RESOURCE_TYPE_ATTRIBUTE_MAP => new AttributeMapCollectorStoragePlugin(),
                AvailabilityConfig::RESOURCE_TYPE_AVAILABILITY_ABSTRACT => new AvailabilityCollectorStoragePlugin(),
                CategoryConfig::RESOURCE_TYPE_CATEGORY_NODE => new CategoryNodeCollectorStoragePlugin(),
                CategoryConfig::RESOURCE_TYPE_NAVIGATION => new NavigationCollectorStoragePlugin(),
                NavigationConfig::RESOURCE_TYPE_NAVIGATION_MENU => new NavigationMenuCollectorStoragePlugin(),
                TranslationManager::TOUCH_TRANSLATION => new TranslationCollectorStoragePlugin(),
                CmsConstants::RESOURCE_TYPE_PAGE => new CmsVersionPageCollectorStoragePlugin(),
                CmsBlockConfig::RESOURCE_TYPE_CMS_BLOCK => new CmsBlockCollectorStoragePlugin(),
                CmsBlockCategoryConnectorConfig::RESOURCE_TYPE_CMS_BLOCK_CATEGORY_CONNECTOR => new CmsBlockCategoryConnectorCollectorPlugin(),
                CmsBlockProductConnectorConstants::RESOURCE_TYPE_CMS_BLOCK_PRODUCT_CONNECTOR => new CmsBlockProductConnectorCollectorPlugin(),
                UrlConfig::RESOURCE_TYPE_REDIRECT => new RedirectCollectorStoragePlugin(),
                UrlConfig::RESOURCE_TYPE_URL => new UrlCollectorPlugin(),
                ProductSearchConfig::RESOURCE_TYPE_PRODUCT_SEARCH_CONFIG_EXTENSION => new ProductSearchConfigExtensionCollectorPlugin(),
                ProductOptionConfig::RESOURCE_TYPE_PRODUCT_OPTION => new ProductOptionCollectorStoragePlugin(),
                ProductRelationConstants::RESOURCE_TYPE_PRODUCT_RELATION => new ProductRelationCollectorPlugin(),
                ProductGroupConfig::RESOURCE_TYPE_PRODUCT_GROUP => new ProductGroupCollectorStoragePlugin(),
                ProductGroupConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT_GROUPS => new ProductAbstractGroupsCollectorStoragePlugin(),
                ProductLabelConstants::RESOURCE_TYPE_PRODUCT_LABEL_DICTIONARY => new ProductLabelDictionaryCollectorStoragePlugin(),
                ProductLabelConstants::RESOURCE_TYPE_PRODUCT_ABSTRACT_PRODUCT_LABEL_RELATIONS => new ProductLabelProductAbstractRelationCollectorStoragePlugin(),
                ProductSetConfig::RESOURCE_TYPE_PRODUCT_SET => new ProductSetCollectorStoragePlugin(),
                ProductReviewConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT_REVIEW => new ProductAbstractReviewCollectorStoragePlugin(),
                ProductCategoryFilterConfig::RESOURCE_TYPE_PRODUCT_CATEGORY_FILTER => new ProductCategoryFilterCollectorPlugin(),
                ProductCustomerPermissionConfig::RESOURCE_TYPE_PRODUCT_CUSTOMER_PERMISSION => new ProductCustomerPermissionCollectorStoragePlugin(),
            ];
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function getProductDataMapPlugin(Container $container)
    {
        $container[self::PLUGIN_PRODUCT_DATA_PAGE_MAP] = function () {
            return new ProductDataPageMapPlugin();
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function getCategoryNodeDataPageMapPlugin(Container $container)
    {
        $container[self::PLUGIN_CATEGORY_NODE_DATA_PAGE_MAP] = function () {
            return new CategoryNodeDataPageMapPlugin();
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addUtilDataReaderService(Container $container)
    {
        $container[static::SERVICE_UTIL_DATA_READER] = function (Container $container) {
            return $container->getLocator()->utilDataReader()->service();
        };
    }
}
