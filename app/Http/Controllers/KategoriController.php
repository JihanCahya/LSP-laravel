<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    public function index()
    {
        $title = 'Kelola kategori';
        $user = session('user');
        return view('kategori', compact('title', 'user'));
    }

    public function get_data()
    {
        $result = DB::table('kategori')->get();
        return response()->json($result);
    }

    public function get_data_id(Request $request)
    {
        $id = $request->id;
        $result = DB::table('kategori')
            ->where('id', $id)
            ->get();

        return response()->json($result);
    }

    public function insert_data(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255|unique:kategori,name',
            'keterangan' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        try {
            DB::table('kategori')->insert([
                'name' => $request->nama,
                'keterangan' => $request->keterangan,
                'created_at' => now()
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menambahkan data']);
        }

        return response()->json(['success' => 'Data berhasil ditambahkan']);
    }

    public function edit_data(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255|unique:kategori,name,' . $request->id,
            'keterangan' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $data = DB::table('kategori')->where('id', $request->id)->first();
        if ($request->nama == $data->name && $request->keterangan == $data->keterangan) {
            return response()->json(['nothing' => 'Tidak ada perubahan data']);
        } else {
            try {
                DB::table('kategori')
                    ->where('id', $request->id)
                    ->update([
                        'name' => $request->nama,
                        'keterangan' => $request->keterangan,
                        'updated_at' => now()
                    ]);


                return response()->json(['success' => 'Data berhasil diupdate']);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Gagal mengupdate data']);
            }
        }
    }

    public function delete_data(Request $request)
    {
        try {
            DB::table('kategori')->where('id', $request->id)->delete();
            return response()->json(['success' => 'Data berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menghapus data']);
        }
    }
}
