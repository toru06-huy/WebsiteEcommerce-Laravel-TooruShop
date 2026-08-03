<?php

namespace App\Http\Controllers;

use App\Models\ProductVariant;
use App\Models\RestockRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierRestockController extends Controller
{

    public function show($token)
    {
        $restockRequest = RestockRequest::with([
            'items.variant.size',
            'items.variant.color',
            'product',
            'manufacturer',
        ])->where('token', $token)->firstOrFail();

        return view('supplier.restock-confirm', compact('restockRequest'));
    }

    public function confirm(Request $request, $token)
    {
        $restockRequest = RestockRequest::where('token', $token)->firstOrFail();

        if (!$restockRequest->isPending()) {
            return redirect()->route('supplier.restock.show', $token)
                ->with('info', 'Yêu cầu này đã được xử lý trước đó, không thể xác nhận lại.');
        }

        DB::transaction(function () use ($restockRequest) {
            $locked = RestockRequest::where('restockRequestID', $restockRequest->restockRequestID)
                ->where('status', RestockRequest::STATUS_PENDING)
                ->lockForUpdate()
                ->first();

            if (!$locked) {
                return;
            }

            $locked->status      = RestockRequest::STATUS_SUPPLIER_CONFIRMED;
            $locked->confirmedAt = now();
            $locked->save();
        });

        return redirect()->route('supplier.restock.show', $token)
            ->with('success', 'Cảm ơn quý nhà cung cấp đã xác nhận! Nhân viên cửa hàng sẽ kiểm hàng khi nhận được và xác nhận nhập kho chính thức.');
    }

    public function decline(Request $request, $token)
    {
        $restockRequest = RestockRequest::where('token', $token)->firstOrFail();

        if (!$restockRequest->isPending()) {
            return redirect()->route('supplier.restock.show', $token)
                ->with('info', 'Yêu cầu này đã được xử lý trước đó, không thể từ chối.');
        }

        $reason = trim((string) $request->input('reason'));

        DB::transaction(function () use ($restockRequest, $reason) {
            $locked = RestockRequest::where('restockRequestID', $restockRequest->restockRequestID)
                ->where('status', RestockRequest::STATUS_PENDING)
                ->lockForUpdate()
                ->first();

            if (!$locked) {
                return;
            }

            $locked->status            = RestockRequest::STATUS_CANCELLED;
            $locked->cancelReason      = $reason !== '' ? $reason : null;
            $locked->cancelledByType   = RestockRequest::CANCELLED_BY_SUPPLIER;
            $locked->cancelledAt       = now();
            $locked->save();
        });

        return redirect()->route('supplier.restock.show', $token)
            ->with('info', 'Bạn đã từ chối yêu cầu nhập hàng này. Cửa hàng sẽ được thông báo.');
    }
}
