<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

   public function up()
{
    Schema::create('resources', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->foreignId('category_id')->constrained()->onDelete('cascade');
        $table->foreignId('manager_id')->constrained('users'); // The tech manager
        $table->json('specifications')->nullable();
        $table->enum('state', ['available', 'maintenance', 'out_of_order'])->default('available');
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
