<?php

namespace Alura\Pdo\Infrastructure\Service;

class CepService
{
    private const VIACEP_URL = 'https://viacep.com.br/ws/{cep}/json/';

    public function buscarEndereco(string $cep): ?array
    {
        // Remove caracteres não numéricos do CEP
        $cep = preg_replace('/[^0-9]/', '', $cep);
        
        if (strlen($cep) !== 8) {
            return null;
        }

        $url = str_replace('{cep}', $cep, self::VIACEP_URL);
        
        try {
            $response = file_get_contents($url);
            $data = json_decode($response, true);
            
            if ($data && !isset($data['erro'])) {
                return [
                    'cep' => $data['cep'],
                    'logradouro' => $data['logradouro'],
                    'bairro' => $data['bairro'],
                    'localidade' => $data['localidade'],
                    'uf' => $data['uf'],
                    'endereco_completo' => $this->formatarEndereco($data)
                ];
            }
        } catch (\Exception $e) {
            // Log do erro se necessário
            return null;
        }
        
        return null;
    }

    private function formatarEndereco(array $data): string
    {
        $endereco = [];
        
        if (!empty($data['logradouro'])) {
            $endereco[] = $data['logradouro'];
        }
        
        if (!empty($data['bairro'])) {
            $endereco[] = $data['bairro'];
        }
        
        if (!empty($data['localidade'])) {
            $endereco[] = $data['localidade'];
        }
        
        if (!empty($data['uf'])) {
            $endereco[] = $data['uf'];
        }
        
        return implode(', ', $endereco);
    }
} 