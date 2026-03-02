<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantApp\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\MerchantOnboardingContentTransfer;
use Generated\Shared\Transfer\ReadyForMerchantAppOnboardingTransfer;
use Orm\Zed\MerchantApp\Persistence\SpyMerchantAppOnboarding;
use Spryker\Zed\MerchantApp\Dependency\Service\MerchantAppToUtilEncodingServiceInterface;

class ReadyForMerchantAppOnboardingMapper
{
    /**
     * @var \Spryker\Zed\MerchantApp\Dependency\Service\MerchantAppToUtilEncodingServiceInterface
     */
    protected MerchantAppToUtilEncodingServiceInterface $utilEncodingService;

    public function __construct(MerchantAppToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    public function mapReadyForMerchantAppOnboardingTransferToMerchantAppOnboardingEntity(
        ReadyForMerchantAppOnboardingTransfer $readyForMerchantAppOnboardingTransfer,
        SpyMerchantAppOnboarding $merchantAppOnboardingEntity
    ): SpyMerchantAppOnboarding {
        $merchantAppOnboardingData = $readyForMerchantAppOnboardingTransfer->modifiedToArray();
        $additionalContent = [];

        if ($readyForMerchantAppOnboardingTransfer->getAdditionalLinks()->count() > 0) {
            $additionalContent[MerchantOnboardingContentTransfer::LINKS] = $merchantAppOnboardingData['additional_links'];
        }

        if ($readyForMerchantAppOnboardingTransfer->getMerchantOnboardingStates()->count() > 0) {
            $additionalContent[MerchantOnboardingContentTransfer::MERCHANT_ONBOARDING_STATES] = $merchantAppOnboardingData['merchant_onboarding_states'];
        }

        $merchantAppOnboardingData['additional_content'] = json_encode($additionalContent);

        $merchantAppOnboardingEntity->fromArray($merchantAppOnboardingData);
        $merchantAppOnboardingEntity->setOnboardingUrl($readyForMerchantAppOnboardingTransfer->getOnboardingOrFail()->getUrlOrFail());
        $merchantAppOnboardingEntity->setOnboardingStrategy($readyForMerchantAppOnboardingTransfer->getOnboardingOrFail()->getStrategyOrFail());

        return $merchantAppOnboardingEntity;
    }
}
