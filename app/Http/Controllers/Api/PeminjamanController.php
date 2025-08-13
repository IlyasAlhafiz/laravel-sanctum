<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjaman = Peminjaman::latest()->get();

        return response()->json([
            'success' => true,
            'data' => $peminjaman,
            'message' => 'List semua peminjaman'
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'buku_id' => 'required|exists:bukus,id',
            'tanggal_pinjam' => 'required|date',
            'tenggat' => 'required|date|after_or_equal:tanggal_pinjam',
            'status' => 'nullable|string|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $peminjaman = new Peminjaman;
        $peminjaman->user_id = $request->user_id;
        $peminjaman->buku_id = $request->buku_id;
        $peminjaman->tanggal_pinjam = $request->tanggal_pinjam;
        $peminjaman->tenggat = $request->tenggat;
        $peminjaman->tanggal_pengembalian = $request->tanggal_pengembalian ?? null;
        $peminjaman->status = $request->status;
        $peminjaman->save();

        return response()->json([
            'success' => true,
            'data' => $peminjaman,
            'message' => 'Peminjaman berhasil ditambahkan'
        ], 201);
    }

    public function show($id)
    {
        $peminjaman = Peminjaman::find($id);

        if (!$peminjaman) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $peminjaman,
            'message' => 'Detail peminjaman'
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'buku_id' => 'required|exists:bukus,id',
            'tanggal_pinjam' => 'required|date',
            'tenggat' => 'required|date|after_or_equal:tanggal_pinjam',
            'tanggal_pengembalian' => 'nullable|date|after_or_equal:tanggal_pinjam',
            'status' => 'nullable|string|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $peminjaman = Peminjaman::find($id);
        if (!$peminjaman) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $peminjaman->user_id = $request->user_id;
        $peminjaman->buku_id = $request->buku_id;
        $peminjaman->tanggal_pinjam = $request->tanggal_pinjam;
        $peminjaman->tenggat = $request->tenggat;
        $peminjaman->tanggal_pengembalian = $request->tanggal_pengembalian ?? null;
        $peminjaman->status = $request->status;
        $peminjaman->save();

        return response()->json([
            'success' => true,
            'data' => $peminjaman,
            'message' => 'Peminjaman berhasil diupdate'
        ], 200);
    }

    public function destroy($id)
    {
        $peminjaman = Peminjaman::find($id);

        if (!$peminjaman) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $peminjaman->delete();

        return response()->json([
            'success' => true,
            'message' => 'Peminjaman berhasil dihapus',
            'data' => []
        ], 200);
    }
}
