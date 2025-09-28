<?php

if (!function_exists('formatConditions')) {
    function formatConditions($json)
    {
        $conds = json_decode($json, true);
        if (empty($conds['conditions'])) {
            return 'Tidak ada kondisi spesifik.';
        }

        $logic = $conds['logic_type'] === 'all' ? 'SEMUA' : 'SALAH SATU';
        $formatted = "Tampilkan jika $logic kondisi berikut terpenuhi: ";
        foreach ($conds['conditions'] as $cond) {
            $formatted .= esc($cond['field']) . ' ' . esc($cond['operator']) . ' ' . esc($cond['value']) . '; ';
        }
        return rtrim($formatted, '; ');
    }
}