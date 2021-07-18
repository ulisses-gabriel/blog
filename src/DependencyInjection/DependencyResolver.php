<?php

declare(strict_types=1);

namespace App\DependencyInjection;

class DependencyResolver
{
    private array $dependencies;

    public function getClass(string $class, array $dependencies = []): object
    {
        $this->dependencies = $dependencies;
        $class = new \ReflectionClass($class);

        if (!$class->isInstantiable()) {
            throw new \Exception(sprintf('%s is not instantiable.', $class->getName()));
        }

        $constructor = $class->getConstructor();

        if (!$constructor) {
            return new ($class->name)();
        }

        return $class->newInstance($this->resolveParameters($constructor->getParameters()));
    }

    private function resolveParameters(array $parameters): array
    {
        $dependencies = [];

        foreach ($parameters as $parameter) {
            /** @var \ReflectionParameter $dependency */
            $dependency = $parameter->getClass();

            if ($dependency) {
                $dependencies[] = $this->getClass($dependency->name, $this->dependencies);
            } else {
                $dependencies[] = $this->getDependencies($parameter);
            }
        }

        return $dependencies;
    }

    private function getDependencies(\ReflectionParameter $parameter)
    {
        if (isset($this->dependencies[$parameter->name])) {
            return $this->dependencies[$parameter->name];
        }

        if ($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }

        throw new \Exception(sprintf('%s did not receive a valid value.', $parameter->name));
    }
}