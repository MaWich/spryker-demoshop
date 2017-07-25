<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\Catalog\Controller;

use Spryker\Yves\Kernel\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Pyz\Yves\Catalog\CatalogFactory getFactory()
 * @method \Pyz\Client\Catalog\CatalogClientInterface getClient()
 */
class SaleController extends AbstractController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $searchResults = $this
            ->getClient()
            ->saleSearch($request->query->all());

        return $this->viewResponse($searchResults);
    }

}
