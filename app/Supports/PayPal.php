<?php

namespace App\Supports;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class PayPal
{
    /**
     * Http Client
     * 
     * @var PendingRequest
     */
    protected $http;

    /**
     * Paypal oauth2 token.
     * 
     * @var string
     */
    protected $token;


    /**
     * Subscription Production.
     * 
     * @var array
     */
    protected $product;


    /**
     * Subscription Plan.
     * 
     * @var array
     */
    protected $plan;



    /**
     * Setup http client with Paypal's base configuration.
     * 
     * @return void 
     * @throws \InvalidArgumentException 
     */
    public function __construct()
    {
        $this->http = \optional(Http::baseUrl(\env('PAYPAL_REST_API_URL')), function (PendingRequest $http) {

            if ($this->token) {

                return $http->withToken($this->token);
            }

            return $this->authorize($http);
        });
    }


    /**
     * Generate paypal token with basic auth.
     * 
     * @param \Illuminate\Http\Client\PendingRequest $http 
     * @return \Illuminate\Http\Client\PendingRequest 
     * @throws \InvalidArgumentException 
     * @throws \Exception 
     */
    protected function authorize(PendingRequest $http): PendingRequest
    {

        return \optional(

            // Initilize HTTP client with basic auth credentials.
            (clone $http)->asForm()

                ->withBasicAuth(\env('PAYPAL_SANDBOX_CLIENT_ID'), \env('PAYPAL_SANDBOX_SECRET'))

                ->post('/oauth2/token', ['grant_type' => 'client_credentials']),

            // Bind the token with http client after receiving the the oauth token.
            function (Response $response) use ($http) {

                if ($response->ok()) {
                    return $http->withToken(
                        $this->token = $response->json('access_token')
                    );
                }
            }
        );
    }


    /**
     * Fetch product from catalog or create new product if catalog is empty.
     * 
     * @return $this 
     * @throws \Exception 
     */
    public function fetchOrCreateProduct()
    {
        \tap($this->http->get('catalogs/products'), function (Response $response) {

            if ($response->ok()) {
                $products = collect($response->json('products'));

                $this->product = $products->count() > 0 ? $products->first() : $this->createProduct();
            }
        });

        return $this;
    }

    /**
     * Create new product in paypal inventory.
     * 
     * @return array 
     * @throws \Illuminate\Contracts\Container\BindingResolutionException 
     * @throws \Psr\Container\NotFoundExceptionInterface 
     * @throws \Psr\Container\ContainerExceptionInterface 
     * @throws \Exception 
     */
    public function createProduct(): array
    {
        return \optional(

            (clone $this->http)->asJson()->post('/catalogs/products', [
                'name' => config('paypal.subscription.product.name', 'Dummy Product Name'),
                'type' => config('paypal.subscription.product.type', 'Dummy Product Type'),
                'description' => config('paypal.subscription.product.description'),
                'category' => config('paypal.subscription.product.category', 'SOFTWARE'),
            ]),

            function (Response $response) {
                if ($response->status() === HttpResponse::HTTP_CREATED) {
                    return $response->json();
                }
            }
        );
    }

    public function createPlan(array $options)
    {
        \tap(
            (clone $this->http)->asJson()->post('/billing/plans', \array_merge($options, ['product_id' => $this->product['id']])),
            function (Response $response) {
                if ($response->status() === HttpResponse::HTTP_CREATED) {
                    $this->plan = $response->json();
                }
            }
        );

        return $this;
    }

    public function getPlanID()
    {
        return $this->plan['id'];
    }
}
