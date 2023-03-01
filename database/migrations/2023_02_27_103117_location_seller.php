<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('location_seller', function (Blueprint $table) {
            $table->foreignId('seller_id')->constrained();
            $table->foreignId('location_id')->constrained();
            $table->unique(['seller_id', 'location_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
