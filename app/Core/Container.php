<?php

declare(strict_types=1);

namespace App\Core;

use ReflectionClass;
use RuntimeException;

class Container
{
    /** @var array<string, mixed> */
    private array $bindings = [];

    public function set(string $id, callable|string $concrete): void
    {
        $this->bindings[$id] = $concrete;
    }

    public function has(string $id): bool
    {
        return isset($this->bindings[$id]) || class_exists($id);
    }

    public function get(string $id): mixed
    {
        if (isset($this->bindings[$id])) {
            $concrete = $this->bindings[$id];
            return is_callable($concrete) ? $concrete($this) : $this->build($concrete);
        }

        if (class_exists($id)) {
            return $this->build($id);
        }

        throw new RuntimeException("Unresolvable dependency: {$id}");
    }

    private function build(string $class): mixed
    {
        $reflector = new ReflectionClass($class);
        $constructor = $reflector->getConstructor();
        if ($constructor === null) {
            return new $class();
        }
        $params = [];
        foreach ($constructor->getParameters() as $param) {
            $type = $param->getType();
            if ($type && !$type->isBuiltin()) {
                /** @var class-string $name */
                $name = $type->getName();
                $params[] = $this->get($name);
            } elseif ($param->isDefaultValueAvailable()) {
                $params[] = $param->getDefaultValue();
            } else {
                $params[] = null;
            }
        }
        return $reflector->newInstanceArgs($params);
    }
}
