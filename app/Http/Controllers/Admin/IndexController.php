<?php declare(strict_types=1);
/**
 * Created 2023-04-20
 * Author Dmitry Kushneriov
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function index()
    {
        return 'ADMIN PANEL';
    }
}
