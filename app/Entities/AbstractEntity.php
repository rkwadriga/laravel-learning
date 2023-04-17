<?php declare(strict_types=1);
/**
 * Created 2023-04-17
 * Author Dmitry Kushneriov
 */

namespace App\Entities;

abstract class AbstractEntity
{
    public function __construct(array $params = [])
    {
        foreach ($params as $name => $value) {
            if (is_string($name)) {
                $this->setParam($name, $value);
            }
        }
    }

    protected function setParam(string $name, $value): self
    {
        $setter = 'set' . ucfirst($name);
        if (method_exists($this, $setter)) {
            $this->$setter($value);
        } elseif (property_exists($this, $name)) {
            $this->$name = $value;
        }

        return $this;
    }
}
