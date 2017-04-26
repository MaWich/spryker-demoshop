<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Cms\Business;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Cms\Business\CmsFacadeInterface as SprykerCmsFacadeInterface;
use Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilderInterface;

interface CmsFacadeInterface extends SprykerCmsFacadeInterface
{

    /**
     * @param \Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilderInterface $pageMapBuilder
     * @param array $cmsData
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return \Generated\Shared\Transfer\PageMapTransfer
     */
    public function buildPageMap(PageMapBuilderInterface $pageMapBuilder, array $cmsData, LocaleTransfer $locale);

    /**
     * @param \Generated\Shared\Transfer\PageTransfer $pageTransfer
     * @param string $url
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function createPageUrlWithLocale($pageTransfer, $url, LocaleTransfer $localeTransfer);

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer
     */
    public function saveBlockAndTouch(CmsBlockTransfer $cmsBlockTransfer);

}
