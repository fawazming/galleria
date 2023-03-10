<?php

namespace App\Controllers;
require_once('Gallery.php');
use CodeIgniter\API\ResponseTrait;

class Home extends BaseController
{
    use ResponseTrait;

    public function index($key)
    {
        $res = Hi($key);
        return $this->respond($res, 200, 'Do append with preferred size =w250-h250');
        // echo view('header');
        // echo Hi();

        // // echo view('home');
        // echo view('footer');
    }

}
