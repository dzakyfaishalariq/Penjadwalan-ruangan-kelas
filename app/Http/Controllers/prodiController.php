<?php
namespace App\Http\Controllers;

use App\Services\prodiService;
use Illuminate\Http\Request;

class prodiController extends Controller
{
    /**
     * inisialisiasi constractor yang menampung variabel class prodi service untuk memanggil fungsi yang berkaitan dengan manajemen data prodi.
     */
    public $prodiService;
    public function __construct(prodiService $prodiService)
    {
        $this->prodiService = $prodiService;
    }

    public function getProdi($paginate)
    {
        // memanggil semua data prodi
        $prodi = $this->prodiService->getProdi($paginate);

        return $prodi;
    }
    public function getProdiToFakultas($paginate)
    {
        // memanggil data prodi dengan relasi pada table fakultas
        $prodi = $this->prodiService->getProdiToFakultas($paginate);

        return $prodi;
    }

    public function getFakultasToProdi($paginate)
    {
        /**
         * Memanggil data fakultas yang berlerasi dengan prodi dengan relasi satu ke banyak
         * hasil keluaranya berupa data fakultas yang didalamnya ada beberapa data prodi.
         */
        $fakutlas_to_prodi = $this->prodiService->getFakultasToProdi($paginate);

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

    public function getProdiByName($paginate, $name)
    {
        /**
         * Memanggil data prodi berdasarkan name prodi yang mana data dalam prodi dapat berelasi pada table fakultas dan data fakultas dapat di panggil ke dalam data prodi
         */
        $prodi_by_name = $this->prodiService->getProdiByName($paginate, $name);

        return $prodi_by_name;
    }

    public function createProdi(Request $request)
    {
        // memanggil fungsi untuk menambahkan data prodi dengan validasi dan parameter inputan POST
        $prodi_add = $this->prodiService->createProdi($request);

        return $prodi_add;
    }

    public function updateProdi(Request $request, $id)
    {
        // memanggil fungsi untuk mengupdate data prodi dengan validasi dan parameter inputan POST
        $prodi_update = $this->prodiService->updateProdi($request, $id);

        return $prodi_update;
    }
}
