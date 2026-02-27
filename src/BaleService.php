<?php

namespace LaravelIran\BaleMessenger;

use LaravelIran\BaleMessenger\Contracts\BaleClientInterface;
use LaravelIran\BaleMessenger\Messages\MessageBuilder;
use LaravelIran\BaleMessenger\Traits\HasNotifications;
use LaravelIran\BaleMessenger\Support\NotificationTemplate;

/**
 * سرویس اصلی ارسال پیام به بله
 */
class BaleService
{
    use HasNotifications;

    protected BaleClientInterface $client;
    protected int|string|null $defaultChatId = null;

    public function __construct(BaleClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * تنظیم شناسه چت پیش‌فرض
     */
    public function to(int|string $chatId): static
    {
        $this->defaultChatId = $chatId;
        return $this;
    }

    /**
     * ایجاد سازنده پیام جدید
     */
    public function message(): MessageBuilder
    {
        return MessageBuilder::create();
    }

    /**
     * ارسال پیام متنی ساده
     */
    public function send(string $text, int|string|null $chatId = null, array $options = []): array
    {
        return $this->client->sendMessage(
            $chatId ?? $this->defaultChatId ?? throw new \InvalidArgumentException('Chat ID is required'),
            $text,
            $options
        );
    }

    /**
     * ارسال پیام از MessageBuilder
     */
    public function sendBuilder(MessageBuilder $builder, int|string|null $chatId = null): array
    {
        return $this->client->sendMessage(
            $chatId ?? $this->defaultChatId ?? throw new \InvalidArgumentException('Chat ID is required'),
            $builder->getContent(),
            $builder->toArray()
        );
    }

    /**
     * ارسال پیام موفقیت
     */
    public function sendSuccess(string $title, ?string $message = null, int|string|null $chatId = null): array
    {
        $builder = $this->message()
            ->emoji('✅ ')->bold($title);

        if ($message) {
            $builder->newLine()->text($message);
        }

        return $this->sendBuilder($builder, $chatId);
    }

    /**
     * ارسال پیام خطا
     */
    public function sendError(string $title, ?string $message = null, int|string|null $chatId = null): array
    {
        $builder = $this->message()
            ->emoji('❌ ')->bold($title);

        if ($message) {
            $builder->newLine()->text($message);
        }

        return $this->sendBuilder($builder, $chatId);
    }

    /**
     * ارسال پیام هشدار
     */
    public function sendWarning(string $title, ?string $message = null, int|string|null $chatId = null): array
    {
        $builder = $this->message()
            ->emoji('⚠️ ')->bold($title);

        if ($message) {
            $builder->newLine()->text($message);
        }

        return $this->sendBuilder($builder, $chatId);
    }

    /**
     * ارسال پیام اطلاع‌رسانی
     */
    public function sendInfo(string $title, ?string $message = null, int|string|null $chatId = null): array
    {
        $builder = $this->message()
            ->emoji('ℹ️ ')->bold($title);

        if ($message) {
            $builder->newLine()->text($message);
        }

        return $this->sendBuilder($builder, $chatId);
    }

    /**
     * ارسال عکس
     */
    public function sendPhoto(string $photo, ?string $caption = null, int|string|null $chatId = null, array $options = []): array
    {
        if ($caption) {
            $options['caption'] = $caption;
        }

        return $this->client->sendPhoto(
            $chatId ?? $this->defaultChatId ?? throw new \InvalidArgumentException('Chat ID is required'),
            $photo,
            $options
        );
    }

    /**
     * ارسال فایل
     */
    public function sendDocument(string $document, ?string $caption = null, int|string|null $chatId = null, array $options = []): array
    {
        if ($caption) {
            $options['caption'] = $caption;
        }

        return $this->client->sendDocument(
            $chatId ?? $this->defaultChatId ?? throw new \InvalidArgumentException('Chat ID is required'),
            $document,
            $options
        );
    }

    /**
     * ارسال ویدیو
     */
    public function sendVideo(string $video, ?string $caption = null, int|string|null $chatId = null, array $options = []): array
    {
        if ($caption) {
            $options['caption'] = $caption;
        }

        return $this->client->sendVideo(
            $chatId ?? $this->defaultChatId ?? throw new \InvalidArgumentException('Chat ID is required'),
            $video,
            $options
        );
    }

    /**
     * ارسال موقعیت مکانی
     */
    public function sendLocation(float $latitude, float $longitude, int|string|null $chatId = null, array $options = []): array
    {
        return $this->client->sendLocation(
            $chatId ?? $this->defaultChatId ?? throw new \InvalidArgumentException('Chat ID is required'),
            $latitude,
            $longitude,
            $options
        );
    }

    /**
     * ارسال نوتیفیکیشن سفارشی با قالب
     */
    public function sendNotification(NotificationTemplate $template, int|string|null $chatId = null): array
    {
        return $this->sendBuilder($template->build(), $chatId);
    }

    /**
     * نمایش وضعیت تایپینگ
     */
    public function typing(int|string|null $chatId = null): bool
    {
        return $this->client->sendChatAction(
            $chatId ?? $this->defaultChatId ?? throw new \InvalidArgumentException('Chat ID is required'),
            'typing'
        );
    }

    /**
     * نمایش وضعیت آپلود عکس
     */
    public function uploadingPhoto(int|string|null $chatId = null): bool
    {
        return $this->client->sendChatAction(
            $chatId ?? $this->defaultChatId ?? throw new \InvalidArgumentException('Chat ID is required'),
            'upload_photo'
        );
    }

    /**
     * نمایش وضعیت آپلود ویدیو
     */
    public function uploadingVideo(int|string|null $chatId = null): bool
    {
        return $this->client->sendChatAction(
            $chatId ?? $this->defaultChatId ?? throw new \InvalidArgumentException('Chat ID is required'),
            'upload_video'
        );
    }

    /**
     * دریافت اطلاعات ربات
     */
    public function getMe(): array
    {
        return $this->client->getMe();
    }

    /**
     * سنجش سرعت ارتباط با سرور
     */
    public function ping(): float
    {
        return $this->client->ping();
    }

    /**
     * دسترسی مستقیم به کلاینت
     */
    public function getClient(): BaleClientInterface
    {
        return $this->client;
    }
}
