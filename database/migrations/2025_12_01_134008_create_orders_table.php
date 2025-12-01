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
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        
        // ID замовлення в KeyCRM (для зв'язку)
        $table->string('keycrm_id')->unique();
        
        // Основні дані
        $table->string('client_name')->nullable();
        $table->string('phone')->nullable();
        $table->string('status')->default('new');
        $table->decimal('grand_total', 10, 2)->default(0);
        
        // Поле для збереження всього JSON відповіді (корисно для налагодження)
        $table->json('raw_data')->nullable();
        
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
