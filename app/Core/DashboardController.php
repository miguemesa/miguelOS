<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;

final class DashboardController extends Controller
{
    public function index(): Response
    {
        return $this->view(
            'dashboard/index',
            [
                'title' => 'MiguelOS',
                'version' => '0.0.3'
            ]
        );
    }
}