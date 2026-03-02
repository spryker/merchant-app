<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantApp\Dependency\Facade;

use Generated\Shared\Transfer\AcpHttpRequestTransfer;
use Generated\Shared\Transfer\AcpHttpResponseTransfer;

interface MerchantAppToKernelAppFacadeInterface
{
    public function makeRequest(AcpHttpRequestTransfer $acpHttpRequestTransfer): AcpHttpResponseTransfer;
}
