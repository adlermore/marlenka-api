<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;

class ApiDataLogger
{
    private $startTime;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->startTime = microtime(true);
        return $next($request);
    }
    public function terminate($request, $response)
    {
            $endTime = microtime(true);
            $filename = 'api_data_logger_new_' . date('y-m-d') . '.log';
            $dataToLog  = 'Time: '   . date('y-m-d h:i:s') . "\n";
            $dataToLog .= 'Duration: ' . number_format($endTime - LARAVEL_START, 3) . "\n";
            $dataToLog .= 'IP Address: ' . $this->getRealIpAddr() . "\n";
            $dataToLog .= 'USER: ' . ((Auth::user())  ? Auth::user()->id : '') . "\n";
            $dataToLog .= 'headers: ' . $request->header('Authorization') . "\n";
            $dataToLog .= 'URL: '    . $request->fullUrl() . "\n";
            $dataToLog .= 'Method: ' . $request->method() . "\n";
            $dataToLog .= 'Input: '  . $request->getContent() . "\n";
            $dataToLog .= 'output: '  . $response->getContent() . "\n";

            \File::append( storage_path('logs' . DIRECTORY_SEPARATOR ."api".DIRECTORY_SEPARATOR . $filename), $dataToLog . "\n" . str_repeat("=", 20) . "\n\n");
    }

    function getRealIpAddr()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
        {
            $ip=$_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
        {
            $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
            $ip=$_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}
