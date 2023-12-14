<?php


namespace App\Traits;

use App\Models\Document;
use App\Http\Controllers\PDFController;
use File;
use Lang;

trait ModelHasPrintingsTrait
{
    use ModelHasDocumentsTrait;

    public static function bootModelHasPrintingsTrait() {
        static::saved(function ($object) {
            $object->addPrinting();
        });
    }

    public abstract function printingView(): string;

    public function printingName(): string
    {
        $name = $this->sequence_number;
        if ($this->modification_number) {
            $name .= '-'.$this->modification_number;
        }

        return $name;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function printings() {
        return $this->all_documents()->where('document_type', '=', Document::DOCUMENT_PRINT_TYPE);
    }

    public function getLastPrintingAttribute() {
        return $this->printings()->latest('id')->first();
    }

    public function addPrinting() {
        $path  = $this->getDocumentsPath().$this->printingName().".pdf";
        $document = Document::where('path', $path)->first();

        $pdf_controller = new PDFController();
        $pdf = $pdf_controller->create_pdf($this, $this->printingView(), Lang::getLocale());

        if (!File::exists(storage_path('app/public/'.$this->getDocumentsPath()))) {
            File::makeDirectory(storage_path('app/public/'.$this->getDocumentsPath()), 0755, true);
        }
        $pdf->save(storage_path('app/public/').$path);

        if (!$document) {
            $newDoc                 = new Document();
            $newDoc->name           = $this->printingName();
            $newDoc->path           = $path;
            $newDoc->mime_type      = 'application/pdf';
            $newDoc->document_type  = Document::DOCUMENT_PRINT_TYPE;
            $newDoc->save();

            $this->printings()->attach($newDoc->id);
        } else {
            $document->path = $path;
            $document->save();

            if (!$this->printings()->firstWhere('documents.id', $document->id)) {
                $this->printings()->attach($document->id);
            }
        }
    }
}
