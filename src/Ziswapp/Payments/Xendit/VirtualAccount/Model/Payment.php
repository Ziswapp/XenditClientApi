<?php declare(strict_types=1);

namespace Ziswapp\Payments\Xendit\VirtualAccount\Model;

use Carbon\Carbon;
use DateTimeInterface;
use Ziswapp\Payments\Xendit\Model\ModelInterface;

/**
 * @author Nuradiyana <me@nooradiana.com>
 */
final class Payment implements ModelInterface
{
    /**
     * @var string|null
     */
    private $id;

    /**
     * @var string|null
     */
    private $paymentId;

    /**
     * @var string|null
     */
    private $virtualAccountId;

    /**
     * @var string|null
     */
    private $externalId;

    /**
     * @var string|null
     */
    private $merchantCode;

    /**
     * @var string|null
     */
    private $accountNumber;

    /**
     * @var string|null
     */
    private $bankCode;

    /**
     * @var float|null
     */
    private $amount;

    /**
     * @var DateTimeInterface|null
     */
    private $timestamp;

    /**
     * @param string|null            $id
     * @param string|null            $paymentId
     * @param string|null            $virtualAccountId
     * @param string|null            $externalId
     * @param string|null            $merchantCode
     * @param string|null            $accountNumber
     * @param string|null            $bankCode
     * @param float|null             $amount
     * @param DateTimeInterface|null $timestamp
     */
    public function __construct(?string $id = null, ?string $paymentId = null, ?string $virtualAccountId = null, ?string $externalId = null, ?string $merchantCode = null, ?string $accountNumber = null, ?string $bankCode = null, ?float $amount = null, ?DateTimeInterface $timestamp = null)
    {
        $this->id = $id;
        $this->paymentId = $paymentId;
        $this->virtualAccountId = $virtualAccountId;
        $this->externalId = $externalId;
        $this->merchantCode = $merchantCode;
        $this->accountNumber = $accountNumber;
        $this->bankCode = $bankCode;
        $this->amount = $amount;
        $this->timestamp = $timestamp;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string|null $id
     */
    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getPaymentId(): ?string
    {
        return $this->paymentId;
    }

    /**
     * @param string|null $paymentId
     */
    public function setPaymentId(?string $paymentId): void
    {
        $this->paymentId = $paymentId;
    }

    /**
     * @return string|null
     */
    public function getVirtualAccountId(): ?string
    {
        return $this->virtualAccountId;
    }

    /**
     * @param string|null $virtualAccountId
     */
    public function setVirtualAccountId(?string $virtualAccountId): void
    {
        $this->virtualAccountId = $virtualAccountId;
    }

    /**
     * @return string|null
     */
    public function getExternalId(): ?string
    {
        return $this->externalId;
    }

    /**
     * @param string|null $externalId
     */
    public function setExternalId(?string $externalId): void
    {
        $this->externalId = $externalId;
    }

    /**
     * @return string|null
     */
    public function getMerchantCode(): ?string
    {
        return $this->merchantCode;
    }

    /**
     * @param string|null $merchantCode
     */
    public function setMerchantCode(?string $merchantCode): void
    {
        $this->merchantCode = $merchantCode;
    }

    /**
     * @return string|null
     */
    public function getAccountNumber(): ?string
    {
        return $this->accountNumber;
    }

    /**
     * @param string|null $accountNumber
     */
    public function setAccountNumber(?string $accountNumber): void
    {
        $this->accountNumber = $accountNumber;
    }

    /**
     * @return string|null
     */
    public function getBankCode(): ?string
    {
        return $this->bankCode;
    }

    /**
     * @param string|null $bankCode
     */
    public function setBankCode(?string $bankCode): void
    {
        $this->bankCode = $bankCode;
    }

    /**
     * @return float|null
     */
    public function getAmount(): ?float
    {
        return $this->amount;
    }

    /**
     * @param float|null $amount
     */
    public function setAmount(?float $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getTimestamp(): ?DateTimeInterface
    {
        return $this->timestamp;
    }

    /**
     * @param DateTimeInterface|null $timestamp
     */
    public function setTimestamp(?DateTimeInterface $timestamp): void
    {
        $this->timestamp = $timestamp;
    }

    /**
     * @param array $content
     *
     * @return ModelInterface|self
     */
    public static function fromApi(array $content): ModelInterface
    {
        $self = new static();

        $self->setId($content['id']);
        $self->setPaymentId($content['payment_id']);
        $self->setVirtualAccountId($content['callback_virtual_account_id']);
        $self->setExternalId($content['external_id']);
        $self->setMerchantCode($content['merchant_code']);
        $self->setAccountNumber($content['account_number']);
        $self->setBankCode($content['bank_code']);
        $self->setAmount(\floatval($content['amount']));

        $timestamp = Carbon::createFromTimeString($content['transaction_timestamp']);
        $self->setTimestamp($timestamp);

        return $self;
    }
}
