<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    /**
     * Show the internal dashboard: rendered project documentation + domain overview.
     */
    public function __invoke(): View
    {
        $docsPath = base_path('docs');

        /** @var array<string, string> $markdownFiles */
        $markdownFiles = [
            'Arhitektura_i_workflow.md' => 'Tehnička arhitektura i projektni workflow',
            'ERD_shema_baze.md' => 'ERD i shema baze podataka',
            'DOZVOLE.md' => 'Dozvole i korisnici (server / Virtualmin)',
        ];

        $docs = [];
        foreach ($markdownFiles as $file => $title) {
            $path = $docsPath.DIRECTORY_SEPARATOR.$file;
            if (File::exists($path)) {
                $docs[] = [
                    'title' => $title,
                    'file' => $file,
                    'html' => Str::markdown(File::get($path)),
                ];
            }
        }

        $mermaidPath = $docsPath.DIRECTORY_SEPARATOR.'ERD_dijagram.mermaid';
        $mermaid = File::exists($mermaidPath) ? File::get($mermaidPath) : null;

        return view('dashboard', compact('docs', 'mermaid'));
    }
}
