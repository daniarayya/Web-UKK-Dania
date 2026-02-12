<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        
        $query = Kategori::query();
        
        if ($search) {
            $query->where('nama_kategori', 'like', '%' . $search . '%');
        }
        
        $kategori = $query->orderBy('id_kategori', 'desc')->paginate(5);
        
        // Jika request AJAX, kembalikan JSON dengan data kategori
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'kategori' => $kategori
            ]);
        }
        
        return view('admin.kategori', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategoris,nama_kategori'
        ]);

        Kategori::create([
            'nama_kategori' => $request->nama_kategori
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil ditambahkan'
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategoris,nama_kategori,' . $id . ',id_kategori'
        ]);

        $kategori = Kategori::findOrFail($id);
        $kategori->update([
            'nama_kategori' => $request->nama_kategori
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil diperbarui'
        ]);
    }

    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil dihapus'
        ]);
    }
}