<?php

namespace Bluebottle\SalesforceTransactionalEmail\Model\Integration;

use GuzzleHttp\Client as GuzzleHttpClient;
use Bluebottle\SalesforceTransactionalEmail\Helper\Configuration;
use GuzzleHttp\Psr7\Request;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Serialize\Serializer\Json as JsonSerializer;
use Magento\Store\Model\StoreManagerInterface;
use Bluebottle\SalesforceTransactionalEmail\Logger\SalesForce as SalesForceLogger;

class Email
{
    protected GuzzleHttpClient $client;
    protected Configuration $config;
    protected StoreManagerInterface $storeManager;
    protected SalesForceLogger $salesForceLogger;
    protected JsonSerializer $serializer;

    public function __construct(
        Configuration $configuration,
        StoreManagerInterface $storeManager,
        SalesForceLogger $salesForceLogger,
        JsonSerializer $serializer
    )
    {
        $this->config = $configuration;
        $this->storeManager = $storeManager;
        $this->salesForceLogger = $salesForceLogger;
        $this->serializer = $serializer;
        $this->client = new GuzzleHttpClient();
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function send(array $body)
    {
        try {
            $storeId = $this->storeManager->getStore()->getId();
        } catch (NoSuchEntityException $e) {
            $this->salesForceLogger->info($e->getMessage());
            $storeId = null;
        }

        $api = $this->config->getIntegrateApi($storeId);

        $request = new Request(
            'POST',
            $api,
            $this->initHeader($storeId),
            $this->serializer->serialize($body)
        );

        $result = $this->client->send($request);
        return $this->serializer->unserialize($result->getBody()->getContents());
    }

    /**
     * @param int|null $storeId
     *
     * @return array
     */
    private function initHeader(?int $storeId): array
    {
        $header = [];
        $header['Content-Type'] = 'application/json';
        $header['x-api-key'] = $this->config->getIntegrateToken($storeId);

        return $header;
    }
}
