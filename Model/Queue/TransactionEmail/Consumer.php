<?php

namespace Bluebottle\SalesforceTransactionalEmail\Model\Queue\TransactionEmail;

use Bluebottle\SalesforceTransactionalEmail\Logger\SalesForce as SalesForceLogger;
use Bluebottle\SalesforceTransactionalEmail\Model\Sender;
use Magento\Framework\Serialize\Serializer\Json as JsonSerializer;

class Consumer
{
    const TOPIC_NAME = 'sales_force_transaction_email.send';

    protected SalesForceLogger $salesForceLogger;
    protected Sender $sender;
    protected JsonSerializer $jsonSerializer;

    public function __construct(
        SalesForceLogger $salesForceLogger,
        Sender           $sender,
        JsonSerializer   $jsonSerializer
    )
    {
        $this->salesForceLogger = $salesForceLogger;
        $this->sender = $sender;
        $this->jsonSerializer = $jsonSerializer;
    }

    /**
     * @param string $data
     *
     * @return void
     */
    public function process(string $data)
    {
        $this->sender->sendEmail($this->jsonSerializer->unserialize($data));
    }
}
