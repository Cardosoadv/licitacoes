<?php

namespace App\Services;

use CodeIgniter\Cache\CacheInterface;

class CacheService
{
    protected CacheInterface $cache;

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    public function remember(string $key, callable $callback, int $ttl = 3600): mixed
    {
        if ($this->cache->get($key)) {
            return $this->cache->get($key);
        }

        $value = $callback();
        $this->cache->save($key, $value, $ttl);
        return $value;
    }

    public function get(string $key): mixed
    {
        return $this->cache->get($key);
    }

    public function set(string $key, mixed $value, int $ttl = 3600): bool
    {
        return $this->cache->save($key, $value, $ttl);
    }

    public function delete(string $key): bool
    {
        return $this->cache->delete($key);
    }

    public function clear(?string $pattern = null) : bool
    {
        // Implementar limpeza por padrão se necessário
        return $this->cache->clean();
    }
}