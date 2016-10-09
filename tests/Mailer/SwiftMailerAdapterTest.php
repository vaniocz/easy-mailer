<?php
namespace Vanio\EasyMailer\Tests\Mailer;

use PHPUnit\Framework\TestCase;
use Swift_Mailer;
use Swift_Message;
use Vanio\EasyMailer\EmailAddress;
use Vanio\EasyMailer\HtmlMessageContent;
use Vanio\EasyMailer\Mailer\SwiftMailerAdapter;
use Vanio\EasyMailer\Message;

class SwiftMailerAdapterTests extends TestCase
{
    /** @var Swift_Mailer */
    private $swift;

    /** @var SwiftMailerAdapter */
    private $adapter;

    /** @var Message */
    private $message;

    protected function setUp()
    {
        $this->swift = $this->getMockBuilder(Swift_Mailer::class)->disableOriginalConstructor()->getMock();
        $this->adapter = new SwiftMailerAdapter($this->swift);

        $image = (new HtmlMessageContent(''))->embed(__DIR__ . '/../Fixtures/attachments/image.png');
        $this->message = new Message(
            'Test Title',
            'Test Subject',
            new HtmlMessageContent(sprintf('<h1>Dear sirs, ...</h1><img src="%s" />', $image)),
            [new EmailAddress('john@doe.foo', 'John Doe')],
            [new EmailAddress('jane@doe.foo', 'Jane Doe')],
            [new EmailAddress('foo@bar.baz', 'Foo Bar')],
            new EmailAddress('info@vanio.cz', 'Vanio Info'),
            [new EmailAddress('info@vanio.cz', 'Vanio Info'), new EmailAddress('foo@bar.baz')]
        );

        $this->message->content()->attach(__DIR__ . '/../Fixtures/attachments/file.txt');
        $this->message->content()->embed(__DIR__ . '/../Fixtures/attachments/image.png');
    }

    function test_message_sending()
    {
        $this->swift
            ->expects($this->once())
            ->method('send')
            ->will($this->returnCallback(function (Swift_Message $swiftMessage) {
                $this->assertSame($this->message->subject(), $swiftMessage->getSubject());
                $this->assertContains('<h1>Dear sirs, ...</h1><img src="', $swiftMessage->getBody());
                $this->assertSame($this->message->content()->asPlainText(), $swiftMessage->getChildren()[2]->getBody());
                $this->assertSame('#file.txt', trim($swiftMessage->getChildren()[0]->getBody()));
                $this->assertSame('', $swiftMessage->getChildren()[1]->getBody());
                $this->assertContains($swiftMessage->getChildren()[1]->getId(), $swiftMessage->getBody());
                $this->assertSame($this->message->to()[0]->email(), key($swiftMessage->getTo()));
                $this->assertSame($this->message->to()[0]->name(), current($swiftMessage->getTo()));
                $this->assertSame($this->message->cc()[0]->email(), key($swiftMessage->getCc()));
                $this->assertSame($this->message->cc()[0]->name(), current($swiftMessage->getCc()));
                $this->assertSame($this->message->bcc()[0]->email(), key($swiftMessage->getBcc()));
                $this->assertSame($this->message->bcc()[0]->name(), current($swiftMessage->getBcc()));
                $this->assertSame($this->message->bcc()[0]->email(), key($swiftMessage->getBcc()));
                $this->assertSame($this->message->bcc()[0]->name(), current($swiftMessage->getBcc()));
                $this->assertSame($this->message->sender()->email(), key($swiftMessage->getSender()));
                $this->assertSame($this->message->sender()->name(), current($swiftMessage->getSender()));
                $this->assertSame($this->message->from()[0]->email(), key($swiftMessage->getFrom()));
                $this->assertSame($this->message->from()[0]->name(), current($swiftMessage->getFrom()));
                $this->assertSame($this->message->from()[1]->email(), array_keys($swiftMessage->getFrom())[1]);
                $this->assertSame($this->message->from()[1]->name(), array_values($swiftMessage->getFrom())[1]);

                $this->assertSame('to@foo.bar', array_keys($swiftMessage->getTo())[1]);
                $this->assertSame('Foo Bar', array_values($swiftMessage->getTo())[1]);
                $this->assertSame('cc@foo.bar', array_keys($swiftMessage->getCc())[1]);
                $this->assertSame('Foo Bar', array_values($swiftMessage->getCc())[1]);
                $this->assertSame('bcc@foo.bar', array_keys($swiftMessage->getBcc())[1]);
                $this->assertSame('Foo Bar', array_values($swiftMessage->getBcc())[1]);
            }));

        $this->adapter->sendMessage(
            $this->message,
            [new EmailAddress('to@foo.bar', 'Foo Bar')],
            [new EmailAddress('cc@foo.bar', 'Foo Bar')],
            [new EmailAddress('bcc@foo.bar', 'Foo Bar')]
        );
    }
}
