<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantApp\Persistence;

use Generated\Shared\Transfer\MerchantAppOnboardingStatusTransfer;
use Generated\Shared\Transfer\ReadyForMerchantAppOnboardingTransfer;

interface MerchantAppEntityManagerInterface
{
    public function persistAppMerchantAppOnboarding(ReadyForMerchantAppOnboardingTransfer $readyForMerchantAppOnboardingTransfer): void;

    public function persistAppMerchantAppOnboardingStatus(MerchantAppOnboardingStatusTransfer $merchantAppOnboardingStatus): void;

    public function deleteMerchantAppOnboardingByAppIdentifier(string $appIdentifier): void;
}
