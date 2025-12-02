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
    //========================================
    // STEP 1: PERSIAPAN CHECKOUT
    // User memilih item dari keranjang untuk checkout
    //========================================
    public function prepareCheckout(Request $request)
    {
        // Validasi: cek apakah ada item yang dipilih
        if (!$request->items || count($request->items) === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Pilih produk dahulu'
            ], 400);
        }

        // Simpan ID items ke session untuk digunakan di halaman checkout
        session(['checkout_items' => $request->items]);

        // Redirect ke halaman preview checkout
        return response()->json([
            'success' => true,
            'redirect' => route('pembeli.checkout.preview')
        ]);
    }

    //========================================
    // STEP 2: PREVIEW CHECKOUT
    // Menampilkan detail produk sebelum pembayaran
    //========================================
    public function checkoutPreview()
    {
        // Ambil data pembeli yang sedang login
        $pembeli = Auth::guard('pembeli')->user();

        // Ambil ID items dari session yang disimpan di prepareCheckout
        $ids = session('checkout_items', []);

        // Validasi: cek apakah ada item di session
        if (empty($ids)) {
            return redirect()->route('keranjang.index')
                ->with('error', 'Tidak ada produk yang dipilih');
        }

        // Query database: ambil produk dari keranjang beserta detail karya seni
        $produk = Keranjang::with('karya')
            ->whereIn('id_keranjang', $ids)
            ->where('id_pembeli', $pembeli->id_pembeli)
            ->get();

        // Validasi: cek apakah produk ditemukan
        if ($produk->isEmpty()) {
            return redirect()->route('keranjang.index')
                ->with('error', 'Produk tidak ditemukan');
        }

        // Hitung total harga semua produk
        $total = 0;
        foreach ($produk as $item) {
            $harga = (int) ($item->karya->harga ?? 0);
            $jumlah = (int) $item->jumlah;
            $total += $harga * $jumlah;
        }

        // Tampilkan halaman checkout dengan data produk
        return view('pembeli.checkout', [
            'pembeli' => $pembeli,
            'produk' => $produk,
            'total' => $total,
            'ids' => $ids
        ]);
    }

    //========================================
    // STEP 3: PROSES PEMBAYARAN (MAIN PROCESS)
    // User klik tombol "Bayar Sekarang"
    //========================================
    public function bayar(Request $request)
    {
        //--- INISIALISASI DATA ---//
        // Ambil data pembeli yang sedang login
        $pembeli = Auth::guard('pembeli')->user();

        // Ambil ID items yang akan dibayar dari request
        $ids = $request->input('ids', []);

        //--- VALIDASI INPUT ---//
        if (!$ids || count($ids) === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada produk yang dipilih'
            ], 400);
        }

        //--- QUERY DATABASE: AMBIL ITEMS DARI KERANJANG ---//
        $items = Keranjang::with('karya')
            ->whereIn('id_keranjang', $ids)
            ->where('id_pembeli', $pembeli->id_pembeli)
            ->get();

        //--- VALIDASI: CEK APAKAH ITEMS DITEMUKAN ---//
        if ($items->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }

        //--- GENERATE ORDER ID UNIK ---//
        // Format: ORDER-{id_pembeli}-{timestamp}-{random}
        $orderId = 'ORDER-' . $pembeli->id_pembeli . '-' . time() . '-' . rand(100, 999);

        //--- INISIALISASI VARIABEL ---//
        $totalAmount = 0;      // Total harga semua items
        $itemDetails = [];      // Array untuk dikirim ke Midtrans
        $transaksiIds = [];     // Array untuk menyimpan ID transaksi

        //--- MULAI DATABASE TRANSACTION ---//
        // Semua operasi database akan di-rollback jika ada error
        DB::beginTransaction();
        try {
            //--- LOOP SETIAP ITEM ---//
            foreach ($items as $item) {
                $karya = $item->karya;
                $quantity = (int) $item->jumlah;

                //--- VALIDASI STOK ---//
                if ($karya->stok < $quantity) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Stok '{$karya->nama_karya}' tidak cukup. Sisa stok: {$karya->stok}"
                    ], 400);
                }

                //--- HITUNG SUBTOTAL ---//
                $namaProduk = $karya->nama_karya ?? "Produk Seni";
                $harga = (int) ($karya->harga ?? 0);
                $subtotal = $harga * $quantity;
                $totalAmount += $subtotal;

                //--- SIMPAN TRANSAKSI KE DATABASE (STATUS: PENDING) ---//
                $transaksi = Transaksi::create([
                    'order_id' => $orderId,
                    'id_pembeli' => $pembeli->id_pembeli,
                    'kode_seni' => $item->kode_seni,
                    'tanggal_jual' => now(),
                    'harga' => $subtotal,
                    'jumlah' => $quantity,
                    'status' => 'pending',  // Status awal: pending
                    'cart_id' => $item->id_keranjang
                ]);

                // Simpan ID transaksi untuk update snap_token nanti
                $transaksiIds[] = $transaksi->no_transaksi;

                //--- SIAPKAN DATA ITEM UNTUK MIDTRANS ---//
                $itemDetails[] = [
                    'id' => $karya->kode_seni ?? 'ITEM-' . rand(100, 999),
                    'price' => $harga,
                    'quantity' => $quantity,
                    'name' => substr($namaProduk, 0, 50)
                ];
            }

            //--- VALIDASI TOTAL AMOUNT ---//
            if ($totalAmount <= 0) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Total pembayaran tidak valid.'
                ], 400);
            }

            //--- SIAPKAN DATA CUSTOMER UNTUK MIDTRANS ---//
            $customerDetails = [
                'first_name' => $pembeli->nama ?: 'Pembeli',
                'email' => $pembeli->email ?: 'noemail@example.com',
                'phone' => $pembeli->no_hp ?: '0000000000',
            ];

            //========================================
            //🚀 API CALL KE MIDTRANS (PENGIRIMAN DATA)
            //========================================
            // Buat instance MidtransService
            $midtrans = new \App\Services\MidtransService();

            // Kirim data ke Midtrans API dan dapatkan Snap Token
            // Di dalam method ini terjadi HTTP POST ke Midtrans
            $snapToken = $midtrans->createTransaction($orderId, (int) $totalAmount, $customerDetails, $itemDetails);
            //========================================

            //--- VALIDASI: CEK APAKAH TOKEN BERHASIL DIBUAT ---//
            if (!$snapToken) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Token pembayaran tidak bisa dibuat.'
                ], 500);
            }

            //--- UPDATE DATABASE: SIMPAN SNAP TOKEN ---//
            Transaksi::whereIn('no_transaksi', $transaksiIds)->update(['snap_token' => $snapToken]);

            //--- COMMIT DATABASE TRANSACTION ---//
            DB::commit();

            //--- LOGGING: CATAT TRANSAKSI BERHASIL ---//
            Log::info('Transaction Created Successfully', [
                'order_id' => $orderId,
                'total' => $totalAmount,
                'items_count' => count($items)
            ]);

            //--- SIMPAN DATA PEMBAYARAN KE SESSION ---//
            session([
                'payment_data' => [
                    'order_id' => $orderId,
                    'snap_token' => $snapToken,
                    'total' => $totalAmount,
                    'cart_ids' => $ids
                ]
            ]);

            //--- RESPONSE: KIRIM TOKEN KE FRONTEND ---//
            // Frontend akan gunakan snap_token ini untuk menampilkan popup Midtrans
            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'order_id' => $orderId
            ]);

        } catch (\Exception $e) {
            //--- ERROR HANDLING ---//
            DB::rollBack();

            // Hapus transaksi yang sudah dibuat jika ada error
            if (!empty($transaksiIds)) {
                Transaksi::whereIn('no_transaksi', $transaksiIds)->delete();
            }

            // Log error untuk debugging
            Log::error('Payment Creation Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    //========================================
    // STEP 4: WEBHOOK CALLBACK DARI MIDTRANS
    // Midtrans akan POST data ke endpoint ini saat status pembayaran berubah
    //========================================
    public function paymentCallback(Request $request)
    {
        //--- LOGGING: CATAT SEMUA DATA YANG DIKIRIM MIDTRANS ---//
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

        //--- HANDLE TEST NOTIFICATION DARI MIDTRANS DASHBOARD ---//
        if (!$request->has('order_id') || !$request->has('signature_key')) {
            Log::info('Test notification received (no order_id or signature)');
            return response()->json([
                'status' => 'success',
                'message' => 'Webhook endpoint is ready and accessible'
            ], 200);
        }

        try {
            //--- EKSTRAK DATA DARI REQUEST ---//
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

            //--- VERIFIKASI SIGNATURE (SECURITY) ---//
            // Memastikan request benar-benar dari Midtrans
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

            //--- QUERY DATABASE: AMBIL TRANSAKSI BERDASARKAN ORDER ID ---//
            $transaksiList = Transaksi::where('order_id', $orderId)->get();

            if ($transaksiList->isEmpty()) {
                Log::error('Transaction Not Found', ['order_id' => $orderId]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Transaction not found'
                ], 404);
            }

            //--- TENTUKAN STATUS BARU BERDASARKAN TRANSACTION STATUS ---//
            $newStatus = 'pending';
            if (in_array($transactionStatus, ['capture', 'settlement'])) {
                $newStatus = 'success';  // Pembayaran berhasil
            } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                $newStatus = 'failed';   // Pembayaran gagal
            }

            Log::info('Status Determination', [
                'transaction_status' => $transactionStatus,
                'new_status' => $newStatus
            ]);

            //--- UPDATE DATABASE ---//
            DB::beginTransaction();
            try {
                //--- LOOP SETIAP TRANSAKSI ---//
                foreach ($transaksiList as $transaksi) {
                    Log::info('Processing Transaction', [
                        'no_transaksi' => $transaksi->no_transaksi,
                        'old_status' => $transaksi->status,
                        'new_status' => $newStatus
                    ]);

                    //--- UPDATE STATUS TRANSAKSI ---//
                    $transaksi->status = $newStatus;
                    $transaksi->payment_type = $paymentType;

                    //--- JIKA PEMBAYARAN BERHASIL ---//
                    if ($newStatus === 'success') {
                        // Catat waktu pembayaran
                        $transaksi->paid_at = now();

                        //--- UPDATE STOK KARYA SENI ---//
                        $karya = KaryaSeni::where('kode_seni', $transaksi->kode_seni)->first();
                        if ($karya) {
                            // Kurangi stok
                            $oldStok = $karya->stok;
                            $newStok = max(0, $karya->stok - $transaksi->jumlah);
                            $karya->stok = $newStok;

                            // Tambah jumlah terjual
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

                        //--- HAPUS DARI KERANJANG ---//
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

                    //--- SIMPAN PERUBAHAN TRANSAKSI ---//
                    $transaksi->save();

                    Log::info('Transaction Updated', [
                        'no_transaksi' => $transaksi->no_transaksi,
                        'final_status' => $transaksi->status
                    ]);
                }

                //--- COMMIT DATABASE TRANSACTION ---//
                DB::commit();

                Log::info('========================================');
                Log::info('WEBHOOK PROCESSING SUCCESS');
                Log::info('Order ID: ' . $orderId);
                Log::info('Final Status: ' . $newStatus);
                Log::info('Transactions Updated: ' . $transaksiList->count());
                Log::info('========================================');

                //--- RESPONSE: BERITAHU MIDTRANS BAHWA WEBHOOK BERHASIL DIPROSES ---//
                return response()->json([
                    'status' => 'success',
                    'message' => 'Notification processed successfully',
                    'order_id' => $orderId,
                    'new_status' => $newStatus
                ], 200);

            } catch (\Exception $e) {
                //--- ERROR HANDLING: ROLLBACK DATABASE ---//
                DB::rollBack();

                Log::error('Database Error During Webhook Processing', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'order_id' => $orderId
                ]);

                throw $e;
            }

        } catch (\Exception $e) {
            //--- GENERAL ERROR HANDLING ---//
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

    //========================================
    // STEP 5: HALAMAN SUKSES PEMBAYARAN
    // User diarahkan ke halaman ini setelah selesai dari Midtrans
    //========================================
    public function paymentSuccess(Request $request)
    {
        //--- AMBIL ORDER ID DARI REQUEST ---//
        $orderId = $request->query('order_id') ?? $request->order_id;

        //--- VALIDASI: CEK ORDER ID ---//
        if (!$orderId) {
            return redirect()->route('pembeli.dashboard')
                ->with('error', 'Order ID tidak ditemukan');
        }

        //--- QUERY DATABASE: AMBIL TRANSAKSI ---//
        $transaksi = Transaksi::with('karya')
            ->where('order_id', $orderId)
            ->where('id_pembeli', Auth::guard('pembeli')->id())
            ->get();

        //--- VALIDASI: CEK TRANSAKSI ---//
        if ($transaksi->isEmpty()) {
            return redirect()->route('pembeli.dashboard')
                ->with('error', 'Transaksi tidak ditemukan');
        }

        //--- HITUNG TOTAL ---//
        $total = $transaksi->sum('harga');

        //--- CLEAR SESSION ---//
        session()->forget(['checkout_items', 'payment_data']);

        //--- LOGGING ---//
        Log::info('Payment Success Page Accessed', [
            'order_id' => $orderId,
            'pembeli_id' => Auth::guard('pembeli')->id()
        ]);

        //--- TAMPILKAN HALAMAN SUCCESS ---//
        return view('pembeli.payment-succsess', compact('transaksi', 'orderId', 'total'));
    }

    //========================================
    // HALAMAN DAFTAR PESANAN SAYA
    // User bisa melihat semua pesanannya
    //========================================
    public function myOrders()
    {
        //--- AMBIL DATA PEMBELI ---//
        $pembeli = Auth::guard('pembeli')->user();

        //--- QUERY DATABASE: AMBIL SEMUA PESANAN ---//
        $orders = Transaksi::with('karya')
            ->where('id_pembeli', $pembeli->id_pembeli)
            ->orderBy('tanggal_jual', 'desc')
            ->get();

        //--- TAMPILKAN HALAMAN MY ORDERS ---//
        return view('pembeli.myorder', compact('orders'));
    }
}