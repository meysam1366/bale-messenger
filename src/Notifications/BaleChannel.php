<?php

namespace meysammaghsoudi\BaleMessenger\Notifications;

use Illuminate\Notifications\Notification;
use meysammaghsoudi\BaleMessenger\BaleService;
use meysammaghsoudi\BaleMessenger\Messages\MessageBuilder;

/**
 * کانال نوتیفیکیشن بله برای لاراول
 */
class BaleChannel
{
    protected BaleService $bale;

    public function __construct(BaleService $bale)
    {
        $this->bale = $bale;
    }

    public function send(mixed $notifiable, Notification $notification): ?array
    {
        $chatId = $notifiable->routeNotificationFor('bale', $notification);

        if (!$chatId) {
            return null;
        }

        $message = $notification->toBale($notifiable);

        if ($message instanceof MessageBuilder) {
            return $this->bale->sendBuilder($message, $chatId);
        }

        if (is_array($message)) {
            if (isset($message['photo'])) {
                return $this->bale->sendPhoto(
                    $message['photo'],
                    $message['caption'] ?? null,
                    $chatId,
                    $message['options'] ?? []
                );
            }

            if (isset($message['document'])) {
                return $this->bale->sendDocument(
                    $message['document'],
                    $message['caption'] ?? null,
                    $chatId,
                    $message['options'] ?? []
                );
            }

            if (isset($message['video'])) {
                return $this->bale->sendVideo(
                    $message['video'],
                    $message['caption'] ?? null,
                    $chatId,
                    $message['options'] ?? []
                );
            }

            if (isset($message['location'])) {
                return $this->bale->sendLocation(
                    $message['location']['latitude'],
                    $message['location']['longitude'],
                    $chatId,
                    $message['options'] ?? []
                );
            }

            return $this->bale->send(
                $message['text'] ?? $message['content'] ?? '',
                $chatId,
                $message['options'] ?? []
            );
        }

        return $this->bale->send((string) $message, $chatId);
    }
}
