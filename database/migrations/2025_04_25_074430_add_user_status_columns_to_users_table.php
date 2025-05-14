<?php

use App\Enums\SubscriptionType;
use App\Enums\UserRole;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default(UserRole::STANDARD->value)->after('id');
            $table->string('subscription_type')->default(SubscriptionType::FREE->value)->after('role');
            $table->boolean('is_active')->default(true)->after('subscription_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'subscription_type', 'is_active']);
        });
    }
};
