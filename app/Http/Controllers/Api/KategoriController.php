<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategoris = Kategori::latest()->get();

        $res = [
            'success' => true,
            'data' => $kategoris,
            'message' => 'List posts',
        ];
        return response()->json($res, 200);
    }

    /**
     * Show the form for creating a new resource.
     */


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:155|unique:kategoris',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }


        $kategori = new Kategori;
        $kategori->nama = $request->nama;
        $kategori->save();

        $res = [
            'success' => true,
            'data' => $kategori,
            'message' => 'Kategori Berhasil Ditambahkan',
        ];
        return response()->json($res, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
         $kategori = Kategori::find($id);
        if (! $kategori) {
            return response()->json([
                'message' => 'Data NOt Found',
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $kategori,
            'message' => 'Show Kategori Detail',
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:155|unique:kategoris,nama,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }


        $kategori = Kategori::find($id);
        $kategori->nama = $request->nama;
        $kategori->save();

        $res = [
            'success' => true,
            'data' => $kategori,
            'message' => 'Kategori berhasil diubah',
        ];
        return response()->json($res, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $kategori = Kategori::find($id);



        $kategori->delete();
        return response()->json([
            'data' => [],
            'message' => 'Kategori berhasil dihapus',
            'success' => true
        ]);
    }
}