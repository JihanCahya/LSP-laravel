<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SuratController extends Controller
{

    public function index()
    {
        $title = 'Kelola surat';
        $select = DB::table('kategori')->get();
        $user = session('user');
        return view('surat', compact('title', 'select', 'user'));
    }

    public function get_data()
    {
        $result = DB::table('surat')
            ->join('kategori', 'surat.id_kategori', '=', 'kategori.id')
            ->select('surat.*', 'kategori.name')
            ->get();
        return response()->json($result);
    }

    public function get_data_id(Request $request)
    {
        $result = DB::table('surat')
            ->join('kategori', 'surat.id_kategori', '=', 'kategori.id')
            ->select('surat.*', 'kategori.name')
            ->where('surat.id', $request->id)
            ->get();

        return response()->json($result);
    }

    public function insert_data(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nomor' => 'required|string|max:255|unique:surat,nomor',
            'kategori' => 'required|string|max:255',
            'keterangan' => 'required|string',
            'file' => 'required|mimes:pdf|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        try {
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('files'), $fileName);
            } else {
                return response()->json(['error' => 'Gambar tidak ditemukan']);
            }

            DB::table('surat')->insert([
                'nomor' => $request->nomor,
                'id_kategori' => $request->kategori,
                'judul' => $request->keterangan,
                'file_name' => $fileName,
                'created_at' => now()
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menambahkan data']);
        }

        return response()->json(['success' => 'Data berhasil ditambahkan']);
    }

    public function edit_data(Request $request)
    {
        $surat = DB::table('surat')->first();
        $validator = Validator::make($request->all(), [
            'file2' => 'mimes:pdf|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        try {
            if ($request->hasFile('file2')) {
                $file = $request->file('file2');
                $fileName = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('files'), $fileName);

                $filePath = public_path('files/' . $surat->file_name);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            } else {
                return response()->json(['nothing' => 'Tidak ada perubahan']);
            }

            DB::table('surat')
                ->where('id', $request->id)
                ->update([
                    'file_name' => $fileName,
                    'updated_at' => now()
                ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menambahkan data']);
        }

        return response()->json(['success' => 'Data berhasil ditambahkan']);
    }

    public function delete_data(Request $request)
    {
        try {
            $filePath = public_path('files/' . $request->file);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            DB::table('surat')->where('id', $request->id)->delete();
            return response()->json(['success' => 'Data berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menghapus data']);
        }
    }

    public function downloadFile($file_name)
    {
        $file_path = public_path('files/' . $file_name);

        if (file_exists($file_path)) {
            return response()->download($file_path);
        } else {
            abort(404, 'File not found');
        }
    }
}
