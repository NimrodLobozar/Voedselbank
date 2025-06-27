<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('
            DROP PROCEDURE IF EXISTS sp_GetFoodPackages;
            CREATE PROCEDURE sp_GetFoodPackages()
            BEGIN
                SELECT
                    fp.id AS food_package_id,
                    c.first_name AS customer_first_name,
                    fp.package_name,
                    fp.assembled_at,
                    fp.distribution_date,
                    p.name AS produce_name
                FROM
                    food_package fp
                INNER JOIN
                    customer c ON fp.customer_id = c.id
                LEFT JOIN
                    food_package_produce fpp ON fp.id = fpp.food_package_id
                LEFT JOIN
                    produce p ON fpp.produce_id = p.id;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_GetFoodPackages;');
    }
};
