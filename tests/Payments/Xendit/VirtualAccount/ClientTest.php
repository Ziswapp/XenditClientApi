<?php declare(strict_types=1);

namespace Tests\Payments\Xendit\VirtualAccount;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Ziswapp\Payments\Xendit\Model\ModelInterface;
use Ziswapp\Payments\Xendit\VirtualAccount\Client;
use Ziswapp\Payments\Xendit\VirtualAccount\Model\Bank;
use Symfony\Component\HttpClient\Response\MockResponse;
use Ziswapp\Payments\Xendit\VirtualAccount\Model\Payment;
use Ziswapp\Payments\Xendit\VirtualAccount\Model\VirtualAccount;

/**
 * @author Nuradiyana <me@nooradiana.com>
 */
final class ClientTest extends TestCase
{
    public function testGetVirtualAccountBanks()
    {
        $content = <<<JSON
[
    {"name":"Bank Mandiri","code":"MANDIRI"},
    {"name":"Bank Negara Indonesia","code":"BNI"},
    {"name":"Bank Rakyat Indonesia","code":"BRI"},
    {"name":"Bank Permata","code":"PERMATA"},
    {"name":"Bank Central Asia","code":"BCA"}
]
JSON;

        $http = new MockHttpClient([new MockResponse($content)], 'https://api.xendit.co');

        $client = new Client($http);
        $banks = $client->getVirtualAccountBanks();

        $this->assertCount(5, $banks);
        $this->assertInstanceOf(Bank::class, $banks[0]);
        $this->assertClassHasAttribute('code', \get_class($banks[0]));
        $this->assertClassHasAttribute('name', \get_class($banks[0]));
    }

    public function testCreateVirtualAccount()
    {
        $content = <<<JSON
{
   "owner_id":"57b4e5181473eeb61c11f9b9",
   "external_id":"1",
   "bank_code":"BNI",
   "merchant_code":"8808",
   "name":"Nuradiyana",
   "account_number":"88082548",
   "is_closed": false,
   "id":"57f6fbf26b9f064272622aa6",
   "is_single_use": true,
   "status": "ACTIVE"
}
JSON;

        $http = new MockHttpClient([new MockResponse($content)], 'https://api.xendit.co');

        $client = new Client($http);

        $account = new VirtualAccount('1', 'BNI', 'Nuradiyana');

        $newAccount = $client->createVirtualAccount($account);

        $this->assertInstanceOf(VirtualAccount::class, $newAccount);
        $this->assertSame('57f6fbf26b9f064272622aa6', $newAccount->getId());
        $this->assertSame('57b4e5181473eeb61c11f9b9', $newAccount->getOwnerId());
        $this->assertSame('1', $newAccount->getExternalId());
        $this->assertSame('BNI', $newAccount->getBankCode());
        $this->assertSame('Nuradiyana', $newAccount->getName());
        $this->assertSame('8808', $newAccount->getMerchantCode());
        $this->assertSame('88082548', $newAccount->getAccountNumber());
        $this->assertSame(false, $newAccount->isClose());
        $this->assertSame(true, $newAccount->isSingleUse());
        $this->assertSame('ACTIVE', $newAccount->getStatus());

        $this->assertNull($newAccount->getExpiredDate());
        $this->assertSame(0.0, $newAccount->getExpectedAmount());
        $this->assertSame(0.0, $newAccount->getSuggestedAmount());
    }

