<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Buyer;
use App\Models\Seller;
use App\Models\Location;
use App\Models\Product;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Buyer::class);
            $table->foreignIdFor(Seller::class);
            $table->foreignIdFor(Location::class);
            $table->foreignIdFor(Product::class);
            $table->integer('amount');
            $table->integer('reference');
            $table->datetime('dateTime');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
