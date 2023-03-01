<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Buyer;
use App\Models\Location;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Seller;
use App\Models\User;
use App\Traits\CSVReaderTrait as CSVReader;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ReadAndSaveEntitiesFromCSV extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csv:readAndSave';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'command that will call a set of other commands in order to read and save several entities on the DB, provided from a CSV';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        try
        {
            $data = CSVReader::read(storage_path() . '/app/orders.csv', true);
        }
        catch (Exception $error)
        {
            $this->error($error->getMessage());
            return;
        }

        $this->saveBuyersLocationsSellersProducts($data);
        $purchases = $this->createAndSavePurchases($data);
        $this->associateSellerAndLocation($purchases);
    }

    /**
     * @param array $purchases
     */
    public function associateSellerAndLocation(array $purchases)
    {
        for ($i = 0; $i < count($purchases); $i++)
        {
            unset($purchases[$i]['buyer_id'], $purchases[$i]['product_id'], $purchases[$i]['amount'], $purchases[$i]['reference'], $purchases[$i]['dateTime'], $purchases[$i]['datetime']);
        }

        DB::table('location_seller')->insertOrIgnore($purchases);
    }

    /**
     * @param array $data
     */
    public function createAndSavePurchases(array $data): array
    {
        $purchases = [];

        foreach ($data as $datum)
        {
            $curPurchase = [];
            $curProduct = explode(',', Arr::get($datum, 'Product'));
            $curPurchase['product'] = Arr::get($curProduct, 0);
            $curPurchase['amount'] = Arr::get($curProduct, 1, 1);
            $curPurchase['reference'] = Arr::get($datum, 'ID');
            $curPurchase['dateTime'] = Carbon::createFromFormat('d/m/Y H:s', Arr::get($datum, 'Datum / tijd'));
            $curPurchase['buyer'] = Arr::get($datum, 'Koper');
            [$curPurchase['location'],$curPurchase['seller']] = explode('/', Arr::get($datum, 'Vestiging / verkoper'));

            $buyer_id = User::where('name', Arr::get($curPurchase, 'buyer'))->with('buyer')->first()->buyer->id;
            $seller_id = User::where('name', Arr::get($curPurchase, 'seller'))->with('seller')->first()->seller->id;

            $purchase = [
                'buyer_id'    => $buyer_id,
                'seller_id'   => $seller_id,
                'location_id' => Location::where('name', Arr::get($curPurchase, 'location'))->pluck('id')->first(),
                'product_id'  => Product::where('name', Arr::get($curPurchase, 'product'))->pluck('id')->first(),
                'amount'      => $curPurchase['amount'],
                'reference'   => Arr::get($curPurchase, 'reference'),
                'dateTime'    => Arr::get($curPurchase, 'dateTime'),
            ];
            $purchases[] = $purchase;
        }
        Purchase::insert($purchases);

        return $purchases;
    }

    /**
     * @param array $data
     */
    public function saveBuyersLocationsSellersProducts(array $data): void
    {
        $buyers = [];
        $locations = [];
        $sellers = [];
        $products = [];
        foreach ($data as $datum)
        {
            $locationSeller = explode('/', Arr::get($datum, 'Vestiging / verkoper'));
            [$product] = explode(',', Arr::get($datum, 'Product'));
            $products[] = $product;
            $buyers[] = Arr::Get($datum, 'Koper');
            $locations[] = Arr::get($locationSeller, 0);
            $sellers[] = Arr::get($locationSeller, 1);
        }

        $buyers = array_unique($buyers);
        $buyers = $this->createIndexedArray($buyers, 'name');
        $this->saveEntitiesAsUsers($buyers, 'buyer');

        $locations = array_values(array_unique($locations));
        $locations = $this->createIndexedArray($locations, 'name');
        Location::insertOrIgnore($locations);

        $sellers = array_values(array_unique($sellers));

        $sellers = $this->createIndexedArray($sellers, 'name');
        $this->saveEntitiesAsUsers($sellers, 'seller');

        $products = array_unique($products);
        $products = $this->createIndexedArray($products, 'name');
        Product::insertOrIgnore($products);

        return;
    }

    /**
     * @param array $entities
     * @param string $entityName
     * 
     */
    public function saveEntitiesAsUsers(array $entities, string $entityName): void
    {
        $users = [];
        foreach ($entities as $key=>$entity)
        {
            $user = User::create([
                'name'     => $entity['name'],
                'email'    => $entityName . $key . '@email.com',
                'password' => 'password',
            ]);
            $users[] = ['user_id' => $user->id];
        }

        if ($entityName == 'buyer')
        {
            Buyer::insertOrIgnore($users);
        }
        else
        {
            Seller::insertOrIgnore($users);
        }
    }

    /**
     * @param array $array
     * @param string $keyName
     * @return array
     */
    public function createIndexedArray(array $array, string $keyName): array
    {
        foreach ($array as $key=>$node)
        {
            $array[$key] = [$keyName=>$node];
        }
        return $array;
    }
}
