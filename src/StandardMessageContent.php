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
     * @param string $path The file path.
     * @param string|null $filename The file name.
     */
    public function attach(string $path, ?string $filename = null)
    {
        $this->attachments[$this->getAttachmentId($path)] = new Attachment($path, $filename);
    }

    /**
     * Embed the given file into this message.
     *
     * @param string $path The file path.
     * @param string|null $filename The file name.
     *
     * @return string The attachment ID.
     */
    public function embed(string $path, ?string $filename = null): string
    {
        $id = $this->getAttachmentId($path);
        $this->embeddedAttachments[$id] = new Attachment($path, $filename);

        return $id;
    }

    /**
     * Get this message attachments.
     *
     * @return Attachment[] This message attachments.
     */
    public function attachments(): array
    {
        return $this->attachments;
    }

    /**
     * Get this message embedded attachments.
     *
     * @return Attachment[] This message embedded attachments.
     */
    public function embeddedAttachments(): array
    {
        return $this->embeddedAttachments;
    }

    /**
     * Get ID of the given attachment.
     *
     * @param string $path The file path.
     *
     * @return string The attachment ID.
     */
    private function getAttachmentId(string $path): string
    {
        return sprintf('easy-mailer:attachment:%s', sha1($path));
    }
}
