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
        DB::unprepared('
            DROP PROCEDURE IF EXISTS sp_GetCustomers;
            CREATE PROCEDURE sp_GetCustomers()
            BEGIN
            SELECT
                c.id,
                CONCAT(c.first_name, \' \', IFNULL(CONCAT(c.middle_name, \' \'), \'\'), c.last_name) AS full_name,
                c.birth_date,
                CONCAT(c.street, \' \', c.house_number, IFNULL(CONCAT(\' \', c.addition), \'\'), \', \', c.postal_code, \' \', c.city) AS full_address,
                c.mobile,
                c.email,
                c.household_size,
                c.adults_count,
                c.children_count,
                c.babies_count,
                c.income,
                c.registration_date,
                c.is_actief,
                c.no_pork,
                c.is_vegan,
                c.is_vegetarian,
                c.opmerking
            FROM
                customer c
            WHERE
                c.is_actief = 1
            ORDER BY
                c.last_name, c.first_name;
            END
        ');

        DB::unprepared('
            DROP PROCEDURE IF EXISTS sp_GetCustomerById;
            CREATE PROCEDURE sp_GetCustomerById(IN p_customer_id BIGINT UNSIGNED)
            BEGIN
                SELECT
                    c.id,
                    c.user_id,
                    c.first_name,
                    c.middle_name,
                    c.last_name,
                    c.birth_date,
                    c.street,
                    c.house_number,
                    c.addition,
                    c.postal_code,
                    c.city,
                    c.mobile,
                    c.email,
                    c.household_size,
                    c.adults_count,
                    c.children_count,
                    c.babies_count,
                    c.income,
                    c.registration_date,
                    c.is_actief,
                    c.no_pork,
                    c.is_vegan,
                    c.is_vegetarian,
                    c.opmerking,
                    c.created_at,
                    c.updated_at,
                    c.datum_aangemaakt,
                    c.datum_gewijzigd
                FROM
                    customer c
                WHERE
                    c.id = p_customer_id;
            END
        ');

        // New procedure for customer package history
        DB::unprepared('
            DROP PROCEDURE IF EXISTS sp_GetCustomerPackageHistory;
            CREATE PROCEDURE sp_GetCustomerPackageHistory(IN p_customer_id BIGINT UNSIGNED)
            BEGIN
                SELECT
                    fp.id,
                    fp.package_name,
                    fp.distribution_date,
                    fp.pickup_time,
                    fp.status,
                    fp.assembled_at,
                    GROUP_CONCAT(
                        CONCAT(p.name, \' (\', fpp.quantity, \' \', p.unit, \')\')
                        SEPARATOR \', \'
                    ) AS package_contents,
                    u.name as prepared_by_name
                FROM
                    food_package fp
                    LEFT JOIN food_package_produce fpp ON fp.id = fpp.food_package_id
                    LEFT JOIN produce p ON fpp.produce_id = p.id
                    LEFT JOIN users u ON fp.prepared_by = u.id
                WHERE
                    fp.customer_id = p_customer_id
                GROUP BY
                    fp.id, fp.package_name, fp.distribution_date, fp.pickup_time, 
                    fp.status, fp.assembled_at, u.name
                ORDER BY
                    fp.distribution_date DESC, fp.assembled_at DESC;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_GetCustomers;');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_GetCustomerById;');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_GetCustomerPackageHistory;');
    }
};
