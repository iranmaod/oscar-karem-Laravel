<x-app-layout>
    <x-slot name="header_content">
        <h1>{{ __('Edit Order') }}</h1>

        <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">{{__("Dashboard")}}</a></div>
        <div class="breadcrumb-item"><a href="{{ route('order') }}">{{__("Orders")}}</a></div>
        <div class="breadcrumb-item"><a href="#">{{__("Edit Orders")}}</a></div>
        </div>
    </x-slot>

    <div>
        <div class="bg-gray-100 text-gray-900 tracking-wider leading-normal">
          <div class="p-8 pt-4 mt-2 bg-white">
              <div class="flex pb-4 -ml-3">

              </div>

              <div class="row">
                <form class="form-row" method="post" action="{{route('order.update')}}">
                  @csrf
                  @method('PUT')
                  <div class="form-group col-md-6 required">
                    <input type="hidden" name="order_id" value="{{base64_encode($order->id)}}" />
                    <input type="hidden" name="hs_vid" value="{{ $order->hs_vid }}" />
                    <label class="control-label">First name</label>
                    <input type="text" class="form-control" value="{{ $order->firstname }}" name="firstname" required/>
                  </div>
                  <div class="form-group col-md-6 required">
                    <label class="control-label">Last name</label>
                    <input type="text" class="form-control" value="{{ $order->lastname }}" name="lastname" required/>
                  </div>

                  <div class="form-group col-md-6 required">
                    <label class="control-label">E-mail</label>
                    <input type="text" class="form-control" value="{{ $order->email }}" name="email" required/>
                  </div>
                  <div class="form-group col-md-6 required">
                    <label class="control-label">Phone Number</label>
                    <input type="text" class="form-control" value="{{ $order->phone }}" name="phone" required/>
                  </div>
                  <div class="form-group col-md-12 required">
                    <label class="control-label">Product Selection</label>
                    <select id="product" class="form-control" name="elopage_product_id" required/>
                       <option selected="">Choose Product</option>
                       @forelse($products as $product )
                       <option value="{{ base64_encode(($product['id'] ?? null).'#^#'.($product['name'] ?? null)) }}" {{($order->product_id == $product['id']) ? 'selected' : '' }} >{{ $product['name'] ?? null }}</option>
                       @empty
                       @endforelse
                    </select>
                  </div>

                  <div class="form-group col-md-6 required">
                    <label class="control-label">One-time payment / Installments</label>
                    <select id="installment" class="form-control" name="installment_id" required/>
                       <option selected="" value="">Choose Installment</option>
                       @forelse($installments as $installment )
                       <option value="{{ $installment->id }}" {{($order->installment_id == $installment->id) ? 'selected' : '' }}>{{ $installment->name }}</option>
                       @empty
                       @endforelse
                    </select>
                  </div>

                  <div class="form-group col-md-6 required">
                    <label class="control-label">lot</label>
                    <input type="text" class="form-control" name="lot" value="{{$order->lot}}" required/>
                  </div>

                  <div class="form-group col-md-6 required">
                    <label class="control-label">Address Line</label>
                    <input type="text" class="form-control"  name="address" value="{{$order->address}}" required/>
                  </div>

                  <div class="form-group col-md-6 required">
                    <label class="control-label">City</label>
                    <input type="text" class="form-control" name="city" value="{{$order->city}}" required/>
                  </div>

                  <div class="form-group col-md-6 required">
                    <label class="control-label">Country / Region</label>
                    <input type="text" class="form-control"  name="country" value="{{$order->country->name}}" required/>
                  </div>

                  <div class="form-group col-md-6 required">
                    <label class="control-label">PLZ</label>
                    <input type="text" class="form-control" name="plz" value="{{$order->plz}}" required/>
                  </div>

                  <div class="form-group col-md-12 required">
                    <label class="control-label">Company Name</label>
                    <input type="text" class="form-control" name="company_name" value="{{$order->company_name}}" required/>
                  </div>

                  <div class="form-group col-md-6 required">
                    <label class="control-label">VAT ID no.</label>
                    <input type="text" class="form-control" name="vat" value="{{$order->vat}}" required/>
                  </div>

                  <div class="form-group col-md-6 required">
                    <label class="control-label">VAT%</label>
                    <select id="vat_percentage" class="form-control" name="vat_percentage_id" required/>
                       <option selected="" value="">Choose Vat%</option>
                       @forelse($vat_percentages as $vat_percentage )
                       <option value="{{ $vat_percentage->id }}" {{($order->vat_percentage_id == $vat_percentage->id) ? 'selected' : '' }} >{{ $vat_percentage->name }}</option>
                       @empty
                       @endforelse
                    </select>
                  </div>
                  <div class="form-group col-md-12">
                    <button class="btn btn-primary" type="submit">Update Order</button>
                  </div>
                </form>
              </div>


          </div>
        </div>
    </div>
</x-app-layout>
