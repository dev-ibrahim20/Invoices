<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class invoice_attachments extends Model
{
    use HasFactory;
    protected $fillable = [
        'file_name',
        'invoice_id',
        'Created_by',
        'invoice_number',
        'created_at',
        'updated_at',
    ];

    public function invoices()
    {
        return $this->belongsTo(invoices::class);
    }

    public function sections()
    {
        return $this->belongsTo(sections::class);
    }
    public function products()
    {
        return $this->belongsTo(products::class);
    }
    public function invoices_details()
    {
        return $this->belongsTo(invoices_details::class);
    }
}
