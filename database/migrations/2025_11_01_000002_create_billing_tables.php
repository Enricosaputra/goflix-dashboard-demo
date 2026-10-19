<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // App users (viewers). They sign up through the Go API; the dashboard reads and manages them.
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('login_type');
            // Mirrors production's user.expiry_date: the last day of the paid subscription.
            $table->date('subscription_ends_on')->nullable()->index();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedInteger('price'); // IDR
            $table->unsignedTinyInteger('duration_months');
            $table->unsignedTinyInteger('max_devices');
            $table->string('max_quality', 10);
            $table->boolean('ads_free')->default(true);
            $table->boolean('watch_on_tv')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Free-form lines shown on the plan card, like production's package_detail (key / value).
        Schema::create('package_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('value');
            $table->timestamps();
        });

        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('amount_type'); // fixed or percent
            $table->unsignedInteger('amount');
            $table->date('starts_on');
            $table->date('ends_on');
            $table->boolean('single_use')->default(false); // once per user
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Production's `transaction` table. Rows are never edited or deleted: they are what was paid.
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->foreignId('package_id')->constrained()->restrictOnDelete();
            $table->foreignId('coupon_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('price');
            $table->unsignedInteger('discount')->default(0);
            $table->unsignedInteger('amount');
            $table->string('payment_reference');
            $table->date('starts_on');
            $table->date('ends_on');
            $table->timestamps();
        });

        // Production's `rent_transction`: one movie, paid once, watchable until it expires.
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->foreignId('movie_id')->constrained()->restrictOnDelete();
            $table->foreignId('coupon_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('price');
            $table->unsignedInteger('discount')->default(0);
            $table->unsignedInteger('amount');
            $table->dateTime('expires_at')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['rentals', 'subscriptions', 'coupons', 'package_features', 'packages', 'users'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
