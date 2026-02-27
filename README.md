# پکیج لاراول بله (Laravel Bale Messenger)

<p align="center">
    <strong>پکیج لاراول برای ارسال پیام‌های زیبا به پیام‌رسان بله</strong>
</p>

<p align="center">
    <a href="#نصب">نصب</a> •
    <a href="#استفاده-پایه">استفاده پایه</a> •
    <a href="#messagebuilder">MessageBuilder</a> •
    <a href="#نوتیفیکیشنها">نوتیفیکیشن‌ها</a> •
    <a href="#قالبهای-آماده">قالب‌های آماده</a>
</p>

---

## معرفی

این پکیج به شما امکان می‌دهد به سادگی از طریق برنامه لاراول خود به پیام‌رسان بله پیام ارسال کنید. با استفاده از **MessageBuilder** می‌توانید پیام‌های زیبا و فرمت‌شده بسازید و از قالب‌های آماده برای نوتیفیکیشن‌های رایج استفاده کنید.

### ویژگی‌ها

- ✅ ارسال پیام متنی با قالب‌بندی Markdown و HTML
- ✅ ارسال عکس، ویدیو، فایل و موقعیت مکانی
- ✅ **MessageBuilder** برای ساخت پیام‌های زیبا
- ✅ قالب‌های آماده نوتیفیکیشن (سفارش، پرداخت، خطا و...)
- ✅ پشتیبانی از **Laravel Notifications**
- ✅ کیبوردهای اینلاین و معمولی
- ✅ کارت‌ها و جدول‌های زیبا

---

## نصب

### ۱. نصب با Composer

```bash
composer require meysammaghsoudi/bale-messenger
```

### ۲. انتشار فایل کانفیگ

```bash
php artisan vendor:publish --tag=bale-config
```

### ۳. تنظیم متغیرهای محیطی

فایل `.env` را باز کنید و توکن ربات بله را اضافه کنید:

```env
BALE_BOT_TOKEN=your_bot_token_here
BALE_DEFAULT_CHAT_ID=123456789
```

---

## استفاده پایه

### ارسال پیام ساده

```php
use meysammaghsoudi\BaleMessenger\Facades\Bale;

// ارسال پیام ساده
Bale::send('سلام! این یک پیام تست است.', 123456789);

// یا با تعیین چت پیش‌فرض
Bale::to(123456789)->send('سلام دنیا!');
```

### ارسال پیام‌های زیبا

```php
use meysammaghsoudi\BaleMessenger\Facades\Bale;

// پیام موفقیت
Bale::sendSuccess('ثبت‌نام موفق', 'کاربر با موفقیت ثبت‌نام شد.');

// پیام خطا
Bale::sendError('خطا در پرداخت', 'لطفاً مجدداً تلاش کنید.');

// پیام هشدار
Bale::sendWarning('موجودی کم', 'موجودی محصول رو به اتمام است.');

// پیام اطلاع‌رسانی
Bale::sendInfo('به‌روزرسانی',نسخه جدید سیستم منتشر شد.');
```

### ارسال رسانه

```php
// ارسال عکس
Bale::sendPhoto('https://example.com/image.jpg', 'توضیحات عکس');

// ارسال فایل
Bale::sendDocument('https://example.com/file.pdf', 'فایل گزارش');

// ارسال ویدیو
Bale::sendVideo('https://example.com/video.mp4', 'ویدیو معرفی');

// ارسال موقعیت مکانی
Bale::sendLocation(35.6892, 51.3890); // تهران
```

---

## MessageBuilder

با **MessageBuilder** می‌توانید پیام‌های زیبا و حرفه‌ای بسازید:

### مثال پایه

```php
use meysammaghsoudi\BaleMessenger\Facades\Bale;

$message = Bale::message()
    ->header('گزارش فروش', 1)
    ->separator()
    ->bold('تاریخ: ')->text(now()->format('Y/m/d'))->newLine()
    ->bold('فروش کل: ')->text('۱۵,۰۰۰,۰۰۰ تومان')->newLine()
    ->bold('تعداد سفارش: ')->text('۱۲۵')->newLine()
    ->separator()
    ->emoji('📈 ')->text('رشد ۲۳٪ نسبت به هفته گذشته');

Bale::sendBuilder($message, 123456789);
```

### قالب‌بندی متن

```php
$message = Bale::message()
    ->bold('متن بولد')->newLine()
    ->italic('متن ایتالیک')->newLine()
    ->strikethrough('متن خط‌خورده')->newLine()
    ->code('کد برنامه')->newLine()
    ->codeBlock('echo "Hello World";', 'php')->newLine()
    ->link('لینک به سایت', 'https://example.com');
```

### کارت اطلاعاتی

```php
$message = Bale::message()
    ->card('اطلاعات کاربر', [
        'نام' => 'علی محمدی',
        'ایمیل' => 'ali@example.com',
        'تلفن' => '۰۹۱۲۱۲۳۴۵۶۷',
        'شهر' => 'تهران',
    ]);
```

### لیست‌ها

```php
$message = Bale::message()
    ->header('لیست وظایف', 2)
    ->separator()
    ->listItem('تماس با مشتری')
    ->listItem('ارسال فاکتور')
    ->listItem('پیگیری سفارش')
    ->listItem('بکاپ گیری', '✓');
```

