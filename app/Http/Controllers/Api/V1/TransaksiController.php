<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    /**
     * Get all transactions
     */
    public function index(Request $request)
    {
        $query = Transaksi::query();

        // Filter by date range if provided
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('tanggal', [
                $request->start_date,
                $request->end_date,
            ]);
        }

        // Filter by user if provided
        if ($request->has('id_user')) {
            $query->where('id_user', $request->id_user);
        }

        // Sort by latest first
        $query->orderBy('id_transaksi', 'desc');

        $perPage = $request->get('per_page', 15);
        $transaksis = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $transaksis,
        ]);
    }

    /**
     * Get single transaction
     */
    public function show($id)
    {
        $transaksi = Transaksi::findOrFail($id, 'id_transaksi');

        return response()->json([
            'success' => true,
            'data' => $transaksi,
        ]);
    }

    /**
     * Create new transaction
     */
    public function store(Request $request)
    {
        $request->validate([
            'total' => 'required|numeric|min:0',
            'id_user' => 'required|exists:users,id',
            'tanggal' => 'required|date',
        ]);

        $transaksi = Transaksi::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil dibuat',
            'data' => $transaksi,
        ], 201);
    }

    /**
     * Update transaction
     */
    public function update(Request $request, $id)
    {
        $transaksi = Transaksi::findOrFail($id, 'id_transaksi');

        $request->validate([
            'total' => 'numeric|min:0',
            'tanggal' => 'date',
        ]);

        $transaksi->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil diupdate',
            'data' => $transaksi,
        ]);
    }

    /**
     * Delete transaction
     */
    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id, 'id_transaksi');
        $transaksi->delete();

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil dihapus',
        ]);
    }

    /**
     * Get transaction summary by date
     */
    public function summary(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $summary = Transaksi::whereBetween('tanggal', [
            $request->start_date,
            $request->end_date,
        ])
            ->select(
                DB::raw('DATE(tanggal) as tanggal'),
                DB::raw('COUNT(*) as jumlah_transaksi'),
                DB::raw('SUM(total) as total_penjualan')
            )
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $summary,
        ]);
    }

    /**
     * Get daily report
     */
    public function dailyReport(Request $request)
    {
        $date = $request->get('date', now()->format('Y-m-d'));

        $total = Transaksi::whereDate('tanggal', $date)
            ->sum('total');

        $count = Transaksi::whereDate('tanggal', $date)
            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'tanggal' => $date,
                'jumlah_transaksi' => $count,
                'total_penjualan' => $total,
            ],
        ]);
    }
}
