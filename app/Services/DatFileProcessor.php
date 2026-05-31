<?php

namespace App\Services;

use Carbon\Carbon;

class DatFileProcessor
{
    public function process(string $path): array
    {
        $rows = [];
        $handle = fopen($path, 'r');

        while (($line = fgets($handle)) !== false) {
            $line = trim($line);

            if ($line === '') {
                continue;
            }

            // Split by ANY whitespace (tabs or spaces)
            $columns = preg_split('/\s+/', $line);

            if (count($columns) < 6) {
                continue; // invalid row
            }

            [
                $biometricId,
                $date,
                $time,
                $verifyMode,
                $inOutMode,
                $workCode,
                $reserved
            ] = array_pad($columns, 7, null);

            try {
                $dateTime = Carbon::parse($date . ' ' . $time);
                $rows[] = [
                    'EmpID' => $biometricId,
                    'DateTime'   => $dateTime->format('Y-m-d H:i:s'),
                    'VerificationMode' =>  $verifyMode,
                    'InOut' => $inOutMode,
                    'WordCode' => $workCode,
                    'Reserved' => $reserved,

                ];
            } catch (\Exception) {
                // skip invalid datetime
            }
        }

        fclose($handle);

        return $rows;
    }
}
