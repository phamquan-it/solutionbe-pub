<?php

namespace App\DTO;

class PaymentRequest
{
    public string $gateway;
    public string $transactionDate;
    public string $accountNumber;
    public ?string $subAccount;
    public ?string $code;
    public string $content;
    public string $transferType;
    public string $description;
    public float $transferAmount;
    public string $referenceCode;
    public float $accumulated;
    public int $id;

    public function __construct(array $data)
    {
        $this->gateway = $data['gateway'];
        $this->transactionDate = $data['transactionDate'];
        $this->accountNumber = $data['accountNumber'];
        $this->subAccount = $data['subAccount'] ?? null;
        $this->code = $data['code'] ?? null;
        $this->content = $data['content'];
        $this->transferType = $data['transferType'];
        $this->description = $data['description'];
        $this->transferAmount = (float) $data['transferAmount'];
        $this->referenceCode = $data['referenceCode'];
        $this->accumulated = (float) $data['accumulated'];
        $this->id = (int) $data['id'];
    }
}