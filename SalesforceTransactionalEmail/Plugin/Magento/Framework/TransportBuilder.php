<?php

namespace Bluebottle\SalesforceTransactionalEmail\Plugin\Magento\Framework;

use Bluebottle\SalesforceTransactionalEmail\Helper\Configuration as SalesForceConfig;
use Bluebottle\SalesforceTransactionalEmail\Model\TransportFactory;
use Magento\Framework\DataObject;
use Magento\Framework\Mail\AddressConverter;
use Magento\Framework\Mail\EmailMessageInterfaceFactory;
use Magento\Framework\Mail\MessageInterface;
use Magento\Framework\Mail\MessageInterfaceFactory;
use Magento\Framework\Mail\MimeMessageInterfaceFactory;
use Magento\Framework\Mail\MimePartInterfaceFactory;
use Magento\Framework\Mail\Template\FactoryInterface;
use Magento\Framework\Mail\Template\SenderResolverInterface;
use Magento\Framework\Mail\Template\TransportBuilder as BaseTransPortBuilder;
use Magento\Framework\Mail\TransportInterfaceFactory;
use Magento\Framework\ObjectManagerInterface;
use Bluebottle\SalesforceTransactionalEmail\Model\Classify;
use Bluebottle\SalesforceTransactionalEmail\Logger\SalesForce as SalesForceLogger;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class TransportBuilder extends BaseTransPortBuilder
{
    protected SalesForceConfig $salesForceConfig;
    protected TransportInterfaceFactory $transportInterfaceFactory;
    protected TransportFactory $transportFactory;
    protected Classify $classify;
    protected SalesForceLogger $logger;

    public function __construct(
        SalesForceConfig             $salesForceConfig,
        TransportInterfaceFactory    $transportInterfaceFactory,
        TransportFactory             $transportFactory,
        Classify                     $classify,
        SalesForceLogger             $logger,
        FactoryInterface             $templateFactory,
        MessageInterface             $message,
        SenderResolverInterface      $senderResolver,
        ObjectManagerInterface       $objectManager,
        TransportInterfaceFactory    $mailTransportFactory,
        MessageInterfaceFactory      $messageFactory = null,
        EmailMessageInterfaceFactory $emailMessageInterfaceFactory = null,
        MimeMessageInterfaceFactory  $mimeMessageInterfaceFactory = null,
        MimePartInterfaceFactory     $mimePartInterfaceFactory = null,
        AddressConverter             $addressConverter = null
    )
    {
        $this->salesForceConfig = $salesForceConfig;
        $this->transportInterfaceFactory = $transportInterfaceFactory;
        $this->transportFactory = $transportFactory;
        $this->classify = $classify;
        $this->logger = $logger;
        parent::__construct(
            $templateFactory,
            $message,
            $senderResolver,
            $objectManager,
            $mailTransportFactory,
            $messageFactory,
            $emailMessageInterfaceFactory,
            $mimeMessageInterfaceFactory,
            $mimePartInterfaceFactory,
            $addressConverter
        );
    }

    /**
     * @param \Magento\Framework\Mail\Template\TransportBuilder $subject
     * @param callable $proceed
     *
     * @return \Bluebottle\SalesforceTransactionalEmail\Model\Transport|\Magento\Framework\Mail\TransportInterface
     */
    public function aroundGetTransport(
        BaseTransPortBuilder $subject,
        callable             $proceed
    )
    {
        if (!$this->salesForceConfig->isEnableSalesForce()) {
            return $proceed();
        }

        $templateVars = $subject->templateVars;
        $message = $this->classify->execute($templateVars);

        if (is_null($message)) {
            $this->logger->info(__('Empty Data'));
            return $proceed();
        }

        return $this->transportFactory->create(['message' => new DataObject($message)]);
    }
}
