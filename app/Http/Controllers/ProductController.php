<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProductController extends Controller
{
    /**
     * Menyimpan data produk baru ke database beserta gambar.
     */
    public function store(Request $request)
    {
        // 1. Validasi input form
        $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric',
            'image'       => 'required|image|mimes:jpeg,png,jpg,webp|max:2048', // Maksimal 2MB
        ]);

        // 2. Inisialisasi ImageManager dengan Driver GD (Sesuai modul Versi 4)
        $manager = new ImageManager(new Driver());

        // 3. Proses File Gambar yang Diunggah
        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.jpg';

            $image = $manager->read($file);
            $image->scale(width: 500);

            $destinationPath = storage_path('app/public/products');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $image->toJpeg(quality: 80)->save($destinationPath . '/' . $filename);
            $imagePath = 'products/' . $filename;
        }

        // 4. Simpan Informasi Produk ke Database
        Product::create([
            'name'  => $request->validated('name'),
            'price' => $request->validated('price'),
            'image' => $imagePath,
        ]);

        // 5. Redirect dengan pesan sukses
        return redirect()->back()->with('success', 'Produk berhasil ditambahkan dengan gambar!');
    }
}
