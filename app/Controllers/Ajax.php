<?php

namespace App\Controllers;

use App\Models\Info;
use App\Models\Mhs;
use App\Models\Pengurus;
use App\Models\Produk;

class Ajax extends BaseController
{
    public function cek_nrp($nrp)
    {
        $mhs = new Mhs();
        $query1 = $mhs->where("nrp",$nrp)
            ->first();

        return ($query1 !== null) ? json_encode($query1) : "error";
    }

    public function cek_pengurus($id_pengurus)
    {
        $pengurus = new Pengurus();
        $query1 = $pengurus->select(["nama","nama_departemen","jabatan","nama_panggilan","id_line","no_wa"])
            ->where("id_pengurus",$id_pengurus)
            ->join("mhs","pengurus.nrp = mhs.nrp")
            ->join("departemen","pengurus.id_departemen = departemen.id_departemen")
            ->first();

        return ($query1 !== null) ? json_encode($query1) : "error";
    }

    public function cek_narahubung()
    {
        $nama = trim((string) $this->request->getGet("key"));

        if ($nama === '') {
            return json_encode([]);
        }

        $pengurus = new Pengurus();
        $query1 = $pengurus->select(["nama","id_pengurus","pengurus.nrp"])
            ->join("mhs","pengurus.nrp = mhs.nrp")
            ->like("nama",$nama)
            ->orderBy("nama")
            ->get()
            ->getResult();

        return ($query1 !== null) ? json_encode($query1) : "error";
    }

    public function cek_kontak($id_pengurus)
    {
        $pengurus = new Pengurus();
        $query1 = $pengurus->select(["id_line","no_wa"])
            ->where("id_pengurus",$id_pengurus)
            ->first();

        return ($query1 !== null) ? json_encode($query1) : "error";
    }

    public function cek_password($id_pengurus)
    {
        $pengurus = new Pengurus();
        $query1 = $pengurus->select(["password"])
            ->where("id_pengurus",$id_pengurus)
            ->first();

        if ($query1 === null || !isset($query1->password)) {
            return json_encode("aman");
        }

        return ($query1->password === '$2y$10$VMP7SX97IwLIkP2lOTIs6etJ8uJHLiiDIQaE6Weh8VCrAuNukNVsa') ?
            json_encode("ganti") : json_encode("aman");
    }

    public function cek_barang()
    {
        $produk = new Produk();
        $query1 = $produk->select(["nama_barang","kode_barang","harga_jual"])
            ->orderBy("kode_barang")
            ->get()
            ->getResult();

        return json_encode($query1);
    }

    public function cek_info()
    {
        $info = new Info();
        $query1 = $info->select(["detail_info"])
            ->orderBy("id_info")
            ->get()
            ->getResult();

        return json_encode($query1);
    }
}
