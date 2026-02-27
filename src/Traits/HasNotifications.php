<?php

namespace meysammaghsoudi\BaleMessenger\Traits;

use meysammaghsoudi\BaleMessenger\Messages\MessageBuilder;

/**
 * تریت برای ارسال نوتیفیکیشن‌های آماده
 */
trait HasNotifications
{
    /**
     * ارسال نوتیفیکیشن ثبت‌نام کاربر جدید
     */
    public function notifyNewUser(array $userData, int|string|null $chatId = null): array
    {
        $builder = $this->message()
            ->emoji('👤 ')->bold('کاربر جدید ثبت‌نام کرد')->newLine()
            ->separator()
            ->bold('نام: ')->text($userData['name'] ?? 'نامشخص')->newLine()
            ->bold('ایمیل: ')->text($userData['email'] ?? 'نامشخص')->newLine()
            ->bold('تلفن: ')->text($userData['phone'] ?? 'نامشخص')->newLine()
            ->bold('زمان: ')->text(now()->format('Y/m/d H:i:s'));

        return $this->sendBuilder($builder, $chatId);
    }

    /**
     * ارسال نوتیفیکیشن سفارش جدید
     */
    public function notifyNewOrder(array $orderData, int|string|null $chatId = null): array
    {
        $builder = $this->message()
            ->emoji('🛒 ')->bold('سفارش جدید دریافت شد')->newLine()
            ->separator()
            ->bold('شماره سفارش: ')->text($orderData['id'] ?? 'نامشخص')->newLine()
            ->bold('مشتری: ')->text($orderData['customer'] ?? 'نامشخص')->newLine()
            ->bold('مبلغ: ')->text(number_format($orderData['amount'] ?? 0) . ' تومان')->newLine()
            ->bold('تعداد اقلام: ')->text($orderData['items_count'] ?? 0)->newLine()
            ->separator()
            ->bold('زمان: ')->text(now()->format('Y/m/d H:i:s'));

        return $this->sendBuilder($builder, $chatId);
    }

    /**
     * ارسال نوتیفیکیشن پرداخت موفق
     */
    public function notifyPayment(array $paymentData, int|string|null $chatId = null): array
    {
        $builder = $this->message()
            ->emoji('💳 ')->bold('پرداخت موفق')->newLine()
            ->separator()
            ->bold('شماره تراکنش: ')->text($paymentData['transaction_id'] ?? 'نامشخص')->newLine()
            ->bold('مبلغ: ')->text(number_format($paymentData['amount'] ?? 0) . ' تومان')->newLine()
            ->bold('پرداخت‌کننده: ')->text($paymentData['payer'] ?? 'نامشخص')->newLine()
            ->separator()
            ->emoji('✅ ')->text('پرداخت با موفقیت انجام شد');

        return $this->sendBuilder($builder, $chatId);
    }

    /**
     * ارسال نوتیفیکیشن خطای سیستم
     */
    public function notifyError(string $error, array $context = [], int|string|null $chatId = null): array
    {
        $builder = $this->message()
            ->emoji('🚨 ')->bold('خطای سیستم')->newLine()
            ->separator()
            ->bold('پیام: ')->text($error)->newLine()
            ->bold('فایل: ')->text($context['file'] ?? 'نامشخص')->newLine()
            ->bold('خط: ')->text($context['line'] ?? 'نامشخص')->newLine()
            ->separator()
            ->bold('زمان: ')->text(now()->format('Y/m/d H:i:s'));

        return $this->sendBuilder($builder, $chatId);
    }

    /**
     * ارسال نوتیفیکیشن بکاپ دیتابیس
     */
    public function notifyBackup(array $backupData, int|string|null $chatId = null): array
    {
        $status = ($backupData['success'] ?? false) ? '✅ موفق' : '❌ ناموفق';

        $builder = $this->message()
            ->emoji('💾 ')->bold('گزارش بکاپ دیتابیس')->newLine()
            ->separator()
            ->bold('وضعیت: ')->text($status)->newLine()
            ->bold('حجم: ')->text($backupData['size'] ?? 'نامشخص')->newLine()
            ->bold('مدت زمان: ')->text($backupData['duration'] ?? 'نامشخص')->newLine()
            ->separator()
            ->bold('زمان: ')->text(now()->format('Y/m/d H:i:s'));

        return $this->sendBuilder($builder, $chatId);
    }

    /**
     * ارسال نوتیفیکیشن ورود به سیستم
     */
    public function notifyLogin(array $loginData, int|string|null $chatId = null): array
    {
        $status = ($loginData['success'] ?? false) ? '✅ موفق' : '❌ ناموفق';

        $builder = $this->message()
            ->emoji('🔐 ')->bold('ورود به سیستم')->newLine()
            ->separator()
            ->bold('کاربر: ')->text($loginData['user'] ?? 'نامشخص')->newLine()
            ->bold('وضعیت: ')->text($status)->newLine()
            ->bold('IP: ')->text($loginData['ip'] ?? 'نامشخص')->newLine()
            ->bold('مرورگر: ')->text($loginData['browser'] ?? 'نامشخص')->newLine()
            ->separator()
            ->bold('زمان: ')->text(now()->format('Y/m/d H:i:s'));

        return $this->sendBuilder($builder, $chatId);
    }

    /**
     * ارسال نوتیفیکیشن گزارش روزانه
     */
    public function notifyDailyReport(array $reportData, int|string|null $chatId = null): array
    {
        $builder = $this->message()
            ->emoji('📊 ')->bold('گزارش روزانه')->newLine()
            ->separator()
            ->bold('تاریخ: ')->text(now()->format('Y/m/d'))->newLine()
            ->separator();

        foreach ($reportData as $key => $value) {
            $builder->bold($key . ': ')->text($value)->newLine();
        }

        return $this->sendBuilder($builder, $chatId);
    }

    /**
     * ارسال نوتیفیکیشن به‌روزرسانی سیستم
     */
    public function notifyUpdate(string $version, array $changes = [], int|string|null $chatId = null): array
    {
        $builder = $this->message()
            ->emoji('🔄 ')->bold('به‌روزرسانی سیستم')->newLine()
            ->separator()
            ->bold('نسخه جدید: ')->text($version)->newLine()
            ->separator();

        if (!empty($changes)) {
            $builder->bold('تغییرات:')->newLine();
            foreach ($changes as $change) {
                $builder->listItem($change);
            }
        }

        return $this->sendBuilder($builder, $chatId);
    }
}
