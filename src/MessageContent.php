<?php
namespace Vanio\EasyMailer;

/**
 * Common interface for different types of message content.
 */
interface MessageContent
{
    /**
     * Modify this content.
     *
     * @param string $content Content data.
     * @param string|null $plainText Plain text version of this content.
     */
    function modify(string $content, string $plainText = null);

    /**
     * Get MIME type of this content.
     *
     * @return string MIME type of this content.
     */
    function mimeType(): string;

    /**
     * Convert this content into a plain text.
     *
     * @return string This content as a plain text.
     */
    function asPlainText(): string;

    /**
     * Get this content.
     *
     * @return string This content.
     */
    function __toString(): string;

    /**
     * Attach the given file to this message.
     *
     * @param string $path The file path.
     * @param string $filename The file name.
     */
    function attach(string $path, ?string $filename = null);

    /**
     * Embed the given file into this message.
     *
     * @param string $path The file path.
     * @param string $filename The file name.
     *
     * @return string The attachment ID.
     */
    function embed(string $filePath, ?string $filename = null): string;

    /**
     * Get this message attachments.
     *
     * @return Attachment[] This message attachments.
     */
    function attachments(): array;

    /**
     * Get this message embedded attachments.
     *
     * @return Attachment[] This message embedded attachments.
     */
    function embeddedAttachments(): array;
}
