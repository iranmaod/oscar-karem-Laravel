<div class="bg-gray-100 text-gray-900 tracking-wider leading-normal hs-ok-data-table-wrap">
    <div class="p-8 pt-4 mt-2 bg-white" x-data="window.__controller.dataTableMainController()" x-init="setCallback();">

        @php
          $installments = \App\Models\Installment::pluck('id', 'name')->toArray();
          $installments["One Time Payment"] = 0;
          $products = \App\Models\Product::pluck('elopage_product_id', 'name')->toArray();
        @endphp
        <div class="flex pb-4 -ml-3">
            <a href="{{ $data->href->create_new }}"  class="-ml- btn btn-primary shadow-none hs-ok-data-create-new">
                <span class="fas fa-plus"></span> {{ $data->href->create_new_text }}
            </a>
        </div>
        <div class="row mb-4">
            <div class="col hs-ok-data-table-search">
                <x-btui-select id="products" class="mt-1 block w-full form-control shadow-none"  :options="$products" wire:model="product" placeholder="{{__('All Products')}}" />
            </div>
            <div class="col hs-ok-data-table-search">
                <x-btui-select id="installment" class="mt-1 block w-full form-control shadow-none"  :options="$installments" wire:model="installment" placeholder="{{__('All Installments')}}" />
            </div>
            <div class="col form-inline hs-ok-data-per-page">
                {{__('Per Page')}}: &nbsp;
                <select wire:model="perPage" class="form-control">
                    <option>10</option>
                    <option>15</option>
                    <option>25</option>
                </select>
            </div>

        </div>

        <div class="row">
            <div class="table-responsive">
                <table class="table table-bordered table-striped text-sm text-gray-600 hs-ok-data-table">
                    <thead>
                        {{ $head }}
                    </thead>
                    <tbody>
                        {{ $body }}
                    </tbody>
                </table>
            </div>
        </div>

        <div id="table_pagination hs-ok-data-table-pagination" class="py-3">
            {{ $model->onEachSide(1)->links() }}
        </div>
    </div>
</div>
