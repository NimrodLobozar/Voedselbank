<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('person', function (Blueprint $table) {
            $table->id();

            $table->boolean('is_actief')->default(true);
            $table->string('opmerking', 255)->nullable();
            $table->dateTime('datum_aangemaakt', 6);
            $table->dateTime('datum_gewijzigd', 6);
            $table->timestamps();
        });

        Schema::create('user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained('person');

            $table->boolean('is_actief')->default(true);
            $table->string('opmerking', 255)->nullable();
            $table->dateTime('datum_aangemaakt', 6);
            $table->dateTime('datum_gewijzigd', 6);
            $table->timestamps();
        });

        Schema::create('rol', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('user');

            $table->boolean('is_actief')->default(true);
            $table->string('opmerking', 255)->nullable();
            $table->dateTime('datum_aangemaakt', 6);
            $table->dateTime('datum_gewijzigd', 6);
            $table->timestamps();
        });

        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('user');

            $table->boolean('is_actief')->default(true);
            $table->string('opmerking', 255)->nullable();
            $table->dateTime('datum_aangemaakt', 6);
            $table->dateTime('datum_gewijzigd', 6);
            $table->timestamps();
        });

        Schema::create('supplier', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('user');

            $table->boolean('is_actief')->default(true);
            $table->string('opmerking', 255)->nullable();
            $table->dateTime('datum_aangemaakt', 6);
            $table->dateTime('datum_gewijzigd', 6);
            $table->timestamps();
        });

        Schema::create('familymember', function (Blueprint $table) {
            $table->id();

            $table->boolean('is_actief')->default(true);
            $table->string('opmerking', 255)->nullable();
            $table->dateTime('datum_aangemaakt', 6);
            $table->dateTime('datum_gewijzigd', 6);
            $table->timestamps();
        });

        Schema::create('customer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('user');
            $table->string('first_name', 25);
            $table->string('middle_name', 10)->nullable();
            $table->string('last_name', 25);
            $table->enum('age_group', ['<=2', '>2', '>18']);
            $table->string('street', 50);
            $table->integer('house_number');
            $table->string('addition', 3)->nullable();
            $table->string('postal_code', 6);
            $table->string('mobile', 13);
            $table->string('email', 50);
            $table->boolean('is_actief')->default(true);
            $table->string('opmerking', 255)->nullable();
            $table->dateTime('datum_aangemaakt', 6);
            $table->dateTime('datum_gewijzigd', 6);
            $table->timestamps();
        });

        Schema::create('contact', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('user');

            $table->boolean('is_actief')->default(true);
            $table->string('opmerking', 255)->nullable();
            $table->dateTime('datum_aangemaakt', 6);
            $table->dateTime('datum_gewijzigd', 6);           
            $table->timestamps();
        });

        Schema::create('foodstorage', function (Blueprint $table) {
            $table->id();


            $table->boolean('is_actief')->default(true);
            $table->string('opmerking', 255)->nullable();
            $table->dateTime('datum_aangemaakt', 6);
            $table->dateTime('datum_gewijzigd', 6);
            $table->timestamps();
        });

        Schema::create('allergy', function (Blueprint $table) {
            $table->id();


            $table->boolean('is_actief')->default(true);
            $table->string('opmerking', 255)->nullable();
            $table->dateTime('datum_aangemaakt', 6);
            $table->dateTime('datum_gewijzigd', 6);
            $table->timestamps();
        });

        Schema::create('produce', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('supplier');
            $table->foreignId('food_storage_id')->constrained('foodstorage');
            $table->foreignId('allergy_id')->nullable()->constrained('allergy');
            $table->string('name', 50);
            $table->enum('category', ['Groente', 'Fruit', 'Vlees', 'Lactose']);
            $table->date('expiry_date');
            $table->integer('amount');
            $table->boolean('is_actief')->default(true);
            $table->string('opmerking', 255)->nullable();
            $table->dateTime('datum_aangemaakt', 6);
            $table->dateTime('datum_gewijzigd', 6);
            $table->timestamps();
        });

        Schema::create('produce_allergy', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produce_id')->constrained('produce');
            $table->foreignId('allergy_id')->constrained('allergy');


            $table->boolean('is_actief')->default(true);
            $table->string('opmerking', 255)->nullable();
            $table->dateTime('datum_aangemaakt', 6);
            $table->dateTime('datum_gewijzigd', 6);
            $table->timestamps();
        });

        Schema::create('foodpackage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customer');
            $table->foreignId('produce_id')->constrained('produce');
            $table->date('assembled_at');
            $table->date('distributiondate');
            $table->boolean('is_actief')->default(true);
            $table->string('opmerking', 255)->nullable();
            $table->dateTime('datum_aangemaakt', 6);
            $table->dateTime('datum_gewijzigd', 6);
            $table->timestamps();
        });

        Schema::create('family', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customer');
            $table->foreignId('familymember_id')->constrained('familymember');

            $table->boolean('is_actief')->default(true);
            $table->string('opmerking', 255)->nullable();
            $table->dateTime('datum_aangemaakt', 6);
            $table->dateTime('datum_gewijzigd', 6);            
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('family');
        Schema::dropIfExists('foodpackage');
        Schema::dropIfExists('produce_allergy');
        Schema::dropIfExists('produce');
        Schema::dropIfExists('allergy');
        Schema::dropIfExists('foodstorage');
        Schema::dropIfExists('contact');
        Schema::dropIfExists('customer');
        Schema::dropIfExists('familymember');
        Schema::dropIfExists('supplier');
        Schema::dropIfExists('employees');
        Schema::dropIfExists('rol');
        Schema::dropIfExists('user');
        Schema::dropIfExists('person');
    }
};
