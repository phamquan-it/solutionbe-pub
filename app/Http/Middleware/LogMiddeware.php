<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Stmt\Break_;
use Symfony\Component\HttpFoundation\Response;

class LogMiddeware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Log::debug($request->path());
        switch ($request->path()) {
            case 'api/login':
                switch ($request->method()) {
                    case 'POST':
                        //    Log::debug('Access Path: ' . $request->path());
                        \App\Models\Log::create([
                            'email' => $request->email,
                            'method' => $request->method(),
                            'action' => 'Login'
                        ]);
                        break;
                    default:
                        // code...
                        break;
                }
                break;
            case 'api/register':
                \App\Models\Log::create([
                    'email' => $request->email,
                    'method' => $request->method(),
                    'action' => 'Register'
                ]);
            case 'api/projects':
                switch ($request->method()) {
                    case 'POST':
                        // Log::debug('Access Path: ' . $request->path());
                        // \App\Models\Log::create([
                        //     'email' => $request->user()->email,
                        //     'method' => $request->method(),
                        //     'action' => 'create_solution'
                        // ]);
                        // break;
                    default:
                        // code...
                        break;
                }
                break;
            case 'api/projects/cancel':
                switch ($request->method()) {
                    case 'PATCH':
                        // Log::debug('Access Path: ' . $request->path());
                        \App\Models\Log::create([
                            'email' => $request->user()->email,
                            'method' => $request->method(),
                            'action' => 'cancel_solution'
                        ]);
                        break;
                    default:
                        // code...
                        break;
                }
                break;
            default:
                Log::debug('Access Path: ' . $request->path());
                Log::debug($request);
                Log::debug('Request Method: ' . $request->method());
        };
        return $next($request);
    }
}
