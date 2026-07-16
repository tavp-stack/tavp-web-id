<?php

declare(strict_types=1);

namespace App\Admin;

use Tavp\Cms\Admin\AdminController;
use Tavp\Core\Http\Response;

class MessagesController extends AdminController
{
    public function index(): string|Response
    {
        if ($r = $this->guard()) {
            return $r;
        }

        $db = app('db');
        $messages = $db->fetchAll('SELECT * FROM contact_messages ORDER BY created_at DESC', \PDO::FETCH_ASSOC);

        return $this->admin('messages', [
            'messages' => $messages,
        ]);
    }
}
