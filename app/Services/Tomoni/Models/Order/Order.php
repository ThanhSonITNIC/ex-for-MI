<?php

namespace App\Services\Tomoni\Models\Order;

use App\Services\Tomoni\Machine\OrderService;
use App\Services\Tomoni\Models\Helper\Price as PriceTomoni;
use App\Services\Tomoni\MachineService;
use App\Services\Tomoni\Model;
use App\Services\Tomoni\Models\Warehouse\SFA as SFATomoni;

class Order extends Model
{
    protected static function apiResource(): string
    {
        return 'orders';
    }

    protected static function service(): MachineService
    {
        return new OrderService;
    }

    public static function getInsuranceFee(string $organization, string $currency, float $insurance_declaration, float $timeline = null): float
    {
        $percent = PriceTomoni::amountWithConditions([
            'conditions[type]' => 'insurance-fee',
            'conditions[currency]' => $currency,
            'conditions[organization]' => $organization,
            'range' => $insurance_declaration,
            'timeline' => $timeline ?? time(),
        ]);

        return $insurance_declaration * $percent;
    }

    public static function getSpecialGoodsFee(string $organization, string $currency, float $special_declaration, float $timeline = null): float
    {
        $percent = PriceTomoni::amountWithConditions([
            'conditions[type]' => 'special-goods-fee',
            'conditions[currency]' => $currency,
            'conditions[organization]' => $organization,
            'range' => $special_declaration,
            'timeline' => $timeline ?? time(),
        ]);

        return $special_declaration * $percent;
    }

    public static function getCodCost(string $tracking_id, string $service_fee_currency_id): float
    {
        $sfa = SFATomoni::findByTrackingId($tracking_id);

        if (!$sfa) {
            return 0;
        }

        if ($sfa->currency_id == $service_fee_currency_id) {
            $rate = 1;
        } else {
            $rate = PriceTomoni::amountWithConditions([
                'conditions[type]' => 'exchange-rates',
                'conditions[from]' => $sfa->currency_id,
                'conditions[to]' => $service_fee_currency_id,
                'range' => $sfa->shipping_inside,
                'timeline' => $sfa->arrival_date ?? time(),
            ]);
        }

        return $sfa->shipping_inside * $rate;
    }
}
