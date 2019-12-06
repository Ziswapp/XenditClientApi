<?php declare(strict_types=1);

namespace Ziswapp\Payments\Xendit\Model;

/**
 * @author Nuradiyana <me@nooradiana.com>
 */
interface ModelRequestInterface
{
    /**
     * @return array
     */
    public function toRequestBody(): array;
}
