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
                CONCAT(c.first_name, \' \', IFNULL(CONCAT(c.middle_name, \' \'), c.last_name)) AS full_name,
                c.birth_date,
                CONCAT(c.street, \' \', c.house_number, IFNULL(CONCAT(\' \', c.addition), \'\'), \', \', c.postal_code, \' \', c.city) AS full_address,
                c.mobile,
                c.email,
                c.household_size,
                c.income,
                c.registration_date,
                c.is_actief,
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
                    c.income,
                    c.registration_date,
                    c.is_actief,
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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_GetCustomers;');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_GetCustomerById;');
    }
};
