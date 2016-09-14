<?php
namespace Vanio\EasyMailer\Mailer;

use Vanio\EasyMailer\EmailAddress;
use Vanio\EasyMailer\Message;

/**
 * Interface for mail senders.
 */
interface MailerAdapter
{
    /**
     * Send the given message.
     *
     * @param Message $message Message to be sent.
     * @param EmailAddress[] $to List of recipients which will be merged with those set in the message.
     * @param EmailAddress[] $cc List of recipients of the message copy which will be merged with those set in the message.
     * @param EmailAddress[] $bcc List of recipients who this message will be blind-copied to which will be merged with those set in the message.
     */
    function sendMessage(Message $message, array $to = [], array $cc = [], array $bcc = []);
}
