<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengembalian;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PengembalianController extends Controller
{
    public function index()
    {
        $pengembalian = Pengembalian::with('peminjaman')->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $pengembalian,
            'message' => 'List semua pengembalian'
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'peminjaman_id' => 'required|exists:peminjaman,id',
            'tanggal_pengembalian' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Simpan data pengembalian
        $pengembalian = new Pengembalian;
        $pengembalian->peminjaman_id = $request->peminjaman_id;
        $pengembalian->tanggal_pengembalian = $request->tanggal_pengembalian;
        $pengembalian->save();

        // Update status di tabel peminjaman
        $peminjaman = Peminjaman::find($request->peminjaman_id);
        if ($peminjaman) {
            $peminjaman->status = 'dikembalikan';
            $peminjaman->tanggal_pengembalian = $request->tanggal_pengembalian;
            $peminjaman->save();
        }

        return response()->json([
            'success' => true,
            'data' => $pengembalian,
            'message' => 'Pengembalian berhasil ditambahkan'
        ], 201);
    }

    public function show($id)
    {
        $pengembalian = Pengembalian::with('peminjaman')->find($id);

        if (!$pengembalian) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $pengembalian,
            'message' => 'Detail pengembalian'
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'peminjaman_id' => 'required|exists:peminjaman,id',
            'tanggal_pengembalian' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $pengembalian = Pengembalian::find($id);
        if (!$pengembalian) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $pengembalian->peminjaman_id = $request->peminjaman_id;
        $pengembalian->tanggal_pengembalian = $request->tanggal_pengembalian;
        $pengembalian->save();

        // Update status di tabel peminjaman
        $peminjaman = Peminjaman::find($request->peminjaman_id);
        if ($peminjaman) {
            $peminjaman->status = 'dikembalikan';
            $peminjaman->tanggal_pengembalian = $request->tanggal_pengembalian;
            $peminjaman->save();
        }

        return response()->json([
            'success' => true,
            'data' => $pengembalian,
            'message' => 'Pengembalian berhasil diupdate'
        ], 200);
    }

    public function destroy($id)
    {
        $pengembalian = Pengembalian::find($id);

        if (!$pengembalian) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $pengembalian->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pengembalian berhasil dihapus',
            'data' => []
        ], 200);
    }
}