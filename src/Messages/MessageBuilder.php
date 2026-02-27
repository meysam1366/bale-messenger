<?php

namespace LaravelIran\BaleMessenger\Messages;

/**
 * کلاس ساخت پیام‌های زیبا و فرمت‌شده برای بله
 */
class MessageBuilder
{
    protected string $content = '';
    protected string $parseMode = 'Markdown';
    protected array $inlineKeyboard = [];
    protected array $replyKeyboard = [];
    protected ?int $replyToMessageId = null;
    protected bool $disableWebPagePreview = false;
    protected bool $disableNotification = false;

    /**
     * شروع پیام جدید
     */
    public static function create(): static
    {
        return new static();
    }

    /**
     * تنظیم متن پیام
     */
    public function text(string $text): static
    {
        $this->content .= $text;
        return $this;
    }

    /**
     * اضافه کردن خط جدید
     */
    public function newLine(int $count = 1): static
    {
        $this->content .= str_repeat("\n", $count);
        return $this;
    }

    /**
     * اضافه کردن متن به انتهای پیام
     */
    public function line(string $text): static
    {
        $this->content .= $text . "\n";
        return $this;
    }

    /**
     * اضافه کردن متن بولد
     */
    public function bold(string $text): static
    {
        if ($this->parseMode === 'Markdown') {
            $this->content .= "*{$text}*";
        } else {
            $this->content .= "<b>{$text}</b>";
        }
        return $this;
    }

    /**
     * اضافه کردن متن ایتالیک
     */
    public function italic(string $text): static
    {
        if ($this->parseMode === 'Markdown') {
            $this->content .= "_{$text}_";
        } else {
            $this->content .= "<i>{$text}</i>";
        }
        return $this;
    }

    /**
     * اضافه کردن متن خط‌خورده
     */
    public function strikethrough(string $text): static
    {
        if ($this->parseMode === 'Markdown') {
            $this->content .= "~{$text}~";
        } else {
            $this->content .= "<s>{$text}</s>";
        }
        return $this;
    }

    /**
     * اضافه کردن کد
     */
    public function code(string $code): static
    {
        if ($this->parseMode === 'Markdown') {
            $this->content .= "`{$code}`";
        } else {
            $this->content .= "<code>{$code}</code>";
        }
        return $this;
    }

    /**
     * اضافه کردن بلوک کد
     */
    public function codeBlock(string $code, string $language = ''): static
    {
        if ($this->parseMode === 'Markdown') {
            $this->content .= "```{$language}\n{$code}\n```";
        } else {
            $this->content .= "<pre><code class=\"language-{$language}\">{$code}</code></pre>";
        }
        return $this;
    }

    /**
     * اضافه کردن لینک
     */
    public function link(string $text, string $url): static
    {
        if ($this->parseMode === 'Markdown') {
            $this->content .= "[{$text}]({$url})";
        } else {
            $this->content .= "<a href=\"{$url}\">{$text}</a>";
        }
        return $this;
    }

    /**
     * اضافه کردن منشن کاربر
     */
    public function mention(int $userId, string $text = ''): static
    {
        if ($this->parseMode === 'Markdown') {
            $this->content .= "[{$text}](tg://user?id={$userId})";
        } else {
            $this->content .= "<a href=\"tg://user?id={$userId}\">{$text}</a>";
        }
        return $this;
    }

    /**
     * اضافه کردن ایموجی
     */
    public function emoji(string $emoji): static
    {
        $this->content .= $emoji;
        return $this;
    }

    /**
     * اضافه کردن جداکننده زیبا
     */
    public function separator(): static
    {
        $this->content .= "\n━━━━━━━━━━━━━━━━━━━━\n";
        return $this;
    }

    /**
     * اضافه کردن خط شکست
     */
    public function divider(string $char = '─', int $length = 25): static
    {
        $this->content .= "\n" . str_repeat($char, $length) . "\n";
        return $this;
    }

    /**
     * اضافه کردن آیتم لیست
     */
    public function listItem(string $text, string $bullet = '•'): static
    {
        $this->content .= "{$bullet} {$text}\n";
        return $this;
    }

    /**
     * اضافه کردن آیتم لیست شماره‌دار
     */
    public function numberedItem(string $text, int $number): static
    {
        $this->content .= "{$number}. {$text}\n";
        return $this;
    }

    /**
     * اضافه کردن هدر
     */
    public function header(string $text, int $level = 1): static
    {
        if ($this->parseMode === 'Markdown') {
            $this->content .= str_repeat('#', $level) . " {$text}\n";
        } else {
            $this->content .= "<h{$level}>{$text}</h{$level}>\n";
        }
        return $this;
    }

    /**
     * اضافه کردن بلاک نقل قول
     */
    public function quote(string $text): static
    {
        if ($this->parseMode === 'Markdown') {
            $this->content .= "> {$text}\n";
        } else {
            $this->content .= "<blockquote>{$text}</blockquote>\n";
        }
        return $this;
    }

