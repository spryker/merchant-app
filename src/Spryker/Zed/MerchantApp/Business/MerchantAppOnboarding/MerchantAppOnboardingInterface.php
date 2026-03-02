<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantApp\Business\MerchantAppOnboarding;

use Generated\Shared\Transfer\MerchantAppOnboardingCollectionTransfer;
use Generated\Shared\Transfer\MerchantAppOnboardingCriteriaTransfer;
use Generated\Shared\Transfer\MerchantAppOnboardingInitializationRequestTransfer;
use Generated\Shared\Transfer\MerchantAppOnboardingInitializationResponseTransfer;

interface MerchantAppOnboardingInterface
{
    public function getMerchantAppOnboardingCollection(
        MerchantAppOnboardingCriteriaTransfer $merchantAppOnboardingStatusCriteriaTransfer
    ): MerchantAppOnboardingCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\MerchantAppOnboardingInitializationRequestTransfer $merchantAppOnboardingInitializationRequestTransfer
     *
     * @throws \Spryker\Zed\MerchantApp\Business\Exception\MerchantAppOnboardingNotFoundException
     * @throws \Spryker\Zed\MerchantApp\Business\Exception\MerchantAppOnboardingAlreadyInitializedException
     *
     * @return \Generated\Shared\Transfer\MerchantAppOnboardingInitializationResponseTransfer
     */
    public function initializeMerchantAppOnboarding(
        MerchantAppOnboardingInitializationRequestTransfer $merchantAppOnboardingInitializationRequestTransfer
    ): MerchantAppOnboardingInitializationResponseTransfer;
}
