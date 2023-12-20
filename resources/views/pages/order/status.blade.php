<x-frontend-layout>
    <div>
        <div class="bg-gray-100 text-gray-900 tracking-wider leading-normal">
          <div class="p-8 pt-4 mt-2 bg-white">
              <div class="flex pb-4 -ml-3 text-center">
                    <h2 class="text-center">Bezahlung für Bestellung #HSE-{{$order->id}} {{ $status == 'success' ? "erfolgreich!" : "Gescheitert!"}} </h2>
              </div>

              <div class="row">

                    @if($status == 'success')
                    <p>Eine Rechnung wird dir automatisch an deine Email <b>{{$order->email}}</b> gesendet. Für weitere Rückfragen teile uns immer deine Transaktions ID mit.</p>
                    @else
                    <p>Für weitere Rückfragen teile uns immer deine Transaktions ID mit.</p>
                    @endif
                    <p>
                      <table class="table table-bordered table-striped">
                        <tbody>
                          <tr>
                            <td>Bestellung</td>
                            <td>#HSE-{{$order->id}}</td>
                          </tr>
                          <tr>
                            <td>Status</td>
                            <td>{{ ucfirst($status) }}</td>
                          </tr>
                          <tr>
                            <td>Transaktions ID</td>
                            <td>{{$transactionId}}</td>
                          </tr>
                          <tr>
                            <td>Name</td>
                            <td>{{$order->firstname}} {{$order->lastname}}</td>
                          </tr>
                          <tr>
                            <td>Email</td>
                            <td>{{$order->email}}</td>
                          </tr>
                          <tr>
                            <td>Telefonnummer </td>
                            <td>{{$order->phone}}</td>
                          </tr>
                        </tbody>
                        </table>
                    </p>
                    @if($status == 'success')
                    <p>
                      <b>Herzlichen Glückwunsch und Willkommen an Bord!</b>
                    </p>
                    @endif
              </div>

          </div>
        </div>
    </div>
</x-frontend-layout>
