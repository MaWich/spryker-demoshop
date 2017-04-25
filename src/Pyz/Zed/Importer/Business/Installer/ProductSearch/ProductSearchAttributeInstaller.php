<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Importer\Business\Installer\ProductSearch;

use Pyz\Zed\Importer\Business\Installer\AbstractInstaller;
use Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface;
use Spryker\Zed\ProductSearch\Business\ProductSearchFacadeInterface;

class ProductSearchAttributeInstaller extends AbstractInstaller
{

    /**
     * @var \Spryker\Zed\ProductSearch\Business\ProductSearchFacadeInterface
     */
    protected $productSearchFacade;

    /**
     * @param \Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface $utilDataReaderService
     * @param array|\Pyz\Zed\Importer\Business\Importer\ImporterInterface[] $importerCollection
     * @param string $dataDirectory
     * @param \Spryker\Zed\ProductSearch\Business\ProductSearchFacadeInterface $productSearchFacade
     */
    public function __construct(UtilDataReaderServiceInterface $utilDataReaderService, array $importerCollection, $dataDirectory, ProductSearchFacadeInterface $productSearchFacade)
    {
        parent::__construct($utilDataReaderService, $importerCollection, $dataDirectory);

        $this->productSearchFacade = $productSearchFacade;
    }

    /**
     * @return \Spryker\Service\UtilDataReader\Model\BatchIterator\CountableIteratorInterface
     */
    protected function buildBatchIterator()
    {
        return $this->utilDataReaderService->getCsvBatchIterator($this->getCsvDataFilename());
    }

    /**
     * @return string
     */
    protected function getCsvDataFilename()
    {
        return $this->dataDirectory . '/product_search_attributes.csv';
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return 'Product Search Attributes';
    }

    /**
     * @return void
     */
    protected function afterInstall()
    {
        $this->productSearchFacade->touchProductAbstractByAsynchronousAttributes();
        $this->productSearchFacade->touchProductSearchConfigExtension();
    }

}
