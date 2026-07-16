<?php

declare(strict_types=1);

namespace App\Admin;

use Tavp\Cms\Admin\AdminController;
use Tavp\Core\Http\Response;

class SeoController extends AdminController
{
    public function index(): string|Response
    {
        if ($r = $this->guard()) {
            return $r;
        }

        $db = app('db');
        $pageCount = $db->fetchAll("SELECT COUNT(*) as cnt FROM contents WHERE status='published'", \PDO::FETCH_ASSOC);
        $postCount = $db->fetchAll("SELECT COUNT(*) as cnt FROM contents WHERE type='post' AND status='published'", \PDO::FETCH_ASSOC);

        return $this->admin('seo_dashboard', [
            'pageCount' => (int) ($pageCount[0]['cnt'] ?? 0),
            'postCount' => (int) ($postCount[0]['cnt'] ?? 0),
        ]);
    }
}
