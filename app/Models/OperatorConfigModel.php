<?php

namespace App\Models;

use CodeIgniter\Model;

class OperatorConfigModel extends Model
{
    protected $table = 'operator_config';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'config_key',
        'config_value',
        'description'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getConfig($key)
    {
        $result = $this->where('config_key', $key)->first();
        return $result ? $result['config_value'] : null;
    }

    public function setConfig($key, $value, $description = null)
    {
        $existing = $this->where('config_key', $key)->first();
        if ($existing) {
            return $this->update($existing['id'], [
                'config_value' => $value,
                'description' => $description
            ]);
        } else {
            return $this->insert([
                'config_key' => $key,
                'config_value' => $value,
                'description' => $description
            ]);
        }
    }

    public function getAllConfigs()
    {
        return $this->findAll();
    }
}
