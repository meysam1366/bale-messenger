<?php

namespace meysammaghsoudi\BaleMessenger\Support\Templates;

use meysammaghsoudi\BaleMessenger\Support\NotificationTemplate;

/**
 * قالب نوتیفیکیشن سفارش جدید
 */
class OrderNotification extends NotificationTemplate
{
    protected string $title = 'سفارش جدید';
    protected string $emoji = '🛒';

    public function orderId(int $id): static
    {
        $this->items['شماره سفارش'] = '#' . $id;
        return $this;
    }

    public function customer(string $name): static
    {
        $this->items['مشتری'] = $name;
        return $this;
    }

    public function amount(float $amount): static
    {
        $this->items['مبلغ'] = number_format($amount) . ' تومان';
        return $this;
    }

    public function itemsCount(int $count): static
    {
        $this->items['تعداد اقلام'] = $count;
        return $this;
    }

    public function status(string $status): static
    {
        $this->items['وضعیت'] = $status;
        return $this;
    }

    public function shippingAddress(string $address): static
    {
        $this->items['آدرس ارسال'] = $address;
        return $this;
    }
}
