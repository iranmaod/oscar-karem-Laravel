<x-app-layout>
    <x-slot name="header_content">
        <h1>{{ __('All Products') }}</h1>

        <div class="hs-ok-breadcrumb section-header-breadcrumb">
          <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">{{__("Dashboard")}}</a></div>
          <div class="breadcrumb-item"><a href="{{ route('product.list') }}">{{__("Products")}}</a></div>
          <div class="breadcrumb-item"><a href="{{ route('product.list') }}">{{__("All Products")}}</a></div>
        </div>
    </x-slot>

    <div>
        <div class="bg-gray-100 text-gray-900 tracking-wider leading-normal">
          <div class="p-8 pt-4 mt-2 bg-white">
              <div class="flex pb-4 -ml-3">

              </div>

              <div class="row">
                  <div class="table-responsive">
                      <table class="table table-bordered table-striped text-sm text-gray-600">
                          <thead>
                              <tr>
                                <th>{{__("#")}}  </th>
                                <th>{{__("ID")}}  </th>
                                <th>{{__("Name")}}</th>
                                <th>{{__("Action")}}</th>
                              </tr>
                          </thead>
                          <tbody>

                              @forelse($elopageProducts as $key=>$product)
                              <tr>
                                  <td>{{ $key + 1 }}</td>
                                  <td>{{$product['elopage_product_id']}}</td>
                                  <td>{{$product['name']}}</td>

                                  <td class="whitespace-no-wrap row-action--icon">
                                      @if(!$product['generated'])
                                      <a role="button" href="{{ route('product.generate', ['elopageProductId' => base64_encode($product['elopage_product_id'])])}}" class="mr-3 btn btn-primary"><i class="fas fa-plus-square"></i> {{__("Generate Product")}}</a>
                                      @endif
                                  </td>
                              </tr>
                              @empty
                              @endforelse
                          </tbody>
                      </table>
                  </div>
              </div>


          </div>
        </div>
    </div>
</x-app-layout>
