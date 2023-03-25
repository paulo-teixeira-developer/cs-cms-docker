<?php

namespace App\Helpers;

class Api
{
    public function responseBasic($data = null, $statusCode = 'sc', $message = null, $statusResponse = 200)
    {
        return response()->json(['status' => $this->getStatus($statusCode), 'message' => $message, 'data' => $data], $statusResponse);
    }

    public function response($data = null, $statusCode = 'sc', $message = null, $statusResponse = 200)
    {
        if (empty($data) && $statusCode == "sc" && empty($message))
            $message = ['operação realizada com sucesso.'];

        else if ($statusCode == "nf" && empty($message))
            $message = ['não encontrado.'];

        else if ($statusCode == "er" && empty($message) && $statusResponse == 500)
            $message = ['sistema indisponível.'];

        return response()->json(['status' => $this->getStatus($statusCode), 'message' => $message, 'data' => $data], $statusResponse);
    }

    public function responseArray($data = null, $statusCode = 'sc', $message = [], $statusResponse = 200)
    {
        if (empty($data) && $statusCode == "sc" && empty($message))
            $message = ['operação realizada com sucesso.'];

        else if ($statusCode == "nf" && empty($message))
            $message = ['não encontrado.'];

        else if ($statusCode == "er" && empty($message) && $statusResponse == 500)
            $message = ['sistema indisponível.'];
        
        return ['status' => $this->getStatus($statusCode), 'message' => $message, 'data' => $data, 'statusResponse' => $statusResponse];
    }

    public function responsePaginate($data = null, $statusCode = 'sc', $message = [], $perRange = 3, $statusResponse = 200)
    {
        if ($data->items()) {
            $range = [];
            $lastPage = $data->lastPage();
            $currentPage = $data->currentPage();
            $leftRange = (($currentPage - $perRange) <= 1) ? 1 : ($currentPage - $perRange);
            $rigthRange = (($currentPage + $perRange) >= $lastPage) ? $lastPage : ($currentPage + $perRange);
            $previous = ($data->onFirstPage()) ? false : true;
            $next = ($data->hasMorePages()) ? true : false;

            for ($i = $leftRange; $i <= $rigthRange; $i++) {
                $range[] = [
                    'page' => $i,
                    'active' => ($currentPage === $i) ? true : false,
                ];
            }

            $paginate = [
                "currentPage" => $currentPage,
                "lastPage" => $lastPage,
                "previous" => $previous,
                "next" => $next,
                "range" => $range,

            ];

            return response()->json(['status' => $this->getStatus($statusCode), 'message' => $message, 'paginate' => $paginate, 'data' => $data->items()], $statusResponse);
        } else {
            $paginate = [
                "previous" => false,
                "next" => false,
                "lastPage" => 0,
                "range" => [],
            ];
            return response()->json(['status' => $this->getStatus($statusCode), 'message' => $message, 'paginate' => $paginate, 'data' => null], $statusResponse);
        }
    }

    public function getStatus($status)
    {
        switch ($status) {
            case 'sc':
                return 'success';
            case 'er':
                return 'error';
            case 'nf':
                return 'not-found';
            default:
                return null;
        }
    }

    public function getCodeStatus($status)
    {
        switch ($status) {
            case 'success':
                return 'sc';
            case 'error':
                return 'er';
            case 'not-found':
                return 'nf';
            default:
                return null;
        }
    }

    public function getErrorByCode($error)
    {
        if (isset($error->errorInfo[1])) {
            switch ($error->errorInfo[1]) {
                case '1451':
                    return ['Não é possível excluir devido uma restrição de chave estrangeira'];
                default:
                    return ['Sistema indisponível.'];
            }
        } else {
            return ['Sistema indisponível.'];
        }
    }
}