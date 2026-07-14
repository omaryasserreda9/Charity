<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('budget_operations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('budget_category_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('type', ['in', 'out']);
            $table->string('donor_name');
            $table->decimal('quantity', 12, 2);
            $table->date('operation_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('budget_operations');
    }
};
