<?php

namespace App\Services\Tomoni\Models\Accounting;

use App\Services\Tomoni\MachineService;
use App\Services\Tomoni\Machine\AccountingService;
use App\Services\Tomoni\Model;
use Illuminate\Support\Collection;

class Transaction extends Model
{
    const TYPE_VAILABLE_FOR_ORDER = [
        'debit-order',
        'debit-order-refund',
        'debit-service',
        'debit-service-refund',
        'payment-order',
        'payment-order-refund',
        'payment-service',
        'payment-service-refund',
        'compensation',
        'compensation-refund',
    ];

    const TYPE_VAILABLE_FOR_PURCHASE = [
        'debit-purchase',
        'debit-purchase-refund',
        'payment-purchase',
        'payment-purchase-refund',
    ];

    const TYPE_VAILABLE_FOR_LADING_BILL = [
        'debit-service',
        'debit-service-refund',
        'payment-service',
        'payment-service-refund',
    ];

    protected static function apiResource(): string
    {
        return 'transactions';
    }

    protected static function service(): MachineService
    {
        return new AccountingService;
    }

    // get transactions by reference receipt
    public static function getByReferenceReceipt(array $type_ids, array $currency_ids, array|string $receiptable_ids, string $receiptable_type): Collection
    {
        $receipts = Receipt::whereIn('receiptable_id', (array) $receiptable_ids)->where('receiptable_type', '=', $receiptable_type)->get();
        $transaction_ids = collect($receipts)->unique('transaction_id')->pluck('transaction_id')->all();

        return self::whereIn('type_id', $type_ids)->whereIn('currency_id', $currency_ids)->whereIn('id', $transaction_ids)->get();
    }

    public static function getAmountByReferenceReceipt(string $transaction_type, string $currency_type, string $organization_id, string $object_type, string $object_id): float
    {
        $query = [
            'transaction_type' => $transaction_type,
            'currency_type' => $currency_type,
            'organization_id' => $organization_id,
            'objects[0][type]' => $object_type,
            'objects[0][ids]' => [$object_id],
        ];

        return static::service()->post('amount-by-reference-receipt', $query);
    }

    public static function getAmountByReferenceReceiptForContract(string $transaction_type, string $currency_type, string $organization_id, string $contract_id, array $order_ids): float
    {
        $query = [
            'transaction_type' => $transaction_type,
            'currency_type' => $currency_type,
            'organization_id' => $organization_id,
            'objects[0][type]' => 'contract',
            'objects[0][ids]' => [$contract_id],
        ];

        if (count($order_ids)) {
            $query = array_merge($query, [
                'objects[1][type]' => 'order',
                'objects[1][ids]' => $order_ids,
            ]);
        }

        return static::service()->post('amount-by-reference-receipt', $query);
    }

    // order
    public static function getOrderPurchaseCostPaid(string $order_id, string $currency_id, string $organization_id): float
    {
        return self::getAmountByReferenceReceipt('payment-order', $currency_id, $organization_id, 'order', $order_id);
    }

    public static function getOrderServiceFeePaid(string $order_id, string $currency_id, string $organization_id): float
    {
        return self::getAmountByReferenceReceipt('payment-service', $currency_id, $organization_id, 'order', $order_id);
    }

    public static function getOrderPurchaseCostDebited(string $order_id, string $currency_id, string $organization_id): float
    {
        return self::getAmountByReferenceReceipt('debit-order', $currency_id, $organization_id, 'order', $order_id);
    }

    public static function getOrderServiceFeeDebited(string $order_id, string $currency_id, string $organization_id): float
    {
        return self::getAmountByReferenceReceipt('debit-service', $currency_id, $organization_id, 'order', $order_id);
    }

    public static function getOrderCompensated(string $order_id, string $currency_id, string $organization_id): float
    {
        return self::getAmountByReferenceReceipt('compensation', $currency_id, $organization_id, 'order', $order_id);
    }

    // purchase
    public static function getPurchasePaid(string $order_id, string $currency_id, string $organization_id): float
    {
        return self::getAmountByReferenceReceipt('payment-purchase', $currency_id, $organization_id, 'purchase', $order_id);
    }

