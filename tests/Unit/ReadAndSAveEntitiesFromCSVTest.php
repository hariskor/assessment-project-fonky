<?php

namespace Tests\Unit;

use App\Console\Commands\ReadAndSaveEntitiesFromCSV;
use App\Models\Location;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReadAndSAveEntitiesFromCSVTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     */
    public function setUp(): void
    {
        parent::setUp();
    }

     public function test_saves_data(): void
     {
         $data = $this->initializeData();
         $readAndSave = new ReadAndSaveEntitiesFromCSV();

         $readAndSave->saveBuyersLocationsSellersProducts($data);
         $buyers = User::whereHas('buyer')->get();
         $sellers = User::whereHas('seller')->get();
         $locations = Location::get();
         $products = Product::get();
         $this->assertEquals(5, count($buyers));
         $this->assertEquals(3, count($sellers));
         $this->assertEquals(3, count($locations));
         $this->assertEquals(4, count($products));

         $purchases = $readAndSave->createAndSavePurchases($data);
         $createdPurchases = Purchase::all();
         $this->assertEquals(5, count($createdPurchases));

         $readAndSave->associateSellerAndLocation($purchases);
         $user = User::where('name', ' Mike de Jagt')
             ->with('seller')->first();
         $locations = $user->seller->locations()->get();
         $this->assertEquals(3, count($locations));
     }

    /**
     * @return array
     */
    private function initializeData(): array
    {
        return [
            [
                'ID'                   => '82479',
                'Koper'                => 'S. Schippers',
                'Datum / tijd'         => '08/12/2020 18:43',
                'Product'              => 'D12,5',
                'Vestiging / verkoper' => 'Nijmegen / Esther Oostland',
            ],
            [
                'ID'                   => '82480',
                'Koper'                => 'S. Maasland',
                'Datum / tijd'         => '08/12/2020 18:45',
                'Product'              => 'D8',
                'Vestiging / verkoper' => 'Nijmegen / Alex de Vries',
            ],
            [
                'ID'                   => '82481',
                'Koper'                => 'J.R. Scholtens',
                'Datum / tijd'         => '08/12/2020 18:53',
                'Product'              => 'D7',
                'Vestiging / verkoper' => 'Nijmegen / Mike de Jagt',
            ],
            [
                'ID'                   => '82482',
                'Koper'                => 'I.T. Van der Landweg',
                'Datum / tijd'         => '08/12/2020 18:58',
                'Product'              => 'D10',
                'Vestiging / verkoper' => 'Groningen / Mike de Jagt',
            ],
            [
                'ID'                   => '82483',
                'Koper'                => 'B. Smitse',
                'Datum / tijd'         => '08/12/2020 19:01',
                'Product'              => 'D10',
                'Vestiging / verkoper' => 'Rotterdam / Mike de Jagt',
            ],
        ];
    }
}
