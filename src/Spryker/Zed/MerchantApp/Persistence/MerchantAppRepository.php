<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantApp\Persistence;

use Generated\Shared\Transfer\MerchantAppOnboardingCollectionTransfer;
use Generated\Shared\Transfer\MerchantAppOnboardingCriteriaTransfer;
use Generated\Shared\Transfer\MerchantAppOnboardingStatusCollectionTransfer;
use Orm\Zed\KernelApp\Persistence\Map\SpyAppConfigTableMap;
use Orm\Zed\MerchantApp\Persistence\Map\SpyMerchantAppOnboardingStatusTableMap;
use Orm\Zed\MerchantApp\Persistence\Map\SpyMerchantAppOnboardingTableMap;
use Orm\Zed\MerchantApp\Persistence\SpyMerchantAppOnboardingQuery;
use Orm\Zed\MerchantApp\Persistence\SpyMerchantAppOnboardingStatusQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MerchantApp\Persistence\MerchantAppPersistenceFactory getFactory()
 */
class MerchantAppRepository extends AbstractRepository implements MerchantAppRepositoryInterface
{
    protected const string CONDITION_NO_CONFIG = 'noConfig';

    protected const string CONDITION_ACTIVE_CONFIG = 'activeConfig';

    public function getMerchantAppOnboardingStatusCollection(
        MerchantAppOnboardingCriteriaTransfer $merchantAppOnboardingStatusCriteriaTransfer
    ): MerchantAppOnboardingStatusCollectionTransfer {
        $merchantAppOnboardingStatusQuery = $this->getFactory()->createMerchantAppOnboardingStatusQuery();
        $merchantAppOnboardingStatusQuery = $this->applyStatusCriteria($merchantAppOnboardingStatusQuery, $merchantAppOnboardingStatusCriteriaTransfer);

        $merchantAppOnboardingStatusEntityCollection = $merchantAppOnboardingStatusQuery->find();

        return $this->getFactory()->createMerchantAppOnboardingStatusMapper()->mapMerchantAppOnboardingEntityCollectionToMerchantAppOnboardingTransferCollection(
            $merchantAppOnboardingStatusEntityCollection,
            new MerchantAppOnboardingStatusCollectionTransfer(),
        );
    }

    protected function applyStatusCriteria(
        SpyMerchantAppOnboardingStatusQuery $merchantAppOnboardingStatusQuery,
        MerchantAppOnboardingCriteriaTransfer $merchantAppOnboardingCriteriaTransfer
    ): SpyMerchantAppOnboardingStatusQuery {
        if ($merchantAppOnboardingCriteriaTransfer->getMerchant()) {
            $merchantAppOnboardingStatusQuery->filterByMerchantReference($merchantAppOnboardingCriteriaTransfer->getMerchant()->getMerchantReferenceOrFail());
        }

        if ($merchantAppOnboardingCriteriaTransfer->getAppIdentifiers() !== []) {
            $merchantAppOnboardingStatusQuery
                ->joinWithSpyMerchantAppOnboarding()
                ->useSpyMerchantAppOnboardingQuery()
                    ->filterByAppIdentifier_In($merchantAppOnboardingCriteriaTransfer->getAppIdentifiers())
                ->endUse();
        }

        if ($merchantAppOnboardingCriteriaTransfer->getType()) {
            $merchantAppOnboardingStatusQuery->joinWithSpyMerchantAppOnboarding();
            $merchantAppOnboardingStatusQuery
                ->useSpyMerchantAppOnboardingQuery()
                    ->filterByType($merchantAppOnboardingCriteriaTransfer->getType())
                ->endUse();
        }

        return $merchantAppOnboardingStatusQuery;
    }

    /**
     * @module KernelApp
     */
    public function getMerchantAppOnboardingCollection(
        MerchantAppOnboardingCriteriaTransfer $merchantAppOnboardingStatusCriteriaTransfer
    ): MerchantAppOnboardingCollectionTransfer {
        $merchantAppOnboardingQuery = $this->getFactory()->createMerchantAppOnboardingQuery();

        $merchantAppOnboardingQuery = $this->applyOnboardingCriteria($merchantAppOnboardingQuery, $merchantAppOnboardingStatusCriteriaTransfer);

        // Exclude onboardings whose app config exists but is inactive. Onboardings with no config entry are always included.
        $merchantAppOnboardingQuery
            ->addJoin(
                SpyMerchantAppOnboardingTableMap::COL_APP_IDENTIFIER,
                SpyAppConfigTableMap::COL_APP_IDENTIFIER,
                Criteria::LEFT_JOIN,
            )
            ->condition(static::CONDITION_NO_CONFIG, sprintf('%s IS NULL', SpyAppConfigTableMap::COL_APP_IDENTIFIER))
            ->condition(static::CONDITION_ACTIVE_CONFIG, sprintf('%s = TRUE', SpyAppConfigTableMap::COL_IS_ACTIVE))
            ->combine([static::CONDITION_NO_CONFIG, static::CONDITION_ACTIVE_CONFIG], Criteria::LOGICAL_OR);

        $merchantAppOnboardingEntityCollection = $merchantAppOnboardingQuery->find();

        return $this->getFactory()->createMerchantAppOnboardingMapper()->mapMerchantAppOnboardingEntityCollectionToMerchantAppOnboardingCollectionTransfer(
            $merchantAppOnboardingEntityCollection,
            new MerchantAppOnboardingCollectionTransfer(),
        );
    }

    protected function applyOnboardingCriteria(
        SpyMerchantAppOnboardingQuery $merchantAppOnboardingQuery,
        MerchantAppOnboardingCriteriaTransfer $merchantAppOnboardingCriteriaTransfer
    ): SpyMerchantAppOnboardingQuery {
        // Apply filter for the type of onboarding e.g. payment.
        if ($merchantAppOnboardingCriteriaTransfer->getType()) {
            $merchantAppOnboardingQuery->filterByType($merchantAppOnboardingCriteriaTransfer->getType());
        }

        // Apply filter for the app identifier.
        if ($merchantAppOnboardingCriteriaTransfer->getAppIdentifiers() !== []) {
            $merchantAppOnboardingQuery->filterByAppIdentifier_In($merchantAppOnboardingCriteriaTransfer->getAppIdentifiers());
        }

        // Apply filter for the merchant.
        if ($merchantAppOnboardingCriteriaTransfer->getMerchant()) {
            $merchantAppOnboardingQuery
                ->join('SpyMerchantAppOnboardingStatus', 'LEFT JOIN')
                ->addJoinCondition('SpyMerchantAppOnboardingStatus', 'spy_merchant_app_onboarding_status.merchant_reference = ?', $merchantAppOnboardingCriteriaTransfer->getMerchant()->getMerchantReferenceOrFail())
                ->withColumn(SpyMerchantAppOnboardingStatusTableMap::COL_STATUS, 'status');
        }

        return $merchantAppOnboardingQuery;
    }
}
