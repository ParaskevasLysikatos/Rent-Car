<?php

namespace App\Traits;

use App\Models\Document;
use App\Models\DocumentLink;
use Illuminate\Http\UploadedFile;
use Request;
use Str;

trait ModelHasDocumentsTrait
{
    public static function bootModelHasDocumentsTrait()
    {
        static::saved(function ($object) {
            // $object->addDocuments(); //v1->v2 every model that has this trait, in controller on create method after save, should be invoked like $var->method();
        });
    }

    public abstract function getDocumentsPath();
    public abstract function getInitialFileName();

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function all_documents()
    {
        return $this->morphToMany(Document::class, 'document_link');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function documents()
    {
        return $this->all_documents()->where(function ($q) {
            $q->where('document_type', '!=', Document::DOCUMENT_PRINT_TYPE)->orWhereNull('document_type');
        });
    }

    public function syncDocuments($documents) // this is used for v2 only (api)
    {
        $documents = $documents ? $documents : [];
        $documents = array_merge($documents, $this->all_documents()->whereNotIn('documents.id', $this->documents->pluck('documents.id')->toArray())->pluck('documents.id')->toArray());
        $this->all_documents()->sync($documents);
    }

    /**
     * Associate document with current model
     *
     * @param \App\Document $document
     *
     * @return \App\static:class model class
     */
    public function addDocumentLink(Document $document)
    {
        $document_link = DocumentLink::where(['document_id' => $document->id, 'document_link_id' => $this->id, 'document_link_type' => static::class])->first();

        if (!$document_link) {
            $this->documents()->attach($document->id);
        }

        return $this;
    }

    public function addDocumentFromUploadedFile(UploadedFile $document)
    {
        $path  = $this->getDocumentsPath();
        $initialFileName = $this->getInitialFileName();
        $fileName = $initialFileName . "-" . $this->id . "-" . Str::random(8) . time() . "." . $document->getClientOriginalExtension();
        $document->storeAs('public/' . $path, $fileName);

        $newDoc            = new Document();
        $newDoc->name      = $document->getClientOriginalName();
        $newDoc->path      = $path . $fileName;
        $newDoc->mime_type = $document->getMimeType();
        $newDoc->save();

        $this->addDocumentLink($newDoc);
    }

    public function addDocuments()
    {
        if (!Request::has('documents_added')) {
            $existing_docs = Request::get('files') ?? [];
            $docs_to_remove = $this->documents()->whereNotIn('documents.id', $existing_docs)->pluck('documents.id');
            $this->documents()->detach($docs_to_remove);

            if (Request::hasFile('files')) {
                $documents = Request::file('files') ?? [];
                foreach ($documents as $document) {
                    $this->addDocumentFromUploadedFile($document);
                }
            }
        }
        Request::merge(['documents_added' => true]);
    }
}
