<?php
namespace Vanio\EasyMailer;

interface MessageContent
{
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
}
