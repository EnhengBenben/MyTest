<?php
/*************************************************************************
 *
 * Conglin Network CONFIDENTIAL
 * __________________
 *
 *  [2013] - [2015] Conglin Network Incorporated
 *  All Rights Reserved.
 *
 * NOTICE:  All information contained herein is, and remains
 * the property of Conglin Network Incorporated and its suppliers,
 * if any.  The intellectual and technical concepts contained
 * herein are proprietary to Conglin Network Incorporated
 * and its suppliers and may be covered by C.N. and Foreign Patents,
 * patents in process, and are protected by trade secret or copyright law.
 * Dissemination of this information or reproduction of this material
 * is strictly forbidden unless prior written permission is obtained
 * from Conglin Network Incorporated.
 */

namespace App\Support;


class ExportHelper
{
    public static function exportFromQuery($query, $fileName = '导出数据', array $headers = [])
    {
        \Excel::create($fileName, function ($excel) use ($query, $headers) {
            $excel->sheet('第一页', function ($sheet) use ($query, $headers) {
                if (!empty($headers)) {
                    $sheet->rows($headers);
                }
                $query->chunk(4000, function ($records) use ($sheet) {
                    \Log::info("export chunk size: " . count($records));
                    $rows = [];
                    foreach ($records as $record) {
                        $row = array_values((array)$record);
                        if (!empty($row)) {
                            $rows[] = $row;
                        }
                    }
                    if (!empty($rows)) {
                        $sheet->rows($rows);
                    }
                });
            });
        })->export('xlsx');
    }
}