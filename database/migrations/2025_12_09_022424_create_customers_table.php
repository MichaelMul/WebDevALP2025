<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('address')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('city')->nullable();
            $table->decimal('wallet_balance', 10, 2)->default(0);
            $table->integer('points')->default(0);
            $table->enum('membership_tier', ['bronze', 'silver', 'gold'])->default('bronze');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
