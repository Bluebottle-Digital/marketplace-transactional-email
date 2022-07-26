<?php

namespace Bluebottle\SalesforceTransactionalEmail\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Configuration extends AbstractHelper
{
    const XML_PATH_SALES_FORCE_CONFIGURATION_ENABLED = 'sales_force/configuration/enabled';
    const XML_PATH_SALES_FORCE_CONFIGURATION_QUEUE_ENABLED = 'sales_force/configuration/queue_enabled';

    const XML_PATH_SALES_FORCE_INTEGRATE_URL_API = 'sales_force/integrate/url_api';
    const XML_PATH_SALES_FORCE_INTEGRATE_TOKEN = 'sales_force/integrate/token';

    const XML_PATH_SALES_FORCE_ORDER_ENABLED = 'sales_force/order/enabled';
    const XML_PATH_SALES_FORCE_ORDER_DEFINITION_KEY= 'sales_force/order/definition_key';

    /**
     * Get config enabled sales force
     *
     * @param int|null $storeId
     *
     * @return bool
     */
    public function isEnableSalesForce(int $storeId = null): bool
    {
        return (bool)$this->scopeConfig->getValue(
            self::XML_PATH_SALES_FORCE_CONFIGURATION_ENABLED,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Sending email by queue or direct
     *
     * @param int|null $storeId
     *
     * @return bool
     */
    public function isSendByQueue(int $storeId = null): bool
    {
        return (bool)$this->scopeConfig->getValue(
            self::XML_PATH_SALES_FORCE_CONFIGURATION_QUEUE_ENABLED,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Return api url
     *
     * @param int|null $storeId
     *
     * @return string
     */
    public function getIntegrateApi(int $storeId = null): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_SALES_FORCE_INTEGRATE_URL_API,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /***
     * Return token
     *
     * @param int|null $storeId
     *
     * @return string
     */
    public function getIntegrateToken(int $storeId = null): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_SALES_FORCE_INTEGRATE_TOKEN,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Enable send email order
     *
     * @param int|null $storeId
     *
     * @return bool
     */
    public function isEnableSendEmailOrder(int $storeId = null): bool
    {
        return (bool)$this->scopeConfig->getValue(
            self::XML_PATH_SALES_FORCE_ORDER_ENABLED,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Order definition key
     *
     * @param int|null $storeId
     *
     * @return string
     */
    public function getOrderDefinitionKey(int $storeId = null): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_SALES_FORCE_ORDER_DEFINITION_KEY,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}
