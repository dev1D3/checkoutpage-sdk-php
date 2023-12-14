<?php

namespace dev1d3;

use Exception;

/**
 * Gate
 */
class Gate
{
    const CURRENCY_USD = 'USD';
    const CURRENCY_EUR = 'EUR';

    /**
     * Builder for Checkout page
     *
     * @var CheckoutPage $checkoutPageUrlBuilder
     */
    private $checkoutPageUrlBuilder;

    /**
     * Signature Handler (check, sign)
     *
     * @var SignatureHandler $signatureHandler
     */
    private $signatureHandler;

    /**
     * Gate constructor.
     *
     * @param string $secret Secret key
     * @param string $baseUrl Base URL for concatenate with payment params
     * @param string $encryptSecret Secret key for encode URL path and params
     */
    public function __construct(string $secret, string $baseUrl = '', string $encryptSecret = '')
    {
        $this->signatureHandler = new SignatureHandler($secret);
        $this->checkoutPageUrlBuilder = new CheckoutPage($this->signatureHandler, $baseUrl);

        if ($encryptSecret) {
            $this->checkoutPageUrlBuilder->setEncryptor(new Encryptor($encryptSecret));
        }
    }

    /**
     * @param string $paymentBaseUrl
     * @return Gate
     */
    public function setPaymentBaseUrl(string $paymentBaseUrl = ''): self
    {
        $this->checkoutPageUrlBuilder->setBaseUrl($paymentBaseUrl);

        return $this;
    }

    /**
     * Get URL for purchase checkout page
     *
     * @param Payment $payment Payment object
     *
     * @return string
     * @throws Exception
     */
    public function getPurchaseCheckoutPageUrl(Payment $payment): string
    {
        return $this->checkoutPageUrlBuilder->getUrl($payment);
    }

    /**
     * Callback handler
     *
     * @param string $data RAW string data from Gate
     *
     * @return Callback
     *
     * @throws ProcessException
     */
    public function handleCallback(string $data): Callback
    {
        return new Callback($data, $this->signatureHandler);
    }
}
