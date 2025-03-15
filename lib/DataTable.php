<?php

namespace lib;

class DataTable
{
    /**
     * Hàm lấy ra danh sách giá trị của các key truyền vào nếu có
     * @param array $keys
     * @param array $data
     * @return array
     */
    public static function getAllowData(array $keys, array $data): array
    {
        return array_filter($data, fn($key) => in_array($key, $keys, true), ARRAY_FILTER_USE_KEY);
    }
}
