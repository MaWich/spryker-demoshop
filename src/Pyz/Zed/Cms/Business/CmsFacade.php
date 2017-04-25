<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Cms\Business;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Cms\Business\CmsFacade as SprykerCmsFacade;
use Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilderInterface;

/**
 * @method \Pyz\Zed\Cms\Business\CmsBusinessFactory getFactory()
 */
class CmsFacade extends SprykerCmsFacade implements CmsFacadeInterface
{

    /**
     * @param \Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilderInterface $pageMapBuilder
     * @param array $cmsData
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return \Generated\Shared\Transfer\PageMapTransfer
     */
    public function buildPageMap(PageMapBuilderInterface $pageMapBuilder, array $cmsData, LocaleTransfer $locale)
    {
        return $this
            ->getFactory()
            ->createCmsDataPageMapBuilder()
            ->buildPageMap($pageMapBuilder, $cmsData, $locale);
    }

    /**
     * @param \Generated\Shared\Transfer\PageTransfer $pageTransfer
     * @param string $url
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function createPageUrlWithLocale($pageTransfer, $url, LocaleTransfer $localeTransfer)
    {
        return $this->getFactory()->createPageManager()->createPageUrlWithLocale($pageTransfer, $url, $localeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer
     */
    public function saveBlockAndTouch(CmsBlockTransfer $cmsBlockTransfer)
    {
        return $this->getFactory()->createBlockManager()->saveBlockAndTouch($cmsBlockTransfer);
    }

}
