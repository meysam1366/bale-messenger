<?php

namespace meysammaghsoudi\BaleMessenger;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use meysammaghsoudi\BaleMessenger\Contracts\BaleClientInterface;
use meysammaghsoudi\BaleMessenger\Exceptions\BaleApiException;
use meysammaghsoudi\BaleMessenger\Exceptions\BaleConnectionException;

/**
 * کلاس اصلی ارتباط با API پیام‌رسان بله
 */
class BaleClient implements BaleClientInterface
{
    protected const BASE_URL = 'https://tapi.bale.ai';

    protected Client $httpClient;
    protected string $token;
    protected array $config;

    public function __construct(string $token, array $config = [])
    {
        $this->token = $token;
        $this->config = array_merge([
            'timeout' => 30,
            'connect_timeout' => 10,
            'base_url' => self::BASE_URL,
        ], $config);

        $this->httpClient = new Client([
            'base_uri' => $this->config['base_url'],
            'timeout' => $this->config['timeout'],
            'connect_timeout' => $this->config['connect_timeout'],
            'http_errors' => false,
        ]);
    }

    /**
     * ارسال درخواست به API بله
     */
    public function request(string $method, string $httpMethod = 'POST', array $params = [], array $files = []): array
    {
        $url = "/bot{$this->token}/{$method}";

        try {
            $options = [];

            if ($httpMethod === 'GET') {
                $options['query'] = $params;
            } else {
                if (!empty($files)) {
                    $multipart = [];
                    foreach ($params as $key => $value) {
                        $multipart[] = ['name' => $key, 'contents' => $value];
                    }
                    foreach ($files as $key => $file) {
                        $multipart[] = [
                            'name' => $key,
                            'contents' => fopen($file['path'], 'r'),
                            'filename' => $file['name'] ?? basename($file['path']),
                        ];
                    }
                    $options['multipart'] = $multipart;
                } else {
                    $options['json'] = $params;
                }
            }

            $response = $this->httpClient->request($httpMethod, $url, $options);
            $body = json_decode($response->getBody()->getContents(), true);

            if (!$body['ok'] ?? false) {
                throw new BaleApiException(
                    $body['description'] ?? 'Unknown API error',
                    $body['error_code'] ?? $response->getStatusCode()
                );
            }

            return $body['result'] ?? [];
        } catch (GuzzleException $e) {
            throw new BaleConnectionException(
                "Connection error: {$e->getMessage()}",
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * ارسال پیام متنی
     */
    public function sendMessage(int|string $chatId, string $text, array $options = []): array
    {
        $params = array_merge([
            'chat_id' => $chatId,
            'text' => $text,
        ], $options);

        return $this->request('sendMessage', 'POST', $params);
    }

    /**
     * ارسال عکس
     */
    public function sendPhoto(int|string $chatId, string $photo, array $options = []): array
    {
        $params = array_merge([
            'chat_id' => $chatId,
            'photo' => $photo,
        ], $options);

        return $this->request('sendPhoto', 'POST', $params);
    }

    /**
     * ارسال فایل
     */
    public function sendDocument(int|string $chatId, string $document, array $options = []): array
    {
        $params = array_merge([
            'chat_id' => $chatId,
            'document' => $document,
        ], $options);

        return $this->request('sendDocument', 'POST', $params);
    }

    /**
     * ارسال ویدیو
     */
    public function sendVideo(int|string $chatId, string $video, array $options = []): array
    {
        $params = array_merge([
            'chat_id' => $chatId,
            'video' => $video,
        ], $options);

        return $this->request('sendVideo', 'POST', $params);
    }

    /**
     * ارسال صدا
     */
    public function sendAudio(int|string $chatId, string $audio, array $options = []): array
    {
        $params = array_merge([
            'chat_id' => $chatId,
            'audio' => $audio,
        ], $options);

        return $this->request('sendAudio', 'POST', $params);
    }

    /**
     * ارسال موقعیت مکانی
     */
    public function sendLocation(int|string $chatId, float $latitude, float $longitude, array $options = []): array
    {
        $params = array_merge([
            'chat_id' => $chatId,
            'latitude' => $latitude,
            'longitude' => $longitude,
        ], $options);

        return $this->request('sendLocation', 'POST', $params);
    }

    /**
     * ارسال مخاطب
     */
    public function sendContact(int|string $chatId, string $phoneNumber, string $firstName, array $options = []): array
    {
        $params = array_merge([
            'chat_id' => $chatId,
            'phone_number' => $phoneNumber,
            'first_name' => $firstName,
        ], $options);

        return $this->request('sendContact', 'POST', $params);
    }

    /**
     * ویرایش پیام متنی
     */
    public function editMessageText(int|string $chatId, int $messageId, string $text, array $options = []): array
    {
        $params = array_merge([
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'text' => $text,
        ], $options);

        return $this->request('editMessageText', 'POST', $params);
    }

    /**
     * حذف پیام
     */
    public function deleteMessage(int|string $chatId, int $messageId): bool
    {
        $result = $this->request('deleteMessage', 'POST', [
            'chat_id' => $chatId,
            'message_id' => $messageId,
        ]);

        return $result === true;
    }

    /**
     * فوروارد پیام
     */
    public function forwardMessage(int|string $chatId, int|string $fromChatId, int $messageId, array $options = []): array
    {
        $params = array_merge([
            'chat_id' => $chatId,
            'from_chat_id' => $fromChatId,
            'message_id' => $messageId,
        ], $options);

        return $this->request('forwardMessage', 'POST', $params);
    }

    /**
     * ارسال اکشن چت
     */
    public function sendChatAction(int|string $chatId, string $action): bool
    {
        $result = $this->request('sendChatAction', 'POST', [
            'chat_id' => $chatId,
            'action' => $action,
        ]);

        return $result === true;
    }

    /**
     * دریافت اطلاعات ربات
     */
    public function getMe(): array
    {
        return $this->request('getMe', 'GET');
    }

    /**
     * دریافت آپدیت‌ها
     */
    public function getUpdates(array $options = []): array
    {
        return $this->request('getUpdates', 'GET', $options);
    }

    /**
     * تنظیم وب‌هوک
     */
    public function setWebhook(string $url, array $options = []): bool
    {
        $params = array_merge(['url' => $url], $options);
        $result = $this->request('setWebhook', 'POST', $params);

        return $result === true;
    }

    /**
     * حذف وب‌هوک
     */
    public function deleteWebhook(): bool
    {
        $result = $this->request('deleteWebhook', 'POST');

        return $result === true;
    }

    /**
     * دریافت اطلاعات وب‌هوک
     */
    public function getWebhookInfo(): array
    {
        return $this->request('getWebhookInfo', 'GET');
    }

    /**
     * دریافت اطلاعات چت
     */
    public function getChat(int|string $chatId): array
    {
        return $this->request('getChat', 'GET', ['chat_id' => $chatId]);
    }

    /**
     * دریافت اعضای مدیر چت
     */
    public function getChatAdministrators(int|string $chatId): array
    {
        return $this->request('getChatAdministrators', 'GET', ['chat_id' => $chatId]);
    }

    /**
     * دریافت تعداد اعضای چت
     */
    public function getChatMemberCount(int|string $chatId): int
    {
        return $this->request('getChatMemberCount', 'GET', ['chat_id' => $chatId]);
    }

    /**
     * سنجش latency سرور
     */
    public function ping(): float
    {
        $start = microtime(true);
        $this->getMe();
        return (microtime(true) - $start) * 1000;
    }
}
