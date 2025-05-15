<?php
namespace App\ApiTemplate;

class Template
{
    // melakukan inisialisasi variable status, message, dan data
    public $status;
    public $message;
    public $data;
    public function __construct($status, $message, $data)
    {
        // mengisi variable dengan parameter constructor
        $this->status  = $status;
        $this->message = $message;
        $this->data    = $data;
    }

    public function response()
    {
        // mengembalikan response template json
        if ($this->status == true) {
            return response()->json([
                'status'  => $this->status,
                'message' => $this->message,
                'data'    => $this->data,
            ], 200);
        } else {
            return response()->json([
                'status'  => $this->status,
                'message' => $this->message,
                'data'    => $this->data,
            ], 404);
        }
    }
}
