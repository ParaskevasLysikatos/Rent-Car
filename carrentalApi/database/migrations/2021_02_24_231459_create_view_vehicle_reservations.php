<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateViewVehicleReservations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("DROP VIEW IF EXISTS vehicle_reservations;");

        DB::statement(
            "
CREATE VIEW vehicle_reservations AS
    SELECT
      'rental' AS `type`,
      rentals.id,
      rentals.vehicle_id,
      rentals.checkout_datetime,
      rentals.checkin_datetime
    FROM
      rentals
    WHERE
      rentals.deleted_at IS NULL
      AND rentals.completed_at IS NULL
      AND rentals.checkin_datetime > NOW()
      AND rentals.status NOT IN ('completed', 'cancelled')
    UNION ALL
      (
        SELECT
          'transition' AS `type`,
          transitions.id,
          transitions.vehicle_id,
          transitions.co_datetime AS checkout_datetime,
          transitions.ci_datetime AS checkin_datetime
        FROM
          transitions
        WHERE
          transitions.deleted_at IS NULL
          AND transitions.completed_at IS NULL
          AND transitions.ci_datetime > NOW()
      )
            "
        );
    }

    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS vehicle_reservations;");
    }
}
