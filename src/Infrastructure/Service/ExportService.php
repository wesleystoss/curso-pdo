<?php

namespace Alura\Pdo\Infrastructure\Service;

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Domain\Repository\StudentRepository;

class ExportService
{
    private StudentRepository $repository;
    private EnvironmentConfig $config;
    private Logger $logger;

    public function __construct(StudentRepository $repository)
    {
        $this->repository = $repository;
        $this->config = EnvironmentConfig::getInstance();
        $this->logger = Logger::getInstance();
    }

    public function exportToCsv(array $students = null, string $filename = null): string
    {
        if (!$this->config->get('export_csv_enabled', true)) {
            throw new \RuntimeException('Exportação CSV não está habilitada');
        }

        if ($students === null) {
            $students = $this->repository->allStudents();
        }

        $csv = Writer::createFromString('');
        $csv->setDelimiter(';');
        $csv->setEnclosure('"');
        $csv->setEscape('\\');

        // Cabeçalho
        $csv->insertOne([
            'ID',
            'Nome',
            'Data de Nascimento',
            'Idade',
            'CEP',
            'Endereço',
            'Faixa Etária'
        ]);

        // Dados
        foreach ($students as $student) {
            $csv->insertOne([
                $student->id(),
                $student->name(),
                $student->birthDate()->format('d/m/Y'),
                $student->age(),
                $student->cep(),
                $student->address(),
                $this->getAgeGroup($student->birthDate())
            ]);
        }

        $filename = $filename ?: 'alunos_' . date('Y-m-d_H-i-s') . '.csv';
        $filepath = $this->getExportPath() . '/' . $filename;
        
        file_put_contents($filepath, $csv->toString());
        
        $this->logger->info('Exportação CSV realizada', [
            'filename' => $filename,
            'records_count' => count($students)
        ]);

        return $filepath;
    }

    public function exportToJson(array $students = null, string $filename = null): string
    {
        if ($students === null) {
            $students = $this->repository->allStudents();
        }

        $data = [
            'export_info' => [
                'generated_at' => date('Y-m-d H:i:s'),
                'total_records' => count($students),
                'version' => '1.0'
            ],
            'students' => array_map(function (Student $student) {
                return [
                    'id' => $student->id(),
                    'name' => $student->name(),
                    'birth_date' => $student->birthDate()->format('Y-m-d'),
                    'age' => $student->age(),
                    'cep' => $student->cep(),
                    'address' => $student->address(),
                    'age_group' => $this->getAgeGroup($student->birthDate())
                ];
            }, $students)
        ];

        $filename = $filename ?: 'alunos_' . date('Y-m-d_H-i-s') . '.json';
        $filepath = $this->getExportPath() . '/' . $filename;
        
        file_put_contents($filepath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        
        $this->logger->info('Exportação JSON realizada', [
            'filename' => $filename,
            'records_count' => count($students)
        ]);

        return $filepath;
    }

    public function exportToXml(array $students = null, string $filename = null): string
    {
        if ($students === null) {
            $students = $this->repository->allStudents();
        }

        $xml = new \XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->setIndentString('  ');

        $xml->startDocument('1.0', 'UTF-8');
        $xml->startElement('students');
        $xml->writeAttribute('exported_at', date('Y-m-d H:i:s'));
        $xml->writeAttribute('total_records', count($students));

        foreach ($students as $student) {
            $xml->startElement('student');
            $xml->writeElement('id', $student->id());
            $xml->writeElement('name', $student->name());
            $xml->writeElement('birth_date', $student->birthDate()->format('Y-m-d'));
            $xml->writeElement('age', $student->age());
            $xml->writeElement('cep', $student->cep());
            $xml->writeElement('address', $student->address());
            $xml->writeElement('age_group', $this->getAgeGroup($student->birthDate()));
            $xml->endElement(); // student
        }

        $xml->endElement(); // students
        $xml->endDocument();

        $filename = $filename ?: 'alunos_' . date('Y-m-d_H-i-s') . '.xml';
        $filepath = $this->getExportPath() . '/' . $filename;
        
        file_put_contents($filepath, $xml->outputMemory());
        
        $this->logger->info('Exportação XML realizada', [
            'filename' => $filename,
            'records_count' => count($students)
        ]);

        return $filepath;
    }

    public function generateReport(): array
    {
        $students = $this->repository->allStudents();
        
        $report = [
            'summary' => [
                'total_students' => count($students),
                'generated_at' => date('Y-m-d H:i:s'),
                'average_age' => 0,
                'age_distribution' => [
                    'menor' => 0,
                    'jovem' => 0,
                    'adulto' => 0,
                    'idoso' => 0
                ]
            ],
            'recent_students' => [],
            'statistics' => []
        ];

        if (!empty($students)) {
            // Calcular idade média
            $totalAge = 0;
            foreach ($students as $student) {
                $totalAge += $student->age();
                $ageGroup = $this->getAgeGroup($student->birthDate());
                $report['summary']['age_distribution'][$ageGroup]++;
            }
            $report['summary']['average_age'] = round($totalAge / count($students), 1);

            // Alunos mais recentes (últimos 5)
            $recentStudents = array_slice($students, -5);
            $report['recent_students'] = array_map(function (Student $student) {
                return [
                    'id' => $student->id(),
                    'name' => $student->name(),
                    'age' => $student->age()
                ];
            }, $recentStudents);

            // Estatísticas por CEP
            $cepStats = [];
            foreach ($students as $student) {
                $cep = $student->cep() ?: 'Sem CEP';
                if (!isset($cepStats[$cep])) {
                    $cepStats[$cep] = 0;
                }
                $cepStats[$cep]++;
            }
            arsort($cepStats);
            $report['statistics']['by_cep'] = array_slice($cepStats, 0, 10, true);
        }

        return $report;
    }

    private function getExportPath(): string
    {
        $exportDir = dirname(__DIR__, 3) . '/exports';
        if (!is_dir($exportDir)) {
            mkdir($exportDir, 0755, true);
        }
        return $exportDir;
    }

    private function getAgeGroup(\DateTimeInterface $birthDate): string
    {
        $age = $birthDate->diff(new \DateTimeImmutable())->y;
        
        if ($age < 18) {
            return 'menor';
        } elseif ($age < 30) {
            return 'jovem';
        } elseif ($age < 60) {
            return 'adulto';
        } else {
            return 'idoso';
        }
    }

    public function getAvailableFormats(): array
    {
        $formats = [];

        if ($this->config->get('export_csv_enabled', true)) {
            $formats['csv'] = 'CSV (Excel)';
        }

        $formats['json'] = 'JSON';
        $formats['xml'] = 'XML';

        return $formats;
    }

    public function cleanupOldExports(int $daysOld = 7): int
    {
        $exportDir = $this->getExportPath();
        $files = glob($exportDir . '/*');
        $deleted = 0;
        $cutoff = time() - ($daysOld * 24 * 60 * 60);

        foreach ($files as $file) {
            if (is_file($file) && filemtime($file) < $cutoff) {
                unlink($file);
                $deleted++;
            }
        }

        if ($deleted > 0) {
            $this->logger->info('Limpeza de exportações antigas realizada', [
                'deleted_files' => $deleted,
                'days_old' => $daysOld
            ]);
        }

        return $deleted;
    }
}
