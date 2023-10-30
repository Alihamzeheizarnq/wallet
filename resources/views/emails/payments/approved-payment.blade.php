<x-mail::message>
    کاربر محترم پرداخت شما با شناسه {{ $payment->unique_id }} تایید شد.
Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
