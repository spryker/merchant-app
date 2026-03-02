<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantApp\Business\MerchantAppOnboarding;

use Generated\Shared\Transfer\ReadyForMerchantAppOnboardingTransfer;
use Spryker\Zed\MerchantApp\Persistence\MerchantAppEntityManagerInterface;

class MerchantAppOnboardingWriter implements MerchantAppOnboardingWriterInterface
{
    /**
     * @var \Spryker\Zed\MerchantApp\Persistence\MerchantAppEntityManagerInterface
     */
    protected MerchantAppEntityManagerInterface $merchantAppEntityManager;

    public function __construct(MerchantAppEntityManagerInterface $merchantAppEntityManager)
    {
        $this->merchantAppEntityManager = $merchantAppEntityManager;
    }

    public function persistAppMerchantAppOnboarding(ReadyForMerchantAppOnboardingTransfer $readyForMerchantAppOnboardingTransfer): void
    {
        $this->merchantAppEntityManager->persistAppMerchantAppOnboarding($readyForMerchantAppOnboardingTransfer);
    }
}
