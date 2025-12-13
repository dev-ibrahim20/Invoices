<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class invoices extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'invoice_number',
        'invoice_Date',
        'Due_date',
        'product',
        'section_id',
        'Amount_collection',
        'Amount_Commission',
        'discount',
        'rate_vat',
        'value_vat',
        'total',
        'status',
        'value_Status',
        'note',
        'user',
    ];

    protected $dates = ['deleted_at'];
    public function section()
    {
        return $this->belongsTo(sections::class);
    }
    public function details()
    {
        return $this->belongsTo(invoices_details::class, 'id_Invoice');
    }
    public function attachments()
    {
        return $this->hasMany(invoice_attachments::class);
    }
}
