<?php

namespace LaravelIran\BaleMessenger\Contracts;

/**
 * اینترفیس کلاینت بله
 */
interface BaleClientInterface
{
    public function request(string $method, string $httpMethod = 'POST', array $params = [], array $files = []): array;
    public function sendMessage(int|string $chatId, string $text, array $options = []): array;
    public function sendPhoto(int|string $chatId, string $photo, array $options = []): array;
    public function sendDocument(int|string $chatId, string $document, array $options = []): array;
    public function sendVideo(int|string $chatId, string $video, array $options = []): array;
    public function sendAudio(int|string $chatId, string $audio, array $options = []): array;
}
