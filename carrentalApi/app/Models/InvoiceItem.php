<?php

namespace App;

use App\Invoice;
use Illuminate\Database\Eloquent\Model;
/**
 * @property int $id
 * @property int $invoice_id
 * @property string $title
 * @property int $quantity
 * @property int $daily_price
 * @property float $price
 */
class InvoiceItem extends Model
{
    public $timestamps = false;
    protected $fillable = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'id');
    }

    public function getRowTotalAttribute(){
        return ((float) $this->price * (float) $this->quantity);
    }
}
