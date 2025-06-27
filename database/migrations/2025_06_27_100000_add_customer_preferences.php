<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add dietary preferences to customer table
        Schema::table('customer', function (Blueprint $table) {
            $table->boolean('no_pork')->default(false);
            $table->boolean('is_vegan')->default(false);
            $table->boolean('is_vegetarian')->default(false);
            $table->integer('adults_count')->default(1); // >18 years
            $table->integer('children_count')->default(0); // >2 years
            $table->integer('babies_count')->default(0); // <=2 years
        });

        // Create custom allergies table for manual entries
        Schema::create('custom_allergy', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customer')->onDelete('cascade');
            $table->string('allergy_name', 100);
            $table->enum('severity', ['Low', 'Medium', 'High', 'Life-threatening'])->default('Medium');
            $table->boolean('is_actief')->default(true);
            $table->string('opmerking', 255)->nullable();
            $table->timestamps();
            $table->dateTime('datum_aangemaakt', 6);
            $table->dateTime('datum_gewijzigd', 6);
        });

        // Create food package history view table for better tracking
        Schema::create('customer_package_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customer')->onDelete('cascade');
            $table->foreignId('food_package_id')->constrained('food_package')->onDelete('cascade');
            $table->date('distribution_date');
            $table->text('package_contents')->nullable(); // JSON of products and quantities
            $table->decimal('total_value', 8, 2)->nullable();
            $table->boolean('is_actief')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_package_history');
        Schema::dropIfExists('custom_allergy');
        
        Schema::table('customer', function (Blueprint $table) {
            $table->dropColumn([
                'no_pork', 'is_vegan', 'is_vegetarian', 
                'adults_count', 'children_count', 'babies_count'
            ]);
        });
    }
};
