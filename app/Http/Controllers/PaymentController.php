<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Keranjang;
use App\Models\KaryaSeni;
use App\Models\Transaksi;

class PaymentController extends Controller
{
    public function prepareCheckout(Request $request)
    {
        if (!$request->items || count($request->items) === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Pilih produk dahulu'
            ], 400);
        }

        session(['checkout_items' => $request->items]);

        return response()->json([
            'success' => true,
            'redirect' => route('pembeli.checkout.preview')
        ]);
    }

    public function checkoutPreview()
    {
        $pembeli = Auth::guard('pembeli')->user();
        $ids = session('checkout_items', []);

        if (empty($ids)) {
            return redirect()->route('keranjang.index')
                ->with('error', 'Tidak ada produk yang dipilih');
        }

        $produk = Keranjang::with('karya')
            ->whereIn('id_keranjang', $ids)
            ->where('id_pembeli', $pembeli->id_pembeli)
            ->get();

        if ($produk->isEmpty()) {
            return redirect()->route('keranjang.index')
                ->with('error', 'Produk tidak ditemukan');
        }

        $total = 0;
        foreach ($produk as $item) {
            $harga = (int) ($item->karya->harga ?? 0);
            $jumlah = (int) $item->jumlah;
            $total += $harga * $jumlah;
        }

        return view('pembeli.checkout', [
            'pembeli' => $pembeli,
            'produk' => $produk,
            'total' => $total,
            'ids' => $ids
        ]);
    }

    public function bayar(Request $request)
    {
        $pembeli = Auth::guard('pembeli')->user();
        $ids = $request->input('ids', []);

        if (!$ids || count($ids) === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada produk yang dipilih'
            ], 400);
        }

        $items = Keranjang::with('karya')
            ->whereIn('id_keranjang', $ids)
            ->where('id_pembeli', $pembeli->id_pembeli)
            ->get();

        if ($items->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }

        $orderId = 'ORDER-' . $pembeli->id_pembeli . '-' . time() . '-' . rand(100, 999);

        $totalAmount = 0;
        $itemDetails = [];
        $transaksiIds = [];

        DB::beginTransaction();
        try {
            foreach ($items as $item) {
                $karya = $item->karya;
                $quantity = (int) $item->jumlah;

                if ($karya->stok < $quantity) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Stok '{$karya->nama_karya}' tidak cukup. Sisa stok: {$karya->stok}"
                    ], 400);
                }

                $namaProduk = $karya->nama_karya ?? "Produk Seni";
                $harga = (int) ($karya->harga ?? 0);
                $subtotal = $harga * $quantity;
                $totalAmount += $subtotal;

                $transaksi = Transaksi::create([
                    'order_id' => $orderId,
                    'id_pembeli' => $pembeli->id_pembeli,
                    'kode_seni' => $item->kode_seni,
                    'tanggal_jual' => now(),
                    'harga' => $subtotal,
                    'jumlah' => $quantity,
                    'status' => 'pending',
                    'cart_id' => $item->id_keranjang
                ]);

                $transaksiIds[] = $transaksi->no_transaksi;

                $itemDetails[] = [
                    'id' => $karya->kode_seni ?? 'ITEM-' . rand(100, 999),
                    'price' => $harga,
                    'quantity' => $quantity,
                    'name' => substr($namaProduk, 0, 50)
                ];
            }

            if ($totalAmount <= 0) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Total pembayaran tidak valid.'
                ], 400);
            }

            $customerDetails = [
                'first_name' => $pembeli->nama ?: 'Pembeli',
                'email' => $pembeli->email ?: 'noemail@example.com',
                'phone' => $pembeli->no_hp ?: '0000000000',
            ];

            $midtrans = new \App\Services\MidtransService();
            $snapToken = $midtrans->createTransaction($orderId, (int) $totalAmount, $customerDetails, $itemDetails);

            if (!$snapToken) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Token pembayaran tidak bisa dibuat.'
                ], 500);
            }

            Transaksi::whereIn('no_transaksi', $transaksiIds)->update(['snap_token' => $snapToken]);

            DB::commit();

            Log::info('Transaction Created Successfully', [
                'order_id' => $orderId,
                'total' => $totalAmount,
                'items_count' => count($items)
            ]);

            session([
                'payment_data' => [
                    'order_id' => $orderId,
                    'snap_token' => $snapToken,
                    'total' => $totalAmount,
                    'cart_ids' => $ids
                ]
            ]);

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'order_id' => $orderId
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            if (!empty($transaksiIds)) {
                Transaksi::whereIn('no_transaksi', $transaksiIds)->delete();
            }

            Log::error('Payment Creation Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * WEBHOOK CALLBACK dari Midtrans
     * Method ini dipanggil otomatis oleh server Midtrans saat status pembayaran berubah
     */
    public function paymentCallback(Request $request)
    {
        Log::info('========================================');
        Log::info('MIDTRANS WEBHOOK RECEIVED');
        Log::info('Timestamp: ' . now());
        Log::info('Method: ' . $request->method());
        Log::info('URL: ' . $request->fullUrl());
        Log::info('IP: ' . $request->ip());
        Log::info('User Agent: ' . $request->userAgent());
        Log::info('Headers:', $request->headers->all());
        Log::info('Body:', $request->all());
        Log::info('========================================');

        // HANDLE TEST NOTIFICATION dari Midtrans Dashboard
        if (!$request->has('order_id') || !$request->has('signature_key')) {
            Log::info('Test notification received (no order_id or signature)');
            return response()->json([
                'status' => 'success',
                'message' => 'Webhook endpoint is ready and accessible'
            ], 200);
        }

        try {
            $orderId = $request->order_id;
            $statusCode = (string) $request->status_code;
            $grossAmount = (string) $request->gross_amount;
            $transactionStatus = $request->transaction_status;
            $paymentType = $request->payment_type ?? 'unknown';

            Log::info('Processing Webhook', [
                'order_id' => $orderId,
                'status_code' => $statusCode,
                'gross_amount' => $grossAmount,
                'transaction_status' => $transactionStatus,
                'payment_type' => $paymentType
            ]);

            // Verifikasi signature
            $serverKey = trim(env('MIDTRANS_SERVER_KEY'));
            $signatureKey = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

            if ($signatureKey !== $request->signature_key) {
                // Skip validation untuk test order
                if (str_starts_with($orderId, 'TEST') || str_starts_with($orderId, 'test')) {
                    Log::warning('Test order detected, skipping signature validation');
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Test notification received'
                    ], 200);
                }

                Log::error('Invalid Signature', [
                    'order_id' => $orderId,
                    'expected' => $signatureKey,
                    'received' => $request->signature_key
                ]);

                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid signature'
                ], 200); // Return 200 agar Midtrans tidak retry
            }

            // Ambil transaksi
            $transaksiList = Transaksi::where('order_id', $orderId)->get();

            if ($transaksiList->isEmpty()) {
                Log::error('Transaction Not Found', ['order_id' => $orderId]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Transaction not found'
                ], 404);
            }

            // Tentukan status baru
            $newStatus = 'pending';
            if (in_array($transactionStatus, ['capture', 'settlement'])) {
                $newStatus = 'success';
            } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                $newStatus = 'failed';
            }

            Log::info('Status Determination', [
                'transaction_status' => $transactionStatus,
                'new_status' => $newStatus
            ]);

            // Update database
            DB::beginTransaction();
            try {
                foreach ($transaksiList as $transaksi) {
                    Log::info('Processing Transaction', [
                        'no_transaksi' => $transaksi->no_transaksi,
                        'old_status' => $transaksi->status,
                        'new_status' => $newStatus
                    ]);

                    $transaksi->status = $newStatus;
                    $transaksi->payment_type = $paymentType;

                    if ($newStatus === 'success') {
                        $transaksi->paid_at = now();

                        // Update stok karya
                        $karya = KaryaSeni::where('kode_seni', $transaksi->kode_seni)->first();
                        if ($karya) {
                            $oldStok = $karya->stok;
                            $newStok = max(0, $karya->stok - $transaksi->jumlah);
                            $karya->stok = $newStok;

                            $oldTerjual = $karya->terjual ?? 0;
                            $karya->terjual = $oldTerjual + $transaksi->jumlah;

                            $karya->save();

                            Log::info('Stock Updated Successfully', [
                                'kode_seni' => $karya->kode_seni,
                                'nama_karya' => $karya->nama_karya,
                                'old_stock' => $oldStok,
                                'new_stock' => $newStok,
                                'quantity_sold' => $transaksi->jumlah,
                                'old_terjual' => $oldTerjual,
                                'new_terjual' => $karya->terjual
                            ]);
                        } else {
                            Log::warning('Karya Not Found', [
                                'kode_seni' => $transaksi->kode_seni
                            ]);
                        }

                        // Hapus dari keranjang
                        if ($transaksi->cart_id) {
                            $deletedCount = Keranjang::where('id_keranjang', $transaksi->cart_id)->delete();

                            Log::info('Cart Item Deleted', [
                                'cart_id' => $transaksi->cart_id,
                                'deleted_count' => $deletedCount
                            ]);
                        } else {
                            Log::warning('No cart_id found for transaction', [
                                'no_transaksi' => $transaksi->no_transaksi
                            ]);
                        }
                    }

                    $transaksi->save();

                    Log::info('Transaction Updated', [
                        'no_transaksi' => $transaksi->no_transaksi,
                        'final_status' => $transaksi->status
                    ]);
                }

                DB::commit();

                Log::info('========================================');
                Log::info('WEBHOOK PROCESSING SUCCESS');
                Log::info('Order ID: ' . $orderId);
                Log::info('Final Status: ' . $newStatus);
                Log::info('Transactions Updated: ' . $transaksiList->count());
                Log::info('========================================');

                return response()->json([
                    'status' => 'success',
                    'message' => 'Notification processed successfully',
                    'order_id' => $orderId,
                    'new_status' => $newStatus
                ], 200);

            } catch (\Exception $e) {
                DB::rollBack();

                Log::error('Database Error During Webhook Processing', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'order_id' => $orderId
                ]);

                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('========================================');
            Log::error('WEBHOOK ERROR');
            Log::error('Error: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            Log::error('========================================');

            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function paymentSuccess(Request $request)
    {
        $orderId = $request->query('order_id') ?? $request->order_id;

        if (!$orderId) {
            return redirect()->route('pembeli.dashboard')
                ->with('error', 'Order ID tidak ditemukan');
        }

        $transaksi = Transaksi::with('karya')
            ->where('order_id', $orderId)
            ->where('id_pembeli', Auth::guard('pembeli')->id())
            ->get();

        if ($transaksi->isEmpty()) {
            return redirect()->route('pembeli.dashboard')
                ->with('error', 'Transaksi tidak ditemukan');
        }

        $total = $transaksi->sum('harga');

        // Clear session
        session()->forget(['checkout_items', 'payment_data']);

        Log::info('Payment Success Page Accessed', [
            'order_id' => $orderId,
            'pembeli_id' => Auth::guard('pembeli')->id()
        ]);

        return view('pembeli.payment-succsess', compact('transaksi', 'orderId', 'total'));
    }

    public function myOrders()
    {
        $pembeli = Auth::guard('pembeli')->user();

        $orders = Transaksi::with('karya')
            ->where('id_pembeli', $pembeli->id_pembeli)
            ->orderBy('tanggal_jual', 'desc')
            ->get();

        return view('pembeli.myorder', compact('orders'));
    }
}