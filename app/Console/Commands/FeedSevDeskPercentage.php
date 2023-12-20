<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\VatPercentage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FeedSevDeskPercentage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feed:vat_percentage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Feed Vat Percentages from sevdesk to table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $response = Http::withHeaders([
          'Authorization' => base64_decode(env('SEVDESK_TOKEN'))
        ])->get('https://my.sevdesk.de/api/v1/TaxSet');

        if(isset($response->json()['objects'])){
          VatPercentage::truncate();
          foreach($response->json()['objects'] as $key =>$object){
            VatPercentage::create(array(
              'id' => $key + 1,
              'name' => $object["text"],
              'display_name' => $object["text"],
              'percentage' => $object["taxRate"],
              'sevdesk_taxset_id' => $object["id"],
              'status_id' =>  3
            ));
          }
        }


        return 0;
    }
}
