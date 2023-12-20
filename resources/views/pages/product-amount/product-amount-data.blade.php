<x-app-layout>
    <x-slot name="header_content">
        <h1>{{ __('All Product Amounts') }}</h1>

        <div class="hs-ok-breadcrumb section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">{{__("Dashboard")}}</a></div>
            <div class="breadcrumb-item"><a href="{{ route('product-amount') }}">{{__("Product Amounts")}}</a></div>
            <div class="breadcrumb-item"><a href="#">{{__("All Product Amounts")}}</a></div>
        </div>
    </x-slot>

    <div>
        <livewire:table.main name="product-amount" :model="$productAmount" />
    </div>
</x-app-layout>
