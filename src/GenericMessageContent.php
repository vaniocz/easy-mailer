<?php
namespace Vanio\EasyMailer;

class GenericMessageContent extends StandardMessageContent
{
    /**
     * MIME type of this content.
     *
     * @var string
     */
    private $mimeType;

    /**
     * This content.
     *
     * @var string
     */
    private $content;

    /**
     * Plain text version of the HTML content.
     *
     * @var string
     */
    private $plainText;

    /**
     * @param string $mimeType MIME type of this content.
     * @param string $content This content.
     * @param string $plainText Plain text version of the HTML content.
     */
    public function __construct(string $mimeType, string $content = '', string $plainText = '')
    {
        $this->mimeType = $mimeType;
        $this->modify($content, $plainText);
    }

    /**
     * Modify this content.
     *
     * @param string $content Content data.
     * @param string|null $plainText Plain text version of this content.
     */
    public function modify(string $content, string $plainText = null)
    {
        $this->content = $content;
        $this->plainText = (string) $plainText;
    }

    /**
     * Get MIME type of this content.
     *
     * @return string MIME type of this content.
     */
    function mimeType(): string
    {
        return $this->mimeType;
    }

    /**
     * Convert this content into a plain text.
     *
     * @return string This content as a plain text.
     */
    function asPlainText(): string
    {
        return $this->plainText;
    }

    /**
     * Get this content.
     *
     * @return string This content.
     */
    function __toString(): string
    {
        return $this->content;
    }
}
