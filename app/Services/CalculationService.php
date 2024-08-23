<?php
namespace App\Services;

use App\Models\DiscountCode;
use Carbon\Carbon;

class CalculationService
{
    public static function estimate($data, $team, $isNumberFormat = true)
    {
        // Get Customer Data
        // $customer = $team->customers()->where('id', $data['customer_id'])->firstOrFail();

        // Add Summarise Data
        $data['sub_total'] = 0;
        $data['total_discount'] = 0;
        $data['total_tax'] = 0;
        $data['total_amount'] = 0;

        // Get Primary Currency
        $currency = $team->getTeamPrimary();

        // Search Currency If Different Currency
        if ($currency->iso3 !== $data['currency']) {
            $currentCurrency = $team->currencies()->where('iso3', $data['currency'])->first();
            if ($currentCurrency) {
                $currency = $currentCurrency;
            }
        }

        // Set Currency Data
        $data['exchange_rate'] = $currency->rate;
        $data['currency'] = $currency->iso3;
        $data['currency_symbol'] = $currency->symbol;
        $data['number_format'] = $currency->number_format;

        // Get Currency Tax
        $taxCurrency = $team->getCurrencyTaxes($data['currency']);

        // Calculate Items
        foreach ($data['items'] as $key => $item) {
            // Preset Data
            $data['items'][$key]['line_amount'] = 0;
            $data['items'][$key]['discount_amount'] = 0;

            // Check For Item Discount
            // Replan Revamp
            if ($item['item_type'] === 'service') {
                $service = $team->services()->where('id', $item['service_id'])->first();

                if ($service) {
                    $data['items'][$key]['name'] = $service->name;
                    $data['items'][$key]['description'] = $service->description;
                    $data['items'][$key]['unit_amount'] = $service->price * $data['exchange_rate'];
                    $data['items'][$key]['line_amount'] = $data['items'][$key]['unit_amount'] * $item['quantity'];
                    
                    // Discount Based on Item
                    $serviceDiscount = static::isValidDiscount(
                        $service->discounts, 
                        $data['items'][$key]['line_amount']
                    );
                    $data['items'][$key]['discount_amount'] = $serviceDiscount['totalDiscount'];
                    $data['items'][$key]['discounts'] = $serviceDiscount['discounts'];

                    // Discount Based on Categories
                    // $serviceCategoryDiscount = $this->isValidDiscount(
                    //     $service->serviceCategory->discounts, 
                    //     $data['items'][$key]['line_amount']
                    // );
                    // $data['items'][$key]['discount_amount'] += $serviceCategoryDiscount['totalDiscount'];
                    // $data['items'][$key]['discounts'] = array_merge(
                    //     $data['items'][$key]['discounts'],
                    //     $serviceCategoryDiscount['discounts']
                    // );

                    $data['items'][$key]['line_amount'] -= $data['items'][$key]['discount_amount'];
                }
            } else {
                $data['items'][$key]['line_amount'] = $item['unit_amount'] * $item['quantity'];
            }

            // Round Up Price
            $data['items'][$key]['sub_total'] = round($data['items'][$key]['line_amount'], 2);
            $data['items'][$key]['line_amount'] = round($data['items'][$key]['line_amount'], 2);
            $data['items'][$key]['discount_amount'] = round($data['items'][$key]['discount_amount'], 2);

            // Calculate Sub Total Discount Amount
            if (isset($item['is_enabled'])) {
                if ($item['is_enabled']) {
                    $data['sub_total'] += $data['items'][$key]['line_amount'] ?? 0;
                    $data['total_discount'] += $data['items'][$key]['discount_amount'] ?? 0;
                }
            } else {
                $data['sub_total'] += $data['items'][$key]['line_amount'] ?? 0;
                $data['total_discount'] += $data['items'][$key]['discount_amount'] ?? 0;
            }

            // Format Number Based On Currency
            if ($isNumberFormat) {
                $data['items'][$key]['sub_total'] = number_format($data['items'][$key]['line_amount'], $data['number_format']);
                $data['items'][$key]['line_amount'] = number_format($data['items'][$key]['line_amount'], $data['number_format']);
                $data['items'][$key]['discount_amount'] = number_format($data['items'][$key]['discount_amount'], $data['number_format']);
            }
        }

        // Get All Discount
        $customerDiscount = static::isValidDiscount(
            $team->discounts()->where('discount_apply_type', 'all')->get(), 
            $data['sub_total']
        );
        $data['sub_total'] -= $customerDiscount['totalDiscount'];
        $data['total_discount'] += $customerDiscount['totalDiscount'];
        $data['discounts'] = $customerDiscount['discounts'];

        // Get Customer Discount
        // $customerDiscount = static::isValidDiscount(
        //     $customer->discounts, 
        //     $data['sub_total']
        // );
        // $data['sub_total'] -= $customerDiscount['totalDiscount'];
        // $data['total_discount'] += $customerDiscount['totalDiscount'];
        // $data['discounts'] = $customerDiscount['discounts'];

        // Get Discount Code
        $getDiscountCode = static::isValidDiscountCode(
            $team,
            $data['discount_code'] ?? null, 
            $data['sub_total']
        );
        $data['sub_total'] -= $getDiscountCode['totalDiscount'];
        $data['total_discount'] += $getDiscountCode['totalDiscount'];
        $data['discounts'] = array_merge(
            $data['discounts'] ?? [],
            $getDiscountCode['discounts']
        );
        
        $data['total_amount'] = $data['sub_total'] - $data['total_discount'];

        $data['taxes'] = [];
        foreach($taxCurrency as $tax) {
            $taxAmount = 0;

            if ($tax->charge_type === 'percentage') {
                $taxAmount = $data['total_amount'] -
                    ($data['total_amount'] / (1 + ($tax->amount / 100)));

                if ($tax->tax_type === 'exclusive') {
                    $taxAmount = ($tax->amount / 100) * $data['total_amount'];

                    $data['total_amount'] += $data['total_tax'];
                }

                $data['total_tax'] +=  $taxAmount;
            } else {
                $taxAmount = $tax->amount;

                if ($tax->tax_type === 'exclusive') {
                    $data['total_amount'] += $taxAmount;
                }

                $data['total_tax'] += $taxAmount;
            }
            
            $taxData = [
                'tax_name' => $tax->name,
                'tax_type' => $tax->tax_type,
                'tax_charge_type' => $tax->charge_type,
                'tax_rate' => $tax->amount,
                'tax_amount' => $taxAmount
            ];

            if ($isNumberFormat) {
                $taxData['tax_rate'] = number_format($taxData['tax_rate'], $data['number_format']);
                $taxData['tax_amount'] = number_format($taxData['tax_amount'], $data['number_format']);
            }

            $data['taxes'][] = $taxData;
        }

        // Round Up Price
        $data['sub_total'] = round($data['sub_total'], 2);
        $data['total_discount'] = round($data['total_discount'], 2);
        $data['total_tax'] = round($data['total_tax'], 2);
        $data['total_amount'] = round($data['total_amount'], 2);

        // Format Number Based On Currency
        if ($isNumberFormat) {
            $data['sub_total'] = number_format($data['sub_total'], $data['number_format']);
            $data['total_discount'] = number_format($data['total_discount'], $data['number_format']);
            $data['total_tax'] = number_format($data['total_tax'], $data['number_format']);
            $data['total_amount'] = number_format($data['total_amount'], $data['number_format']);
        }

        return $data;
    }

