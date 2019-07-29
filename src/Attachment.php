<?php
namespace Vanio\EasyMailer;

class Attachment
{
    /** @var string */
    private $path;

    /** @var string */
    private $filename;

    public function __construct(string $path, ?string $filename = null)
    {
        $this->path = $path;
        $this->filename = $filename ?? basename($path);
    }

    public function path(): string
    {
        return $this->path;
    }

    public function filename(): string
    {
        return $this->filename;
    }
}
