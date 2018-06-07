<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\Company\Repository;

use Orm\Zed\Company\Persistence\SpyCompanyQuery;
use Pyz\Zed\DataImport\Business\Exception\EntityNotFoundException;

class CompanyRepository implements CompanyRepositoryInterface
{
    /**
     * @var array
     */
    protected static $resolved = [];

    /**
     * @param string $name
     *
     * @return int
     */
    public function getIdCompanyByName(string $name): int
    {
        if (!isset(static::$resolved[$name])) {
            $this->resolveCompanyByName($name);
        }

        return static::$resolved[$name];
    }

    /**
     * @param string $name
     *
     * @throws \Pyz\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    protected function resolveCompanyByName(string $name): void
    {
        $companyEntity = SpyCompanyQuery::create()
            ->findOneByName($name);

        if (!$companyEntity) {
            throw new EntityNotFoundException(sprintf('Company by name "%s" not found.', $name));
        }

        static::$resolved[$name] = $companyEntity->getIdCompany();
    }
}
