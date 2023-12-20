<?php

namespace App\Traits;



trait WithDataTable {

    public function get_pagination_data ()
    {
        switch ($this->name) {
            case 'user':
                $users = $this->model::search($this->search)
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage);

                return [
                    "view" => 'livewire.table.user',
                    "users" => $users,
                    "data" => array_to_object([
                        'href' => [
                            'create_new' => route('user.new'),
                            'create_new_text' => __('Add New User'),
                        ]
                    ])
                ];
                break;
            case 'order':
                $orders = $this->model::search($this->search, $this->order_status, $this->installment, $this->product, $this->setup_caller, $this->closer, $this->before_date, $this->after_date)
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage);

                return [
                    "view" => 'livewire.table.order',
                    "orders" => $orders,
                    "data" => array_to_object([
                        'href' => [
                            'create_new' => route('order.new'),
                            'create_new_text' => __('Create New Order'),
                        ]
                    ])
                ];
                break;
            case 'paymentMethod':
                $paymentMethods = $this->model::search($this->search)
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage);

                return [
                    "view" => 'livewire.table.payment-method',
                    "paymentMethods" => $paymentMethods,
                    "data" => array_to_object([
                        'href' => [
                            'create_new' => route('payment.method.new'),
                            'create_new_text' => __('Add New Payment Method'),
                        ]
                    ])
                ];
                break;

            case 'payment':
                $orders = $this->model::search($this->search, $this->order_status, $this->installment)
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage);

                return [
                    "view" => 'livewire.table.payment',
                    "orders" => $orders,
                    "data" => array_to_object([
                        'href' => [
                            'create_new' => route('order'),
                            'create_new_text' => __('View Orders'),
                        ]
                    ])
                ];
                break;
            case 'agent':
                $agents = $this->model::search($this->search)
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage);

                return [
                    "view" => 'livewire.table.agent',
                    "agents" => $agents,
                    "data" => array_to_object([
                        'href' => [
                            'create_new' => route('agent.new'),
                            'create_new_text' => __('Add New Agent'),
                        ]
                    ])
                ];
                break;
            case 'product':
                $products = $this->model::search($this->search)
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage);

                return [
                    "view" => 'livewire.table.product',
                    "products" => $products,
                    "data" => array_to_object([
                        'href' => [
                            'create_new' => route('product.list'),
                            'create_new_text' => __('Add New Product'),
                        ]
                    ])
                ];
                break;
              case 'product-amount':
                  $productAmounts = $this->model::search($this->search, $this->product, $this->installment )
                      ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                      ->paginate($this->perPage);

                  return [
                      "view" => 'livewire.table.product-amount',
                      "productAmounts" => $productAmounts,
                      "data" => array_to_object([
                          'href' => [
                              'create_new' => route('product-amount.new'),
                              'create_new_text' => __('Add New Product Amount'),
                          ]
                      ])
                  ];
                  break;

            case 'installment':
                $installments = $this->model::search($this->search)
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage);

                return [
                    "view" => 'livewire.table.installment',
                    "installments" => $installments,
                    "data" => array_to_object([
                        'href' => [
                            'create_new' => route('payment'),
                            'create_new_text' => __('View Payments'),
                        ]
                    ])
                ];
                break;

            case 'commission-percentage':
                $commissionPercentages = $this->model::search($this->search)
                    ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                    ->paginate($this->perPage);

                return [
                    "view" => 'livewire.table.commission-percentage',
                    "commissionPercentages" => $commissionPercentages,
                    "data" => array_to_object([
                        'href' => [
                            'create_new' => route('commission-percentage.new'),
                            'create_new_text' => __('Add New Commission Percentage'),
                        ]
                    ])
                ];
                break;

            default:
                # code...
                break;
        }
    }
}
