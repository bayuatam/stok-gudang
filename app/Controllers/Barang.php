<?php

namespace App\Controllers;

use App\Models\BarangModel;

class Barang extends BaseController
{
    public function index()
    {
        $model = new BarangModel();

        $keyword = $this->request->getGet('keyword');

        if ($keyword) {
            $barang = $model->like('nama_barang', $keyword)->findAll();
        } else {
            $barang = $model->findAll();
        }

        return view('barang/index', [
            'barang' => $barang
        ]);
    }

    public function tambah()
    {
        return view('barang/tambah');
    }

    public function simpan()
    {
        $model = new \App\Models\BarangModel();

        // cek kode barang sudah ada atau belum
        $cek = $model->where('kode_barang', $this->request->getPost('kode_barang'))->first();

        if ($cek) {
            return redirect()->back()->withInput()->with('error', 'Kode barang sudah digunakan!');
        }

        $model->save([
            'kode_barang'   => $this->request->getPost('kode_barang'),
            'nama_barang'   => $this->request->getPost('nama_barang'),
            'kategori'      => $this->request->getPost('kategori'),
            'satuan'        => $this->request->getPost('satuan'),
            'stok'          => $this->request->getPost('stok'),
            'minimum_stok'  => $this->request->getPost('minimum_stok'),
        ]);

        return redirect()->to('/barang')->with('success', 'Barang berhasil ditambahkan');
    }

    public function edit($id)
    {
        $model = new BarangModel();

        return view('barang/edit', [
            'barang' => $model->find($id)
        ]);
    }

    public function update($id)
    {
        $model = new BarangModel();

        $model->update($id, [
            'kode_barang' => $this->request->getPost('kode_barang'),
            'nama_barang' => $this->request->getPost('nama_barang'),
            'kategori' => $this->request->getPost('kategori'),
            'satuan' => $this->request->getPost('satuan'),
            'stok' => $this->request->getPost('stok'),
            'minimum_stok' => $this->request->getPost('minimum_stok'),
        ]);

        return redirect()->to('/barang');
    }

    public function hapus($id)
    {
        $model = new BarangModel();
        $model->delete($id);

        return redirect()->to('/barang');
    }
}
