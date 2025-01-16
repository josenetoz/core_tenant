<?php

use App\Models\Organization;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subscription_cancellations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Organization::class)->constrained()->cascadeOnDelete();
            $table->string('stripe_id')->unique();
            $table->string('reason')->default('other');
            $table->text('coments')->nullable();
            $table->string('rating')->default('other');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_cancellations');
    }
};
