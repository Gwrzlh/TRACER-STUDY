<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\LogActivityModel;

class LogActivityMiddleware implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        log_message('debug', '[LogActivityMiddleware] Before: Request class - ' . get_class($request));
        log_message('debug', '[LogActivityMiddleware] Session data: ' . json_encode(session()->get()));
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        log_message('debug', '[LogActivityMiddleware] After: Request class - ' . get_class($request));
        log_message('debug', '[LogActivityMiddleware] Session data: ' . json_encode(session()->get()));

        $uri = $request->getUri()->getPath();
        $action_type = strtoupper($request->getMethod()) . ' ' . $uri;
        $data = $_POST ?: $_GET;
        $description = !empty($data) ? json_encode($data) : null;

        $logModel = new LogActivityModel();
        $logModel->logAction($action_type, $description);
    }
}