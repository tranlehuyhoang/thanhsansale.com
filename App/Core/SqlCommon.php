<?php
namespace App\Services\Common;

class SqlCommon
{

    public static function INSERT($data, $table)
    {
        $escapedData = array_map(function ($value) {
            return str_replace("'", "\'", $value);
        }, $data);

        $sql = "INSERT INTO $table (Id,";
        $sql .= implode(", ", array_keys($escapedData)) . ") VALUES (NULL,";
        $sql .= "'" . implode("', '", array_values($escapedData)) . "')";
        return $sql;
    }


    public static function UPDATE($table, $data, $id)
    {
        $setStatements = array_map(function ($column, $value) {
            return "$column =" . (is_string($value) ? self::UPDATE_STRING($value) : $value);
        }, array_keys($data), $data);

        $sql = "UPDATE $table SET " . implode(", ", $setStatements) . " WHERE Id = $id";

        return $sql;
    }

    // check $value has L'Oreal Paris Official Store has ' in string
    // to replace ' by \'
    public static function UPDATE_STRING($value)
    {
        $res = str_replace("'", "\'", $value);
        return "'$res'";
    }


    public static function DELETE($table, $id)
    {
        $sql = "DELETE FROM $table WHERE Id = '$id'";
        return $sql;
    }
    // build sql query
    public static function BuildQuery($tableName, $condition = null, $orderByDesc = null, $offset = null, $limit = null)
    {
        $orderByDesc = $orderByDesc ?? 'CreatedAt';
        $sql = "SELECT * FROM $tableName";
        if ($condition != null) {
            $sql .= " WHERE $condition";
        }
        $sql .= " ORDER BY $orderByDesc DESC";
        if ($offset != null && $limit != null) {
            $sql .= " LIMIT $offset, $limit";
        }
        return $sql;
    }
    // SQL Count 
    public static function Count($tableName, $condition = null)
    {
        $sql = "SELECT COUNT(*) AS Total FROM $tableName";
        if ($condition != null) {
            $sql .= " WHERE $condition";
        }
        return $sql;
    }
}
