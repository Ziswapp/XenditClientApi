<?php declare(strict_types=1);

namespace Tests\Payments\Xendit\VirtualAccount\Model;

use PHPUnit\Framework\TestCase;
use Ziswapp\Payments\Xendit\VirtualAccount\Model\VirtualAccount;

/**
 * @author Nuradiyana <me@nooradiana.com>
 */
final class VirtualAccountTest extends TestCase
{
    public function testCanCreateFromApi()
    {
        $content = <<<JSON
{
    "owner_id": "58cd618ba0464eb64acdb246",
    "external_id": "fixed-va-1507867286",
    "bank_code": "BRI",
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

        $data = \json_decode($content, true);

        $account = VirtualAccount::fromApi($data);
        $this->assertInstanceOf(VirtualAccount::class, $account);
        $this->assertSame($data['id'], $account->getId());
        $this->assertSame($data['owner_id'], $account->getOwnerId());
        $this->assertSame($data['external_id'], $account->getExternalId());
        $this->assertSame($data['bank_code'], $account->getBankCode());
        $this->assertSame($data['name'], $account->getName());
        $this->assertSame($data['account_number'], $account->getAccountNumber());
        $this->assertSame($data['is_single_use'], $account->isSingleUse());
        $this->assertSame($data['status'], $account->getStatus());
        $this->assertSame('2048', $account->getExpiredDate()->format('Y'));
        $this->assertSame('10', $account->getExpiredDate()->format('m'));
        $this->assertSame('12', $account->getExpiredDate()->format('d'));
        $this->assertSame($data['is_closed'], $account->isClose());
    }
}
