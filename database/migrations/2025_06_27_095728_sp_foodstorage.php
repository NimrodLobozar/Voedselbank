<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('
            DROP PROCEDURE IF EXISTS sp_GetFoodStorages;
        ');
        
        DB::unprepared('
            CREATE PROCEDURE sp_GetFoodStorages()
            BEGIN
                SELECT
                    fs.id,
                    fs.name,
                    fs.location,
                    fs.capacity,
                    fs.temperature_min,
                    fs.temperature_max,
                    fs.storage_type,
                    CONCAT(fs.temperature_min, "°C - ", fs.temperature_max, "°C") AS temperature_range,
                    fs.is_actief,
                    fs.opmerking,
                    fs.datum_aangemaakt,
                    fs.datum_gewijzigd
                FROM
                    food_storage fs
                WHERE
                    fs.is_actief = 1
                ORDER BY
                    fs.name, fs.storage_type;
            END
        ');

        DB::unprepared('
            DROP PROCEDURE IF EXISTS sp_GetFoodStorageById;
        ');
        
        DB::unprepared('
            CREATE PROCEDURE sp_GetFoodStorageById(IN p_storage_id BIGINT UNSIGNED)
            BEGIN
                SELECT
                    fs.id,
                    fs.name,
                    fs.location,
                    fs.capacity,
                    fs.temperature_min,
                    fs.temperature_max,
                    fs.storage_type,
                    fs.is_actief,
                    fs.opmerking,
                    fs.created_at,
                    fs.updated_at,
                    fs.datum_aangemaakt,
                    fs.datum_gewijzigd
                FROM
                    food_storage fs
                WHERE
                    fs.id = p_storage_id;
            END
        ');

        DB::unprepared('
            DROP PROCEDURE IF EXISTS sp_GetFoodStoragesByType;
        ');
        
        DB::unprepared('
            CREATE PROCEDURE sp_GetFoodStoragesByType(IN p_storage_type VARCHAR(50))
            BEGIN
                SELECT
                    fs.id,
                    fs.name,
                    fs.location,
                    fs.capacity,
                    fs.temperature_min,
                    fs.temperature_max,
                    fs.storage_type,
                    fs.is_actief,
                    fs.opmerking
                FROM
                    food_storage fs
                WHERE
                    fs.storage_type = p_storage_type
                    AND fs.is_actief = 1
                ORDER BY
                    fs.name;
            END
        ');

        DB::unprepared('
            DROP PROCEDURE IF EXISTS sp_GetSuppliers;
        ');
        
        DB::unprepared('
            CREATE PROCEDURE sp_GetSuppliers()
            BEGIN
                SELECT
                    s.id,
                    s.name,
                    s.contact_person,
                    s.phone,
                    s.email,
                    s.address,
                    s.supplier_type,
                    s.is_actief,
                    s.opmerking,
                    s.datum_aangemaakt,
                    s.datum_gewijzigd,
                    GROUP_CONCAT(DISTINCT p.brand SEPARATOR ", ") AS brands
                FROM
                    suppliers s
                    LEFT JOIN produce p ON s.id = p.supplier_id AND p.brand IS NOT NULL AND p.is_actief = 1
                WHERE
                    s.is_actief = 1
                GROUP BY
                    s.id, s.name, s.contact_person, s.phone, s.email, s.address, s.supplier_type, s.is_actief, s.opmerking, s.datum_aangemaakt, s.datum_gewijzigd
                ORDER BY
                    s.name, s.supplier_type;
            END
        ');

        DB::unprepared('
            DROP PROCEDURE IF EXISTS sp_GetSupplierById;
        ');
        
        DB::unprepared('
            CREATE PROCEDURE sp_GetSupplierById(IN p_supplier_id BIGINT UNSIGNED)
            BEGIN
                SELECT
                    s.id,
                    s.name,
                    s.contact_person,
                    s.phone,
                    s.email,
                    s.address,
                    s.supplier_type,
                    s.is_actief,
                    s.opmerking,
                    s.created_at,
                    s.updated_at,
                    s.datum_aangemaakt,
                    s.datum_gewijzigd,
                    GROUP_CONCAT(DISTINCT p.brand SEPARATOR ", ") AS brands
                FROM
                    suppliers s
                    LEFT JOIN produce p ON s.id = p.supplier_id AND p.brand IS NOT NULL AND p.is_actief = 1
                WHERE
                    s.id = p_supplier_id
                GROUP BY
                    s.id, s.name, s.contact_person, s.phone, s.email, s.address, s.supplier_type, s.is_actief, s.opmerking, s.created_at, s.updated_at, s.datum_aangemaakt, s.datum_gewijzigd;
            END
        ');

        DB::unprepared('
            DROP PROCEDURE IF EXISTS sp_GetProduce;
        ');
        
        DB::unprepared('
            CREATE PROCEDURE sp_GetProduce()
            BEGIN
                SELECT
                    p.id,
                    p.supplier_id,
                    s.name AS supplier_name,
                    p.food_storage_id,
                    fs.name AS storage_name,
                    fs.storage_type,
                    p.name,
                    p.brand,
                    p.category,
                    p.expiry_date,
                    p.received_date,
                    p.amount,
                    p.unit,
                    p.weight_per_unit,
                    CONCAT(p.amount, " ", p.unit) AS amount_display,
                    DATEDIFF(p.expiry_date, CURDATE()) AS days_until_expiry,
                    CASE 
                        WHEN DATEDIFF(p.expiry_date, CURDATE()) < 0 THEN "Verlopen"
                        WHEN DATEDIFF(p.expiry_date, CURDATE()) <= 3 THEN "Kritiek"
                        WHEN DATEDIFF(p.expiry_date, CURDATE()) <= 7 THEN "Waarschuwing"
                        ELSE "Goed"
                    END AS expiry_status,
                    p.is_actief,
                    p.opmerking,
                    p.datum_aangemaakt,
                    p.datum_gewijzigd
                FROM
                    produce p
                    INNER JOIN suppliers s ON p.supplier_id = s.id
                    INNER JOIN food_storage fs ON p.food_storage_id = fs.id
                WHERE
                    p.is_actief = 1
                ORDER BY
                    p.expiry_date ASC, p.category, p.name;
            END
        ');

        DB::unprepared('
            DROP PROCEDURE IF EXISTS sp_GetProduceById;
        ');
        
        DB::unprepared('
            CREATE PROCEDURE sp_GetProduceById(IN p_produce_id BIGINT UNSIGNED)
            BEGIN
                SELECT
                    p.id,
                    p.supplier_id,
                    s.name AS supplier_name,
                    p.food_storage_id,
                    fs.name AS storage_name,
                    p.name,
                    p.brand,
                    p.category,
                    p.expiry_date,
                    p.received_date,
                    p.amount,
                    p.unit,
                    p.weight_per_unit,
                    p.is_actief,
                    p.opmerking,
                    p.created_at,
                    p.updated_at,
                    p.datum_aangemaakt,
                    p.datum_gewijzigd
                FROM
                    produce p
                    INNER JOIN suppliers s ON p.supplier_id = s.id
                    INNER JOIN food_storage fs ON p.food_storage_id = fs.id
                WHERE
                    p.id = p_produce_id;
            END
        ');

        DB::unprepared('
            DROP PROCEDURE IF EXISTS sp_GetProduceByCategory;
        ');
        
        DB::unprepared('
            CREATE PROCEDURE sp_GetProduceByCategory(IN p_category VARCHAR(50))
            BEGIN
                SELECT
                    p.id,
                    p.name,
                    p.brand,
                    p.amount,
                    p.unit,
                    p.expiry_date,
                    s.name AS supplier_name,
                    fs.name AS storage_name,
                    DATEDIFF(p.expiry_date, CURDATE()) AS days_until_expiry
                FROM
                    produce p
                    INNER JOIN suppliers s ON p.supplier_id = s.id
                    INNER JOIN food_storage fs ON p.food_storage_id = fs.id
                WHERE
                    p.category = p_category
                    AND p.is_actief = 1
                ORDER BY
                    p.expiry_date ASC;
            END
        ');

        DB::unprepared('
            DROP PROCEDURE IF EXISTS sp_SearchProduce;
        ');
        
        DB::unprepared('
            CREATE PROCEDURE sp_SearchProduce(
                IN p_barcode VARCHAR(50),
                IN p_product_name VARCHAR(100),
                IN p_category VARCHAR(50)
            )
            BEGIN
                SELECT
                    p.id AS streepjescode_id,
                    p.name AS productnaam,
                    p.category AS categorie,
                    CONCAT(p.amount, " ", p.unit) AS aantal,
                    IFNULL(p.brand, "N/A") AS brand,
                    DATE_FORMAT(p.expiry_date, "%d-%m-%Y") AS vervaldatum,
                    fs.name AS locatie,
                    p.supplier_id,
                    s.name AS supplier_name,
                    p.food_storage_id,
                    DATEDIFF(p.expiry_date, CURDATE()) AS days_until_expiry,
                    CASE 
                        WHEN DATEDIFF(p.expiry_date, CURDATE()) < 0 THEN "Verlopen"
                        WHEN DATEDIFF(p.expiry_date, CURDATE()) <= 3 THEN "Kritiek"
                        WHEN DATEDIFF(p.expiry_date, CURDATE()) <= 7 THEN "Waarschuwing"
                        ELSE "Goed"
                    END AS expiry_status
                FROM
                    produce p
                    INNER JOIN suppliers s ON p.supplier_id = s.id
                    INNER JOIN food_storage fs ON p.food_storage_id = fs.id
                WHERE
                    p.is_actief = 1
                    AND (p_barcode IS NULL OR p.id = p_barcode)
                    AND (p_product_name IS NULL OR p.name LIKE CONCAT("%", p_product_name, "%"))
                    AND (p_category IS NULL OR p_category = "Alle categorieën" OR p.category = p_category)
                ORDER BY
                    p.expiry_date ASC, p.category, p.name;
            END
        ');

        DB::unprepared('
            DROP PROCEDURE IF EXISTS sp_GetProduceForOverview;
        ');
        
        DB::unprepared('
            CREATE PROCEDURE sp_GetProduceForOverview()
            BEGIN
                SELECT
                    p.id AS streepjescode_id,
                    p.name AS productnaam,
                    p.category AS categorie,
                    CONCAT(p.amount, " ", p.unit) AS aantal,
                    IFNULL(p.brand, "N/A") AS brand,
                    DATE_FORMAT(p.expiry_date, "%d-%m-%Y") AS vervaldatum,
                    fs.name AS locatie
                FROM
                    produce p
                    INNER JOIN suppliers s ON p.supplier_id = s.id
                    INNER JOIN food_storage fs ON p.food_storage_id = fs.id
                WHERE
                    p.is_actief = 1
                ORDER BY
                    p.id ASC;
            END
        ');

        DB::unprepared('
            DROP PROCEDURE IF EXISTS sp_GetCategoriesForDropdown;
        ');
        
        DB::unprepared('
            CREATE PROCEDURE sp_GetCategoriesForDropdown()
            BEGIN
                SELECT DISTINCT
                    category AS categorie
                FROM
                    produce
                WHERE
                    is_actief = 1
                ORDER BY
                    category;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_GetFoodStorages;');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_GetFoodStorageById;');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_GetFoodStoragesByType;');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_GetSuppliers;');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_GetSupplierById;');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_GetProduce;');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_GetProduceById;');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_GetProduceByCategory;');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_SearchProduce;');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_GetProduceForOverview;');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_GetCategoriesForDropdown;');
    }
};
