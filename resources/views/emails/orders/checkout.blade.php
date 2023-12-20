@component('mail::message')
# Auftragsbestätigung

<p>BlackWood GmbH<br/>
Karl Farkas Gasse 22/9<br/>
1030 Wien<br/>
ATU 74884039</p>

Vielen Dank für Ihren Einkauf! Nachstehend finden Sie zur Bestätigung das
bestellte Produkt in der Übersicht.


@component('mail::table')
|               |          |
| ------------- | --------:|
| Gesamtsumme dieser Bestellung:      | EUR  {{$order->amount}}      |
| Bestellinformationen:      | {{ get_order_address($order)}}      |
| Menge:      | {{@$order->qty}}      |
| Bezeichnung:      | {{@$order->product->name}}      |
| Preis:      | (netto) {{hs_ok_money_format(@$order->amount)}}      |
| Zzgl: Mehrwertsteuer:      | {{@$order->vat_percentage->percentage == 0 ? $order->vat_percentage->percentage.'%': $order->vat_percentage->display_name}}      |
| Gesamt:      | {{ hs_ok_money_format($order->amount + calculate_vat_amount($order->amount, @$order->vat_percentage->percentage))}}      |
@endcomponent


Hiermit verlange ich ausdrücklich, dass die BlackWood GmbH VOR Ablauf der 14 tägigen Widerrufsfrist mit der Ausführung der beauftragten Dienstleistung beginnt.

@component('mail::button', ['url' => route('order.payment', array('orderId' => base64_encode($orderId), 'key' => base64_encode($order->auth_code)))])
Jetzt Bezahlen
@endcomponent

Mit freundlichen Grüßen<br/>
<b>BlackWood GmbH</b>

<p>HR-Nr.: FN 520369 z I U-ID: ATU74884039 I Steuer-Nr.: 22/348/493 I GISA-Zahl: 32254808 I AT873200200003830395 I BIC: RLNWATW1002 I
Geschäftsführung: Oscar Karem</p>

@endcomponent
