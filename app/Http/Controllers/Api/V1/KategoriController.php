<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /**
     * Get all categories
     */
    public function index()
    {
        $kategoris = Kategori::all();

        return response()->json([
            'success' => true,
            'data' => $kategoris,
        ]);
    }

    /**
     * Get single category
     */
    public function show($id)
    {
        $kategori = Kategori::findOrFail($id, 'id_kategori');

        return response()->json([
            'success' => true,
            'data' => $kategori,
        ]);
    }

    /**
     * Create new category
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:kategori,nama',
        ]);

        $kategori = Kategori::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil ditambahkan',
            'data' => $kategori,
        ], 201);
    }

    /**
     * Update category
     */
    public function update(Request $request, $id)
    {
        $kategori = Kategori::findOrFail($id, 'id_kategori');

        $request->validate([
            'nama' => 'string|max:255|unique:kategori,nama,' . $id . ',id_kategori',
        ]);

        $kategori->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil diupdate',
            'data' => $kategori,
        ]);
    }

    /**
     * Delete category
     */
    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id, 'id_kategori');
        $kategori->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil dihapus',
        ]);
    }
}
