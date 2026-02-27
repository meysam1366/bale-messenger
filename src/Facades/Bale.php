<?php

namespace LaravelIran\BaleMessenger\Facades;

use Illuminate\Support\Facades\Facade;
use LaravelIran\BaleMessenger\BaleService;
use LaravelIran\BaleMessenger\Messages\MessageBuilder;

/**
 * Facade برای دسترسی آسان به سرویس بله
 *
 * @method static BaleService to(int|string $chatId)
 * @method static MessageBuilder message()
 * @method static array send(string $text, int|string|null $chatId = null, array $options = [])
 * @method static array sendBuilder(MessageBuilder $builder, int|string|null $chatId = null)
 * @method static array sendSuccess(string $title, ?string $message = null, int|string|null $chatId = null)
 * @method static array sendError(string $title, ?string $message = null, int|string|null $chatId = null)
 * @method static array sendWarning(string $title, ?string $message = null, int|string|null $chatId = null)
 * @method static array sendInfo(string $title, ?string $message = null, int|string|null $chatId = null)
 * @method static array sendPhoto(string $photo, ?string $caption = null, int|string|null $chatId = null, array $options = [])
 * @method static array sendDocument(string $document, ?string $caption = null, int|string|null $chatId = null, array $options = [])
 * @method static array sendVideo(string $video, ?string $caption = null, int|string|null $chatId = null, array $options = [])
 * @method static array sendLocation(float $latitude, float $longitude, int|string|null $chatId = null, array $options = [])
 * @method static bool typing(int|string|null $chatId = null)
 * @method static array getMe()
 * @method static float ping()
 *
 * @see \LaravelIran\BaleMessenger\BaleService
 */
class Bale extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return BaleService::class;
    }
}
