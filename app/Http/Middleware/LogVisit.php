<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Visit;

class LogVisit
{
    public function handle(Request $request, Closure $next)
    {
        $ipAddress = $request->ip();
        $macAddress = null;
        $referer = $request->headers->get('referer');

        // Intentar obtener la dirección MAC (sólo funciona en ciertos entornos)
        if (PHP_OS === 'Linux' || PHP_OS === 'Darwin') {
            $output = shell_exec("arp -a | grep " . escapeshellarg($ipAddress));
            if ($output) {
                $parts = preg_split('/\s+/', trim($output));
                $macAddress = isset($parts[3]) ? $parts[3] : null;
            }
        } elseif (PHP_OS === 'WINNT') {
            $output = shell_exec("arp -a " . escapeshellarg($ipAddress));
            if ($output) {
                preg_match('/(([a-f0-9]{2}-){5}[a-f0-9]{2})/i', $output, $matches);
                $macAddress = isset($matches[1]) ? $matches[1] : null;
            }
        }

        // Registrar la visita
        Visit::create([
            'ip_address' => $ipAddress,
            'mac_address' => $macAddress,
            'referer' => $referer,
        ]);


        return $next($request);
    }
}

