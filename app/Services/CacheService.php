<?php

namespace App\Services;

use CodeIgniter\Cache\CacheInterface;

class CacheService
{
    protected $cache;

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    public function remember($key, $callback, $ttl = 3600)
    {
        if ($this->cache->get($key)) {
            return $this->cache->get($key);
        }

        $value = $callback();
        $this->cache->save($key, $value, $ttl);
        return $value;
    }

    public function get($key)
    {
        return $this->cache->get($key);
    }

    public function set($key, $value, $ttl = 3600)
    {
        return $this->cache->save($key, $value, $ttl);
    }

    public function delete($key)
    {
        return $this->cache->delete($key);
    }

    public function clear($pattern = null)
    {
        // Implementar limpeza por padrão se necessário
        return $this->cache->clean();
    }
}