<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantApp\Persistence;

use Generated\Shared\Transfer\MerchantAppOnboardingStatusTransfer;
use Generated\Shared\Transfer\ReadyForMerchantAppOnboardingTransfer;
use Orm\Zed\MerchantApp\Persistence\SpyMerchantAppOnboardingQuery;
use Orm\Zed\MerchantApp\Persistence\SpyMerchantAppOnboardingStatus;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\MerchantApp\Persistence\MerchantAppPersistenceFactory getFactory()
 */
class MerchantAppEntityManager extends AbstractEntityManager implements MerchantAppEntityManagerInterface
{
    public function persistAppMerchantAppOnboarding(ReadyForMerchantAppOnboardingTransfer $readyForMerchantAppOnboardingTransfer): void
    {
        $spyMerchantAppOnboardingEntity = SpyMerchantAppOnboardingQuery::create()
            ->filterByAppIdentifier($readyForMerchantAppOnboardingTransfer->getAppIdentifier())
            ->filterByAppName($readyForMerchantAppOnboardingTransfer->getAppName())
            ->findOneOrCreate();

        $merchantAppOnboardingEntity = $this->getFactory()
            ->createReadyForMerchantAppOnboardingMapper()
            ->mapReadyForMerchantAppOnboardingTransferToMerchantAppOnboardingEntity($readyForMerchantAppOnboardingTransfer, $spyMerchantAppOnboardingEntity);

        $merchantAppOnboardingEntity->save();
    }

    public function persistAppMerchantAppOnboardingStatus(MerchantAppOnboardingStatusTransfer $merchantAppOnboardingStatus): void
    {
        $merchantAppOnboardingStatusEntity = new SpyMerchantAppOnboardingStatus();

        if ($merchantAppOnboardingStatus->getIdMerchantAppOnboardingStatus()) {
            /** @var \Orm\Zed\MerchantApp\Persistence\SpyMerchantAppOnboardingStatus $merchantAppOnboardingStatusEntity */
            $merchantAppOnboardingStatusEntity = $this->getFactory()
                ->createMerchantAppOnboardingStatusQuery()
                ->filterByIdMerchantAppOnboardingStatus($merchantAppOnboardingStatus->getIdMerchantAppOnboardingStatus())
                ->findOne();
        }

        $merchantAppOnboardingStatusEntity = $this->getFactory()
            ->createMerchantAppOnboardingStatusMapper()
            ->mapMerchantAppOnboardingStatusTransferToMerchantAppOnboardingStatusEntity($merchantAppOnboardingStatus, $merchantAppOnboardingStatusEntity);

        $merchantAppOnboardingStatusEntity->save();
    }

    public function deleteMerchantAppOnboardingByAppIdentifier(string $appIdentifier): void
    {
        $merchantAppOnboardingEntities = $this->getFactory()->createMerchantAppOnboardingQuery()
            ->findByAppIdentifier($appIdentifier);

        foreach ($merchantAppOnboardingEntities as $appOnboardingEntity) {
            $appOnboardingEntity->getSpyMerchantAppOnboardingStatuses()->delete();
            $appOnboardingEntity->delete();
        }
    }
}
