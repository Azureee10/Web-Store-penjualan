<form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <label>Nama Produk:</label>
    <input type="text" name="name" required>

    <label>Harga:</label>
    <input type="number" name="price" required>

    <label>Pilih Gambar:</label>
    <input type="file" name="image" accept="image/*" required>

    <button type="submit">Simpan Produk</button>
</form>
