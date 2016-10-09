<?php
namespace Vanio\EasyMailer;

/**
 * Provides standard functionality for working with attachments.
 */
abstract class StandardMessageContent implements MessageContent
{
    /**
     * This message attachments.
     *
     * @var string[]
     */
    private $attachments = [];

    /**
     * This message embedded attachments.
     *
     * @var string[]
     */
    private $embeddedAttachments = [];

    /**
     * Get MIME type of this content.
     *
     * @return string MIME type of this content.
     */
    abstract public function mimeType(): string;

    /**
     * Convert this content into a plain text.
     *
     * @return string This content as a plain text.
     */
    abstract public function asPlainText(): string;

    /**
     * Get this content.
     *
     * @return string This content.
     */
    abstract public function __toString(): string;

    /**
     * Attach the given file to this message.
     *
     * @param string $filePath The file path.
     */
    public function attach(string $filePath)
    {
        $this->attachments[$this->getAttachmentId($filePath)] = $filePath;
    }

    /**
     * Embed the given file into this message.
     *
     * @param string $filePath The file path.
     *
     * @return string The attachment ID.
     */
    public function embed(string $filePath): string
    {
        $id = $this->getAttachmentId($filePath);
        $this->embeddedAttachments[$id] = $filePath;

        return $id;
    }

    /**
     * Get this message attachments.
     *
     * @return string[] This message attachments.
     */
    public function attachments(): array
    {
        return $this->attachments;
    }

    /**
     * Get this message embedded attachments.
     *
     * @return string[] This message embedded attachments.
     */
    public function embeddedAttachments(): array
    {
        return $this->embeddedAttachments;
    }

    /**
     * Get ID of the given attachment.
     *
     * @param string $filePath The file path.
     *
     * @return string The attachment ID.
     */
    private function getAttachmentId(string $filePath): string
    {
        return sprintf('easy-mailer:attachment:%s', sha1($filePath));
    }
}