    public static function renewalEstimate($data)
    {   
        // Date Term Interval
        $dateInterval = [
            'day' => 'P1D',
            'week' => 'P1W',
            'month' => 'P1M',
            'annual' => 'P1Y',
        ];

        // Preparation Data Convert String to DateTime
        $startDate = Carbon::parse($data['start_date']);
        $subscriptionDate = new DateTimeImmutable($startDate);
        $currentDate = new DateTimeImmutable($startDate);

        $result = [];
        for ($i = 1; $i <= $data['frequency']; $i++) {
            $currentDate = $subscriptionDate;

            switch ($data['term']) {
                case 'day':
                case 'week':
                    // now add interval to the current renewal date
                    $newRenewalDate = $subscriptionDate->add(new DateInterval($dateInterval[$data['term']]));
                    break;
                case 'month':
                case 'annual':
                    // month subscription, replace year and month with current year and month
                    // year subscription, replace year
                    $newRenewalDate = $subscriptionDate->setDate(
                        $currentDate->format('Y'),
                        $data['term'] === 'month' ? $currentDate->format('m') : $startDate->format('m'),
                        $startDate->format('d')
                    );

                    // now add interval to the current renewal date
                    $newRenewalDate = $newRenewalDate->add(new DateInterval($dateInterval[$data['term']]));

                    // adjust date if the day of the month doesn't exist in the current month
                    if ($subscriptionDate->format('d') !== $newRenewalDate->format('d')) {
                        $newRenewalDate = $newRenewalDate->modify('last day of previous month');
                    }
                    break;
                    // year subscription, replace year
                    $newRenewalDate = $subscriptionDate->setDate(
                        $currentDate->format('Y'),
                        $startDate->format('m'),
                        $startDate->format('d')
                    );

                    // now add interval to the current renewal date only if above calculated date is in the past
                    $newRenewalDate = $newRenewalDate->add(new DateInterval('P1Y'));

                    // adjust date if the day of the month doesn't exist in the current month
                    if ($subscriptionDate->format('d') !== $newRenewalDate->format('d')) {
                        $newRenewalDate = $newRenewalDate->modify('last day of previous month');
                    }
                    break;
            }

            // Set new renewal date
            $renewalDate = $newRenewalDate;

            $result[] = [
                'current_term' => $subscriptionDate->format('Y-m-d'),
                'next_term' => $renewalDate->format('Y-m-d')
            ];
            
            // Prepare for next renewal date calculation
            $subscriptionDate = $renewalDate;
        }

        return $result;
    }

