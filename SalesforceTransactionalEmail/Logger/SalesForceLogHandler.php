<?php

namespace Bluebottle\SalesforceTransactionalEmail\Logger;

use Monolog\Logger;
use Magento\Framework\Logger\Handler\Base;

class SalesForceLogHandler extends Base
{
    /**
     * Logging level
     * @var int
     */
    protected $loggerType = Logger::INFO;

    /**
     * File name
     * @var string
     */
    protected $fileName = '/var/log/sales_force.log';
}
