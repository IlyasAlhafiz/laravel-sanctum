<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BukuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bukus = Buku::latest()->get();

        $res = [
            'success' => true,
            'data' => $bukus,
            'message' => 'List posts',
        ];
        return response()->json($res, 200);
    }

    private function generateKodeBuku()
    {
        return 'BK-' . str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul'        => 'required|string|max:255|unique:bukus',
            'penulis'      => 'required|string|max:255',
            'penerbit'     => 'required|string|max:255',
            'tahun_terbit' => 'required|integer',
            'stok'         => 'required|integer|min:0',
            'kategori_id'  => 'required|integer',
            'cover'        => 'required|image|mimes:png,jpg,jpeg,webp|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data'    => [],
                'message' => $validator->errors(),
                'success' => false
            ], 400);
        }

        $buku = new Buku;
        $buku->kode_buku    = $this->generateKodeBuku();
        $buku->judul        = $request->judul;
        $buku->penulis      = $request->penulis;
        $buku->penerbit     = $request->penerbit;
        $buku->tahun_terbit = $request->tahun_terbit;
        $buku->stok         = $request->stok;
        $buku->kategori_id  = $request->kategori_id;

        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->store('buku', 'public');
            $buku->cover = $path;
        }

        $buku->save();

        return response()->json([
            'data'    => $buku,
            'message' => 'Buku berhasil ditambahkan.',
            'success' => true
        ], 201);
    }

    /**
     * Display the specified resource.
     */


    /**
     * Show the form for editing the specified resource.
     */


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'judul'        => 'required|string|max:255|unique:bukus,id,' . $id,
            'penulis'      => 'required|string|max:255',
            'penerbit'     => 'required|string|max:255',
            'tahun_terbit' => 'required|integer',
            'stok'         => 'required|integer|min:0',
            'kategori_id'  => 'required|integer',
            'cover'        => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data'    => [],
                'message' => $validator->errors(),
                'success' => false
            ], 400);
        }

        $buku = Buku::find($id);
        $buku->kode_buku    = $this->generateKodeBuku();
        $buku->judul        = $request->judul;
        $buku->penulis      = $request->penulis;
        $buku->penerbit     = $request->penerbit;
        $buku->tahun_terbit = $request->tahun_terbit;
        $buku->stok         = $request->stok;
        $buku->kategori_id  = $request->kategori_id;

        if($request->hasFile('cover')){
            if($buku->cover && Storage::disk('public')->exists($buku->cover)){
                Storage::disk('public')->delete($buku->cover);
            }
            $path = $request->file('cover')->store('posts', 'public');
            $buku->cover = $path;
        }

        $buku->save();

        return response()->json([
            'data'    => $buku,
            'message' => 'Buku berhasil diubah.',
            'success' => true
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $buku = Buku::find($id);
        if (! $buku) {
            return response()->json(['message' => 'Data Not Found'], 404);
        }
        if ($buku->cover && Storage::disk('public')->exists($buku->cover)) {
            Storage::disk('public')->delete($buku->cover);
        }


        $buku->delete();
        return response()->json([
            'data' => [],
            'message' => 'Buku berhasil dihapus',
            'success' => true
        ]);
    }
}
