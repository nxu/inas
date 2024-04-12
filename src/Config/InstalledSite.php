<?php

namespace Nxu\Inas\Config;

use Bayfront\ArrayHelpers\Arr;
use JsonSerializable;

readonly class InstalledSite implements JsonSerializable
{
    public function __construct(
        public string $name,
        public string $baseDir,
        public ?string $docroot,
        public string $php,
    ) {
    }

    public function jsonSerialize(): mixed
    {
        return [
            'name' => $this->name,
            'baseDir' => $this->baseDir,
            'docroot' => $this->docroot,
            'php' => $this->php,
        ];
    }

    public static function fromConfig(array $config): self
    {
        return new self(
            name: Arr::get($config, 'name'),
            baseDir: Arr::get($config, 'baseDir'),
            docroot: Arr::get($config, 'docroot'),
            php: Arr::get($config, 'php'),
        );
    }
}
