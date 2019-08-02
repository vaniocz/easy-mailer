<?php
namespace Vanio\EasyMailer;

/**
 * Represents a single e-mail message.
 */
class Message
{
    /**
     * This message title.
     *
     * @var string
     */
    private $title;

    /**
     * This message subject.
     *
     * @var string
     */
    private $subject;

    /**
     * This message content.
     *
     * @var MessageContent
     */
    private $content;

    /**
     * Recipients of this message.
     *
     * @var EmailAddress[]
     */
    private $to;

    /**
     * Recipients of this message copy.
     *
     * @var EmailAddress[]
     */
    private $cc;

    /**
     * Recipients who this message will be blind-copied to.
     *
     * @var EmailAddress[]
     */
    private $bcc;

    /**
     * The sender of this message.
     *
     * @var EmailAddress
     */
    private $sender;

    /**
     * Writers of this message.
     *
     * @var EmailAddress[]
     */
    private $from;

    /**
     * Reply-To of this message.
     *
     * @var EmailAddress[]
     */
    private $replyTo;

    /**
     * Set this message data.
     *
     * @param string $title This message title.
     * @param string $subject This message subject.
     * @param MessageContent $content This message content.
     * @param EmailAddress[] $to Recipients of this message.
     * @param EmailAddress[] $cc Recipients of this message copy.
     * @param EmailAddress[] $bcc Recipients who this message will be blind-copied to.
     * @param EmailAddress $sender The sender of this message.
     * @param EmailAddress[] $from Writers of this message.
     * @param EmailAddress[] $replyTo Reply-To of this message.
     */
    public function __construct(
        string $title,
        string $subject,
        MessageContent $content,
        array $to,
        array $cc,
        array $bcc,
        EmailAddress $sender,
        array $from = [],
        array $replyTo = []
    ) {
        $this->title = $title;
        $this->subject = $subject;
        $this->content = $content;
        $this->to = $to;
        $this->cc = $cc;
        $this->bcc = $bcc;
        $this->sender = $sender;
        $this->from = $from;
        $this->replyTo = $replyTo;
    }

    /**
     * Get this message title.
     *
     * @return string This message title.
     */
    public function title(): string
    {
        return $this->title;
    }

    /**
     * Get this message subject.
     *
     * @return string This messsage subject.
     */
    public function subject(): string
    {
        return $this->subject;
    }

    /**
     * Get this message content.
     *
     * @return MessageContent This message content.
     */
    public function content(): MessageContent
    {
        return $this->content;
    }

    /**
     * Get recipients of this message.
     *
     * @return EmailAddress[] Recipients of this message.
     */
    public function to(): array
    {
        return $this->to;
    }

    /**
     * Get recipients of this message copy.
     *
     * @return EmailAddress[] Recipients of this message copy.
     */
    public function cc(): array
    {
        return $this->cc;
    }

    /**
     * Get recipients who this message will be blind-copied to.
     *
     * @return EmailAddress[] Recipients who this message will be blind-copied to.
     */
    public function bcc(): array
    {
        return $this->bcc;
    }

    /**
     * Get the sender of this message.
     *
     * @return EmailAddress The sender of this message.
     */
    public function sender(): EmailAddress
    {
        return $this->sender;
    }

    /**
     * Get writers of this message.
     *
     * @return EmailAddress[] Writers of this message.
     */
    public function from(): array
    {
        return $this->from;
    }

    /**
     * Get Reply-To of this message.
     *
     * @return EmailAddress[] Reply-To of this message.
     */
    public function replyTo(): array
    {
        return $this->replyTo;
    }
}