    public static function getPurchaseDebited(string $order_id, string $currency_id, string $organization_id): float
    {
        return self::getAmountByReferenceReceipt('debit-purchase', $currency_id, $organization_id, 'purchase', $order_id);
    }

    // action order
    public static function debitPurchaseCost(float $amount, string $currency_id, string $organization_id, string $objectable_id, string $receiptable_id, string $receiptable_type)
    {
        return static::createTransaction(
            $amount,
            $objectable_id,
            $receiptable_id,
            $receiptable_type,
            $currency_id,
            $organization_id,
            'debit-order',
            'debit-order-refund',
        );
    }

    public static function debitServiceFee(float $amount, string $currency_id, string $organization_id, string $objectable_id, string $receiptable_id, string $receiptable_type)
    {
        return static::createTransaction(
            $amount,
            $objectable_id,
            $receiptable_id,
            $receiptable_type,
            $currency_id,
            $organization_id,
            'debit-service',
            'debit-service-refund',
        );
    }

    public static function paymentPurchaseCost(float $amount, string $currency_id, string $organization_id, string $objectable_id, string $receiptable_id, string $receiptable_type)
    {
        return static::createTransaction(
            $amount,
            $objectable_id,
            $receiptable_id,
            $receiptable_type,
            $currency_id,
            $organization_id,
            'payment-order',
            'payment-order-refund',
        );
    }

    public static function paymentServiceFee(float $amount, string $currency_id, string $organization_id, string $objectable_id, string $receiptable_id, string $receiptable_type)
    {
        return static::createTransaction(
            $amount,
            $objectable_id,
            $receiptable_id,
            $receiptable_type,
            $currency_id,
            $organization_id,
            'payment-service',
            'payment-service-refund',
        );
    }

    public static function compensation(float $amount, string $currency_id, string $organization_id, string $objectable_id, string $receiptable_id, string $receiptable_type)
    {
        return static::createTransaction(
            $amount,
            $objectable_id,
            $receiptable_id,
            $receiptable_type,
            $currency_id,
            $organization_id,
            'compensation',
            'compensation-refund',
        );
    }

    // action purchase
    public static function debitPurchase(float $amount, string $currency_id, string $organization_id, string $objectable_id, string $receiptable_id, string $receiptable_type)
    {
        return static::createTransaction(
            $amount,
            $objectable_id,
            $receiptable_id,
            $receiptable_type,
            $currency_id,
            $organization_id,
            'debit-purchase',
            'debit-purchase-refund',
        );
    }

    public static function paymentPurchase(float $amount, string $currency_id, string $organization_id, string $objectable_id, string $receiptable_id, string $receiptable_type)
    {
        return static::createTransaction(
            $amount,
            $objectable_id,
            $receiptable_id,
            $receiptable_type,
            $currency_id,
            $organization_id,
            'payment-purchase',
            'payment-purchase-refund',
        );
    }

    public static function createTransaction(
        float $amount,
        string $objectable_id,
        string $receiptable_id,
        string $receiptable_type,
        string $currency_id,
        string $organization_id,
        string $transaction_type,
        string $transaction_type_refund
    ) {
        if ($amount == 0) {
            return null;
        }

        $transaction = [
            'amount' => $amount,
            'transactionable_id' => $objectable_id,
            'currency_id' => $currency_id,
            'organization_id' => $organization_id,
            'receipts[0][receiptable_id]' => $receiptable_id,
            'receipts[0][receiptable_type]' => $receiptable_type,
        ];

        try {
            $transaction['prepared_by_id'] =  auth()->user()->id();
        } catch (\Illuminate\Auth\AuthenticationException $th) {
        }

        $path = $transaction_type;
        if ($amount < 0) {
            $transaction['amount'] = abs($transaction['amount']);
            $path = $transaction_type_refund;
        }

        try {
            return static::service()->post(static::apiResource() . '/' . $path, $transaction);
        } catch (\GuzzleHttp\Exception\ClientException $ex) {
            logger($ex);
            return null;
        };
    }
}
