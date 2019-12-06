<?php declare(strict_types=1);

namespace Ziswapp\Payments\Xendit;

use Carbon\Carbon;
use DateTimeInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Ziswapp\Payments\Xendit\VirtualAccount\Model\Payment;
use Ziswapp\Payments\Xendit\VirtualAccount\Client as VAClient;
use Ziswapp\Payments\Xendit\VirtualAccount\Model\VirtualAccount;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;

/**
 * @author Nuradiyana <me@nooradiana.com>
 */
final class Client
{
    /**
     * @var VAClient
     */
    private $va;

    /**
     * @param HttpClientInterface $client
     */
    public function __construct(HttpClientInterface $client)
    {
        $this->va = new VAClient($client);
    }

    /**
     * @return array
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getVirtualAccountBanks(): array
    {
        return $this->va->getVirtualAccountBanks();
    }

    /**
     * @param VirtualAccount $account
     *
     * @return Model\ModelInterface|VirtualAccount
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws Exception\ExpectedAmountException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function createVirtualAccount(VirtualAccount $account)
    {
        return $this->va->createVirtualAccount($account);
    }

    /**
     * @param string $id
     *
     * @return Model\ModelInterface|VirtualAccount
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function findVirtualAccount(string $id)
    {
        return $this->va->findVirtualAccount($id);
    }

    /**
     * @param string $id
     * @param float  $suggestedAmount
     *
     * @return Model\ModelInterface|VirtualAccount
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function changeSuggestedAmount(string $id, float $suggestedAmount)
    {
        return $this->va->update($id, 'suggested_amount', $suggestedAmount);
    }

    /**
     * @param string $id
     * @param float  $expectedAmount
     *
     * @return Model\ModelInterface|VirtualAccount
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function changeExpectedAmount(string $id, float $expectedAmount)
    {
        return $this->va->update($id, 'expected_amount', $expectedAmount);
    }

    /**
     * @param string            $id
     * @param DateTimeInterface $expiredDate
     *
     * @return Model\ModelInterface|VirtualAccount
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function changeExpiredDate(string $id, DateTimeInterface $expiredDate)
    {
        return $this->va->update($id, 'expiration_date', Carbon::instance($expiredDate)->toISOString());
    }

    /**
     * @param string $id
     * @param bool   $isSingleUse
     *
     * @return Model\ModelInterface|VirtualAccount
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function changeSingleUse(string $id, bool $isSingleUse)
    {
        return $this->va->update($id, 'is_single_use', $isSingleUse);
    }

    /**
     * @param string $id
     *
     * @return Model\ModelInterface|Payment
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getPaymentVirtualAccount(string $id)
    {
        return $this->va->getPaymentVirtualAccount($id);
    }
}
