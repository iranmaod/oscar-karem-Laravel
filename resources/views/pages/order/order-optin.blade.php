<x-frontend-layout>
    <div>
        <div class="bg-gray-100 text-gray-900 tracking-wider leading-normal">
          <div class="p-8 pt-4 mt-2 bg-white">
              <div class="flex pb-4 -ml-3">

              </div>

              <div class="row">
                <form class="form-row" method="post" action="{{route('order.optin.process')}}">
                  @csrf
                  @if($errors->any())
                  <div class="form-group col-md-12">
                    <div class="alert alert-danger">
                        {{$errors->first()}}
                    </div>
                  </div>

                  @endif
                  <div class="form-group col-md-12 required">
                    <label class="control-label">E-mail</label>
                    <input type="text" class="form-control" value="" name="email" required/>
                    <input type="hidden" class="form-control" value="{{base64_encode($order->id)}}" name="order_id" required/>
                  </div>

                  <div class="form-group col-md-12 required">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="hs-extension-oblige" required>
                        <label class="form-check-label" for="hs-extension-oblige">
                          Hiermit verlange ich ausdrücklich, dass die BlackWood GmbH VOR Ablauf der 14 tätigen Widerrufsfrist mit der Ausführung der beauftragten Dienstleistung beginnt.
                        </label>
                      </div>
                  </div>
                  <div class="form-group col-md-12">
                    <button class="btn btn-primary" type="submit">Bestätigen</button>
                  </div>
                </form>
              </div>


          </div>
        </div>
    </div>
</x-frontend-layout>
