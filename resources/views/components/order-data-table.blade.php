<div class="bg-gray-100 text-gray-900 tracking-wider leading-normal hs-ok-data-table-wrap">
    <div class="p-8 pt-4 mt-2 bg-white" x-data="window.__controller.dataTableMainController()" x-init="setCallback();">

        @php
          $agents = \App\Models\Agent::get()->pluck('hs_vid', 'full_detail')->toArray();
          $order_statuses = \App\Models\OrderStatus::pluck('id', 'name')->toArray();
          $installments = \App\Models\Installment::pluck('id', 'name')->toArray();
          $products = \App\Models\Product::pluck('elopage_product_id', 'name')->toArray();
        @endphp
        <div class="row mb-4">
            <div class="col hs-ok-data-table-search">
                <x-btui-select id="order_status_id" class="mt-1 block w-full form-control shadow-none"  :options="$order_statuses" wire:model="order_status" placeholder="{{__('All Order Status')}}" />
            </div>
            <div class="col hs-ok-data-table-search">
                <x-btui-select id="installment" class="mt-1 block w-full form-control shadow-none"  :options="$installments" wire:model="installment" placeholder="{{__('All Installments')}}" />
            </div>
            <div class="col hs-ok-data-table-search">
                <x-btui-select id="products" class="mt-1 block w-full form-control shadow-none"  :options="$products" wire:model="product" placeholder="{{__('All Products')}}" />
            </div>
            <div class="col hs-ok-data-table-search">
                <x-btui-select id="setter_id" class="mt-1 block w-full form-control shadow-none"  :options="$agents" wire:model="setup_caller" placeholder="{{__('All Setup Caller')}}" />
            </div>
            <div class="col hs-ok-data-table-search">
                <x-btui-select id="closer_id" class="mt-1 block w-full form-control shadow-none"  :options="$agents" wire:model="closer" placeholder="{{__('All Closer')}}" />
            </div>
        </div>
        <div class="row mb-4">

            <div class="col hs-ok-data-table-search">
                <input wire:model="search" class="form-control" type="text" placeholder="{{__('Search by Order No., Name, Email or Phone')}}">
            </div>
            <div class="col form-inline hs-ok-data-table-search">
                {{__('Date After')}}: &nbsp;
                <input wire:model="after_date" class="form-control" type="date" placeholder="{{__('Date After')}}">
            </div>
            <div class="col form-inline hs-ok-data-table-search">
                {{__('Date Before')}}: &nbsp;
                <input wire:model="before_date" class="form-control" type="date" placeholder="{{__('Date Before')}}">
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