    protected static function isValidDiscount($discountData, $amount)
    {
        $discounts = [];
        $totalDiscount = 0;

        foreach ($discountData as $discount) {
            $discount->valid_to = $discount->valid_to ?? Carbon::now()->setTime(23,59,59);
            if (Carbon::now() >= $discount->valid_from && Carbon::now() <= $discount->valid_to) {
                $isValidDiscount = true;

                if ($discount->is_redemption) {
                    if ($discount->quantity < 0) {
                        $isValidDiscount = false;
                    }
                }

                if ($isValidDiscount) {
                    $discounts[] = [
                        'name' => $discount->name,
                        'description' => $discount->description,
                        'discount_type' => $discount->discount_type,
                        'amount' => $discount->amount
                    ];
                    
                    if ($discount->discount_type === 'percentage') {
                        $totalDiscount += ($discount->amount / 100) * $amount;
                    } else {
                        $totalDiscount += $discount->amount;
                    }
                }
            }
        }

        return [
            'discounts' => $discounts,
            'totalDiscount' => $totalDiscount
        ];
    }

    protected static function isValidDiscountCode($team, $discountCode, $amount)
    {
        $discounts = [];
        $totalDiscount = 0;

        $discount = DiscountCode::leftJoin(
            'discounts', 
            'discount_codes.discount_id', 
            '=', 
            'discounts.id'
        )->where('discounts.team_id', $team->id)
        ->where('discount_codes.discount_code', $discountCode)
        ->where('discount_codes.is_used', false)
        ->select('discounts.*')
        ->first();

        if ($discount) {
            $discount->valid_to = $discount->valid_to ?? Carbon::now()->setTime(23,59,59);
            if (Carbon::now() >= $discount->valid_from && Carbon::now() <= $discount->valid_to) {
                $isValidDiscount = true;

                if ($isValidDiscount) {
                    $discounts[] = [
                        'name' => $discount->name,
                        'description' => $discount->description,
                        'discount_type' => $discount->discount_type,
                        'amount' => $discount->amount
                    ];
                    
                    if ($discount->discount_type === 'percentage') {
                        $totalDiscount += ($discount->amount / 100) * $amount;
                    } else {
                        $totalDiscount += $discount->amount;
                    }
                }
            }
        }

        return [
            'discounts' => $discounts,
            'totalDiscount' => $totalDiscount
        ];
    }
}