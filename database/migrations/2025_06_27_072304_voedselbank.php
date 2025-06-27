<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('person', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->boolean('is_actief')->default(true);
            $table->string('opmerking', 255)->nullable();
            $table->timestamps();
            $table->dateTime('datum_aangemaakt', 6);
            $table->dateTime('datum_gewijzigd', 6);
        });

        Schema::create('role', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('rol', ['Admin', 'Medewerker', 'Vrijwilliger']);
            $table->boolean('is_actief')->default(true);
            $table->string('opmerking', 255)->nullable();
            $table->timestamps();
            $table->dateTime('datum_aangemaakt', 6);
            $table->dateTime('datum_gewijzigd', 6);
        });

        Schema::create('employee', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('functie', 50);
            $table->date('datum_in_dienst');
            $table->decimal('salary', 8, 2)->nullable();
            $table->boolean('is_actief')->default(true);
            $table->string('opmerking', 255)->nullable();
            $table->timestamps();
            $table->dateTime('datum_aangemaakt', 6);
            $table->dateTime('datum_gewijzigd', 6);
        });

        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('contact_person', 100);
            $table->string('phone', 20);
            $table->string('email', 100);
            $table->string('address', 200)->nullable();
            $table->enum('supplier_type', ['Supermarket', 'Farmer', 'Wholesaler', 'Individual'])->default('Individual');
            $table->boolean('is_actief')->default(true);
            $table->string('opmerking', 255)->nullable();
            $table->timestamps();
            $table->dateTime('datum_aangemaakt', 6);
            $table->dateTime('datum_gewijzigd', 6);
        });

        Schema::create('family_member', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 50);
            $table->string('middle_name', 20)->nullable();
            $table->string('last_name', 50);
            $table->date('birth_date');
            $table->enum('gender', ['M', 'F', 'Other'])->nullable();
            $table->boolean('is_actief')->default(true);
            $table->string('opmerking', 255)->nullable();
            $table->timestamps();
            $table->dateTime('datum_aangemaakt', 6);
            $table->dateTime('datum_gewijzigd', 6);
        });

        Schema::create('customer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('first_name', 50);
            $table->string('middle_name', 20)->nullable();
            $table->string('last_name', 50);
            $table->date('birth_date');
            $table->string('street', 100);
            $table->string('house_number', 10);
            $table->string('addition', 10)->nullable();
            $table->string('postal_code', 7);
            $table->string('city', 50);
            $table->string('mobile', 20);
            $table->string('email', 100);
            $table->integer('household_size')->default(1);
            $table->decimal('income', 8, 2)->nullable();
            $table->date('registration_date');
            $table->boolean('is_actief')->default(true);
            $table->string('opmerking', 255)->nullable();
            $table->timestamps();
            $table->dateTime('datum_aangemaakt', 6);
            $table->dateTime('datum_gewijzigd', 6);
        });

        Schema::create('contact', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customer')->onDelete('cascade');
            $table->string('contact_type', 30);
            $table->string('phone', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('relationship', 50)->nullable();
            $table->boolean('is_emergency_contact')->default(false);
            $table->boolean('is_actief')->default(true);
            $table->string('opmerking', 255)->nullable();
            $table->timestamps();
            $table->dateTime('datum_aangemaakt', 6);
            $table->dateTime('datum_gewijzigd', 6);
        });

        Schema::create('food_storage', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('location', 200);
            $table->integer('capacity');
            $table->decimal('temperature_min', 5, 2)->nullable();
            $table->decimal('temperature_max', 5, 2)->nullable();
            $table->enum('storage_type', ['Refrigerated', 'Frozen', 'Dry', 'Fresh'])->default('Dry');
            $table->enum('status', ['onderweg', 'in_behandeling', 'geleverd'])->default('onderweg');
            $table->boolean('is_actief')->default(true);
            $table->string('opmerking', 255)->nullable();
            $table->timestamps();
            $table->dateTime('datum_aangemaakt', 6);
            $table->dateTime('datum_gewijzigd', 6);
        });

        Schema::create('allergy', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->text('description')->nullable();
            $table->boolean('is_actief')->default(true);
            $table->string('opmerking', 255)->nullable();
            $table->timestamps();
            $table->dateTime('datum_aangemaakt', 6);
            $table->dateTime('datum_gewijzigd', 6);
        });

        Schema::create('produce', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('cascade');
            $table->foreignId('food_storage_id')->constrained('food_storage')->onDelete('cascade');
            $table->string('name', 100);
            $table->string('brand', 100)->nullable();
            $table->enum('category', ['Groente', 'Fruit', 'Vlees', 'Zuivel', 'Granen', 'Conserven', 'Diepvries', 'Brood', 'Overig']);
            $table->date('expiry_date');
            $table->date('received_date');
            $table->integer('amount');
            $table->string('unit', 20)->default('stuks');
            $table->decimal('weight_per_unit', 8, 3)->nullable();
            $table->boolean('is_actief')->default(true);
            $table->string('opmerking', 255)->nullable();
            $table->timestamps();
            $table->dateTime('datum_aangemaakt', 6)->useCurrent();
            $table->dateTime('datum_gewijzigd', 6)->useCurrent();
        });

        Schema::create('produce_allergy', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produce_id')->constrained('produce')->onDelete('cascade');
            $table->foreignId('allergy_id')->constrained('allergy')->onDelete('cascade');
            $table->boolean('is_actief')->default(true);
            $table->string('opmerking', 255)->nullable();
            $table->timestamps();
            $table->dateTime('datum_aangemaakt', 6);
            $table->dateTime('datum_gewijzigd', 6);
        });

        Schema::create('food_package', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customer')->onDelete('cascade');
            $table->foreignId('prepared_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('package_name', 100);
            $table->date('assembled_at');
            $table->date('distribution_date');
            $table->time('pickup_time')->nullable();
            $table->enum('status', ['Assembled', 'Ready', 'Distributed', 'Cancelled'])->default('Assembled');
            $table->boolean('is_actief')->default(true);
            $table->string('opmerking', 255)->nullable();
            $table->timestamps();
            $table->dateTime('datum_aangemaakt', 6);
            $table->dateTime('datum_gewijzigd', 6);
        });

        Schema::create('family', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customer')->onDelete('cascade');
            $table->foreignId('family_member_id')->constrained('family_member')->onDelete('cascade');
            $table->enum('relationship', ['Partner', 'Child', 'Parent', 'Sibling', 'Other']);
            $table->boolean('is_actief')->default(true);
            $table->string('opmerking', 255)->nullable();
            $table->timestamps();
            $table->dateTime('datum_aangemaakt', 6);
            $table->dateTime('datum_gewijzigd', 6);
        });

        Schema::create('customer_allergy', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customer')->onDelete('cascade');
            $table->foreignId('allergy_id')->constrained('allergy')->onDelete('cascade');
            $table->enum('severity', ['Low', 'Medium', 'High', 'Life-threatening']);
            $table->boolean('is_actief')->default(true);
            $table->string('opmerking', 255)->nullable();
            $table->timestamps();
            $table->dateTime('datum_aangemaakt', 6);
            $table->dateTime('datum_gewijzigd', 6);
        });

        // Create junction table for foodpackage and produce (many-to-many)
        Schema::create('food_package_produce', function (Blueprint $table) {
            $table->id();
            $table->foreignId('food_package_id')->constrained('food_package')->onDelete('cascade');
            $table->foreignId('produce_id')->constrained('produce')->onDelete('cascade');
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('food_package_produce');
        Schema::dropIfExists('customer_allergy');
        Schema::dropIfExists('family');
        Schema::dropIfExists('food_package');
        Schema::dropIfExists('produce_allergy');
        Schema::dropIfExists('produce');
        Schema::dropIfExists('allergy');
        Schema::dropIfExists('food_storage');
        Schema::dropIfExists('contact');
        Schema::dropIfExists('customer');
        Schema::dropIfExists('family_member');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('employee');
        Schema::dropIfExists('role');
        Schema::dropIfExists('person');
    }
};
