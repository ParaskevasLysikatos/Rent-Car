<?php

namespace Tests\Model;

use App\Agent;
use App\Document;
use App\DocumentType;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AgentDocumentTest extends TestCase
{
    protected $truncate = [
        'documents',
        'agents',
    ];

    public function test_agent_documents()
    {
        $agent      = factory(Agent::class)->create();
        $document_1 = factory(Document::class)->create();
        $document_2 = factory(Document::class)->create();
        $document_3 = factory(Document::class)->create();

        $agent->addDocument($document_1);
        $agent->addDocument($document_2);
        $agent->addDocument($document_3);

        $expected = [
            $document_1->id,
            $document_2->id,
            $document_3->id,
        ];

        $result = array_map(function ($item) {
            return (int)$item['id'];
        }, $agent->documents->toArray());

        $this->assertEquals($expected, $result);
    }

    public function test_addDocument_doesnt_add_same_document_twice()
    {
        $agent      = factory(Agent::class)->create();
        $document_1 = factory(Document::class)->create();
        $document_2 = factory(Document::class)->create();

        $agent->addDocument($document_1);
        $agent->addDocument($document_1);
        $agent->addDocument($document_2);
        $agent->addDocument($document_2);
        $agent->addDocument($document_1);
        $agent->addDocument($document_2);

        $expected = [
            $document_1->id,
            $document_2->id,
        ];

        $result = array_map(function ($item) {
            return (int)$item['id'];
        }, $agent->documents->toArray());

        $this->assertEquals($expected, $result);
    }

}
