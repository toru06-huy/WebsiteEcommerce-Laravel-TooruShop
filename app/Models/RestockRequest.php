<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestockRequest extends Model
{
    protected $table = 'restock_requests';
    protected $primaryKey = 'restockRequestID';

    // Vừa gửi yêu cầu, chờ nhà cung cấp phản hồi qua email
    const STATUS_PENDING = 'pending';
    // NCC đã bấm xác nhận trong email — CHƯA cộng kho, chờ nhân viên kiểm hàng thực tế
    const STATUS_SUPPLIER_CONFIRMED = 'supplier_confirmed';
    // Nhân viên đã kiểm hàng & xác nhận nhập kho chính thức — kho đã được cộng
    const STATUS_COMPLETED = 'completed';
    // Bị hủy — do NCC từ chối yêu cầu, hoặc do nhân viên từ chối nhận hàng (hàng không đạt)
    const STATUS_CANCELLED = 'cancelled';

    const CANCELLED_BY_SUPPLIER = 'supplier';
    const CANCELLED_BY_STAFF    = 'staff';

    protected $fillable = [
        'productID',
        'manufacturerID',
        'token',
        'status',
        'requestedBy',
        'confirmedAt',
        'receivedBy',
        'receivedAt',
        'cancelReason',
        'cancelledByType',
        'cancelledByUserID',
        'cancelledAt',
    ];

    protected $casts = [
        'confirmedAt' => 'datetime',
        'receivedAt'  => 'datetime',
        'cancelledAt' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'productID', 'productID');
    }

    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class, 'manufacturerID', 'manufacturerID');
    }

    public function items()
    {
        return $this->hasMany(RestockRequestItem::class, 'restockRequestID', 'restockRequestID');
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isSupplierConfirmed(): bool
    {
        return $this->status === self::STATUS_SUPPLIER_CONFIRMED;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function isCancelledBySupplier(): bool
    {
        return $this->isCancelled() && $this->cancelledByType === self::CANCELLED_BY_SUPPLIER;
    }

    public function isCancelledByStaff(): bool
    {
        return $this->isCancelled() && $this->cancelledByType === self::CANCELLED_BY_STAFF;
    }
}
