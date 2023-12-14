# 1D3 PHP SDK

This is a set of libraries in the PHP language to ease integration of your service
with the 1D3 Checkout Page.

Please note that for correct SDK operating you must have at least PHP 7.0.  

## Payment flow

![Payment flow](flow.png)

## Installation

Install with composer
```bash
composer require dev1d3/checkoutpage-sdk
```

### Get URL for payment

```php
$gate = new dev1d3\Gate('secret');
$payment = new dev1d3\Payment('11', 'some payment id');
$payment->setPaymentAmount(1000)->setPaymentCurrency('EUR');
$url = $gate->getPurchaseCheckoutPageUrl($payment);
``` 

`$url` here is the signed URL.

If you want to use another domain for URL you can change it with optional `Gate` constructor parameter:
```php
new dev1d3\Gate('secret', 'https://mydomain.com/payment');
```
or change it with method 
```php
$gate->setPaymentBaseUrl('https://mydomain.com/payment');
```

### Handle callback from 1D3

You'll need to autoload this code in order to handle notifications:

```php
$gate = new dev1d3\Gate('secret');
$callback = $gate->handleCallback($data);
```

`$data` is the JSON data received from payment system;

`$callback` is the Callback object describing properties received from payment system;
`$callback` implements these methods: 
1. `Callback::getPaymentStatus();`
    Get payment status.
2. `Callback::getPayment();`
    Get all payment data.
3. `Callback::getPaymentId();`
    Get payment ID in your system.
    
### TODO

- [x] Checkout Page opening 
- [x] Notifications handling
- [ ] Direct Gate requests
- [ ] PHPDoc
