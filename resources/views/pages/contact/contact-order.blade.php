<x-app-layout>
    <x-slot name="header_content">
        <h1>{{ __('Generate Order') }}</h1>

        <div class="hs-ok-breadcrumb section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">{{__("Dashboard")}}</a></div>
        <div class="breadcrumb-item"><a href="{{ route('contact') }}">{{__("Contact")}}</a></div>
        <div class="breadcrumb-item"><a href="#">{{__("Generate Order")}}</a></div>
        </div>
    </x-slot>

    <div>
        <div class="bg-gray-100 text-gray-900 tracking-wider leading-normal">
          <div class="p-8 pt-4 mt-2 bg-white" x-data="window.__controller.dataTableMainController()" x-init="setCallback();">
              <div class="flex pb-4 -ml-3">

              </div>

              <div class="row">
                <form class="form-row" method="post" action="{{route('order.save')}}">
                  @csrf
                  <div class="form-group col-md-6 required">

                    <input type="hidden" value="{{$contact['vid'] ?? null }}" name="hs_vid">
                    <label class="control-label">First name</label>
                    <input type="text" class="form-control" value="{{$contact['firstname'] ?? null }}" name="firstname" required/>
                  </div>
                  <div class="form-group col-md-6 required">
                    <label class="control-label">Last name</label>
                    <input type="text" class="form-control" value="{{$contact['lastname'] ?? null }}" name="lastname" required/>
                  </div>

                  <div class="form-group col-md-6 required">
                    <label class="control-label">E-mail</label>
                    <input type="text" class="form-control" value="{{$contact['email'] ?? null }}" name="email" required/>
                  </div>
                  <div class="form-group col-md-6 required">
                    <label class="control-label">Phone Number</label>
                    <input type="text" class="form-control" value="{{$contact['phone'] ?? null }}" name="phone" required/>
                  </div>
                  <div class="form-group col-md-12 required">
                    <label class="control-label">Product Selection</label>
                    <select id="product" class="form-control" name="elopage_product_id" required/>
                       <option selected="">Choose Product</option>
                       @forelse($products as $product )
                       <option value="{{ base64_encode(($product['id'] ?? null).'#^#'.($product['name'] ?? null)) }}">{{ $product['name'] ?? null }}</option>
                       @empty
                       @endforelse
                    </select>
                  </div>

                  <div class="form-group col-md-6 required">
                    <label class="control-label">One-time payment / Installments</label>
                    <select id="installment" class="form-control" name="installment_id" required/>
                       <option selected="" value="">Choose Installment</option>
                       @forelse($installments as $installment )
                       <option value="{{ $installment->id }}">{{ $installment->name }}</option>
                       @empty
                       @endforelse
                    </select>
                  </div>

                  <div class="form-group col-md-6 required">
                    <label class="control-label">lot</label>
                    <input type="text" class="form-control" name="lot" required/>
                  </div>

                  <div class="form-group col-md-6 required">
                    <label class="control-label">Address Line</label>
                    <input type="text" class="form-control" value="{{$contact['address'] ?? null }}" name="address" required/>
                  </div>

                  <div class="form-group col-md-6 required">
                    <label class="control-label">City</label>
                    <input type="text" class="form-control" value="{{$contact['city'] ?? null }}" name="city" required/>
                  </div>

                  <div class="form-group col-md-6 required">
                    <label class="control-label">Country / Region</label>
                    <input type="text" class="form-control" value="{{$contact['country'] ?? null }}" name="country" required/>
                  </div>

                  <div class="form-group col-md-6 required">
                    <label class="control-label">PLZ</label>
                    <input type="text" class="form-control" name="plz" required/>
                  </div>

                  <div class="form-group col-md-12 required">
                    <label class="control-label">Company Name</label>
                    <input type="text" class="form-control" value="{{$contact['company'] ?? null }}" name="company_name" required/>
                  </div>

                  <div class="form-group col-md-6 required">
                    <label class="control-label">VAT ID no.</label>
                    <input type="text" class="form-control" name="vat" required/>
                  </div>

                  <div class="form-group col-md-6 required">
                    <label class="control-label">VAT%</label>
                    <select id="vat_percentage" class="form-control" name="vat_percentage_id" required/>
                       <option selected="" value="">Choose Vat%</option>
                       @forelse($vat_percentages as $vat_percentage )
                       <option value="{{ $vat_percentage->id }}">{{ $vat_percentage->name }}</option>
                       @empty
                       @endforelse
                    </select>
                  </div>
                  <div class="form-group col-md-12">
                    <button class="btn btn-primary" type="submit">{{__("Generate Order")}}</button>
                  </div>
                </form>
              </div>


          </div>
        </div>
    </div>
</x-app-layout>
