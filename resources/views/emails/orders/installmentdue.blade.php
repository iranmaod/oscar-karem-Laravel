@component('mail::message')
# There are installment due for your Order

Installment : €{{$installment_details->amount}}
Due date    :   {{$installment_details->due_date}}

@component('mail::button', ['url' => route('order.payment', array('orderId' => base64_encode($orderId), 'key' => base64_encode($order->auth_code), 'installmentId' => base64_encode($installment_details->id)))])
Jetzt Bezahlen
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
