<?php


namespace Tests\Model;

use App\Booking;
use App\Payment;
use Tests\TestCase;

class BookingPaymentsTest extends TestCase
{
    protected $truncate = [
        'documents',
        'payments',
        'bookings',
        'vehicles',
        'types',
        'stations',
        'locations',
        'users',
        'user_roles',
    ];

    public function test_paymentsObserver()
    {
        $total_net = 100;
        $vat       = 24 / 100;
        $total     = $total_net * (1 + $vat);

        $booking = factory(Booking::class)->states(['with_stations', 'with_user'])->create([
            'total_net' => $total_net,
            'vat'       => $vat,
        ]);

        $payment_1 = factory(Payment::class)->create(['booking_id' => $booking->id, 'user_id' => $booking->user_id, 'amount' => 10]);
        $payment_2 = factory(Payment::class)->create(['booking_id' => $booking->id, 'user_id' => $booking->user_id, 'amount' => 15]);
        $payment_3 = factory(Payment::class)->create(['booking_id' => $booking->id, 'user_id' => $booking->user_id, 'amount' => 20]);
        $payment_4 = factory(Payment::class)->create(['booking_id' => $booking->id, 'user_id' => $booking->user_id, 'amount' => 25]);

        $payment_2->delete();
        $payment_3->delete();

        $booking->refresh();

        $expected_paid    = 10 + 25;
        $expected_balance = $total - $expected_paid;

        $this->assertEquals($expected_paid, $booking->getTotalPaid()); // calculated on the fly
        $this->assertEquals($expected_paid, $booking->total_paid); // stored on booking every time a payment is saved
        $this->assertEquals($expected_balance, $booking->balance); // calculated every time a booking is updated
    }

    public function test_calculateNetTotal()
    {
        $duration       = 5; // days
        $rate           = 25; // per day
        $discount       = 10 / 100;
        $vat            = 24 / 100;
        $voucher        = 5;
        $transport_fee  = 10;
        $insurance_fee  = 20;
        $options_fee    = 30;
        $fuel_fee       = 40;
        $subcharges_fee = 50;

        $expected_net_total = [
            $duration * $rate,
            -($duration * $rate * $discount),
            -$voucher,
            $transport_fee,
            $insurance_fee,
            $options_fee,
            $fuel_fee,
            $subcharges_fee,
        ];

        $expected_net_total = array_sum($expected_net_total);
        $expected_net_total = round($expected_net_total, 2);

        $net_total = Booking::calculateNetTotal($duration, $rate, $discount, $voucher, $transport_fee, $insurance_fee, $options_fee, $fuel_fee, $subcharges_fee);

        $this->assertEquals($expected_net_total, $net_total);

        $expected_total = $expected_net_total + $expected_net_total * $vat;
        $expected_total = round($expected_total, 2);

        $total = Booking::calculateTotal($net_total, $vat);

        $this->assertEquals($expected_total, $total);
    }

}
