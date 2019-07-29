<?php
namespace Vanio\EasyMailer\Mailer;

use Swift_Attachment;
use Swift_Mailer;
use Swift_Message;
use Vanio\EasyMailer\EmailAddress;
use Vanio\EasyMailer\Message;

/**
 * Adapter for Swift Mailer.
 */
class SwiftMailerAdapter implements MailerAdapter
{
    /**
     * @var Swift_Mailer
     */
    private $swift;

    /**
     * @param Swift_Mailer $swift
     */
    public function __construct(Swift_Mailer $swift)
    {
        $this->swift = $swift;
    }

    /**
     * Send the given message.
     *
     * @param Message $message Message to be sent.
     * @param EmailAddress[] $to List of recipients which will be merged with those set in the message.
     * @param EmailAddress[] $cc List of recipients of the message copy which will be merged with those set in the message.
     * @param EmailAddress[] $bcc List of recipients who this message will be blind-copied to which will be merged with those set in the message.
     */
    public function sendMessage(Message $message, array $to = [], array $cc = [], array $bcc = [])
    {
        $this->swift->send($this->adaptMessage($message, $to, $cc, $bcc));
    }

    /**
     * Adapt the given message to a Swift message.
     *
     * @param Message $message
     * @param EmailAddress[] $to List of recipients which will be merged with those set in the message.
     * @param EmailAddress[] $cc List of recipients of the message copy which will be merged with those set in the message.
     * @param EmailAddress[] $bcc List of recipients who this message will be blind-copied to which will be merged with those set in the message.
     *
     * @return Swift_Message
     */
    private function adaptMessage(Message $message, array $to, array $cc, array $bcc): Swift_Message
    {
        $swiftMessage = new Swift_Message($message->subject(), null, $message->content()->mimeType());
        $cids = $this->moveAttachments($message, $swiftMessage);
        $swiftMessage->setBody(str_replace(array_keys($cids), $cids, $message->content()));

        if ($message->content()->asPlainText()) {
            $swiftMessage->addPart($message->content()->asPlainText(), 'text/plain');
        }

        $swiftMessage->setTo(array_merge($this->adaptAddresses($message->to()), $this->adaptAddresses($to)));
        $swiftMessage->setCc(array_merge($this->adaptAddresses($message->cc()), $this->adaptAddresses($cc)));
        $swiftMessage->setBcc(array_merge($this->adaptAddresses($message->bcc()), $this->adaptAddresses($bcc)));
        $swiftMessage->setSender($this->adaptAddresses([$message->sender()]));
        $swiftMessage->setFrom($this->adaptAddresses($message->from()));

        return $swiftMessage;
    }

    /**
     * Adapt the given e-mail addresses to a format suitable for Swift Mailer.
     *
     * @param EmailAddress[] $addresses
     *
     * @return array
     */
    private function adaptAddresses(array $addresses): array
    {
        $adapted = [];
        foreach ($addresses as $address) {
            if ($address->name() !== null) {
                $adapted[$address->email()] = $address->name();
            } else {
                $adapted[] = $address->email();
            }
        }

        return $adapted;
    }

    /**
     * Move attachments from the given message to the given Swift message.
     *
     * @param Message $from Source message.
     * @param Swift_Message $to Destination message.
     *
     * @return string[]
     */
    private function moveAttachments(Message $from, Swift_Message $to): array
    {
        foreach ($from->content()->attachments() as $attachment) {
            $swiftAttachment = Swift_Attachment::fromPath($attachment->path())
                ->setFilename($attachment->filename());
            $to->attach($swiftAttachment);
        }
        $cids = [];
        foreach ($from->content()->embeddedAttachments() as $id => $attachment) {
            $swiftAttachment = Swift_Attachment::fromPath($attachment->path())
                ->setFilename($attachment->filename());
            $cids[$id] = $to->embed($swiftAttachment);
        }

        return $cids;
    }
}