    public function testFindVirtualAccount()
    {
        $content = <<<JSON
{
    "owner_id": "58cd618ba0464eb64acdb246",
    "external_id": "fixed-va-1507867286",
    "bank_code": "BNI",
    "merchant_code": "26215",
    "name": "Steve Wozniak",
    "account_number": "262151000393993",
    "is_single_use": false,
    "status": "ACTIVE",
    "expiration_date": "2048-10-12T17:00:00.000Z",
    "is_closed": false,
    "id": "59e03a976fab8b1850fdf347"
}
JSON;

        $http = new MockHttpClient([new MockResponse($content)], 'https://api.xendit.co');

        $client = new Client($http);

        $newAccount = $client->findVirtualAccount('59e03a976fab8b1850fdf347');

        $this->assertInstanceOf(VirtualAccount::class, $newAccount);
        $this->assertSame('59e03a976fab8b1850fdf347', $newAccount->getId());
        $this->assertSame('58cd618ba0464eb64acdb246', $newAccount->getOwnerId());
        $this->assertSame('fixed-va-1507867286', $newAccount->getExternalId());
        $this->assertSame('BNI', $newAccount->getBankCode());
        $this->assertSame('Steve Wozniak', $newAccount->getName());
        $this->assertSame('26215', $newAccount->getMerchantCode());
        $this->assertSame('262151000393993', $newAccount->getAccountNumber());
        $this->assertSame(false, $newAccount->isClose());
        $this->assertSame(false, $newAccount->isSingleUse());
        $this->assertSame('ACTIVE', $newAccount->getStatus());

        $this->assertSame('2048-10-12', $newAccount->getExpiredDate()->format('Y-m-d'));
        $this->assertSame(0.0, $newAccount->getExpectedAmount());
        $this->assertSame(0.0, $newAccount->getSuggestedAmount());
    }

    public function testUpdate()
    {
        $content = <<<JSON
{
   "owner_id":"57b4e5181473eeb61c11f9b9",
   "external_id":"demo-1475804036622",
   "bank_code":"BNI",
   "merchant_code":"8808",
   "name":"Rika Sutanto",
   "account_number":"88082548",
   "is_closed": false,
   "id":"59e03a976fab8b1850fdf347",
   "is_single_use": true,
   "status": "ACTIVE",
   "suggested_amount": 1000000,
   "expected_amount": 1000000,
   "expiration_date": "2068-10-12T17:00:00.000Z"
}
JSON;

        $current = <<<JSON
{
    "owner_id": "58cd618ba0464eb64acdb246",
    "external_id": "fixed-va-1507867286",
    "bank_code": "BNI",
    "merchant_code": "26215",
    "name": "Steve Wozniak",
    "account_number": "262151000393993",
    "is_single_use": false,
    "status": "ACTIVE",
    "expiration_date": "2048-10-12T17:00:00.000Z",
    "is_closed": false,
    "id": "59e03a976fab8b1850fdf347"
}
JSON;

        $account = VirtualAccount::fromApi(\json_decode($current, true));

        $responses = [new MockResponse($content), new MockResponse($content), new MockResponse($content), new MockResponse($content)];

        $http = new MockHttpClient($responses, 'https://api.xendit.co');

        $client = new Client($http);

        $newAccount = $client->update('59e03a976fab8b1850fdf347', 'suggested_amount', 1000000);
        $this->assertNotSame($account->getSuggestedAmount(), $newAccount->getSuggestedAmount());

        $newAccount = $client->update('59e03a976fab8b1850fdf347', 'expected_amount', 1000000);
        $this->assertNotSame($account->getExpectedAmount(), $newAccount->getExpectedAmount());

        $newAccount = $client->update('59e03a976fab8b1850fdf347', 'expiration_date', '2068-10-12T17:00:00.000Z');
        $this->assertNotSame($account->getExpiredDate()->format('Y-m-d'), $newAccount->getExpiredDate()->format('Y-m-d'));

        $newAccount = $client->update('59e03a976fab8b1850fdf347', 'is_single_use', true);
        $this->assertNotSame($account->isSingleUse(), $newAccount->isSingleUse());
    }

    public function testGetPaymentVirtualAccount()
    {
        $content = <<<JSON
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

        $http = new MockHttpClient([new MockResponse($content)], 'https://api.xendit.co');

        $client = new Client($http);

        $payment = $client->getPaymentVirtualAccount('1502450097080');
        $this->assertInstanceOf(ModelInterface::class, $payment);
        $this->assertInstanceOf(Payment::class, $payment);

        $this->assertSame('598d91b1191029596846047f', $payment->getId());
        $this->assertSame('1502450097080', $payment->getPaymentId());
        $this->assertSame('598d5f71bf64853820c49a18', $payment->getVirtualAccountId());
        $this->assertSame('demo-1502437214715', $payment->getExternalId());
        $this->assertSame('77517', $payment->getMerchantCode());
        $this->assertSame('1000016980', $payment->getAccountNumber());
        $this->assertSame('BNI', $payment->getBankCode());
        $this->assertSame('2017-08-11', $payment->getTimestamp()->format('Y-m-d'));
    }
}
