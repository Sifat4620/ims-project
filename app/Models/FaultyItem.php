<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaultyItem extends Model
{
    protected $fillable = [
        'item_id',
        'description',
        'status',
        'reported_date',
        'lc_po_type',
        'created_at',
        'updated_at',
    ];

    /**
     * Cast attributes to native types.
     */
    protected $casts = [
        'reported_date' => 'date',
    ];

    /**
     * Relationship with the Item model.
     */
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }
}
