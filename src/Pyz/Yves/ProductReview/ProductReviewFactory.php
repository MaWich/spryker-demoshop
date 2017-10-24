<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ProductReview;

use Pyz\Yves\ProductReview\Controller\Calculator\ProductReviewSummaryCalculator;
use Pyz\Yves\ProductReview\Form\DataProvider\ProductReviewFormDataProvider;
use Pyz\Yves\ProductReview\Form\ProductReviewForm;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\ProductReview\ProductReviewFactory as SprykerProductReviewFactory;

class ProductReviewFactory extends SprykerProductReviewFactory
{
    /**
     * @return \Pyz\Client\Customer\CustomerClientInterface
     */
    public function getCustomerClient()
    {
        return $this->getProvidedDependency(ProductReviewDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \Spryker\Client\Product\ProductClientInterface
     */
    public function getProductClient()
    {
        return $this->getProvidedDependency(ProductReviewDependencyProvider::CLIENT_PRODUCT);
    }

    /**
     * @return \Spryker\Client\ProductReview\ProductReviewClientInterface
     */
    public function getProductReviewClient()
    {
        return $this->getProvidedDependency(ProductReviewDependencyProvider::CLIENT_PRODUCT_REVIEW);
    }

    /**
     * @return \Symfony\Component\Form\FormFactory
     */
    protected function getFormFactory()
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createProductReviewForm($idProductAbstract)
    {
        $dataProvier = $this->createProductReviewFormDataProvider();
        $form = $this->getFormFactory()->create(
            new ProductReviewForm($this->getProductReviewClient()),
            $dataProvier->getData($idProductAbstract),
            $dataProvier->getOptions()
        );

        return $form;
    }

    /**
     * @return \Pyz\Yves\ProductReview\Controller\Calculator\ProductReviewSummaryCalculatorInterface
     */
    public function createProductReviewSummaryCalculator()
    {
        return new ProductReviewSummaryCalculator($this->getProductReviewClient());
    }

    /**
     * @return \Pyz\Yves\ProductReview\Form\DataProvider\ProductReviewFormDataProvider
     */
    protected function createProductReviewFormDataProvider()
    {
        return new ProductReviewFormDataProvider();
    }
}
