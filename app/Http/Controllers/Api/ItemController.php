<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Get all items
     */
    public function index(Request $request)
    {
        $query = Item::query();

        // Filter by kategori if provided
        if ($request->has('kategori_id')) {
            $query->where('id_kategori', $request->kategori_id);
        }

        // Filter by nama if provided
        if ($request->has('nama')) {
            $query->where('nama', 'like', '%' . $request->nama . '%');
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $items = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $items,
        ]);
    }

    /**
     * Get single item
     */
    public function show($id)
    {
        $item = Item::findOrFail($id, 'id_item');

        return response()->json([
            'success' => true,
            'data' => $item,
        ]);
    }

    /**
     * Create new item
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'id_satuan' => 'required|exists:satuan,id_satuan',
            'id_supplier' => 'required|exists:supplier,id_supplier',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
        ]);

        $item = Item::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Item berhasil ditambahkan',
            'data' => $item,
        ], 201);
    }

    /**
     * Update item
     */
    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id, 'id_item');

        $request->validate([
            'nama' => 'string|max:255',
            'id_kategori' => 'exists:kategori,id_kategori',
            'id_satuan' => 'exists:satuan,id_satuan',
            'id_supplier' => 'exists:supplier,id_supplier',
            'harga_beli' => 'numeric|min:0',
            'harga_jual' => 'numeric|min:0',
            'stok' => 'integer|min:0',
        ]);

        $item->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Item berhasil diupdate',
            'data' => $item,
        ]);
    }

    /**
     * Delete item
     */
    public function destroy($id)
    {
        $item = Item::findOrFail($id, 'id_item');
        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item berhasil dihapus',
        ]);
    }

    /**
     * Get items with low stock
     */
    public function lowStock(Request $request)
    {
        $threshold = $request->get('threshold', 10);
        $items = Item::where('stok', '<=', $threshold)->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $items,
        ]);
    }
}
