set session sql_mode='STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION'

SELECT *
FROM `licence_plates`
WHERE vehicle_id IN (
    SELECT vehicle_id
    FROM `licence_plates`
    GROUP BY vehicle_id
    HAVING COUNT(vehicle_id) > 1
)
AND vehicle_id IN (
    SELECT id
    FROM vehicles
    WHERE deleted_at IS NULL
)
ORDER BY registration_date DESC
