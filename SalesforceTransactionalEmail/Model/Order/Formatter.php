<?php

namespace Bluebottle\SalesforceTransactionalEmail\Model\Order;

use Magento\Sales\Model\Order;
use Bluebottle\SalesforceTransactionalEmail\Helper\Configuration;

class Formatter
{
    protected Configuration $configuration;

    public function __construct(
        Configuration $configuration
    )
    {
        $this->configuration = $configuration;
    }

    /**
     * @param Order $order
     *
     * @return array
     */
    public function format(Order $order): array
    {
        return [
            'definitionKey' => $this->configuration->getOrderDefinitionKey((int)$order->getStoreId()),
            'recipients' => [
                [
                    'email' => $order->getCustomerEmail(),
                    'attributes' => [
                        'FirstName' => $order->getCustomerFirstname(),
                        'Doctor' => $order->getCustomerLastname()
                    ]
                ],
            ]
        ];
    }
}
