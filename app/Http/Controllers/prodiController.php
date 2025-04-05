<?php
namespace App\Http\Controllers;

use App\Services\prodiService;

class prodiController extends Controller
{
    /**
     * inisialisiasi constractor yang menampung variabel class prodi service untuk memanggil fungsi yang berkaitan dengan manajemen data prodi.
     */
    public function __construct(prodiService $prodiService)
    {
        $this->prodiService = $prodiService;
    }

    public function getProdi()
    {
        // memanggil semua data prodi
        $prodi = $this->prodiService->getProdi();

        return $prodi;
    }
    public function getProdiToFakultas()
    {
        // memanggil data prodi dengan relasi pada table fakultas
        $prodi = $this->prodiService->getProdiToFakultas();

        return $prodi;
    }

    public function getFakultasToProdi()
    {
        /**
         * Memanggil data fakultas yang berlerasi dengan prodi dengan relasi satu ke banyak
         * hasil keluaranya berupa data fakultas yang didalamnya ada beberapa data prodi.
         */
        $fakutlas_to_prodi = $this->prodiService->getFakultasToProdi();

        return $fakutlas_to_prodi;
    }

    public function getProdiById($id)
    {
        /**
         * Memanggil data prodi berdasarkan id prodi yang digunakan untuk menampilkan data dan informasi prodi berdasarkan id_prodi
         */
        $prodi_by_id = $this->prodiService->getProdiById($id);

        return $prodi_by_id;
    }
}
