<?php

namespace Bluebottle\SalesforceTransactionalEmail\Model;

use Magento\Framework\DataObject;
use Magento\Framework\Mail\TransportInterface;
use Bluebottle\SalesforceTransactionalEmail\Model\Integration\Email;
use Bluebottle\SalesforceTransactionalEmail\Logger\SalesForce as SalesForceLogger;
use Magento\Framework\MessageQueue\PublisherInterface;
use Bluebottle\SalesforceTransactionalEmail\Model\Queue\TransactionEmail\Consumer;
use Bluebottle\SalesforceTransactionalEmail\Helper\Configuration;
use Bluebottle\SalesforceTransactionalEmail\Model\Sender;

class Transport implements TransportInterface
{
    protected Email $integrationEmail;
    protected SalesForceLogger $salesForceLogger;
    protected PublisherInterface $publisher;
    protected DataObject $message;
    protected Configuration $configuration;
    protected Sender $sender;

    /**
     * @param \Bluebottle\SalesforceTransactionalEmail\Model\Integration\Email $integrationEmail
     * @param \Bluebottle\SalesforceTransactionalEmail\Logger\SalesForce $salesForceLogger
     * @param \Magento\Framework\MessageQueue\PublisherInterface $publisher
     * @param \Magento\Framework\DataObject $message
     * @param \Bluebottle\SalesforceTransactionalEmail\Helper\Configuration $configuration
     * @param \Bluebottle\SalesforceTransactionalEmail\Model\Sender $sender
     */
    public function __construct(
        Email              $integrationEmail,
        SalesForceLogger   $salesForceLogger,
        PublisherInterface $publisher,
        DataObject         $message,
        Configuration      $configuration,
        Sender             $sender
    )
    {
        $this->integrationEmail = $integrationEmail;
        $this->salesForceLogger = $salesForceLogger;
        $this->publisher = $publisher;
        $this->message = $message;
        $this->configuration = $configuration;
        $this->sender = $sender;
    }

    /**
     * @inheritDoc
     */
    public function sendMessage()
    {
        //retrieve data from transport
        $message = $this->getMessage();

        if ($this->configuration->isSendByQueue()) {
            $this->publisher->publish(Consumer::TOPIC_NAME, $message->toJson());
        } else {
            $this->sender->sendEmail($message->toArray());
        }
    }

    /**
     * @inheritDoc
     */
    public function getMessage()
    {
        return $this->message;
    }
}
