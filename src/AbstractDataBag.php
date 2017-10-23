<?php

namespace CS\Salary;

abstract class AbstractDataBag
{
    /**
     * Array of resolved options.
     *
     * Private so nobody directly messes with this stuff.
     *
     * @var array
     */
    private $data;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->resolve($data);
    }

    /**
     * Returns array of resolved options.
     *
     * @return array
     */
    protected function getData()
    {
        return $this->data;
    }

    /**
     * Returns an option by name.
     *
     * @param $name
     * @throws \LogicException
     * @return mixed
     */
    protected function get($name)
    {
        if (!$this->has($name)) {
            throw new \LogicException("Requested an option '{$name}' which does not exist.");
        }
        return $this->data[$name];
    }


    /**
     * Checks whether the option exists.
     *
     * @param string $name
     * @return bool
     */
    protected function has($name)
    {
        return array_key_exists($name, $this->data);
    }

    /**
     * Resolves the configuration and stores the resulting option array.
     *
     * @param array $data
     */
    protected function resolve(array $data)
    {
        $optionResolver = new DataResolver();
        $this->configure($optionResolver);
        $this->data = $optionResolver->resolve($data);
    }

    /**
     * Configures the options resolver setting default options, etc.
     *
     * @param DataResolver $optionsResolver
     */
    abstract protected function configure(DataResolver $optionsResolver);

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     * @return static
     */
    public static function createFromArray(array $data)
    {
        return new static($data);
    }
}