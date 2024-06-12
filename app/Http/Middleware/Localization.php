<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{App, Log, Session};

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('locale')) {
            switch ($request->locale) {
                case 'english':
                    App::setLocale('en');
                    break;
                case 'simplified':
                    App::setLocale('cn');
                    break;
                default:
                    break;
            }
        }

        unset($request['locale']);

        return $next($request);
    }
}
