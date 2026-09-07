<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Get all suppliers
     */
    public function index(Request $request)
    {
        $query = Supplier::query();

        if ($request->has('nama')) {
            $query->where('nama', 'like', '%' . $request->nama . '%');
        }

        $perPage = $request->get('per_page', 15);
        $suppliers = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $suppliers,
        ]);
    }

    /**
     * Get single supplier
     */
    public function show($id)
    {
        $supplier = Supplier::findOrFail($id, 'id_supplier');

        return response()->json([
            'success' => true,
            'data' => $supplier,
        ]);
    }

    /**
     * Create new supplier
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kontak' => 'string|max:255',
            'alamat' => 'string|max:255',
            'no_telepon' => 'string|max:20',
            'email' => 'email|nullable',
        ]);

        $supplier = Supplier::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Supplier berhasil ditambahkan',
            'data' => $supplier,
        ], 201);
    }

    /**
     * Update supplier
     */
    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id, 'id_supplier');

        $request->validate([
            'nama' => 'string|max:255',
            'kontak' => 'string|max:255',
            'alamat' => 'string|max:255',
            'no_telepon' => 'string|max:20',
            'email' => 'email|nullable',
        ]);

        $supplier->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Supplier berhasil diupdate',
            'data' => $supplier,
        ]);
    }

    /**
     * Delete supplier
     */
    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id, 'id_supplier');
        $supplier->delete();

        return response()->json([
            'success' => true,
            'message' => 'Supplier berhasil dihapus',
        ]);
    }
}
