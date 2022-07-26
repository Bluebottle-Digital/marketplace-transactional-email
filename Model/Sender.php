<?php

namespace Bluebottle\SalesforceTransactionalEmail\Model;

use Bluebottle\SalesforceTransactionalEmail\Model\Integration\Email;
use GuzzleHttp\Exception\GuzzleException;
use Bluebottle\SalesforceTransactionalEmail\Logger\SalesForce as SalesForceLogger;

class Sender
{
    protected Email $email;
    protected SalesForceLogger $salesForceLogger;

    public function __construct(
        Email $email,
        SalesForceLogger $salesForceLogger
    )
    {
        $this->email = $email;
        $this->salesForceLogger = $salesForceLogger;
    }

    /**
     * @param array $information
     *
     * @return void
     */
    public function sendEmail(array $information): void
    {
        try {
            $this->email->send($information);
        } catch (GuzzleException $e) {
            $this->salesForceLogger->info($e->getMessage());
        }
    }
}
