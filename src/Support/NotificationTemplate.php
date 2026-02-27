<?php

namespace meysammaghsoudi\BaleMessenger\Support;

use meysammaghsoudi\BaleMessenger\Messages\MessageBuilder;

/**
 * کلاس پایه قالب نوتیفیکیشن
 */
abstract class NotificationTemplate
{
    protected string $title = '';
    protected string $emoji = '';
    protected array $items = [];
    protected array $buttons = [];
    protected bool $showTime = true;

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function title(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function emoji(string $emoji): static
    {
        $this->emoji = $emoji;
        return $this;
    }

    public function item(string $key, mixed $value): static
    {
        $this->items[$key] = $value;
        return $this;
    }

    public function button(string $text, string $data): static
    {
        $this->buttons[$text] = $data;
        return $this;
    }

    public function showTime(bool $show = true): static
    {
        $this->showTime = $show;
        return $this;
    }

    public function build(): MessageBuilder
    {
        $builder = MessageBuilder::create();

        if ($this->emoji) {
            $builder->emoji($this->emoji . ' ');
        }
        $builder->bold($this->title)->newLine();
        $builder->separator();

        foreach ($this->items as $key => $value) {
            $builder->bold($key . ': ')->text($value)->newLine();
        }

        if ($this->showTime) {
            $builder->separator()
                ->bold('زمان: ')->text(now()->format('Y/m/d H:i:s'));
        }

        if (!empty($this->buttons)) {
            $builder->inlineRow($this->buttons);
        }

        return $builder;
    }

    public function toArray(): array
    {
        return $this->build()->toArray();
    }
}
