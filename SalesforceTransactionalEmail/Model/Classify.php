<?php

namespace Bluebottle\SalesforceTransactionalEmail\Model;

use Bluebottle\SalesforceTransactionalEmail\Model\Order\Formatter as OrderFormatter;
use Bluebottle\SalesforceTransactionalEmail\Helper\Configuration;

class Classify
{
    protected OrderFormatter $orderFormatter;
    protected Configuration $configuration;

    public function __construct(
        OrderFormatter $orderFormatter,
        Configuration $configuration
    )
    {
        $this->orderFormatter = $orderFormatter;
        $this->configuration = $configuration;
    }

    /**
     * @param array $templateVar
     *
     * @return array|null
     */
    public function execute(array $templateVar): ?array
    {
        //Send order when customer buy good
        if ($this->configuration->isEnableSendEmailOrder()) {
            if (array_key_exists('order', $templateVar) && array_key_exists('vendor', $templateVar)) {
                /** @var \Magento\Sales\Model\Order $order */
                $order = $templateVar['order'];
                return $this->orderFormatter->format($order);
            }
        }

        return null;
    }
}
