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
        Schema::create('endpoint_hits', function (Blueprint $table) {
            $table->id();
            $table->uuid('endpoint_id');
            $table->char('signature', 32);
            $table->timestamp('created_at');

            // Indexes
            $table->index('endpoint_id');
            $table->index('signature');
            $table->index(['endpoint_id', 'signature']);
        });

        Schema::table('endpoint_hits', function ($table) {
            $table->foreign('endpoint_id')->references('id')
                  ->on('endpoints')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('endpoint_hits');
    }
};
