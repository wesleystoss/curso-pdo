<?php
require_once __DIR__ . '/../vendor/autoload.php';

// Carregar variáveis do .env
if (file_exists(dirname(__DIR__) . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->load();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/bootstrap.php';

use Alura\Pdo\Infrastructure\Service\EnvironmentConfig;
use Alura\Pdo\Infrastructure\Service\AppLogger;
use Alura\Pdo\Infrastructure\Service\Cache;

// Obter instâncias de configuração e logger
$config = EnvironmentConfig::getInstance();
$logger = AppLogger::getInstance();

// Obter repositório de alunos
$repository = $repository ?? (new Alura\Pdo\Infrastructure\Repository\PdoStudentRepository(
    Alura\Pdo\Infrastructure\Persistence\ConnectionCreator::createConnection()
));

// Buscar alunos e estatísticas
$students = $repository->allStudents();
$totalStudents = count($students);
$studentsWithCep = count(array_filter($students, fn($s) => !empty($s->cep())));
$studentsWithoutCep = $totalStudents - $studentsWithCep;

// Distribuição por idade
$ageDistribution = [
    '0-17' => 0,
    '18-25' => 0,
    '26-35' => 0,
    '36-50' => 0,
    '51+' => 0
];
foreach ($students as $student) {
    $age = $student->age();
    if ($age <= 17) $ageDistribution['0-17']++;
    elseif ($age <= 25) $ageDistribution['18-25']++;
    elseif ($age <= 35) $ageDistribution['26-35']++;
    elseif ($age <= 50) $ageDistribution['36-50']++;
    else $ageDistribution['51+']++;
}

// Faixa etária
$ageGroupDistribution = [
    'menor' => 0,
    'jovem' => 0,
    'adulto' => 0,
    'idoso' => 0
];
foreach ($students as $student) {
    $age = $student->age();
    if ($age < 18) $ageGroupDistribution['menor']++;
    elseif ($age < 30) $ageGroupDistribution['jovem']++;
    elseif ($age < 60) $ageGroupDistribution['adulto']++;
    else $ageGroupDistribution['idoso']++;
}

// Idade média
$averageAge = $totalStudents > 0 ? round(array_sum(array_map(fn($s) => $s->age(), $students)) / $totalStudents, 1) : 0;

// Alunos mais recentes
$recentStudents = array_slice($students, -5);

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
$topCeps = array_slice($cepStats, 0, 10, true);

// Estatísticas de log e cache
$logStats = $logger->getLogStats();
$cacheStats = Cache::getInstance()->getStats();

$pageTitle = 'Dashboard - ' . $config->get('app_name');
include __DIR__ . '/includes/header.php';
?>
<div class="dashboard">
    <div class="dashboard-header">
        <h1>📊 Dashboard do Sistema</h1>
        <p>Visão geral e estatísticas em tempo real</p>
        <div class="dashboard-actions">
            <button class="btn btn-primary" onclick="location.reload()">🔄 Atualizar</button>
        </div>
    </div>
    <div class="stats-grid">
        <div class="stat-card primary">
            <div class="stat-icon">👥</div>
            <div class="stat-content">
                <div class="stat-number"><?= $totalStudents ?></div>
                <div class="stat-label">Total de Alunos</div>
            </div>
        </div>
        <div class="stat-card success">
            <div class="stat-icon">📅</div>
            <div class="stat-content">
                <div class="stat-number"><?= $averageAge ?></div>
                <div class="stat-label">Idade Média</div>
            </div>
        </div>
        <div class="stat-card warning">
            <div class="stat-icon">📍</div>
            <div class="stat-content">
                <div class="stat-number"><?= $studentsWithCep ?></div>
                <div class="stat-label">Com CEP</div>
            </div>
        </div>
        <div class="stat-card info">
            <div class="stat-icon">🆕</div>
            <div class="stat-content">
                <div class="stat-number"><?= count($recentStudents) ?></div>
                <div class="stat-label">Novos (últimos 5)</div>
            </div>
        </div>
    </div>
    <div class="charts-grid">
        <div class="chart-card">
            <h3>📈 Distribuição por Idade</h3>
            <div class="chart-container">
                <canvas id="ageChart" width="400" height="300"></canvas>
            </div>
        </div>
        <div class="chart-card">
            <h3>🎯 Faixa Etária</h3>
            <div class="chart-container">
                <canvas id="ageGroupChart" width="400" height="300"></canvas>
            </div>
        </div>
        <div class="chart-card">
            <h3>📍 Status do CEP</h3>
            <div class="chart-container">
                <canvas id="cepChart" width="400" height="300"></canvas>
            </div>
        </div>
    </div>
    <div class="tables-grid">
        <div class="table-card">
            <h3>🆕 Alunos Mais Recentes</h3>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Idade</th>
                            <th>CEP</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_reverse($recentStudents) as $student): ?>
                        <tr>
                            <td><?= $student->id() ?></td>
                            <td><?= htmlspecialchars($student->name()) ?></td>
                            <td><?= $student->age() ?> anos</td>
                            <td><?= !empty($student->cep()) ? htmlspecialchars($student->cep()) : '-' ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="table-card">
            <h3>🏆 Top CEPs</h3>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>CEP</th>
                            <th>Quantidade</th>
                            <th>%</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($topCeps as $cep => $count): ?>
                        <tr>
                            <td><?= htmlspecialchars($cep) ?></td>
                            <td><?= $count ?></td>
                            <td><?= $totalStudents > 0 ? round(($count / $totalStudents) * 100, 1) : 0 ?>%</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="system-info-grid">
        <div class="info-card">
            <h3>⚙️ Status do Sistema</h3>
            <div class="info-list">
                <div class="info-item">
                    <span class="info-label">Ambiente:</span>
                    <span class="info-value <?= $config->isProduction() ? 'success' : 'warning' ?>">
                        <?= ucfirst($config->get('app_env')) ?>
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Debug:</span>
                    <span class="info-value <?= $config->isDebugEnabled() ? 'warning' : 'success' ?>">
                        <?= $config->isDebugEnabled() ? 'Ativado' : 'Desativado' ?>
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Cache:</span>
                    <span class="info-value <?= $config->get('cache_enabled') ? 'success' : 'error' ?>">
                        <?= $config->get('cache_enabled') ? 'Ativo' : 'Inativo' ?>
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Notificações:</span>
                    <span class="info-value <?= $config->get('notifications_enabled') ? 'success' : 'error' ?>">
                        <?= $config->get('notifications_enabled') ? 'Ativas' : 'Inativas' ?>
                    </span>
                </div>
            </div>
        </div>
        <div class="info-card">
            <h3>📝 Logs do Sistema</h3>
            <div class="info-list">
                <?php foreach ($logStats as $filename => $stats): ?>
                <div class="info-item">
                    <span class="info-label"><?= $filename ?>:</span>
                    <span class="info-value"><?= $stats['size_formatted'] ?> (<?= $stats['lines'] ?> linhas)</span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="info-card">
            <h3>💾 Cache</h3>
            <div class="info-list">
                <div class="info-item">
                    <span class="info-label">Hits:</span>
                    <span class="info-value"><?= $cacheStats['hits'] ?? 0 ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Misses:</span>
                    <span class="info-value"><?= $cacheStats['misses'] ?? 0 ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Taxa de Hit:</span>
                    <span class="info-value">
                        <?php 
                        $hits = $cacheStats['hits'] ?? 0;
                        $misses = $cacheStats['misses'] ?? 0;
                        $total = $hits + $misses;
                        echo $total > 0 ? round(($hits / $total) * 100, 1) . '%' : '0%';
                        ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Verificar se os elementos existem antes de criar os gráficos
document.addEventListener('DOMContentLoaded', function() {
    // Verificar se os dados são válidos
    const ageData = <?= json_encode(array_values($ageDistribution)) ?>;
    const ageLabels = <?= json_encode(array_keys($ageDistribution)) ?>;
    const ageGroupData = <?= json_encode(array_values($ageGroupDistribution)) ?>;
    const ageGroupLabels = <?= json_encode(array_keys($ageGroupDistribution)) ?>;
    const cepData = [<?= $studentsWithCep ?>, <?= $studentsWithoutCep ?>];
    const cepLabels = ['Com CEP', 'Sem CEP'];
    
    // Verificar se os dados são arrays válidos
    if (!Array.isArray(ageData) || !Array.isArray(ageLabels) || 
        !Array.isArray(ageGroupData) || !Array.isArray(ageGroupLabels) ||
        !Array.isArray(cepData) || !Array.isArray(cepLabels)) {
        console.error('Dados dos gráficos inválidos');
        return;
    }
    
    const colors = {
        primary: '#667eea',
        secondary: '#764ba2',
        success: '#28a745',
        warning: '#ffc107',
        danger: '#dc3545',
        info: '#17a2b8',
        light: '#f8f9fa',
        dark: '#343a40'
    };

    // Gráfico de Distribuição por Idade
    const ageCanvas = document.getElementById('ageChart');
    if (ageCanvas) {
        const ageCtx = ageCanvas.getContext('2d');
        new Chart(ageCtx, {
            type: 'bar',
            data: {
                labels: ageLabels,
                datasets: [{
                    label: 'Quantidade de Alunos',
                    data: ageData,
                    backgroundColor: [
                        colors.primary,
                        colors.success,
                        colors.warning,
                        colors.info,
                        colors.danger
                    ],
                    borderColor: colors.dark,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Gráfico de Faixa Etária
    const ageGroupCanvas = document.getElementById('ageGroupChart');
    if (ageGroupCanvas) {
        const ageGroupCtx = ageGroupCanvas.getContext('2d');
        new Chart(ageGroupCtx, {
            type: 'doughnut',
            data: {
                labels: ageGroupLabels.map(label => label.charAt(0).toUpperCase() + label.slice(1)),
                datasets: [{
                    data: ageGroupData,
                    backgroundColor: [
                        colors.primary,
                        colors.success,
                        colors.warning,
                        colors.danger
                    ],
                    borderWidth: 2,
                    borderColor: colors.light
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    // Gráfico de CEP
    const cepCanvas = document.getElementById('cepChart');
    if (cepCanvas) {
        const cepCtx = cepCanvas.getContext('2d');
        new Chart(cepCtx, {
            type: 'pie',
            data: {
                labels: cepLabels,
                datasets: [{
                    data: cepData,
                    backgroundColor: [colors.success, colors.warning],
                    borderWidth: 2,
                    borderColor: colors.light
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
});
</script>
<?php include __DIR__ . '/includes/footer.php'; ?>
