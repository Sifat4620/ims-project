<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Returns;
use App\Models\FaultyItem;

class Item extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'items';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lc_po_type', 
        'category', 
        'brand', 
        'model_no', 
        'serial_no',
        'specification',
        'condition', 
        'status', 
        'holding_location', 
        'date',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Define the relationship with the Returns model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function returns()
    {
        return $this->hasOne(Returns::class, 'item_id', 'id');
    }

    /**
     * Define the relationship with the FaultyItem model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function faultyItem()
    {
        return $this->hasOne(FaultyItem::class, 'item_id', 'id');
    }

    /**
     * Booted method to handle model events.
     */

    public function ajaxBarcode()
    {
        return $this->hasOne(ItemBarcode::class, 'item_id');
    }


     public function itemBarcode()
    {
        return $this->hasMany(ItemBarcode::class);
    }

    public function product()
    {
         return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
    



     protected static function booted()
     {
         static::updated(function ($item) {
             \Log::info('Updated event triggered for Item ID: ' . $item->id);
     
             try {
                 // Handle "Returned" status
                 if ($item->isDirty('status') && $item->status === 'Return') {
                     \Log::info('Condition met for Returned status', [
                         'item_id' => $item->id,
                         'status' => $item->status,
                         'isDirty' => $item->isDirty('status'),
                     ]);
     
                     $returns = Returns::updateOrCreate(
                         ['item_id' => $item->id], // Unique key to find or create
                         [
                             'description' => 'Item returned successfully', // Replace with meaningful description
                             'status' => 'Return', // Default return status
                             'lc_po_type' => $item->lc_po_type,
                         ]
                     );
     
                     \Log::info('Returns Table Updated Successfully for Item ID: ' . $item->id, [
                         'data' => $returns->toArray(),
                     ]);
                 }
     
                 // Faulty status logic remains unchanged
                 if ($item->isDirty('status') && $item->status === 'Faulty') {
                     \Log::info('Condition met for Faulty status', [
                         'item_id' => $item->id,
                         'status' => $item->status,
                         'isDirty' => $item->isDirty('status'),
                     ]);
     
                     $faultyItem = FaultyItem::updateOrCreate(
                         ['item_id' => $item->id], // Unique key to find or create
                         [
                             'description' => 'Fault detected in the item', // Replace with meaningful description
                             'status' => 'Reported', // Default faulty status
                             'reported_date' => now(), // Current date as reported_date
                             'lc_po_type' => $item->lc_po_type,
                         ]
                     );
     
                     \Log::info('Faulty Items Table Updated Successfully for Item ID: ' . $item->id, [
                         'data' => $faultyItem->toArray(),
                     ]);
                 }
             } catch (\Exception $e) {
                 \Log::error('Error processing status update for Item ID: ' . $item->id, [
                     'error' => $e->getMessage(),
                     'item_data' => $item->toArray(),
                 ]);
             }
         });
     }
     

}
