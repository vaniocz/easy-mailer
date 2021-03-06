<?php
namespace Vanio\EasyMailer;

use Html2Text\Html2Text;
use Pelago\Emogrifier;

/**
 * Represents an HTML content.
 */
class HtmlMessageContent extends StandardMessageContent
{
    /**
     * HTML content.
     *
     * @var string
     */
    private $html;

    /**
     * Plain text version of the HTML content.
     *
     * @var string|null
     */
    private $text;

    /**
     * Whether to run emogrifier over HTML content.
     *
     * @var bool
     */
    private $shouldEmogrify;

    /**
     * Set the given HTML content.
     *
     * @param string $html HTML content.
     * @param string|null $text Plain text version of the HTML content.
     */
    public function __construct(string $html = '', string $text = null)
    {
        $this->modify($html, $text);
    }

    /**
     * Modify this content.
     *
     * @param string $content Content data.
     * @param string|null $plainText Plain text version of this content.
     */
    public function modify(string $content, string $plainText = null)
    {
        $this->html = $content;
        $this->text = $plainText;
    }

    /**
     * Get MIME type of this content.
     *
     * @return string MIME type of this content.
     */
    public function mimeType(): string
    {
        return 'text/html';
    }

    /**
     * Convert this content into a plain text.
     *
     * @return string This content as a plain text.
     */
    public function asPlainText(): string
    {
        return $this->text ?? $this->text = (new Html2Text($this->html))->getText();
    }

    /**
     * Get emogrified HTML content.
     */
    public function enableEmogrifier()
    {
        $this->shouldEmogrify = true;
    }

    /**
     * Get this content.
     *
     * @return string This content.
     */
    public function __toString(): string
    {
        if ($this->shouldEmogrify) {
            $emogrifier = new Emogrifier($this->html);
            return $emogrifier->emogrify();
        }

        return $this->html;
    }
}
