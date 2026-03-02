<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantApp\Business\MessageBroker;

use Generated\Shared\Transfer\MerchantAppOnboardingStatusChangedTransfer;
use Generated\Shared\Transfer\MerchantAppOnboardingStatusTransfer;
use Generated\Shared\Transfer\MerchantAppOnboardingTransfer;
use Spryker\Zed\MerchantApp\Business\MerchantAppOnboarding\MerchantAppOnboardingStatusInterface;

class MerchantAppOnboardingStatusChangedMessageHandler implements MerchantAppOnboardingStatusChangedMessageHandlerInterface
{
    protected MerchantAppOnboardingStatusInterface $merchantAppOnboardingStatus;

    public function __construct(MerchantAppOnboardingStatusInterface $merchantAppOnboardingStatus)
    {
        $this->merchantAppOnboardingStatus = $merchantAppOnboardingStatus;
    }

    public function handleMerchantAppOnboardingStatusChanged(MerchantAppOnboardingStatusChangedTransfer $merchantOnboardingStatusChangedTransfer): void
    {
        $merchantOnboardingStatusTransfer = (new MerchantAppOnboardingStatusTransfer())->fromArray($merchantOnboardingStatusChangedTransfer->toArray(), true);
        $merchantAppOnboardingTransfer = (new MerchantAppOnboardingTransfer())
            ->setAppIdentifier($merchantOnboardingStatusChangedTransfer->getAppIdentifierOrFail())
            ->setType($merchantOnboardingStatusChangedTransfer->getTypeOrFail());
        $merchantOnboardingStatusTransfer->setMerchantAppOnboarding($merchantAppOnboardingTransfer);

        $this->merchantAppOnboardingStatus->updateMerchantAppOnboardingStatus($merchantOnboardingStatusTransfer);
    }
}
