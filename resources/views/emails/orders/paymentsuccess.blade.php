@component('mail::message')
Zahlung erfolgreich für Order # {{$order->id}}

<p>Hallo,</p>
<p>
    Die Zahlung in Höhe von EUR {{$transaction->amount}} wurde erfolgreich eingezogen.
   
</p>
 
<p>
    Details zur Bestellung:
</p>



@component('mail::table')
|               |          |
| ------------- | --------:|
| Order Nummer:      |  # {{$order->id}}      |
| Anzahl Raten      |   {{$order->installment_id}}      |
| Transaction ID für diese Zahlung:      | {{$transaction->transaction_id}}      |
| Datum der Bezahlung:      | {{$transaction->date}}      |
@endcomponent



<p>
Für Fragen oder Problemen erreichst du unser Support Team unter folgender E-Mail:
</p>
<a style="display: block;margin-bottom:25px;" href="mailto: rechnung@blackwood.at">rechnung@blackwood.at</a>

<p><b>BlackWood GmbH</b></p>

<p>
    HR-Nr.: FN 520369 z I U-ID: ATU74884039 I Steuer-Nr.: 22/348/493 I GISA-Zahl: 32254808 I <br>
    AT873200200003830395 I BIC: RLNWATW1002 I Geschäftsführung: Oscar Karem
</p>
 

Thanks,<br>
{{ config('app.name') }}
@endcomponent