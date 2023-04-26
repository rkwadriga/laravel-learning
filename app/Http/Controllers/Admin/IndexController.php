<?php declare(strict_types=1);
/**
 * Created 2023-04-20
 * Author Dmitry Kushneriov
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        return 'ADMIN PANEL';
    }

    public function sendMail()
    {
        $data = [
            'title' => 'Test mail',
            'content' => 'Test mail content',
        ];

        Mail::send('mail.test-mail', $data, function (Message $message) {
            $message
                ->to('dmitry.kushneriov@obreey-products.com', 'Dmitry')
                ->subject('Test mail from Laravel-learning');
        });

        echo 'Mail sent';
    }
}