### جدول

```php
$message = Bale::message()
    ->table(
        ['محصول', 'تعداد', 'قیمت'],
        [
            ['گوشی A15', '۲', '۳۵,۰۰۰,۰۰۰'],
            ['لپ‌تاپ HP', '۱', '۴۵,۰۰۰,۰۰۰'],
            ['هدفون', '۳', '۱,۵۰۰,۰۰۰'],
        ]
    );
```

### دکمه‌های اینلاین

```php
$message = Bale::message()
    ->text('لطفاً یک گزینه انتخاب کنید:')
    ->inlineRow([
        'تأیید' => 'confirm',
        'رد' => 'reject',
    ])
    ->inlineButton('مشاهده جزئیات', 'https://example.com/details', 'url');
```

### دکمه‌های معمولی

```php
$message = Bale::message()
    ->text('منوی اصلی:')
    ->buttonRow(['محصولات', 'سفارشات'])
    ->buttonRow(['پشتیبانی', 'درباره ما']);
```

---

## نوتیفیکیشن‌ها

### استفاده از سیستم Notifications لاراول

```php
use Illuminate\Notifications\Notification;
use meysammaghsoudi\BaleMessenger\Messages\MessageBuilder;

class OrderCreatedNotification extends Notification
{
    protected $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function via($notifiable): array
    {
        return ['bale'];
    }

    public function toBale($notifiable): MessageBuilder
    {
        return MessageBuilder::create()
            ->emoji('🛒 ')->bold('سفارش جدید')->newLine()
            ->separator()
            ->bold('شماره: ')->text('#' . $this->order->id)->newLine()
            ->bold('مشتری: ')->text($this->order->customer_name)->newLine()
            ->bold('مبلغ: ')->text(number_format($this->order->total) . ' تومان');
    }
}
```

### تعریف route در مدل

```php
use Illuminate\Notifications\Notifiable;

class User extends Model
{
    use Notifiable;

    public function routeNotificationForBale(): int
    {
        return $this->bale_chat_id;
    }
}
```

### ارسال نوتیفیکیشن

```php
$user = User::find(1);
$user->notify(new OrderCreatedNotification($order));
```

---

## قالب‌های آماده

### نوتیفیکیشن‌های سریع

```php
use meysammaghsoudi\BaleMessenger\Facades\Bale;

// کاربر جدید
Bale::notifyNewUser([
    'name' => 'علی محمدی',
    'email' => 'ali@example.com',
    'phone' => '۰۹۱۲۱۲۳۴۵۶۷',
]);

// سفارش جدید
Bale::notifyNewOrder([
    'id' => 1234,
    'customer' => 'علی محمدی',
    'amount' => 1500000,
    'items_count' => 3,
]);

// پرداخت موفق
Bale::notifyPayment([
    'transaction_id' => 'TXN123456',
    'amount' => 500000,
    'payer' => 'علی محمدی',
]);

// خطای سیستم
Bale::notifyError('Database connection failed', [
    'file' => 'app/Services/OrderService.php',
    'line' => 45,
]);

// گزارش روزانه
Bale::notifyDailyReport([
    'سفارشات جدید' => '۱۵',
    'فروش کل' => '۲۵,۰۰۰,۰۰۰ تومان',
    'کاربران جدید' => '۸',
]);
```

### استفاده از قالب‌های کلاسی

```php
use meysammaghsoudi\BaleMessenger\Support\Templates\OrderNotification;

$orderNotification = (new OrderNotification())
    ->orderId(1234)
    ->customer('علی محمدی')
    ->amount(1500000)
    ->itemsCount(3)
    ->status('در انتظار پرداخت')
    ->button('مشاهده سفارش', 'https://example.com/orders/1234');

Bale::sendNotification($orderNotification);
```

---

## API کامل

### متدهای اصلی

| متد | توضیحات |
|-----|---------|
| `send($text, $chatId, $options)` | ارسال پیام متنی |
| `sendBuilder($builder, $chatId)` | ارسال از MessageBuilder |
| `sendPhoto($photo, $caption, $chatId, $options)` | ارسال عکس |
| `sendDocument($document, $caption, $chatId, $options)` | ارسال فایل |
| `sendVideo($video, $caption, $chatId, $options)` | ارسال ویدیو |
| `sendLocation($lat, $long, $chatId, $options)` | ارسال موقعیت |
| `sendSuccess($title, $message, $chatId)` | پیام موفقیت |
| `sendError($title, $message, $chatId)` | پیام خطا |
| `sendWarning($title, $message, $chatId)` | پیام هشدار |
| `sendInfo($title, $message, $chatId)` | پیام اطلاع‌رسانی |
| `typing($chatId)` | نمایش وضعیت تایپ |
| `getMe()` | اطلاعات ربات |
| `ping()` | سنجش سرعت |

---

## مستندات API بله

این پکیج از API رسمی بله استفاده می‌کند:
- مستندات: https://docs.bale.ai
- Endpoint: `https://tapi.bale.ai`

---

## مجوز

این پکیج تحت مجوز MIT منتشر شده است.

---

## مشارکت

برای مشارکت در توسعه این پکیج، لطفاً Pull Request ارسال کنید یا Issue ثبت کنید.
