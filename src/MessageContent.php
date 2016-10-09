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
     * @param string $filePath The file path.
     */
    function attach(string $filePath);

    /**
     * Embed the given file into this message.
     *
     * @param string $filePath The file path.
     *
     * @return string The attachment ID.
     */
    function embed(string $filePath): string;

    /**
     * Get this message attachments.
     *
     * @return string[] This message attachments.
     */
    function attachments(): array;

    /**
     * Get this message embedded attachments.
     *
     * @return string[] This message embedded attachments.
     */
    function embeddedAttachments(): array;
}
