<?php declare(strict_types=1);

namespace Tests\Payments\Xendit\VirtualAccount\Model;

use PHPUnit\Framework\TestCase;
use Ziswapp\Payments\Xendit\Model\ModelInterface;
use Ziswapp\Payments\Xendit\VirtualAccount\Model\Payment;

/**
 * @author Nuradiyana <me@nooradiana.com>
 */
final class PaymentTest extends TestCase
{
    public function testCanCreateFromApi()
    {
        $payment = <<<JSON
{
    "id": "598d91b1191029596846047f",
    "payment_id": "1502450097080",
    "callback_virtual_account_id": "598d5f71bf64853820c49a18",
    "external_id": "demo-1502437214715",
    "merchant_code": "77517",
    "account_number": "1000016980",
    "bank_code": "BNI",
    "amount": 5000,
    "transaction_timestamp": "2017-08-11T11:14:57.080Z"
}
JSON;

        $content = \json_decode($payment, true);

        $payment = Payment::fromApi($content);

        $this->assertInstanceOf(Payment::class, $payment);
        $this->assertInstanceOf(ModelInterface::class, $payment);
        $this->assertSame($content['id'], $payment->getId());
        $this->assertSame($content['payment_id'], $payment->getPaymentId());
        $this->assertSame($content['callback_virtual_account_id'], $payment->getVirtualAccountId());
        $this->assertSame($content['external_id'], $payment->getExternalId());
        $this->assertSame($content['merchant_code'], $payment->getMerchantCode());
        $this->assertSame($content['account_number'], $payment->getAccountNumber());
        $this->assertSame($content['bank_code'], $payment->getBankCode());
        $this->assertSame(\floatval($content['amount']), $payment->getAmount());
        $this->assertSame('2017-08-11', $payment->getTimestamp()->format('Y-m-d'));
    }
}