    /**
     * اضافه کردن هشدار
     */
    public function warning(string $text): static
    {
        $this->content .= "⚠️ {$text}\n";
        return $this;
    }

    /**
     * اضافه کردن موفقیت
     */
    public function success(string $text): static
    {
        $this->content .= "✅ {$text}\n";
        return $this;
    }

    /**
     * اضافه کردن خطا
     */
    public function error(string $text): static
    {
        $this->content .= "❌ {$text}\n";
        return $this;
    }

    /**
     * اضافه کردن اطلاعات
     */
    public function info(string $text): static
    {
        $this->content .= "ℹ️ {$text}\n";
        return $this;
    }

    /**
     * ساخت کارت اطلاعاتی زیبا
     */
    public function card(string $title, array $items): static
    {
        $this->bold($title)->newLine();
        $this->separator();

        foreach ($items as $key => $value) {
            $this->bold($key . ': ')->text($value)->newLine();
        }

        return $this;
    }

    /**
     * ساخت جدول ساده
     */
    public function table(array $headers, array $rows): static
    {
        // سرستون‌ها
        $this->text(implode(' | ', $headers))->newLine();
        $this->text(implode('-|-', array_map(fn($h) => str_repeat('-', mb_strlen($h)), $headers)))->newLine();

        // ردیف‌ها
        foreach ($rows as $row) {
            $this->text(implode(' | ', $row))->newLine();
        }

        return $this;
    }

    /**
     * تنظیم حالت قالب‌بندی
     */
    public function parseMode(string $mode): static
    {
        $this->parseMode = $mode;
        return $this;
    }

    /**
     * فعال کردن حالت HTML
     */
    public function html(): static
    {
        $this->parseMode = 'HTML';
        return $this;
    }

    /**
     * فعال کردن حالت Markdown
     */
    public function markdown(): static
    {
        $this->parseMode = 'Markdown';
        return $this;
    }

    /**
     * اضافه کردن دکمه اینلاین
     */
    public function inlineButton(string $text, string $callbackData, string $type = 'callback_data'): static
    {
        $button = ['text' => $text];

        if ($type === 'url') {
            $button['url'] = $callbackData;
        } else {
            $button['callback_data'] = $callbackData;
        }

        $this->inlineKeyboard[] = [$button];
        return $this;
    }

    /**
     * اضافه کردن ردیف دکمه‌های اینلاین
     */
    public function inlineRow(array $buttons): static
    {
        $row = [];
        foreach ($buttons as $text => $data) {
            if (str_starts_with($data, 'http')) {
                $row[] = ['text' => $text, 'url' => $data];
            } else {
                $row[] = ['text' => $text, 'callback_data' => $data];
            }
        }
        $this->inlineKeyboard[] = $row;
        return $this;
    }

    /**
     * اضافه کردن دکمه معمولی
     */
    public function button(string $text): static
    {
        $this->replyKeyboard[] = [['text' => $text]];
        return $this;
    }

    /**
     * اضافه کردن ردیف دکمه‌های معمولی
     */
    public function buttonRow(array $buttons): static
    {
        $row = array_map(fn($text) => ['text' => $text], $buttons);
        $this->replyKeyboard[] = $row;
        return $this;
    }

    /**
     * پاسخ به پیام خاص
     */
    public function replyTo(int $messageId): static
    {
        $this->replyToMessageId = $messageId;
        return $this;
    }

    /**
     * غیرفعال کردن پیش‌نمایش لینک
     */
    public function disablePreview(bool $disable = true): static
    {
        $this->disableWebPagePreview = $disable;
        return $this;
    }

    /**
     * غیرفعال کردن نوتیفیکیشن
     */
    public function silent(bool $disable = true): static
    {
        $this->disableNotification = $disable;
        return $this;
    }

    /**
     * دریافت محتوای پیام
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * دریافت پارامترهای پیام برای ارسال
     */
    public function toArray(): array
    {
        $params = [
            'text' => $this->content,
            'parse_mode' => $this->parseMode,
        ];

        if (!empty($this->inlineKeyboard)) {
            $params['reply_markup'] = json_encode([
                'inline_keyboard' => $this->inlineKeyboard,
            ]);
        } elseif (!empty($this->replyKeyboard)) {
            $params['reply_markup'] = json_encode([
                'keyboard' => $this->replyKeyboard,
                'resize_keyboard' => true,
            ]);
        }

        if ($this->replyToMessageId !== null) {
            $params['reply_to_message_id'] = $this->replyToMessageId;
        }

        if ($this->disableWebPagePreview) {
            $params['disable_web_page_preview'] = true;
        }

        if ($this->disableNotification) {
            $params['disable_notification'] = true;
        }

        return $params;
    }

    /**
     * تبدیل به رشته
     */
    public function __toString(): string
    {
        return $this->content;
    }
}
