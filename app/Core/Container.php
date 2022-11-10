<?php

namespace App\Core;

use App\Exceptions\ContainerException;
use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    private array $entries = [];

    public function __construct()
    {
    }

    public function get(string $id)
    {
        if ($this->has($id)) {
            $entry = $this->entries[$id];

            if (is_callable($entry)) {
                return $entry($this);
            }

            $id = $entry;
        }
        return $this->resolve($id);
    }

    public function has(string $id): bool
    {
        return isset($this->entries[$id]);
    }

    public function bind(string $id, callable|string $concrete)
    {
        $this->entries[$id] = $concrete ?: $id;
    }

    public function resolve(string $id)
    {
        $reflectionClass = new \ReflectionClass($id);

        if (! $reflectionClass->isInstantiable()) {
            throw new ContainerException("Class $id is not instantiable");
        }

        $constructor = $reflectionClass->getConstructor();
        $prameters = $constructor?->getParameters();

        if (! $constructor || ! $prameters) {
            return new $id();
        }

        $dependencies = $this->getDependencies($id, $prameters);

        return $reflectionClass->newInstanceArgs($dependencies);
    }

    protected function getDependencies(string $id, array $prameters)
    {
        return array_map(function (\ReflectionParameter $param) use ($id) {
            $name = $param->getName();
            $type = $param->getType();

            if (! $type) {
                throw new ContainerException("Faild to resolve class $id because param $name is missing a type hint");
            }

            if ($type instanceof \ReflectionUnionType) {
                throw new ContainerException("Faild to resolve class $id because of union type for param $name");
            }

            if ($type instanceof \ReflectionNamedType && ! $type->isBuiltin()) {
                return $this->get($type->getName());
            }

            throw new ContainerException("Faild to resolve class $id because invaild param $name");
        }, $prameters);
    }
}
