<?php

namespace App\Services;

class SystemMonitorService
{
    public function getStats(): array
    {
        return [
            'cpu' => $this->cpuUsage(),
            'ram' => $this->ramUsage(),
            'disk' => $this->diskUsage(),
            'network' => $this->networkUsage(),
            'uptime' => $this->uptime(),
            'load' => sys_getloadavg(),
        ];
    }

    private function cpuUsage(): float
    {
        $stat1 = explode(' ', preg_replace('/\s+/', ' ', trim(file('/proc/stat')[0])));
        $stat1 = array_map('intval', array_slice($stat1, 1));

        $idle1 = $stat1[3] + $stat1[4];
        $total1 = array_sum($stat1);

        usleep(500000); // 0.5 секунды

        $stat2 = explode(' ', preg_replace('/\s+/', ' ', trim(file('/proc/stat')[0])));
        $stat2 = array_map('intval', array_slice($stat2, 1));

        $idle2 = $stat2[3] + $stat2[4];
        $total2 = array_sum($stat2);

        $totalDiff = $total2 - $total1;
        $idleDiff = $idle2 - $idle1;

        if ($totalDiff === 0) {
            return 0;
        }

        return round((1 - ($idleDiff / $totalDiff)) * 100, 1);
    }

    private function ramUsage(): array
    {
        $meminfo = file('/proc/meminfo');

        $total = (int) filter_var($meminfo[0], FILTER_SANITIZE_NUMBER_INT);
        $available = (int) filter_var($meminfo[2], FILTER_SANITIZE_NUMBER_INT);

        $used = $total - $available;

        return [
            'total' => round($total / 1024),
            'used' => round($used / 1024),
            'percent' => round($used / $total * 100, 1),
        ];
    }

    private function diskUsage(): array
    {
        $total = disk_total_space("/");
        $free = disk_free_space("/");

        $used = $total - $free;

        return [
            'total' => round($total / 1024 / 1024 / 1024),
            'used' => round($used / 1024 / 1024 / 1024),
            'percent' => round($used / $total * 100, 1),
        ];
    }

    private function networkUsage(): array
    {
        $lines = file('/proc/net/dev');

        foreach ($lines as $line) {

            if (str_contains($line, 'eth0') || str_contains($line, 'ens')) {

                preg_match('/:\s*(\d+)/', $line, $matches);

                return [
                    'received_bytes' => (int)$matches[1],
                ];
            }
        }

        return [];
    }

    private function uptime(): int
    {
        return (int) explode(' ', file_get_contents('/proc/uptime'))[0];
    }
}