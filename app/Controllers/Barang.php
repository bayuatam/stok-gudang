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
            $barang = $model->like('nama_material', $keyword)->findAll();
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
        $model = new BarangModel();

        $cek = $model->where('kode_sumber_daya', $this->request->getPost('kode_sumber_daya'))->first();

        if ($cek) {
            return redirect()->back()->withInput()->with('error', 'Kode sudah digunakan!');
        }

        $model->save([
            'kode_sumber_daya' => $this->request->getPost('kode_sumber_daya'),
            'nama_material'    => $this->request->getPost('nama_material'),
            'jenis_material'   => $this->request->getPost('jenis_material'),
            'kategori'         => $this->request->getPost('kategori'),
            'supplier'         => $this->request->getPost('supplier'),
            'no_part'          => $this->request->getPost('no_part'),
            'satuan'           => $this->request->getPost('satuan'),
            'stok'             => $this->request->getPost('stok'),
            'minimum_stok'     => $this->request->getPost('minimum_stok'),
            'lokasi_gudang'    => $this->request->getPost('lokasi_gudang'),
            'status_barang'    => $this->request->getPost('status_barang'),
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
            'kode_sumber_daya' => $this->request->getPost('kode_sumber_daya'),
            'nama_material'    => $this->request->getPost('nama_material'),
            'jenis_material'   => $this->request->getPost('jenis_material'),
            'kategori'         => $this->request->getPost('kategori'),
            'supplier'         => $this->request->getPost('supplier'),
            'no_part'          => $this->request->getPost('no_part'),
            'satuan'           => $this->request->getPost('satuan'),
            'stok'             => $this->request->getPost('stok'),
            'minimum_stok'     => $this->request->getPost('minimum_stok'),
            'lokasi_gudang'    => $this->request->getPost('lokasi_gudang'),
            'status_barang'    => $this->request->getPost('status_barang'),
        ]);

        return redirect()->to('/barang')->with('success', 'Barang berhasil diupdate');
    }

    public function hapus($id)
    {
        $model = new BarangModel();
        $model->delete($id);

        return redirect()->to('/barang')->with('success', 'Barang berhasil dihapus');
    }
}
