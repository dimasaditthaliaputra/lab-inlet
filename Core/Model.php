<?php

namespace Core;

class Model
{
    protected $db;
    protected $table;

    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Get all records
     */
    public function all()
    {
        return $this->db->query("SELECT * FROM {$this->table}")->fetchAll();
    }


    public function orderBy($key, $order = 'ASC') {
        return $this->db->query("SELECT * FROM {$this->table} ORDER BY {$key} {$order}")->fetchAll();
    }

    /**
     * Find record by ID
     */
    public function find($id)
    {
        return $this->db->query("SELECT * FROM {$this->table} WHERE id = :id")
            ->bind(':id', $id)
            ->fetch();
    }

    /**
     * Find record by column
     */
    public function findBy($column, $value)
    {
        return $this->db->query("SELECT * FROM {$this->table} WHERE {$column} = :value")
            ->bind(':value', $value)
            ->fetch();
    }

    /**
     * Create new record
     */
    public function create($data)
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $this->db->query("INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})");

        foreach ($data as $key => $value) {
            $this->db->bind(':' . $key, $value);
        }

        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    /**
     * Update record
     */
    public function update($id, $data)
    {
        $set = '';
        foreach ($data as $key => $value) {
            $set .= "{$key} = :{$key}, ";
        }
        $set = rtrim($set, ', ');

        $this->db->query("UPDATE {$this->table} SET {$set} WHERE id = :id");
        $this->db->bind(':id', $id);

        foreach ($data as $key => $value) {
            $this->db->bind(':' . $key, $value);
        }

        return $this->db->execute();
    }

    /**
     * Delete record
     */
    public function delete($id)
    {
        return $this->db->query("DELETE FROM {$this->table} WHERE id = :id")
            ->bind(':id', $id)
            ->execute();
    }

    /**
     * Custom query
     */
    public function query($sql)
    {
        return $this->db->query($sql);
    }
}
