<?php


namespace Tests\Model;

use App\Booking;
use App\Document;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class BookingDocumentTest extends TestCase
{
    protected $truncate = [
        'documents',
        'bookings',
        'vehicles',
        'types',
        'stations',
        'locations',
        'users',
        'user_roles',
    ];

    public function test_booking_documents()
    {
        $booking    = factory(Booking::class)->state('with_stations')->create();
        $document_1 = factory(Document::class)->create();
        $document_2 = factory(Document::class)->create();
        $document_3 = factory(Document::class)->create();

        $booking->addDocument($document_1);
        $booking->addDocument($document_2);
        $booking->addDocument($document_3);

        $expected = [
            $document_1->id,
            $document_2->id,
            $document_3->id,
        ];

        $result = array_map(function ($item) {
            return (int)$item['id'];
        }, $booking->documents->toArray());

        $this->assertEquals($expected, $result);
    }

    public function test_addDocument_doesnt_add_same_document_twice()
    {
        $booking    = factory(Booking::class)->state('with_stations')->create();
        $document_1 = factory(Document::class)->create();
        $document_2 = factory(Document::class)->create();

        $booking->addDocument($document_1);
        $booking->addDocument($document_1);
        $booking->addDocument($document_2);
        $booking->addDocument($document_2);
        $booking->addDocument($document_1);
        $booking->addDocument($document_2);

        $expected = [
            $document_1->id,
            $document_2->id,
        ];

        $result = array_map(function ($item) {
            return (int)$item['id'];
        }, $booking->documents->toArray());

        $this->assertEquals($expected, $result);
    }

}
