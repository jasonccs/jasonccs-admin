<?php

namespace App\Http\Controllers\Passport;

use Illuminate\Routing\Controller as BaseController;

class PassportController extends BaseController
{

    public function passport()
    {
        return view('passport');
    }


}
